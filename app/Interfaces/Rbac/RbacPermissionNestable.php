<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:54
 */

namespace App\Interfaces\Rbac;
use App\Exceptions\Rbac\PermissionNotFoundException;
use Illuminate\Support\Collection;

/**
 * Interface PermissionChild
 *
 * Interface for an object that can be a child of a permission in the RBAC-structure
 *
 * @package App\Interfaces\Rbac
 */
interface RbacPermissionNestable
{
    /**
     * Assigns this permission to another permission.
     *
     * @param RbacPermission|string $permission an instance or id of the parent permission.
     * @return $this
     */
    public function assignToPermission($permission);

    /**
     * Returns a collection of all the permissions that have this object assigned to it.
     *
     * @return Collection
     */
    public function getParentPermissions();
}