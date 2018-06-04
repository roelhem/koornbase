<?php

namespace App\Providers;

use App\Certificate;
use App\CertificateCategory;
use App\Contracts\Finders\FinderCollection;
use App\Group;
use App\GroupCategory;
use App\Membership;
use App\Person;
use App\Services\Finders\GroupEmailAddressFinder;
use App\Services\Finders\KoornbeursCardFinder;
use App\Services\Finders\ModelByIdFinder;
use App\Services\Finders\ModelByIdOrSlugFinder;
use App\Services\Finders\SimpleFinderCollection;
use App\Services\Finders\UserFinder;
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

        $this->app->singleton(SimpleFinderCollection::class, function($app) {
            $res = new SimpleFinderCollection();
            $res->add(new ModelByIdOrSlugFinder('group', Group::class));
            $res->add(new ModelByIdOrSlugFinder('group_category', GroupCategory::class));
            $res->add(new ModelByIdFinder('person', Person::class));
            $res->add(new ModelByIdFinder('certificate', Certificate::class));
            $res->add(new ModelByIdFinder('membership', Membership::class));
            $res->add(new ModelByIdOrSlugFinder('certificate_category', CertificateCategory::class));
            $res->add(new GroupEmailAddressFinder());
            $res->add(new UserFinder());
            $res->add(new KoornbeursCardFinder());
            return $res;
        });

        $this->app->bind(FinderCollection::class, SimpleFinderCollection::class);
    }
}
