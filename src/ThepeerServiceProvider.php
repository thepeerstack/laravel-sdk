<?php

namespace Thepeer\Sdk;

use Illuminate\Support\ServiceProvider;

class ThepeerServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('laravel-thepeer', function () {
            return new Thepeer;
        });
    }

    /**
     * Get the services provided by the provider
     * @return array
     */
    public function provides()
    {
        return ['laravel-thepeer'];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}