<?php

namespace App\Providers;

use Cocur\Slugify\Slugify;
use Illuminate\Support\ServiceProvider;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Slugify::class, function() {
            return new Slugify();
        });

    }
}
