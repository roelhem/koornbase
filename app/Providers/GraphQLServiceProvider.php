<?php

namespace App\Providers;

use App\Enums\GraphQLOperationType;
use App\Enums\MembershipStatus;
use App\Enums\OAuthClientType;
use App\Enums\OAuthProvider;
use App\Enums\OAuthScope;
use App\Enums\SortOrderDirection;
use App\Services\GraphQL\GraphQL;
use App\Services\GraphQL\GraphQLBuilder;
use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\DisableIntrospection;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;
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
        $this->bootTypes();
        //$this->bootEnumTypes();

        /** @var GraphQL $gql */
        $gql = $this->app['graphql'];
        $this->bootSchemas();
    }

    /**
     * Adds the Enum-types that represent the Enum-classes on the server.
     *
     * @return void
     */
    protected function bootEnumTypes()
    {
        /** @var GraphQL $gql */
        $gql = $this->app['graphql'];
        $gql->addType(MembershipStatus::getGraphQLType());
        $gql->addType(OAuthClientType::getGraphQLType());
        $gql->addType(OAuthProvider::getGraphQLType());
        $gql->addType(SortOrderDirection::getGraphQLType());
        $gql->addType(OAuthScope::getGraphQLType());
        $gql->addType(GraphQLOperationType::getGraphQLType());
    }

    /**
     * Bootstrap publishes
     *
     * @return void
     */
    protected function bootTypes()
    {
        /** @var GraphQL $gql */
        $gql = $this->app['graphql'];
        $configTypes = config('graphql.types');
        foreach($configTypes as $name => $type)
        {
            if(is_numeric($name))
            {
                $gql->addType($type);
            }
            else
            {
                $gql->addType($type, $name);
            }
        }
    }
    /**
     * Add schemas from config
     *
     * @return void
     */
    protected function bootSchemas()
    {
        /** @var GraphQL $gql */
        $gql = $this->app['graphql'];
        $configSchemas = config('graphql.schemas');
        foreach ($configSchemas as $name => $schema) {
            $gql->addSchema($name, $schema);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGraphQL();
        $this->app->singleton(GraphQLBuilder::class);
    }

    /**
     * Registers the GraphQL application service
     */
    public function registerGraphQL()
    {
        $this->app->singleton('graphql', function($app) {
            $res = new GraphQL($app);

            $this->applySecurityRules();

            return $res;
        });
    }

    /**
     * Configure security from config
     *
     * @return void
     */
    protected function applySecurityRules()
    {
        $maxQueryComplexity = config('graphql.security.query_max_complexity');
        if ($maxQueryComplexity !== null) {
            /** @var QueryComplexity $queryComplexity */
            $queryComplexity = DocumentValidator::getRule('QueryComplexity');
            $queryComplexity->setMaxQueryComplexity($maxQueryComplexity);
        }
        $maxQueryDepth = config('graphql.security.query_max_depth');
        if ($maxQueryDepth !== null) {
            /** @var QueryDepth $queryDepth */
            $queryDepth = DocumentValidator::getRule('QueryDepth');
            $queryDepth->setMaxQueryDepth($maxQueryDepth);
        }
        $disableIntrospection = config('graphql.security.disable_introspection');
        if ($disableIntrospection === true) {
            /** @var DisableIntrospection $disableIntrospection */
            $disableIntrospection = DocumentValidator::getRule('DisableIntrospection');
            $disableIntrospection->setEnabled(DisableIntrospection::ENABLED);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['graphql', GraphQLBuilder::class];
    }
}
