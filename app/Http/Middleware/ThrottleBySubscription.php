<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleBySubscription
{
    /**
     * The rate limiter instance.
     */
    protected RateLimiter $limiter;

    /**
     * Create a new middleware instance.
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get rate limit based on subscription
        $limit = $this->getLimit($user->subscription_plan);
        $key = 'api_' . $user->id;

        if ($this->limiter->tooManyAttempts($key, $limit)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $seconds,
            ], 429)->header('Retry-After', $seconds)
                ->header('X-RateLimit-Limit', $limit)
                ->header('X-RateLimit-Remaining', 0);
        }

        $this->limiter->hit($key, 60); // 60 seconds window

        $response = $next($request);

        // Add rate limit headers
        $remaining = $limit - $this->limiter->attempts($key);

        return $response->withHeaders([
            'X-RateLimit-Limit' => $limit,
            'X-RateLimit-Remaining' => max(0, $remaining),
        ]);
    }

    /**
     * Get rate limit based on subscription plan.
     */
    private function getLimit(string $plan): int
    {
        return match($plan) {
            'free' => 100,          // 100 requests per minute
            'starter' => 500,       // 500 requests per minute
            'professional' => 2000, // 2000 requests per minute
            'agency' => 10000,      // 10000 requests per minute
            'enterprise' => 50000,  // 50000 requests per minute
            default => 100,
        };
    }
}
