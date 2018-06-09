<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 17:29
 */

namespace App\Interfaces\Rbac;

use App\Exceptions\Rbac\PermissionNotFoundException;
use Illuminate\Routing\Route;


/**
 * Interface Permission
 *
 * For classes that represent permissions in RBAC-structures
 *
 * @package App\Interfaces\Rbac
 */
interface RbacPermission extends RbacNode, RbacPermissionAssignable, RbacPermissionNestable, RbacRoleNestable, RbacPermissionAuthorizable
{

    /**
     * Sets the route-name of the permission in the case that the permission is a route-permission.
     *
     * You can give the value null to reset the routing of the permission.
     *
     * @param Route|string|null $route
     * @return $this
     */
    public function route($route);

    /**
     * Assigns this permission to a PermissionAssignable object.
     *
     * The $target will be a new parent of this Permission.
     *
     * @param RbacPermissionAssignable $target
     * @return $this
     */
    public function assignTo(RbacPermissionAssignable $target);

    /**
     * Assigns a provided $permission to this permission.
     *
     * The $permission will be a new child of this Permission.
     *
     * @param RbacPermission $permission
     * @return $this
     */
    public function assign($permission);

    /**
     * Assigns multiple permissions to this permission.
     *
     * The permissions will be children of this Permission
     *
     * @param iterable $permissions
     * @return $this
     */
    public function assignAll(iterable $permissions);

    /**
     * Adds a new constraint to this permission.
     *
     * @param string $constraint
     * @param array|null $params
     * @return $this
     */
    public function addConstraint($constraint, $params = null);

}