<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 18:48
 */

namespace App\Interfaces\Rbac;
use App\Exceptions\Rbac\RoleNotFoundException;
use Illuminate\Support\Collection;

/**
 * Interface RoleAssignable
 *
 * An interface for objects that can have roles assigned to them.
 *
 * @package App\Interfaces\Rbac
 */
interface RbacRoleAssignable
{

    /**
     * Assigns the provided role to this object.
     *
     * @param RbacRole|string $role an instance or id of the Role to be assigned.
     * @return $this
     */
    public function assignRole($role);

    /**
     * Assigns this role to all the roles in the provided $roles array.
     *
     * @param iterable $roles
     * @return mixed
     */
    public function assignRoles(iterable $roles);

    /**
     * Returns a collection of all the roles that are directly assigned to this object.
     *
     * @return RbacRole[]|Collection
     */
    public function getAssignedRoles();

}