<?php

namespace App\Providers;

use App\Contracts\Finders\FinderCollection;
use App\Services\Finders\CertificateCategoryFinder;
use App\Services\Finders\CertificateFinder;
use App\Services\Finders\GroupCategoryFinder;
use App\Services\Finders\GroupFinder;
use App\Services\Finders\PersonFinder;
use App\Services\Finders\SimpleFinderCollection;
use Illuminate\Support\ServiceProvider;

class ModelFinderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('finds', '\App\Services\Finders\FindableValidator@validatedFindable');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GroupCategoryFinder::class);
        $this->app->singleton(GroupFinder::class);
        $this->app->singleton(PersonFinder::class);
        $this->app->singleton(CertificateFinder::class);
        $this->app->singleton(CertificateCategoryFinder::class);

        $this->app->singleton(SimpleFinderCollection::class, function($app) {
            $res = new SimpleFinderCollection();
            $res->add($app->make(GroupCategoryFinder::class));
            $res->add($app->make(GroupFinder::class));
            $res->add($app->make(PersonFinder::class));
            $res->add($app->make(CertificateFinder::class));
            $res->add($app->make(CertificateCategoryFinder::class));
            return $res;
        });

        $this->app->bind(FinderCollection::class, SimpleFinderCollection::class);
    }
}
