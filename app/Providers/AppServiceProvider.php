<?php

namespace App\Providers;

use App\Contracts\Rbac\RbacAuthorizer;
use App\Contracts\Rbac\RbacBuilder;
use App\Contracts\Rbac\RbacChecker;
use App\Contracts\Rbac\RbacGraph;
use App\Services\Navigation\BreadcrumbService;
use App\Services\Navigation\NavbarService;
use App\Services\Navigation\NavigationItemRepository;
use App\Services\Navigation\SitemapService;
use App\Services\Rbac\Authorizers\SimpleRbacAuthorizer;
use App\Services\Rbac\DatabaseRbacBuilder;
use App\Services\Rbac\DatabaseRbacGraph;
use App\Services\Rbac\RbacGenerator;
use App\Services\Rbac\SimpleRbacChecker;
use App\Services\Sorters\CertificateCategorySorter;
use App\Services\Sorters\CertificateSorter;
use App\Services\Sorters\GroupCategorySorter;
use App\Services\Sorters\GroupSorter;
use App\Services\Sorters\KoornbeursCardSorter;
use App\Services\Sorters\MembershipSorter;
use App\Services\Sorters\PersonSorter;
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
        
        $this->app->singleton(PersonSorter::class);
        $this->app->singleton(GroupSorter::class);
        $this->app->singleton(GroupCategorySorter::class);
        $this->app->singleton(CertificateSorter::class);
        $this->app->singleton(CertificateCategorySorter::class);
        $this->app->singleton(KoornbeursCardSorter::class);
        $this->app->singleton(MembershipSorter::class);
        

        $this->app->singleton(NavigationItemRepository::class);

        $this->app->bind(NavbarService::class);
        $this->app->bind(BreadcrumbService::class);
        $this->app->bind(SitemapService::class);

    }
}
