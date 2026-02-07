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
        // Cargar helpers globales (currency, status, etc.)
        $helperPath = app_path('Helpers/CurrencyHelper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
        
        $statusHelperPath = app_path('Helpers/StatusHelper.php');
        if (file_exists($statusHelperPath)) {
            require_once $statusHelperPath;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\ProductVariant::observe(\App\Observers\ProductVariantObserver::class);
        \App\Models\LiveSession::observe(\App\Observers\LiveSessionObserver::class);
    }
}
