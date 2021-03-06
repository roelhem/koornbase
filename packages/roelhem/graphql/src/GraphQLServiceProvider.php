<?php

namespace Roelhem\GraphQL;

use Illuminate\Support\ServiceProvider;
use Roelhem\GraphQL\Contracts\ErrorFormatterContract;
use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Exceptions\ErrorFormatter;
use Roelhem\GraphQL\Repositories\ConnectionTypeRepository;
use Roelhem\GraphQL\Repositories\EnumTypeRepository;
use Roelhem\GraphQL\Repositories\InterfaceTypeRepository;
use Roelhem\GraphQL\Repositories\InternalTypeRepository;
use Roelhem\GraphQL\Repositories\ModelTypeRepository;
use Roelhem\GraphQL\Repositories\EntryTypeRepository;
use Roelhem\GraphQL\Repositories\RequiredTypeRepository;
use Roelhem\GraphQL\Repositories\TypeLoader;
use Roelhem\GraphQL\Repositories\TypeRepository;

class GraphQLServiceProvider extends ServiceProvider
{


    public function boot()
    {

    }


    public function register()
    {
        $this->registerHelper();
        $this->registerTypeRepository();
        $this->registerTypeLoader();
        $this->registerErrorFormatter();
    }

    protected function registerHelper()
    {
        $this->app->singleton(GraphQL::class);
    }


    protected function registerTypeRepository()
    {
        $this->app->bind(TypeRepositoryContract::class, TypeRepository::class);
        $this->app->singleton(TypeRepository::class, function() {
            return new TypeRepository([
                new RequiredTypeRepository(),
                new EntryTypeRepository(     config('graphql.query'), config('graphql.mutation', null) ),
                new EnumTypeRepository(      config('graphql.use.enums')      ),
                new InterfaceTypeRepository( config('graphql.use.interfaces') ),
                new TypeRepository(          config('graphql.use.scalars')    ),
                new ModelTypeRepository(     config('graphql.use.modelTypes') ),
                new TypeRepository(          config('graphql.use.otherTypes') ),
            ]);
        });
    }

    protected function registerTypeLoader()
    {
        $this->app->bind(TypeLoaderContract::class, TypeLoader::class);
        $this->app->singleton(TypeLoader::class);
    }

    protected function registerErrorFormatter()
    {
        $this->app->bind(ErrorFormatterContract::class, ErrorFormatter::class);
        $this->app->singleton(ErrorFormatter::class);
    }


    public function provides()
    {
        return [
            GraphQL::class,
            TypeRepositoryContract::class,
            TypeLoaderContract::class,
            ErrorFormatterContract::class,
        ];
    }

}