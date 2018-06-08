<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 08:06
 */

namespace App\Contracts\Rbac;


use App\Interfaces\Rbac\RbacRole;

/**
 * Contract RbacRequestHandler
 *
 * A contract for services that handle the Rbac functions for the current request.
 *
 * @package App\Contracts\Rbac
 */
interface RbacRequestHandler
{

    /**
     * Determines if the current request has the given $role.
     *
     * @param RbacRole|string   $role   an instance or id of the searched role.
     * @return bool
     */
    public function hasRole($role) : bool ;

    /**
     * Determines if the current request has the given $permission.
     *
     * @param RbacRole|string   $permission   an instance or id of the searched permission.
     * @return bool
     */
    public function hasPermission($permission) : bool ;

    public function userRoles() ;

    public function userPermissions() ;
    

}