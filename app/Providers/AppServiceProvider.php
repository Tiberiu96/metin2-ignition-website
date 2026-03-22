<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureRateLimiting(): void
    {
        // Auth: strict limits to prevent brute force
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                abort(429, 'Too many attempts. Please try again later.');
            });
        });

        // Registration: prevent spam account creation
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                abort(429, 'Too many registration attempts. Please try again later.');
            });
        });

        // Password reset: prevent email spam
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                abort(429, 'Too many password reset attempts. Please try again later.');
            });
        });

        // Pages that query game DB
        RateLimiter::for('game-read', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Downloads: prevent abuse
        RateLimiter::for('download', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                abort(429, 'Too many download requests. Please try again later.');
            });
        });

        // Item shop: prevent purchase spam
        RateLimiter::for('shop', function (Request $request) {
            return Limit::perMinute(50)->by($request->ip())->response(function () {
                abort(429, 'Too many shop requests. Please try again later.');
            });
        });

        // Coupon redemption: prevent brute force
        RateLimiter::for('coupon-redeem', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                abort(429, 'Too many coupon attempts. Please try again later.');
            });
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
