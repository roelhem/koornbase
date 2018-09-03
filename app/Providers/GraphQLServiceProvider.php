<?php

namespace App\Providers;

use App\Certificate;
use App\CertificateCategory;
use App\Debtor;
use App\Enums\GraphQLOperationType;
use App\Enums\MembershipStatus;
use App\Enums\OAuthClientType;
use App\Enums\OAuthProvider;
use App\Enums\OAuthScope;
use App\Enums\SortOrderDirection;
use App\Http\GraphQL\Enums\SortFieldEnum;
use App\Http\GraphQL\Types\Inputs\SortRuleType;
use App\Group;
use App\GroupCategory;
use App\GroupEmailAddress;
use App\Http\GraphQL\Types\LogGraphQLOperationType;
use App\KoornbeursCard;
use App\Logs\LogGraphQLOperation;
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
            LogGraphQLOperation::class,
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


        $this->bootEnumTypes();
    }

    /**
     * Adds the Enum-types that represent the Enum-classes on the server.
     *
     * @return void
     */
    protected function bootEnumTypes()
    {
        \GraphQL::addType(MembershipStatus::getGraphQLType());
        \GraphQL::addType(OAuthClientType::getGraphQLType());
        \GraphQL::addType(OAuthProvider::getGraphQLType());
        \GraphQL::addType(SortOrderDirection::getGraphQLType());
        \GraphQL::addType(OAuthScope::getGraphQLType());
        \GraphQL::addType(GraphQLOperationType::getGraphQLType());
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
