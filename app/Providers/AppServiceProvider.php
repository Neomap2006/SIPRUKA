<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->hasHeader('x-forwarded-host')) {
            $scheme = request()->header('x-forwarded-proto', 'https');
            $host = request()->header('x-forwarded-host');
            \Illuminate\Support\Facades\URL::forceRootUrl("{$scheme}://{$host}");
        } elseif (request()->hasHeader('x-forwarded-proto') && request()->header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
