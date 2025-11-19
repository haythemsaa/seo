<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    /**
     * Basic health check
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'service' => config('app.name'),
            'environment' => config('app.env'),
        ]);
    }

    /**
     * Detailed health check with all services
     */
    public function detailed(): JsonResponse
    {
        $checks = [
            'app' => $this->checkApp(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'redis' => $this->checkRedis(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'healthy')
            ? 'healthy'
            : 'unhealthy';

        return response()->json([
            'status' => $overallStatus,
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->toIso8601String(),
                'timezone' => config('app.timezone'),
            ],
        ], $overallStatus === 'healthy' ? 200 : 503);
    }

    /**
     * Check application health
     */
    private function checkApp(): array
    {
        try {
            $debug = config('app.debug');
            $key = config('app.key');

            return [
                'status' => 'healthy',
                'debug_mode' => $debug,
                'has_app_key' => !empty($key),
                'environment' => config('app.env'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            $startTime = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            // Test query
            $result = DB::select('SELECT 1');

            return [
                'status' => 'healthy',
                'connection' => DB::connection()->getDatabaseName(),
                'driver' => DB::connection()->getDriverName(),
                'response_time_ms' => $responseTime,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache functionality
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test_value';

            // Test write
            Cache::put($key, $value, 60);

            // Test read
            $retrieved = Cache::get($key);

            // Cleanup
            Cache::forget($key);

            $status = ($retrieved === $value) ? 'healthy' : 'unhealthy';

            return [
                'status' => $status,
                'driver' => config('cache.default'),
                'write' => true,
                'read' => ($retrieved === $value),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Redis connectivity
     */
    private function checkRedis(): array
    {
        try {
            $startTime = microtime(true);
            Redis::ping();
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'status' => 'healthy',
                'response_time_ms' => $responseTime,
                'connected' => true,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connected' => false,
            ];
        }
    }

    /**
     * Check storage accessibility
     */
    private function checkStorage(): array
    {
        try {
            $disk = Storage::disk('local');
            $testFile = 'health_check_' . time() . '.txt';
            $testContent = 'health check test';

            // Test write
            $disk->put($testFile, $testContent);

            // Test read
            $retrieved = $disk->get($testFile);

            // Test delete
            $disk->delete($testFile);

            $status = ($retrieved === $testContent) ? 'healthy' : 'unhealthy';

            return [
                'status' => $status,
                'disk' => 'local',
                'writable' => true,
                'readable' => ($retrieved === $testContent),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check queue status
     */
    private function checkQueue(): array
    {
        try {
            $connection = config('queue.default');
            $failedJobs = DB::table('failed_jobs')->count();

            return [
                'status' => 'healthy',
                'connection' => $connection,
                'failed_jobs' => $failedJobs,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get application metrics
     */
    public function metrics(): JsonResponse
    {
        try {
            $metrics = [
                'app' => [
                    'name' => config('app.name'),
                    'version' => '1.0.0',
                    'environment' => config('app.env'),
                    'uptime' => $this->getUptime(),
                ],
                'database' => [
                    'users_count' => DB::table('users')->count(),
                    'projects_count' => DB::table('projects')->count(),
                    'keywords_count' => DB::table('keywords')->count(),
                    'backlinks_count' => DB::table('backlinks')->count(),
                ],
                'queue' => [
                    'failed_jobs' => DB::table('failed_jobs')->count(),
                    'connection' => config('queue.default'),
                ],
                'cache' => [
                    'driver' => config('cache.default'),
                ],
                'system' => [
                    'php_version' => PHP_VERSION,
                    'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                    'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
                ],
            ];

            return response()->json($metrics);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve metrics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get application uptime (placeholder - requires implementation)
     */
    private function getUptime(): string
    {
        // This would require storing app start time in cache or file
        return 'N/A';
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
