<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Cache duration in minutes
     */
    private const CACHE_DURATION = 60;

    /**
     * Routes that should be cached
     */
    private array $cacheableRoutes = [
        'api.projects.index',
        'api.keywords.index',
        'api.backlinks.index',
        'api.analytics.dashboard',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, int $minutes = null): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Check if caching is enabled
        if (!config('cache.response_cache_enabled', false)) {
            return $next($request);
        }

        // Check if route is cacheable
        $routeName = $request->route()->getName();
        if (!in_array($routeName, $this->cacheableRoutes)) {
            return $next($request);
        }

        // Generate cache key
        $cacheKey = $this->generateCacheKey($request);

        // Try to get from cache
        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            $response = response($cachedResponse['content'], $cachedResponse['status'])
                ->withHeaders($cachedResponse['headers']);

            // Add cache header
            $response->headers->set('X-Cache', 'HIT');

            return $response;
        }

        // Get fresh response
        $response = $next($request);

        // Only cache successful responses
        if ($response->getStatusCode() === 200) {
            $cacheDuration = $minutes ?? self::CACHE_DURATION;

            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->all(),
            ], now()->addMinutes($cacheDuration));

            // Add cache header
            $response->headers->set('X-Cache', 'MISS');
            $response->headers->set('X-Cache-Expires', now()->addMinutes($cacheDuration)->toIso8601String());
        }

        return $response;
    }

    /**
     * Generate unique cache key for request
     */
    private function generateCacheKey(Request $request): string
    {
        $user = $request->user();
        $userId = $user ? $user->id : 'guest';

        $uri = $request->getRequestUri();
        $queryParams = $request->query();

        // Sort query parameters for consistent cache keys
        ksort($queryParams);

        return sprintf(
            'response_cache:%s:%s:%s',
            $userId,
            md5($uri),
            md5(json_encode($queryParams))
        );
    }

    /**
     * Clear cache for specific user
     */
    public static function clearUserCache(int $userId): void
    {
        $pattern = "response_cache:{$userId}:*";
        // Note: This requires Redis for pattern-based deletion
        Cache::tags(["user:{$userId}"])->flush();
    }

    /**
     * Clear all response cache
     */
    public static function clearAllCache(): void
    {
        Cache::tags(['response_cache'])->flush();
    }
}
