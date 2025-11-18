<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if subscription is active
        if ($user->subscription_status !== 'active') {
            return redirect()->route('subscription.index')
                ->with('error', 'Your subscription is not active. Please update your subscription.');
        }

        // Get subscription limits
        $limits = $this->getSubscriptionLimits($user->subscription_plan);

        // Check resource-specific limits
        switch ($resource) {
            case 'projects':
                if ($user->projects()->count() >= $limits['projects']) {
                    return back()->with('error', sprintf(
                        'You have reached your project limit (%d). Please upgrade your plan.',
                        $limits['projects']
                    ));
                }
                break;

            case 'keywords':
                $project = $request->route('project');
                if ($project && $project->keywords()->count() >= $limits['keywords']) {
                    return back()->with('error', sprintf(
                        'You have reached your keyword limit (%d). Please upgrade your plan.',
                        $limits['keywords']
                    ));
                }
                break;

            case 'api':
                // API rate limiting is handled separately
                if (!isset($limits['api_access']) || !$limits['api_access']) {
                    return response()->json([
                        'message' => 'API access is not available in your current plan.',
                    ], 403);
                }
                break;
        }

        return $next($request);
    }

    /**
     * Get subscription limits for a plan.
     */
    private function getSubscriptionLimits(string $plan): array
    {
        $limits = [
            'free' => [
                'projects' => 1,
                'keywords' => 10,
                'backlinks' => 100,
                'reports' => 1,
                'crawl_pages' => 100,
                'api_access' => false,
            ],
            'starter' => [
                'projects' => 3,
                'keywords' => 100,
                'backlinks' => 1000,
                'reports' => 10,
                'crawl_pages' => 5000,
                'api_access' => false,
            ],
            'professional' => [
                'projects' => 10,
                'keywords' => 500,
                'backlinks' => 10000,
                'reports' => 50,
                'crawl_pages' => 50000,
                'api_access' => true,
            ],
            'agency' => [
                'projects' => 50,
                'keywords' => 5000,
                'backlinks' => 100000,
                'reports' => 500,
                'crawl_pages' => 500000,
                'api_access' => true,
            ],
            'enterprise' => [
                'projects' => PHP_INT_MAX,
                'keywords' => PHP_INT_MAX,
                'backlinks' => PHP_INT_MAX,
                'reports' => PHP_INT_MAX,
                'crawl_pages' => PHP_INT_MAX,
                'api_access' => true,
            ],
        ];

        return $limits[$plan] ?? $limits['free'];
    }
}
