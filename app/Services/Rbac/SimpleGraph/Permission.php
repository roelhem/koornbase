<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 02:29
 */

namespace App\Services\Rbac\SimpleGraph;


use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAssignable;
use App\Interfaces\Rbac\RbacRole;
use Illuminate\Routing\Route;

class Permission extends Node implements RbacPermission
{

    protected $route;

    /**
     * @inheritdoc
     */
    public function route($route)
    {
        if($route instanceof Route) {
            $this->route = $route->getName();
        } else {
            $this->route = $route;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assignTo(RbacPermissionAssignable $target)
    {
        $target->assignPermission($this);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assignToPermission($permission)
    {
        if($permission instanceof RbacPermission) {
            $id = $permission->getId();
        } elseif(is_string($permission)) {
            $id = $permission;
        } else {
            throw new PermissionNotFoundException;
        }

        $permission = $this->graph->getPermission($id);
        $permission->assignPermission($this);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentPermissions()
    {
        $res = [];
        foreach ($this->graph->getPermissions() as $permission) {
            if($permission instanceof RbacPermission) {
                foreach ($permission->getChildPermissions() as $childPermission) {
                    if(($childPermission instanceof RbacPermission) && $childPermission->getId() === $this->getId()) {
                        $res[] = $permission;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function assignToRole($role)
    {
        if($role instanceof RbacRole) {
            $id = $role->getId();
        } elseif(is_string($role)) {
            $id = $role;
        } else {
            throw new RoleNotFoundException;
        }

        $role = $this->graph->getRole($id);
        $role->assignPermission($this);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentRoles()
    {
        $res = [];
        foreach ($this->graph->getRoles() as $role) {
            if($role instanceof RbacRole) {
                foreach ($role->getChildPermissions() as $childPermission) {
                    if(($childPermission instanceof RbacPermission) && $childPermission->getId() === $this->getId()) {
                        $res[] = $role;
                    }
                }
            }
        }
        return $res;
    }
}