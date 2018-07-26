<?php

namespace App\Providers;

use App\Certificate;
use App\CertificateCategory;
use App\Debtor;
use App\Enums\MembershipStatus;
use App\Enums\OAuthClientType;
use App\Enums\OAuthProvider;
use App\Enums\SortOrderDirection;
use App\GraphQL\Enums\PhpEnumWrapper;
use App\GraphQL\Enums\SortFieldEnum;
use App\GraphQL\Types\Inputs\SortRuleType;
use App\Group;
use App\GroupCategory;
use App\GroupEmailAddress;
use App\KoornbeursCard;
use App\Membership;
use App\Person;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use App\Services\Sorters\PersonSorter;
use App\Services\Sorters\SorterRepository;
use App\User;
use App\UserAccount;
use Illuminate\Support\ServiceProvider;

class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $sorterRepository = app(SorterRepository::class);

        $models = [
            Certificate::class,
            CertificateCategory::class,
            Debtor::class,
            Group::class,
            GroupCategory::class,
            GroupEmailAddress::class,
            KoornbeursCard::class,
            Membership::class,
            Person::class,
            PersonAddress::class,
            PersonEmailAddress::class,
            PersonPhoneNumber::class,
            User::class,
            UserAccount::class
        ];

        foreach ($models as $modelClass) {
            \GraphQL::addType(new SortFieldEnum($modelClass, $sorterRepository));
            \GraphQL::addType(new SortRuleType($modelClass));
        }

        $enums = [
            MembershipStatus::class,
            OAuthClientType::class,
            OAuthProvider::class,
            SortOrderDirection::class
        ];

        foreach ($enums as $enumClass) {
            \GraphQL::addType(new PhpEnumWrapper($enumClass));
        }

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
