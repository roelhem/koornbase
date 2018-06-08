<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 00:30
 */

namespace App\Services\Rbac\Traits;
use App\Facades\Rbac;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use Illuminate\Support\Collection;

/**
 * Trait DefaultRbacPermissionAuthorizable
 *
 * Default implementation of RbacPermissionAuthorizable using the authorizer from `Rbac::autorizer()`.
 *
 * @package App\Services\Rbac\Traits
 */
trait DefaultRbacPermissionAuthorizable
{

    /**
     * Returns if this object has the given RbacPermission
     *
     * @param RbacPermission|string $permission
     * @return bool
     */
    public function hasPermission($permission) {
        if($this instanceof RbacPermissionAuthorizable) {
            return Rbac::authorizer()->hasPermission($this, $permission);
        }
        return false;
    }

    /**
     * Returns a collection of all the permissions of this RbacPermissionAuthorizable object
     *
     * @return Collection
     */
    public function getPermissions() {
        if($this instanceof RbacPermissionAuthorizable) {
            return Rbac::authorizer()->getPermissions($this);
        }
        return collect([]);
    }

    /**
     * Returns if the object has all permissions.
     *
     * @param iterable $permissions
     * @return bool
     */
    public function hasPermissions(iterable $permissions)
    {
        if($this instanceof RbacPermissionAuthorizable) {
            return Rbac::authorizer()->hasPermissions($this, $permissions);
        }
        return false;
    }

    /**
     * Returns if the object has at least one of the provided permissions.
     *
     * @param iterable $permissions
     * @return bool
     */
    public function hasSomePermissions(iterable $permissions)
    {
        if($this instanceof RbacPermissionAuthorizable) {
            return Rbac::authorizer()->hasSomePermissions($this, $permissions);
        }
        return false;
    }

    /**
     * Returns a collection of all the direct child permissions of this RbacPermissionAuthorizable object.
     *
     * @return Collection
     */
    abstract public function getChildPermissions();

}