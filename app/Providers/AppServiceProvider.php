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
        // No database registration needed for our JSON-based product inventory
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure the storage directory exists for products.json
        $storagePath = storage_path('app');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        
        // Create an empty products.json file if it doesn't exist
        $productsPath = storage_path('app/products.json');
        if (!file_exists($productsPath)) {
            file_put_contents($productsPath, json_encode([]));
        }
    }
}
