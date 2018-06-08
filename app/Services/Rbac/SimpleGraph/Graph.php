<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 02:26
 */

namespace App\Services\Rbac\SimpleGraph;


use App\Contracts\Rbac\RbacAuthorizer;
use App\Contracts\Rbac\RbacBuilder;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;

class Graph implements RbacBuilder
{

    /**
     * @var RbacAuthorizer
     */
    protected $authorizer;

    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * Graph constructor.
     *
     * @param RbacAuthorizer $authorizer
     */
    public function __construct(RbacAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * @inheritdoc
     */
    public function permission(string $id, $name = null, $description = null): RbacPermission
    {
        if(array_key_exists($id, $this->permissions)) {
            $permission = $this->permissions[$id];
            if($permission instanceof Permission) {
                if($name !== null) {
                    $permission->name($name);
                }

                if($description !== null) {
                    $permission->description($description);
                }

                return $permission;
            }
        }

        $permission = new Permission($this, $id, $name, $description);
        $this->permissions[$id] = $permission;
        return $permission;
    }

    /**
     * @inheritdoc
     */
    public function role(string $id, $name = null, $description = null): RbacRole
    {
        if(array_key_exists($id, $this->roles)) {
            $role = $this->roles[$id];
            if($role instanceof Role) {
                if($name !== null) {
                    $role->name($name);
                }

                if($description !== null) {
                    $role->description($description);
                }

                return $role;
            }
        }

        $role = new Role($this, $id, $name, $description);
        $this->roles[$id] = $role;
        return $role;
    }

    /**
     * @param string $id
     * @param string $type
     * @return Model
     */
    public function model(string $id, string $type = 'default') {
        return new Model($this, $id, $type);
    }

    /**
     * @inheritdoc
     */
    public function authorizer(): RbacAuthorizer
    {
        return $this->authorizer;
    }

    /**
     * Returns the permissions with the given ID
     *
     * @param string $id
     * @return Permission
     * @throws PermissionNotFoundException
     */
    public function getPermission(string $id) : Permission {
        if(array_key_exists($id, $this->permissions)) {
            $permission = $this->permissions[$id];
            if($permission instanceof Permission) {
                return $permission;
            }
        }
        throw new PermissionNotFoundException;
    }

    /**
     * Returns the whole array of permissions
     *
     * @return Permission[]
     */
    public function getPermissions() {
        return $this->permissions;
    }

    /**
     * Returns the role with the given ID
     *
     * @param string $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function getRole(string $id) : Role {
        if(array_key_exists($id, $this->roles)) {
            $role = $this->roles[$id];
            if($role instanceof Role) {
                return $role;
            }
        }
        throw new RoleNotFoundException;
    }

    /**
     * Returns the whole array of roles
     *
     * @return Role[]
     */
    public function getRoles() {
        return $this->roles;
    }
}