<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProfanityFilter;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
  

public function register()
{
    // Bind ProfanityFilter as a singleton so it can be used globally
    $this->app->singleton(ProfanityFilter::class, function ($app) {
        return new ProfanityFilter();
    });
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
