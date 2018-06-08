<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:54
 */

namespace App\Interfaces\Rbac;
use App\Exceptions\Rbac\RoleNotFoundException;
use Illuminate\Support\Collection;

/**
 * Interface RoleChild
 *
 * An interface for an object that can be a child of a Role in the RBAC-structure.
 *
 * @package App\Interfaces\Rbac
 */
interface RbacRoleNestable
{
    /**
     * Assigns this permission to a role.
     *
     * @param RbacRole|string $role an instance or id of the parent role.
     * @return $this
     */
    public function assignToRole($role);

    /**
     * Returns a collection of roles that have this object as assigned to it.
     *
     * @return Collection
     */
    public function getParentRoles();
}