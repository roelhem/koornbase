<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 14:45
 */

namespace App\Services\Rbac;


use App\Facades\Rbac;
use App\Permission;
use Illuminate\Routing\Route;

class RbacGenerator
{

    /**
     * Runs all the methods to build the rbac-graph.
     */
    public function run() {

        $this->defineRoutePermissions();
        $this->fromRbacFolder();
        $this->assignPermissionsToAdmin();
    }

    /**
     * Defines the roles for the groups.
     */
    public function fromRbacFolder() {

        require_once( __DIR__.'/../../../rbac/constraints/common.php');

        require_once( __DIR__.'/../../../rbac/required.php' );
        require_once( __DIR__.'/../../../rbac/groups.php' );
        require_once( __DIR__.'/../../../rbac/crud.php' );

    }

    /**
     * Defines the permissions based on the currently defined routes.
     */
    public function defineRoutePermissions()
    {
        $routes = \Route::getRoutes();

        $ids = [];

        foreach ($routes->getRoutesByName() as $name => $route) {
            if($route instanceof Route) {
                if(in_array('rbac', $route->gatherMiddleware())) {
                    $uri = $route->uri;
                    $methods = implode('|',$route->methods);
                    $id = "route.$name";
                    $ids[] = $id;

                    Rbac::permission(
                        $id,
                        "Toegang tot Request: [$methods] $uri",
                        "Geeft toegang tot de route met naam '$name'. Deze route hoort bij de HTTP-Request naar de uri $uri met als HTTP-Method(s) $methods."
                    )->route($route);
                }
            }
        }

        Rbac::permission('route.all', 'Toegang tot alle Http-requests.',
            "Geeft toegang tot alle routes die met een permissions zijn beveiligd.")->assignPermissions($ids);
    }

    /**
     * Assigns all the currently defined permissions to the admin role.
     */
    public function assignPermissionsToAdmin()
    {
        $admin = Rbac::role('admin');

        $admin->assignPermissions(Permission::all('id')->flatten());
    }

}