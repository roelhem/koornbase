<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 08:39
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacProvider;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Permission;
use App\Role;
use Illuminate\Routing\Route;

class DatabaseRbacProvider implements RbacProvider
{

    /**
     * Returns a Role model from the database based on the $role parameter.
     *
     * @param Role|RbacRole|string $role
     * @return Role|null
     */
    public function getRole($role)
    {
        if ($role instanceof Role) {
            return $role;
        }

        if ($role instanceof RbacRole) {
            $role = $role->getId();
        }

        if (is_string($role)) {
            return $this->getRoleById($role);
        }

        return null;
    }

    /**
     * Returns a Role-id based on the given parameters.
     *
     * @param Role|RbacRole|string $role
     * @return string|null
     */
    public function getRoleId($role)
    {
        if(is_string($role)) {
            return $role;
        }

        if($role instanceof RbacRole) {
            return $role->getId();
        }

        return null;
    }

    /**
     * Returns a Role model from the database with the id $id.
     *
     * @param string $id
     * @return Role|null
     */
    public function getRoleById(string $id)
    {
        return Role::find($id);
    }

    /**
     * Returns a Permission model from the database based on the $permission parameter.
     *
     * @param RbacPermission|string $permission
     * @return Permission|null $permission
     */
    public function getPermission($permission)
    {
        if ($permission instanceof Permission) {
            return $permission;
        }

        if($permission instanceof RbacPermission) {
            $permission = $permission->getId();
        }

        if(is_string($permission)) {
            return $this->getPermissionById($permission);
        }

        return null;
    }

    /**
     * Returns the id of the Permission described by the $permission parameter.
     *
     * @param RbacPermission|string $permission
     * @return string|null
     */
    public function getPermissionId($permission)
    {
        if(is_string($permission)) {
            return $permission;
        }

        if($permission instanceof RbacPermission) {
            return $permission->getId();
        }

        return null;
    }

    /**
     * Returns a Permission model from the database with the id $id.
     *
     * @param string $id
     * @return Permission|null
     */
    public function getPermissionById(string $id)
    {
        return Permission::find($id);
    }

    /**
     * Returns a Permission model from the database based on the provided $route.
     *
     * @param Route|string $route  a name or instance of a route.
     * @return Permission|null
     */
    public function getPermissionByRoute($route)
    {
        if($route instanceof Route) {
            $route = $route->getName();
        }

        if(is_string($route)) {
            $permission = Permission::query()->where('route', '=', $route)->first();
            if($permission instanceof Permission) {
                return $permission;
            }
        }

        return null;
    }

}