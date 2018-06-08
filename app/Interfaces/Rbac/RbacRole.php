<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 17:44
 */

namespace App\Interfaces\Rbac;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;

/**
 * Interface Role
 *
 * Interfaces for classes that model roles in RBAC-structures.
 *
 * @package App\Interfaces\Rbac
 */
interface RbacRole extends RbacNode, RbacPermissionAssignable, RbacRoleAssignable, RbacRoleNestable, RbacAuthorizable
{

    /**
     * Sets the required state of this role in a fluent way.
     *
     * @param bool $is_required
     * @return $this
     */
    public function required($is_required = true);

    /**
     * Assigns this role to the target RoleAssignable instance.
     *
     * @param RbacRoleAssignable $target
     * @return $this
     */
    public function assignTo(RbacRoleAssignable $target);

    /**
     * Assigns a Role or Permission to this Role.
     *
     * The assigned node will be a child of this role.
     *
     * @param RbacNode $node
     * @return $this
     */
    public function assign($node);

    /**
     * Assigns multiple Roles or Permissions to this Role.
     *
     * @param iterable $nodes
     * @return $this
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
     */
    public function assignAll(iterable $nodes);

}