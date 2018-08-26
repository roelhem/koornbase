<?php

namespace App\Providers;

use App\Certificate;
use App\CertificateCategory;
use App\Debtor;
use App\Enums\MembershipStatus;
use App\Enums\OAuthClientType;
use App\Enums\OAuthProvider;
use App\Enums\OAuthScope;
use App\Enums\SortOrderDirection;
use App\Http\GraphQL\Enums\PhpEnumWrapper;
use App\Http\GraphQL\Enums\SortFieldEnum;
use App\Http\GraphQL\Types\Inputs\SortRuleType;
use App\Group;
use App\GroupCategory;
use App\GroupEmailAddress;
use App\KoornbeursCard;
use App\Membership;
use App\OAuth\Client;
use App\Person;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
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
            UserAccount::class,
            Client::class => 'OAuthClient',
        ];

        foreach ($models as $key => $value) {
            if(is_string($key)) {
                $modelClass = $key;
                $typeName = $value;
            } else {
                $modelClass = $value;
                $typeName = null;
            }
            \GraphQL::addType(new SortFieldEnum($modelClass, $sorterRepository, $typeName));
            \GraphQL::addType(new SortRuleType($modelClass, $typeName));
        }

        $enums = [
            MembershipStatus::class => [
                "description" => 'This `Enum`-type contains the different states in which the membership of a Person at the Koornbeurs can be.'
            ],
            OAuthClientType::class => [
                "description" => 'This `Enum`-type contains the different types of OAuthClients that exist for the OAuth server of the KoornBase.'
            ],
            OAuthProvider::class => [
                "description" => "This `Enum`-type represent the different (OAuth protected) online services that the KoornBase uses as a client."
            ],
            SortOrderDirection::class => [
                "description" => "This `Enum`-type represent the two different ways to order a sorted list."
            ],
            OAuthScope::class => [
                "description" => "This `Enum`-type represent the Token Scopes in the system. These Scopes give an OAuth-Token more permissions."
            ],
        ];

        foreach ($enums as $enumClass => $attributes) {
            \GraphQL::addType(new PhpEnumWrapper($enumClass, $attributes));
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
