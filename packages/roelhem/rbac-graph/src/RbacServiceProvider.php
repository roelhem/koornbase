<?php

namespace Roelhem\RbacGraph;

use Illuminate\Support\ServiceProvider;
use Roelhem\RbacGraph\Database\DatabaseGraph;

/**
 * Class RbacServiceProvider
 *
 * The service-provider for the RbacGraph package
 *
 * @package Roelhem\RbacGraph
 */
class RbacServiceProvider extends ServiceProvider
{

    /**
     * Bootstrapping the application services.
     *
     * @return void
     */
    public function boot() {

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

    }

    /**
     * Registering the application services.
     *
     * @return void
     */
    public function register() {

        $this->app->singleton(DatabaseGraph::class);

    }

}