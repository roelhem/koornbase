<?php

namespace App\Providers;

use App\Contracts\Finders\FinderCollection;
use App\Contracts\Finders\ModelFinder;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        foreach (resolve(FinderCollection::class)->list() as $name => $finder) {
            if($finder instanceof ModelFinder) {
                Route::bind($name, function ($value) use ($finder) {
                    try {
                        return $finder->find($value);
                    } catch (ModelNotFoundException $exception) {
                        abort(404, "Kon geen '{$finder->modelName()}' vinden met de waarde '{$value}'.");
                    } catch (InputNotAcceptedException $exception) {
                        abort(404, "Ongeldige waarde voor een '{$finder->modelName()}'.");
                    }
                });
            }
        }
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapGraphQLRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "graphql" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapGraphQLRoutes()
    {
        Route::prefix('graphql')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/graphql.php'));
    }
}
