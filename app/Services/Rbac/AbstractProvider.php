<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 10:41
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacProvider;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use Illuminate\Routing\Route;

abstract class AbstractProvider extends AbstractServiceComponent implements RbacProvider
{

    /**
     * @inheritdoc
     */
    public function getRole($role)
    {
        if($this->allowAsRoleInstance($role)) {
            return $role;
        }

        if($role instanceof RbacRole) {
            $role = $role->getId();
        }

        if(is_string($role)) {
            return $this->getRoleById($role);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getRoleId($role)
    {
        if(is_string($role)) {
            return $role;
        }

        if($role instanceof RbacRole) {
            return $role->getId();
        }

        return null;
    }

    /**
     * A method that takes a parameter $role and returns if this object can be returned as a Role instance for this
     * provider.
     *
     * @param mixed $role
     * @return bool
     */
    abstract protected function allowAsRoleInstance($role) : bool ;

    /**
     * @inheritdoc
     */
    public function getPermission($permission)
    {
        if($this->allowAsPermissionInstance($permission)) {
            return $permission;
        }

        if($permission instanceof RbacPermission) {
            $permission = $permission->getId();
        }

        if(is_string($permission)) {
            return $this->getPermissionById($permission);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getPermissionId($permission)
    {
        if(is_string($permission)) {
            return $permission;
        }

        if($permission instanceof RbacPermission) {
            return $permission->getId();
        }

        return null;
    }

    /**
     * A methods that takes a parameter $permission and returns if this object can be returned as a Permission
     * instance for this provider.
     *
     * @param mixed $permission
     * @return bool
     */
    abstract protected function allowAsPermissionInstance($permission) : bool ;

    /**
     * @inheritdoc
     */
    public function getPermissionByRoute($route)
    {
        $routeName = null;
        if($route instanceof Route) {
            $routeName = $route->getName();
        } elseif(is_string($route)) {
            $routeName = $route;
        }

        if(is_string($routeName)) {
            return $this->getPermissionByRouteName($routeName);
        }

        return null;
    }

    /**
     * Returns the permission that belongs to the given routeName.
     *
     * @param string $routeName
     * @return RbacPermission|null
     */
    abstract protected function getPermissionByRouteName(string $routeName) ;

}