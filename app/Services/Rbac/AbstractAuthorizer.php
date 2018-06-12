<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 10:01
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacAuthorizer;
use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;

abstract class AbstractAuthorizer extends AbstractServiceComponent implements RbacAuthorizer
{

    /**
     * @inheritdoc
     */
    public function has(RbacAuthorizable $authorizable, RbacNode $node): bool
    {
        if($node instanceof RbacRole) {
            return $this->hasRole($authorizable, $node);
        } elseif($node instanceof RbacPermission) {
            return $this->hasPermission($authorizable, $node);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasAll(RbacAuthorizable $authorizable, iterable $nodes): bool
    {
        foreach ($nodes as $node) {
            if($node instanceof RbacNode) {
                if(!$this->has($authorizable, $node)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasSome(RbacAuthorizable $authorizable, iterable $nodes): bool
    {
        foreach ($nodes as $node) {
            if($node instanceof RbacNode) {
                if($this->has($authorizable, $node)) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function hasRoles(RbacRoleAuthorizable $authorizable, iterable $roles): bool
    {
        foreach ($roles as $role) {
            if(is_string($role) || ($role instanceof RbacRole)) {
                if (!$this->hasRole($authorizable, $role)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasSomeRoles(RbacRoleAuthorizable $authorizable, iterable $roles): bool
    {
        foreach ($roles as $role) {
            if(is_string($role) || ($role instanceof RbacRole)) {
                if($this->hasRole($authorizable, $role)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasPermissions(RbacPermissionAuthorizable $authorizable, iterable $permissions): bool
    {
        foreach ($permissions as $permission) {
            if(is_string($permission) || ($permission instanceof RbacPermission)) {
                if (!$this->hasPermission($authorizable, $permission)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasSomePermissions(RbacPermissionAuthorizable $authorizable, iterable $permissions): bool
    {
        foreach ($permissions as $permission) {
            if(is_string($permission) || ($permission instanceof RbacPermission)) {
                if($this->hasPermission($authorizable, $permissions)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getRoleIds(RbacRoleAuthorizable $authorizable)
    {
        return $this->getRoles($authorizable)->map(function($role) {
            if($role instanceof RbacRole) {
                return $role->getId();
            } else {
                return $role;
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function getPermissionIds(RbacPermissionAuthorizable $authorizable)
    {
        return $this->getPermissions($authorizable)->map(function($permission) {
            if($permission instanceof RbacPermission) {
                return $permission->getId();
            } else {
                return $permission;
            }
        });
    }


}