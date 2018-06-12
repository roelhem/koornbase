<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 23:05
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacService;
use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAssignable;
use App\Interfaces\Rbac\RbacRoleAuthorizable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DatabaseAuthorizer extends AbstractAuthorizer
{

    protected $rbacPostgres;

    public function __construct(RbacService $service, RbacPostgres $rbacPostgres)
    {
        $this->rbacPostgres = $rbacPostgres;
        parent::__construct($service);
    }

    /**
     * Returns the id of a role based on the parameter input.
     *
     * @param mixed $role
     * @return string
     */
    protected function getRoleId($role) {
        if($role instanceof RbacRole) {
            return $role->getId();
        } elseif(is_array($role)) {
            return $role['id'];
        } elseif(is_string($role)) {
            return $role;
        } elseif(property_exists($role, 'id')) {
            return $role->id;
        } else {
            return strval($role);
        }
    }

    /**
     * Returns the ids of the child roles of a RbacRoleAuthorizable object. Uses the childRoles method if available.
     *
     * @param RbacRoleAuthorizable $authorizable
     * @return Collection|static
     */
    protected function getChildRoleIds(RbacRoleAuthorizable $authorizable) {
        if(method_exists($authorizable, 'childRoles')) {
            $query = $authorizable->childRoles();
            if($query instanceof Builder) {
                return $query->pluck('id');
            }
        } else {
            $childRoles = $authorizable->getChildRoles();
            if(!($childRoles instanceof Collection)) {
                $childRoles = collect($childRoles);
            }
            return $childRoles->map(function($role) {
                return $this->getRoleId($role);
            });
        }
    }

    /**
     * Returns the id of a permission based on the parameter input.
     *
     * @param mixed $permission
     * @return string
     */
    protected function getPermissionId($permission) {
        if($permission instanceof RbacRole) {
            return $permission->getId();
        } elseif(is_array($permission)) {
            return $permission['id'];
        } elseif(is_string($permission)) {
            return $permission;
        } elseif(property_exists($permission, 'id')) {
            return $permission->id;
        } else {
            return strval($permission);
        }
    }

    /**
     * @inheritdoc
     */
    public function hasPermission(RbacPermissionAuthorizable $authorizable, $permission): bool
    {

    }

    public function getPermissionIds(RbacPermissionAuthorizable $authorizable, $permission): bool
    {

    }

    /**
     * @inheritdoc
     */
    public function getRoles(RbacRoleAuthorizable $authorizable)
    {
        return $this->rbacPostgres->roleGetRoles(
            $this->getChildRoleIds($authorizable)
        );
    }

    /**
     * @inheritdoc
     */
    public function getRoleIds(RbacRoleAuthorizable $authorizable)
    {
        return $this->rbacPostgres->roleGetAllRoleIds(
            $this->getChildRoleIds($authorizable)
        );
    }

}