<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:26
 */

namespace App\Services\Rbac;


use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Permission;
use App\Role;

class DatabaseProvider extends AbstractProvider
{

    /**
     * @inheritdoc
     */
    protected function allowAsRoleInstance($role): bool
    {
        return $role instanceof Role;
    }

    /**
     * @inheritdoc
     */
    protected function allowAsPermissionInstance($permission): bool
    {
        return $permission instanceof Permission;
    }

    /**
     * @inheritdoc
     */
    protected function getPermissionByRouteName(string $routeName)
    {
        $permission = Permission::query()->where('route','=',$routeName)->first();
        if($permission instanceof Permission) {
            return $permission;
        }
        return $permission;
    }

    /**
     * @inheritdoc
     */
    public function getRoleById(string $id)
    {
        $role = Role::find($id);
        if($role instanceof Role) {
            return $role;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getPermissionById(string $id)
    {
        $permission = Permission::find($id);
        if($permission instanceof Permission) {
            return $permission;
        }
        return null;
    }
}