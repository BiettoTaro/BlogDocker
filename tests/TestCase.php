<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mirror the dynamic limiter registration
        RateLimiter::for('dynamic', function ($request) {
            $user = $request->user();
            if (! $user) {
                return Limit::perMinute(10)->by($request->ip());
            }
            switch ($user->role) {
                case 'admin':
                    return Limit::none();
                case 'premium':
                    return Limit::perMinute(200)->by($user->id);
                default:
                    return Limit::perMinute(60)->by($user->id);
            }
        });

        // Register a public route for throttle testing
        $this->app['router']->get('/test-throttle', function () {
            return response()->json(['ok' => true]);
        })->middleware('throttle:dynamic');
    }

}
