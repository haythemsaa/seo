<?php

namespace App\Services\Payment;

use App\Models\Organization;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\Price;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe customer for an organization.
     */
    public function createCustomer(Organization $organization): Customer
    {
        $owner = $organization->owner;

        $customer = Customer::create([
            'email' => $owner->email,
            'name' => $organization->name,
            'metadata' => [
                'organization_id' => $organization->id,
            ],
        ]);

        $organization->update([
            'settings' => array_merge($organization->settings ?? [], [
                'stripe_customer_id' => $customer->id,
            ]),
        ]);

        return $customer;
    }

    /**
     * Get or create Stripe customer.
     */
    public function getOrCreateCustomer(Organization $organization): Customer
    {
        $customerId = $organization->settings['stripe_customer_id'] ?? null;

        if ($customerId) {
            try {
                return Customer::retrieve($customerId);
            } catch (\Exception $e) {
                // Customer doesn't exist, create new one
            }
        }

        return $this->createCustomer($organization);
    }

    /**
     * Create a subscription for an organization.
     */
    public function createSubscription(
        Organization $organization,
        string $priceId,
        string $paymentMethodId
    ): Subscription {
        $customer = $this->getOrCreateCustomer($organization);

        // Attach payment method to customer
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $customer->id]);

        // Set as default payment method
        Customer::update($customer->id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ],
        ]);

        // Create subscription
        $subscription = Subscription::create([
            'customer' => $customer->id,
            'items' => [
                ['price' => $priceId],
            ],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
            'metadata' => [
                'organization_id' => $organization->id,
            ],
        ]);

        // Update organization
        $this->updateOrganizationFromSubscription($organization, $subscription);

        return $subscription;
    }

    /**
     * Cancel a subscription.
     */
    public function cancelSubscription(Organization $organization, bool $immediately = false): void
    {
        $subscriptionId = $organization->settings['stripe_subscription_id'] ?? null;

        if (!$subscriptionId) {
            throw new \Exception('No active subscription found');
        }

        $subscription = Subscription::retrieve($subscriptionId);

        if ($immediately) {
            $subscription->cancel();
            $organization->update([
                'subscription_status' => 'cancelled',
                'subscription_ends_at' => now(),
            ]);
        } else {
            $subscription->cancel(['at_period_end' => true]);
            $organization->update([
                'subscription_status' => 'cancelled',
                'subscription_ends_at' => \Carbon\Carbon::createFromTimestamp($subscription->current_period_end),
            ]);
        }
    }

    /**
     * Resume a cancelled subscription.
     */
    public function resumeSubscription(Organization $organization): Subscription
    {
        $subscriptionId = $organization->settings['stripe_subscription_id'] ?? null;

        if (!$subscriptionId) {
            throw new \Exception('No subscription found');
        }

        $subscription = Subscription::retrieve($subscriptionId);
        $subscription->cancel_at_period_end = false;
        $subscription = $subscription->save();

        $organization->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => null,
        ]);

        return $subscription;
    }

    /**
     * Change subscription plan.
     */
    public function changePlan(Organization $organization, string $newPriceId): Subscription
    {
        $subscriptionId = $organization->settings['stripe_subscription_id'] ?? null;

        if (!$subscriptionId) {
            throw new \Exception('No active subscription found');
        }

        $subscription = Subscription::retrieve($subscriptionId);

        // Update subscription
        $subscription = Subscription::update($subscriptionId, [
            'items' => [
                [
                    'id' => $subscription->items->data[0]->id,
                    'price' => $newPriceId,
                ],
            ],
            'proration_behavior' => 'create_prorations',
        ]);

        $this->updateOrganizationFromSubscription($organization, $subscription);

        return $subscription;
    }

    /**
     * Get subscription plans with prices.
     */
    public function getPlans(): array
    {
        return [
            'starter' => [
                'name' => 'Starter',
                'price' => 39,
                'price_id' => config('services.stripe.prices.starter'),
                'interval' => 'month',
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 149,
                'price_id' => config('services.stripe.prices.professional'),
                'interval' => 'month',
            ],
            'agency' => [
                'name' => 'Agency',
                'price' => 399,
                'price_id' => config('services.stripe.prices.agency'),
                'interval' => 'month',
            ],
        ];
    }

    /**
     * Update organization from Stripe subscription.
     */
    protected function updateOrganizationFromSubscription(
        Organization $organization,
        Subscription $subscription
    ): void {
        $planMap = [
            config('services.stripe.prices.starter') => 'starter',
            config('services.stripe.prices.professional') => 'professional',
            config('services.stripe.prices.agency') => 'agency',
        ];

        $priceId = $subscription->items->data[0]->price->id;
        $plan = $planMap[$priceId] ?? 'free';

        $organization->update([
            'subscription_plan' => $plan,
            'subscription_status' => $subscription->status === 'active' ? 'active' : 'suspended',
            'subscription_ends_at' => $subscription->current_period_end
                ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)
                : null,
            'settings' => array_merge($organization->settings ?? [], [
                'stripe_subscription_id' => $subscription->id,
            ]),
        ]);
    }

    /**
     * Handle webhook event.
     */
    public function handleWebhook(array $event): void
    {
        $type = $event['type'];
        $data = $event['data']['object'];

        switch ($type) {
            case 'customer.subscription.updated':
            case 'customer.subscription.created':
                $this->handleSubscriptionUpdated($data);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($data);
                break;

            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($data);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($data);
                break;
        }
    }

    protected function handleSubscriptionUpdated(array $subscription): void
    {
        $organizationId = $subscription['metadata']['organization_id'] ?? null;

        if ($organizationId) {
            $organization = Organization::find($organizationId);
            if ($organization) {
                $stripeSubscription = Subscription::retrieve($subscription['id']);
                $this->updateOrganizationFromSubscription($organization, $stripeSubscription);
            }
        }
    }

    protected function handleSubscriptionDeleted(array $subscription): void
    {
        $organizationId = $subscription['metadata']['organization_id'] ?? null;

        if ($organizationId) {
            $organization = Organization::find($organizationId);
            if ($organization) {
                $organization->update([
                    'subscription_plan' => 'free',
                    'subscription_status' => 'cancelled',
                ]);
            }
        }
    }

    protected function handlePaymentSucceeded(array $invoice): void
    {
        // Log successful payment
        \Log::info('Payment succeeded', ['invoice_id' => $invoice['id']]);
    }

    protected function handlePaymentFailed(array $invoice): void
    {
        // Handle failed payment
        \Log::error('Payment failed', ['invoice_id' => $invoice['id']]);

        $customerId = $invoice['customer'];
        $organization = Organization::where('settings->stripe_customer_id', $customerId)->first();

        if ($organization) {
            $organization->update(['subscription_status' => 'suspended']);

            // TODO: Send email notification
        }
    }
}
