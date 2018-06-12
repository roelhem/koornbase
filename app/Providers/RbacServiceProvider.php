<?php

namespace App\Providers;

use App\Contracts\Rbac\RbacService;
use App\Services\Rbac\DatabaseService;
use App\Services\Rbac\RbacGenerator;
use App\Services\Rbac\RbacPostgres;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
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
        $this->app->singleton(RbacGenerator::class);


        $this->app->singleton(DatabaseService::class);
        $this->app->bind(RbacService::class, DatabaseService::class);

        $this->app->singleton(RbacPostgres::class);
    }
}
