<?php

namespace Roelhem\RbacGraph;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Http\GraphQL\GraphQLService;
use Roelhem\RbacGraph\Services\Builders\RbacBuilder;
use Roelhem\RbacGraph\Commands\NodesCommand;
use Roelhem\RbacGraph\Commands\TypesCommand;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Services\Builder;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Graphs\MutableGraph;
use Roelhem\RbacGraph\Contracts\Tools\PathFinder;
use Roelhem\RbacGraph\Contracts\Services\RbacService;
use Roelhem\RbacGraph\Database\Tools\DatabaseAuthorizer;
use Roelhem\RbacGraph\Database\Tools\DatabasePathFinder;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Observers\EdgeObserver;
use Roelhem\RbacGraph\Database\Observers\NodeObserver;
use Roelhem\RbacGraph\Database\Observers\PathObserver;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Services\CompatibilityService;
use Roelhem\RbacGraph\Services\DefaultRbacService;
use Roelhem\RbacGraph\Services\RuleSerializerService;

/**
 * Class RbacServiceProvider
 *
 * The service-provider for the RbacGraph package
 *
 * @package Roelhem\RbacGraph
 */
class RbacServiceProvider extends ServiceProvider
{

    /**
     * Bootstrapping the application services.
     *
     * @return void
     */
    public function boot() {

        // Registering the migrations needed when using the database implementation.
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        // Registering the commands
        if($this->app->runningInConsole()) {
            $this->commands([
                TypesCommand::class,
                NodesCommand::class,
            ]);
        }

        // Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views','rbac-graph');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/rbac-graph')
        ], 'public');

        // Observers
        Node::observe(NodeObserver::class);
        Edge::observe(EdgeObserver::class);
        Path::observe(PathObserver::class);

        // Gates
        resolve(CompatibilityService::class)->registerGate();

        // GraphQL
        resolve(GraphQLService::class)->register();


    }

    /**
     * Registering the application services.
     *
     * @return void
     */
    public function register() {


        // The graphs
        $this->app->singleton(DatabaseGraph::class);
        $this->app->bind(AuthorizableGraph::class, DatabaseGraph::class);
        $this->app->bind(MutableGraph::class, DatabaseGraph::class);
        $this->app->bind(Graph::class, DatabaseGraph::class);

        // The pathfinder
        $this->app->singleton(DatabasePathFinder::class);
        $this->app->bind(PathFinder::class, DatabasePathFinder::class);


        // The builders
        $this->app->bind(RbacBuilder::class);
        $this->app->bind(Builder::class, RbacBuilder::class);

        // The Services
        $this->app->singleton(DefaultRbacService::class);
        $this->app->bind(RbacService::class, DefaultRbacService::class);

        // CompatibilityService
        $this->app->bind(CompatibilityService::class);

        // RuleSerializer
        $this->app->singleton(RuleSerializerService::class);
        $this->app->bind(RuleSerializer::class, RuleSerializerService::class);


        // GraphQL
        $this->app->singleton(GraphQLService::class);

    }

}