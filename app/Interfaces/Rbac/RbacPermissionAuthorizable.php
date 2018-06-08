<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 00:01
 */

namespace App\Interfaces\Rbac;


use App\Exceptions\Rbac\PermissionNotFoundException;
use Illuminate\Support\Collection;

interface RbacPermissionAuthorizable
{

    /**
     * Returns if the current object has the provided permission.
     *
     * @param RbacPermission|string $permission
     * @return boolean
     */
    public function hasPermission($permission);

    /**
     * Returns if the current object has all the permissions in the given array.
     *
     * @param iterable $permissions
     * @return boolean
     */
    public function hasPermissions(iterable $permissions);

    /**
     * Returns if the current object has at least one of the permissions in the given array.
     *
     * @param iterable $permissions
     * @return mixed
     */
    public function hasSomePermissions(iterable $permissions);

    /**
     * Returns all the permissions that this object has.
     *
     * @return RbacPermission[]|Collection
     */
    public function getPermissions();

    /**
     * Returns all the direct child Permissions of this object.
     *
     * @return RbacPermission[]|Collection
     */
    public function getChildPermissions();


}