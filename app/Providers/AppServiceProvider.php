<?php

namespace App\Providers;

use App\Services\Navigation\BreadcrumbService;
use App\Services\Navigation\NavbarService;
use App\Services\Navigation\NavigationItemRepository;
use App\Services\Navigation\SitemapService;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NavigationItemRepository::class);

        $this->app->bind(NavbarService::class);
        $this->app->bind(BreadcrumbService::class);
        $this->app->bind(SitemapService::class);

    }
}
