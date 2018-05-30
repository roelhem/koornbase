<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-05-18
 * Time: 23:10
 */

namespace App\Services\Rbac;


use App\Permission;
use App\Role;

trait WithRbacParamParsers
{

    /**
     * Returns a Role instance from the given input.
     *
     * @param Role|string $role
     * @return Role
     * @throws
     */
    protected function getRoleModel($role) {
        if($role instanceof Role) {
            return $role;
        } elseif(is_string($role)) {
            return Role::findOrFail($role);
        }
        throw new \Exception("Parameter has to be an instance of Role or a role_id string.");
    }

    /**
     * Returns a Role-id string from the given input.
     *
     * @param Role|string $role
     * @return string
     * @throws
     */
    protected function getRoleId($role) {
        if(is_string($role)) {
            return $role;
        } elseif($role instanceof Role) {
            return $role->id;
        }
        throw new \Exception("Parameter has to be an instance of Role or a role_id string.");
    }

    /**
     * Returns a Permission instance from the given input.
     *
     * @param Permission|string $permission
     * @return Permission
     * @throws
     */
    protected function getPermissionModel($permission) {
        if($permission instanceof Permission) {
            return $permission;
        } elseif(is_string($permission)) {
            return Permission::findOrFail($permission);
        }
        throw new \Exception("Parameter has to be an instance of Permission or a permission_id string.");
    }

    /**
     * Returns a Permission-id from the given input.
     *
     * @param Permission|string $permission
     * @return string
     * @throws
     */
    protected function getPermissionId($permission) {
        if(is_string($permission)) {
            return $permission;
        } elseif($permission instanceof Permission) {
            return $permission->id;
        }
        throw new \Exception("Parameter has to be an instance of Permission or a permission_id string.");
    }
}
