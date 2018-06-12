<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 08:17
 */

namespace App\Contracts\Rbac;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

/**
 * Contract RbacProvider
 *
 * A Contract for service classes that provide the different roles and permissions in the Rbac-structure.
 *
 * @package App\Contracts\Rbac
 */
interface RbacProvider
{

    /**
     * Returns a role based on the $role parameter. Returns null if no role was found.
     *
     * @param RbacRole|string $role
     * @return RbacRole|null
     */
    public function getRole( $role );

    /**
     * Returns the id of the role based on the $role parameter. Returns null if no role was found.
     *
     * @param RbacRole|string $role
     * @return string|null
     */
    public function getRoleId( $role );

    /**
     * Returns the role with the id from the $id parameter. Returns null if no role was found.
     *
     * @param string $id
     * @return RbacRole|null
     */
    public function getRoleById( string $id );

    /**
     * Returns all the direct child roles of a RbacRoleAuthorizable object.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @return Collection
     */
    public function getChildRoles( RbacRoleAuthorizable $authorizable );

    /**
     * Returns all the direct child roles of a RbacRoleAuthorizable object.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @return Collection
     */
    public function getChildRoleIds( RbacRoleAuthorizable $authorizable );

    /**
     * Returns a permission based on the $permission parameter. Returns null if no permission was found.
     *
     * @param RbacPermission|string $permission
     * @return RbacPermission|null
     */
    public function getPermission( $permission );

    /**
     * Returns an id of the permission described by the $permission parameter. Returns null if no permission was found.
     *
     * @param RbacPermission|string $permission
     * @return string|null
     */
    public function getPermissionId( $permission );

    /**
     * Returns the permission with the id from the $id parameter. Returns null if no permission was found.
     *
     * @param string $id
     * @return RbacPermission|null
     */
    public function getPermissionById( string $id );

    /**
     * Returns all the direct child-permissions of a RbacPermissionAuthorizable object.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @return Collection
     */
    public function getChildPermissions( RbacPermissionAuthorizable $authorizable );

    /**
     * Returns all the direct child-permissions of a RbacPermissionAuthorizable object.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @return mixed
     */
    public function getChildPermissionIds( RbacPermissionAuthorizable $authorizable );

    /**
     * Returns the permission that belongs to the given $route. Returns null if no permission was found.
     *
     * @param Route|string $route
     * @return RbacPermission|null
     */
    public function getPermissionByRoute( $route );

}