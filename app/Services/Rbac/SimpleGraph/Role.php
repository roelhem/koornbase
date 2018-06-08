<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 02:20
 */

namespace App\Services\Rbac\SimpleGraph;


use App\Exceptions\Rbac\RoleNotFoundException;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAssignable;

class Role extends Node implements RbacRole
{

    protected $is_required = false;

    /**
     * @inheritdoc
     */
    public function required($is_required = true)
    {
        $this->is_required = true;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assignTo(RbacRoleAssignable $target)
    {
        $target->assignRole($this);
        return $this;
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
        $role->assignRole($this);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentRoles()
    {
        $res = [];
        foreach ($this->graph->getRoles() as $role) {
            if(($role instanceof RbacRole) && $role->getId() !== $this->getId()) {
                foreach ($role->getChildRoles() as $childRole) {
                    if(($childRole instanceof RbacRole) && $childRole->getId() === $this->getId()) {
                        $res[] = $role;
                    }
                }
            }
        }
        return $res;
    }
}