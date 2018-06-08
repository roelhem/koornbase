<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:37
 */

namespace App\Services\Rbac\Authorizers;


use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;
use App\Services\Rbac\AbstractAuthorizer;

class DepthFirstAuthorizer extends AbstractAuthorizer
{

    /**
     * Returns if the provided RbacAuthorizable object has the provided role.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @param RbacRole|string $role
     * @return bool
     */
    public function hasRole(RbacRoleAuthorizable $authorizable, $role): bool
    {
        if ($authorizable instanceof RbacRole) {
            if($this->service->getRoleId($authorizable) === $this->service->getRoleId($role)) {
                return true;
            }
        }

        foreach ($authorizable->getChildRoles() as $childRole) {
            if($this->hasRole($childRole, $authorizable)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns if the provided RbacAuthorizable object has the provided RbacNode
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @param RbacPermission|string $permission
     * @return bool
     */
    public function hasPermission(RbacPermissionAuthorizable $authorizable, $permission): bool
    {
        if ($authorizable instanceof RbacPermission) {
            if($this->service->getPermissionId($authorizable) === $this->service->getPermissionId($permission)) {
                return true;
            }
        }

        foreach ($authorizable->getChildPermissions() as $childPermission) {
            if($this->hasPermission($childPermission, $permission)) {
                return true;
            }
        }

        if($authorizable instanceof RbacAuthorizable) {
            foreach ($authorizable->getChildRoles() as $childRole) {
                if($this->hasPermission($childRole, $permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns all the roles of the RbacRoleAuthorizable object.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @return array
     */
    public function getRoles(RbacRoleAuthorizable $authorizable)
    {
        $result = [];

        if($authorizable instanceof RbacRole) {
            $id = $this->service->getRoleId($authorizable);
            $result[$id] = $authorizable;
        }

        $subList = [];
        foreach ($authorizable->getChildRoles() as $childRole) {
            $subList[] = $this->getRoles($childRole);
        }

        return array_merge($result, ...$subList);
    }


    /**
     * Returns all the permissions of a RbacPermissionAuthorizable object.
     *
     * @param RbacPermissionAuthorizable $authorizable
     * @return array
     */
    public function getPermissions(RbacPermissionAuthorizable $authorizable)
    {
        $result = [];

        if($authorizable instanceof RbacPermission) {
            $id = $this->service->getPermissionId($authorizable);
            $result[$id] = $authorizable;
        }

        $subList = [];
        foreach ($authorizable->getChildPermissions() as $childPermission) {
            $subList[] = $this->getPermissions($childPermission);
        }

        $result = array_merge($result, ...$subList);

        if($authorizable instanceof RbacAuthorizable) {
            $subList = [];
            foreach ($authorizable->getChildRoles() as $childRole) {
                $subList[] = $this->getPermissions($childRole);
            }
            $result = array_merge($result, $subList);
        }

        return $result;
    }
}