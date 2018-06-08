<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 00:37
 */

namespace App\Services\Rbac\Traits;


use App\Facades\Rbac;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;

trait DefaultRbacRoleAuthorizable
{

    /**
     * Returns if the current object has the provided role.
     *
     * @param RbacRole|string $role
     * @return bool
     */
    public function hasRole($role)
    {
        if($this instanceof RbacRoleAuthorizable) {
            return Rbac::authorizer()->hasRole($this, $role);
        }
        return false;
    }

    /**
     * Returns if the current object has all the provided roles.
     *
     * @param iterable $roles
     * @return bool
     */
    public function hasRoles(iterable $roles)
    {
        if($this instanceof RbacRoleAuthorizable) {
            return Rbac::authorizer()->hasRoles($this, $roles);
        }
        return false;
    }

    /**
     * Returns if the current object has at least one of the provided roles.
     *
     * @param iterable $roles
     * @return false;
     */
    public function hasSomeRoles(iterable $roles)
    {
        if($this instanceof RbacRoleAuthorizable) {
            return Rbac::authorizer()->hasRole($this, $roles);
        }
        return false;
    }

    /**
     * Returns all the roles of this object.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRoles()
    {
        if($this instanceof RbacRoleAuthorizable) {
            return Rbac::authorizer()->getRoles($this);
        }
        return collect([]);
    }

    /**
     * Returns the roles that are direct children of this role.
     *
     * @return mixed
     */
    abstract public function getChildRoles();

}