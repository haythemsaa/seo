<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();

            if (!$user) {
                return Limit::perHour(env('API_RATE_LIMIT_FREE', 100));
            }

            $organization = $user->organization;
            $plan = $organization?->subscription_plan ?? 'free';

            $limits = [
                'free' => env('API_RATE_LIMIT_FREE', 100),
                'starter' => env('API_RATE_LIMIT_STARTER', 500),
                'professional' => env('API_RATE_LIMIT_PROFESSIONAL', 2000),
                'agency' => env('API_RATE_LIMIT_AGENCY', 10000),
                'enterprise' => env('API_RATE_LIMIT_ENTERPRISE', 50000),
            ];

            return Limit::perHour($limits[$plan] ?? 100)->by($user->id);
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
