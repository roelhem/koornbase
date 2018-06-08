<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 18:37
 */

namespace App\Interfaces\Rbac;

use App\Exceptions\Rbac\PermissionNotFoundException;
use Illuminate\Support\Collection;

/**
 * Interface PermissionAssignable
 *
 * Interface for classes that can have assigned permissions
 *
 * @package App\Interfaces\Rbac
 */
interface RbacPermissionAssignable
{

    /**
     * Assigns the provided permission to this object.
     *
     * @param RbacPermission|string $permission  an instance or id of a permission.
     * @return $this
     */
    public function assignPermission($permission);

    /**
     * @param iterable $permissions
     * @return $this
     */
    public function assignPermissions(iterable $permissions);

    /**
     * Get all the permissions that are directly assigned to this Permission.
     *
     * @return RbacPermission[]|Collection
     */
    public function getAssignedPermissions();

}