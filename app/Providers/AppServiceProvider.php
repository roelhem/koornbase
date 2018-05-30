<?php

namespace App\Providers;

use App\Contracts\Rbac\RbacChecker;
use App\Contracts\Rbac\RbacGraph;
use App\Services\Navigation\BreadcrumbService;
use App\Services\Navigation\NavbarService;
use App\Services\Navigation\NavigationItemRepository;
use App\Services\Navigation\SitemapService;
use App\Services\Rbac\DatabaseRbacGraph;
use App\Services\Rbac\SimpleRbacChecker;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Carbon::serializeUsing(function($carbon) {
            return $carbon->format('c');
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(DatabaseRbacGraph::class);
        $this->app->bind(RbacGraph::class, DatabaseRbacGraph::class);


        $this->app->singleton(SimpleRbacChecker::class);
        $this->app->bind(RbacChecker::class, SimpleRbacChecker::class);


        $this->app->singleton(NavigationItemRepository::class);

        $this->app->bind(NavbarService::class);
        $this->app->bind(BreadcrumbService::class);
        $this->app->bind(SitemapService::class);

    }
}
