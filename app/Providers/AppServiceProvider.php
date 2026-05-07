<?php

namespace App\Providers;

use App\Models\StockMutation;
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
        // Register the StockMutationObserver to observe the StockMutation model
        StockMutation::observe(\App\Observers\StockMutationObserver::class);
    }
}
