<?php

namespace App\Providers;

use App\Certificate;
use App\CertificateCategory;
use App\Group;
use App\GroupCategory;
use App\GroupEmailAddress;
use App\Person;
use App\User;
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

        Route::bind('group', function($value) {
            if(ctype_digit($value)) {
                return Group::findOrFail($value);
            } else {
                return Group::findBySlugOrFail($value);
            }
        });

        Route::bind('group-category', function($value) {
            if(ctype_digit($value)) {
                return GroupCategory::findOrFail($value);
            } else {
                return GroupCategory::findBySlugOrFail($value);
            }
        });

        Route::bind('group_email_address',function($value) {
            if(ctype_digit($value)) {
                return GroupEmailAddress::findOrFail($value);
            } else {
                return GroupEmailAddress::where('email_address', $value)->first() ?? abort(404);
            }
        });

        Route::bind('user', function($value) {
            if(ctype_digit($value)) {
                return User::findOrFail($value);
            } else {
                return User::where('name', $value)->first() ?? abort(404);
            }
        });

        Route::bind('person', function($value) {
            return Person::findOrFail($value);
        });

        Route::bind('certificates', function($value) {
            return Certificate::findOrFail($value);
        });

        Route::bind('certificate-category', function($value) {
            if(ctype_digit($value)) {
                return CertificateCategory::findOrFail($value);
            } else {
                return CertificateCategory::findBySlugOrFail($value);
            }
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
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
}
