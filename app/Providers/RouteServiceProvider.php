<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->configureRateLimiter();
    }

    protected function configureRateLimiter()
    {

        // Default api limiter
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Stricter global limiter
        RateLimiter::for('global', function($request){
            return Limit::perMinute(30);
        });

        // Per-user limiter
        RateLimiter::for('fast-user', function($request){
            return Limit::perMinute(120)->by($request->user()->id);
        });

        // Dinamically vary limits by role or attribute
        RateLimiter::for('dynamic', function($request){
            $user = $request->user();

            if (! $user) {
                // Guest gets 10 requests / minute by IP
                return Limit::perMinute(10)->by($request->ip());
            }

            switch ($user->role){
                case 'admin':
                    // Unmetered
                    return Limit::none();
                case 'premium':
                    return Limit::perMinute(200)->by($user->id);
                default:
                    return Limit::perMinute(60)->by($user->id);
            }
        });
    }
}
