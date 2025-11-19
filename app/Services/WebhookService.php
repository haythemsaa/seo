<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Send webhook notification
     */
    public function send(string $url, string $event, array $data, ?string $secret = null): bool
    {
        try {
            $payload = [
                'event' => $event,
                'timestamp' => now()->toIso8601String(),
                'data' => $data,
            ];

            $headers = [
                'Content-Type' => 'application/json',
                'X-Webhook-Event' => $event,
                'X-Webhook-Timestamp' => time(),
            ];

            // Add signature if secret is provided
            if ($secret) {
                $signature = $this->generateSignature($payload, $secret);
                $headers['X-Webhook-Signature'] = $signature;
            }

            $response = Http::withHeaders($headers)
                ->timeout(config('webhook.timeout', 10))
                ->post($url, $payload);

            if ($response->successful()) {
                Log::channel('webhook')->info('Webhook sent successfully', [
                    'url' => $url,
                    'event' => $event,
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::channel('webhook')->warning('Webhook failed', [
                'url' => $url,
                'event' => $event,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::channel('webhook')->error('Webhook exception', [
                'url' => $url,
                'event' => $event,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send webhook with retry logic
     */
    public function sendWithRetry(string $url, string $event, array $data, ?string $secret = null, int $maxAttempts = 3): bool
    {
        $attempt = 1;

        while ($attempt <= $maxAttempts) {
            if ($this->send($url, $event, $data, $secret)) {
                return true;
            }

            if ($attempt < $maxAttempts) {
                // Exponential backoff: 2^attempt seconds
                sleep(pow(2, $attempt));
            }

            $attempt++;
        }

        Log::channel('webhook')->error('Webhook failed after retries', [
            'url' => $url,
            'event' => $event,
            'attempts' => $maxAttempts,
        ]);

        return false;
    }

    /**
     * Generate webhook signature
     */
    private function generateSignature(array $payload, string $secret): string
    {
        $jsonPayload = json_encode($payload);
        return hash_hmac('sha256', $jsonPayload, $secret);
    }

    /**
     * Verify webhook signature
     */
    public function verifySignature(string $payload, string $signature, string $secret): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Send ranking changed webhook
     */
    public function sendRankingChanged(int $userId, array $keywordData): bool
    {
        $webhookUrl = $this->getUserWebhookUrl($userId, 'ranking.changed');

        if (!$webhookUrl) {
            return false;
        }

        return $this->sendWithRetry(
            $webhookUrl,
            'ranking.changed',
            $keywordData,
            $this->getUserWebhookSecret($userId)
        );
    }

    /**
     * Send backlink found webhook
     */
    public function sendBacklinkFound(int $userId, array $backlinkData): bool
    {
        $webhookUrl = $this->getUserWebhookUrl($userId, 'backlink.found');

        if (!$webhookUrl) {
            return false;
        }

        return $this->sendWithRetry(
            $webhookUrl,
            'backlink.found',
            $backlinkData,
            $this->getUserWebhookSecret($userId)
        );
    }

    /**
     * Send audit completed webhook
     */
    public function sendAuditCompleted(int $userId, array $auditData): bool
    {
        $webhookUrl = $this->getUserWebhookUrl($userId, 'audit.completed');

        if (!$webhookUrl) {
            return false;
        }

        return $this->sendWithRetry(
            $webhookUrl,
            'audit.completed',
            $auditData,
            $this->getUserWebhookSecret($userId)
        );
    }

    /**
     * Get user webhook URL for specific event (placeholder - would query database)
     */
    private function getUserWebhookUrl(int $userId, string $event): ?string
    {
        // This would query the webhooks table for the user
        // For now, returning null (not implemented)
        return null;
    }

    /**
     * Get user webhook secret (placeholder - would query database)
     */
    private function getUserWebhookSecret(int $userId): ?string
    {
        // This would query the webhooks table for the user
        // For now, returning null (not implemented)
        return null;
    }
}
