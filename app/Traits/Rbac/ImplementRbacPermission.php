<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 19:15
 */

namespace App\Traits\Rbac;
use App\Interfaces\Rbac\RbacPermissionAssignable;
use App\Interfaces\Rbac\RbacPermission as PermissionInterface;
use App\Services\Rbac\Traits\DefaultRbacPermissionAuthorizable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Routing\Route;

/**
 * Trait ImplementRbacPermission
 *
 * Implements the methods for the Permission
 *
 * @package App\Traits\Rbac
 */
trait ImplementRbacPermission
{

    use ImplementRbacNode, DefaultRbacPermissionAuthorizable, HasParentRoles, HasParentPermissions, HasAssignedPermissions;

    /**
     * Sets (or unsets) the route attribute of this permission in a fluent way and saves this permission.
     *
     * This function will only set the name of the route. Pass `null` as a parameter to remove the route form this
     * permission.
     *
     * @param Route|string|null $route  An instance or name of the route to connect or null to unset the route.
     * @return $this
     */
    public function route($route)
    {
        if($route instanceof Route) {
            $this->route = $route->getName();
        } else {
            $this->route = $route;
        }
        $this->save();
        return $this;
    }

    /**
     * Assigns this permission to the target PermissionAssignable object.
     *
     * @param RbacPermissionAssignable $target
     * @return $this
     * @throws
     */
    public function assignTo(RbacPermissionAssignable $target)
    {
        $target->assignPermission($this);
        return $this;
    }


    /**
     * Assigns the provided permission to this Permission as a child.
     *
     * @param PermissionInterface $permission
     * @return $this
     */
    public function assign($permission)
    {
        $this->assignPermission($permission);
        return $this;
    }

    /**
     * Assigns multiple Permissions to this Permission as children.
     *
     * @param iterable $permissions
     * @return $this
     */
    public function assignAll(iterable $permissions)
    {
        foreach ($permissions as $permission) {
            if($permission instanceof PermissionInterface) {
                $this->assign($permission);
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChildPermissions()
    {
        return $this->getAssignedPermissions();
    }

    /**
     * @inheritdoc
     */
    public function addConstraint($constraint, $params = null) {
        $this->constraints()->attach($constraint, ['params' => $params]);
        return $this;
    }

    /**
     * @return BelongsToMany
     */
    abstract public function constraints();


}