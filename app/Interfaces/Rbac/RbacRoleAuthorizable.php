<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 23:58
 */

namespace App\Interfaces\Rbac;


use App\Exceptions\Rbac\RoleNotFoundException;
use Illuminate\Support\Collection;

interface RbacRoleAuthorizable
{
    /**
     * Returns if this object has the provided role.
     *
     * @param RbacRole|string $role
     * @return boolean
     */
    public function hasRole($role);

    /**
     * Returns if this object has all the roles in the given array.
     *
     * @param iterable $roles
     * @return boolean
     */
    public function hasRoles(iterable $roles);

    /**
     * Returns if this object has at least one of the roles in the given array.
     *
     * @param iterable $roles
     * @return mixed
     */
    public function hasSomeRoles(iterable $roles);

    /**
     * Returns a list of all the roles that this object has.
     *
     * @return RbacRole[]|Collection
     */
    public function getRoles();

    /**
     * Returns a list of all roles that are direct children of this object.
     *
     * @return RbacRole[]|Collection
     */
    public function getChildRoles();
}