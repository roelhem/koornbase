<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 22:00
 */

namespace App\Contracts\Rbac;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;
use Illuminate\Support\Collection;

/**
 * Contract RbacAuthorizer
 *
 * A contract for a service that walks trough a RBAC-structure to authorize roles and permissions of
 * RbacAuthorizable objects.
 *
 * @package App\Contracts\Rbac
 */
interface RbacAuthorizer
{

    /**
     * Returns if the provided RbacAuthorizable object has the provided RbacNode.
     *
     * @param RbacAuthorizable $authorizable
     * @param RbacNode $node
     * @return bool
     */
    public function has(RbacAuthorizable $authorizable, RbacNode $node) : bool ;

    /**
     * Returns if the provided RbacAuthorizable object has all of the provided RbacNodes.
     *
     * @param RbacAuthorizable $authorizable
     * @param iterable $nodes
     * @return bool
     */
    public function hasAll(RbacAuthorizable $authorizable, iterable $nodes) : bool ;

    /**
     * Returns if the provided RbacAuthorizable object has at least one of the provided RbacNodes.
     *
     * @param RbacAuthorizable $authorizable
     * @param iterable $nodes
     * @return bool
     */
    public function hasSome(RbacAuthorizable $authorizable, iterable $nodes) : bool ;

    /**
     * Returns if the provided RbacAuthorizable object has the provided role.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @param RbacRole|string $role
     * @return bool
     */
    public function hasRole(RbacRoleAuthorizable $authorizable, $role) : bool ;

    /**
     * Returns if the provided RbacRoleAuthorizable object has all the roles in the $roles parameter.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @param iterable $roles
     * @return mixed
     */
    public function hasRoles(RbacRoleAuthorizable $authorizable, iterable $roles) : bool ;

    /**
     * Returns if the provided RbacRoleAuthorizable object has at least one role in the $roles parameter.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @param iterable $roles
     * @return bool
     */
    public function hasSomeRoles(RbacRoleAuthorizable $authorizable, iterable $roles) : bool ;

    /**
     * Returns if the provided RbacAuthorizable object has the provided RbacNode
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @param RbacPermission|string $permission
     * @return bool
     */
    public function hasPermission(RbacPermissionAuthorizable $authorizable, $permission) : bool ;

    /**
     * Returns if the provided RbacPermissionAuthorizable object has all the provided $permissions.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @param iterable $permissions
     * @return bool
     */
    public function hasPermissions(RbacPermissionAuthorizable $authorizable, iterable $permissions) : bool ;

    /**
     * Returns if the provided RbacPermissionAuthorizable object has at least one of the provided $permissions.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @param iterable $permissions
     * @return bool
     */
    public function hasSomePermissions(RbacPermissionAuthorizable $authorizable, iterable $permissions) : bool ;

    /**
     * Returns all the roles of the RbacRoleAuthorizable object.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @return Collection
     */
    public function getRoles(RbacRoleAuthorizable $authorizable);

    /**
     * Returns all the permissions of a RbacPermissionAuthorizable object.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @return Collection
     */
    public function getPermissions(RbacPermissionAuthorizable $authorizable);

}