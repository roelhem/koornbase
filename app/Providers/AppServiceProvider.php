<?php

namespace App\Providers;

use App\Services\Navigation\BreadcrumbService;
use App\Services\Navigation\NavbarService;
use App\Services\Navigation\NavigationItemRepository;
use App\Services\Navigation\SitemapService;
use App\Services\Sorters\CertificateCategorySorter;
use App\Services\Sorters\CertificateSorter;
use App\Services\Sorters\GroupCategorySorter;
use App\Services\Sorters\GroupSorter;
use App\Services\Sorters\KoornbeursCardSorter;
use App\Services\Sorters\MembershipSorter;
use App\Services\Sorters\PersonSorter;
use App\Services\Sorters\SorterRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Carbon::serializeUsing(function(\Carbon\Carbon $carbon) {
            return $carbon->format('Y-m-d H:i:s');
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Passport::ignoreMigrations();

        $this->app->singleton(SorterRepository::class);

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
