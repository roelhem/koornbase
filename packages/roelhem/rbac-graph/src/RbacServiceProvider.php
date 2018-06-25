<?php

namespace Roelhem\RbacGraph;

use Illuminate\Support\ServiceProvider;
use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Commands\InitCommand;
use Roelhem\RbacGraph\Commands\NodesCommand;
use Roelhem\RbacGraph\Commands\TypesCommand;
use Roelhem\RbacGraph\Contracts\Builder;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\PathFinder;
use Roelhem\RbacGraph\Contracts\RbacService;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\DatabasePathFinder;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Observers\EdgeObserver;
use Roelhem\RbacGraph\Database\Observers\NodeObserver;
use Roelhem\RbacGraph\Database\Observers\PathObserver;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Services\DefaultRbacService;

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
                InitCommand::class,
                TypesCommand::class,
                NodesCommand::class,
            ]);
        }

        Node::observe(NodeObserver::class);
        Edge::observe(EdgeObserver::class);
        Path::observe(PathObserver::class);

    }

    /**
     * Registering the application services.
     *
     * @return void
     */
    public function register() {


        // The graphs
        $this->app->singleton(DatabaseGraph::class);
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

    }

}