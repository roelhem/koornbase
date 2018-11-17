<?php

namespace App\Providers;

use App\OAuth\Client;
use App\OAuth\Observers\ClientObserver;
use App\Services\Parsers\ParseService;
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

        Client::observe(ClientObserver::class);

        \Validator::extend('unique_or_same','App\Services\Validators\DatabaseValidator@validateUniqueOrSame');

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

        $this->app->singleton(ParseService::class);

    }
}
