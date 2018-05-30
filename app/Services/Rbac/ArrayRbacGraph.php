<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 00:51
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacGraph;
use App\Exceptions\Rbac\ModelNotFoundException;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;

class ArrayRbacGraph implements RbacGraph
{


    protected $roles = [];

    protected $permissions = [];

    protected $models = [];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALISATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function __construct($array = null)
    {
        if($array === null) {
            $array = [];
        }

        $this->roles = array_get($array, 'roles', []);
        $this->permissions = array_get($array, 'permissions', []);
        $this->models = array_get($array, 'models', []);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GRAPH MUTATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Creates a new role in the graph.
     *
     * @param string $id
     * @param mixed $value
     */
    public function roleCreate(string $id, $value = null) {
        $this->roles[$id] = [
            'value' => $value,
            'childRoles' => [],
            'childPermissions' => [],
        ];
    }

    /**
     * Creates multiple new roles in the graph.
     *
     * @param iterable $ids
     */
    public function roleCreateMultiple(iterable $ids) {
        foreach ($ids as $key => $value) {
            if(is_integer($key)) {
                $this->roleCreate($value);
            } else {
                $this->roleCreate($key, $value);
            }
        }
    }

    /**
     * Adds a child role to a specific role.
     *
     * @param string $role
     * @param string $childRole
     * @throws RoleNotFoundException
     */
    public function roleAddChildRole(string $role, string $childRole) {
        if(!$this->roleExists($role)) {
            throw new RoleNotFoundException($role);
        }

        if(!$this->roleExists($childRole)) {
            throw new RoleNotFoundException($childRole);
        }

        $this->roles[$role]['childRoles'][] = $childRole;
    }

    /**
     * Adds multiple child roles to a specific role.
     *
     * @param string $role
     * @param iterable $childRoles
     * @throws RoleNotFoundException
     */
    public function roleAddChildRoles(string $role, iterable $childRoles) {
        foreach ($childRoles as $childRole) {
            $this->roleAddChildRole($role, $childRole);
        }
    }

    /**
     * Adds a child permission to a specific role.
     *
     * @param string $role
     * @param string $childPermission
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
     */
    public function roleAddChildPermission(string $role, string $childPermission) {
        if(!$this->roleExists($role)) {
            throw new RoleNotFoundException($role);
        }

        if(!$this->permissionExists($childPermission)) {
            throw new PermissionNotFoundException($childPermission);
        }

        $this->roles[$role]['childPermissions'][] = $childPermission;
    }

    /**
     * Adds multiple child permissions to a specific role.
     *
     * @param string $role
     * @param iterable $childPermissions
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function roleAddChildPermissions(string $role, iterable $childPermissions) {
        foreach ($childPermissions as $childPermission) {
            $this->roleAddChildPermission($role, $childPermission);
        }
    }

    /**
     * Creates a new permission in the graph.
     *
     * @param string $id
     * @param mixed $value
     */
    public function permissionCreate(string $id, $value = null) {
        $this->permissions[$id] = [
            'value' => $value,
            'childPermissions' => [],
        ];
    }

    /**
     * Creates multiple new permissions in the graph.
     *
     * @param iterable $ids
     */
    public function permissionCreateMultiple(iterable $ids) {
        foreach ($ids as $key => $value) {
            if(is_integer($key)) {
                $this->permissionCreate($value);
            } else {
                $this->permissionCreate($key, $value);
            }
        }
    }

    /**
     * Adds a child permission to a specific permission.
     *
     * @param string $permission
     * @param string $childPermission
     * @throws PermissionNotFoundException
     */
    public function permissionAddChildPermission(string $permission, string $childPermission) {
        if(!$this->permissionExists($permission)) {
            throw new PermissionNotFoundException($permission);
        }

        if(!$this->permissionExists($childPermission)) {
            throw new PermissionNotFoundException($childPermission);
        }

        $this->permissions[$permission]['childPermissions'][] = $childPermission;
    }

    /**
     * Adds multiple child permissions to a specific permission.
     *
     * @param string $permission
     * @param iterable $childPermissions
     * @throws PermissionNotFoundException
     */
    public function permissionAddChildPermissions(string $permission, iterable $childPermissions) {
        foreach ($childPermissions as $childPermission) {
            $this->permissionAddChildPermission($permission, $childPermission);
        }
    }

    /**
     * Creates a new model in the graph.
     *
     * @param string $id
     * @param mixed $value
     */
    public function modelCreate(string $id, $value = null) {
        $this->models[$id] = [
            'value' => $value,
            'childRoles' => [],
            'inheritModels' => []
        ];
    }

    /**
     * Creates multiple new models in the graph.
     *
     * @param iterable $ids
     */
    public function modelCreateMultiple(iterable $ids) {
        foreach ($ids as $key => $value) {
            if(is_integer($key)) {
                $this->modelCreate($value);
            } else {
                $this->modelCreate($key, $value);
            }
        }
    }

    /**
     * Adds/assigns a child role to a specific model.
     *
     * @param string $model
     * @param string $childRole
     * @throws ModelNotFoundException
     * @throws RoleNotFoundException
     */
    public function modelAddChildRole(string $model, string $childRole) {
        if(!$this->modelExists($model)) {
            throw new ModelNotFoundException($model);
        }

        if(!$this->roleExists($childRole)) {
            throw new RoleNotFoundException($childRole);
        }

        $this->models[$model]['childRoles'][] = $childRole;
    }

    /**
     * Adds/assigns multiple child roles to a specific model.
     *
     * @param string $model
     * @param iterable $childRoles
     * @throws ModelNotFoundException
     * @throws RoleNotFoundException
     */
    public function modelAddChildRoles(string $model, iterable $childRoles) {
        foreach ($childRoles as $childRole) {
            $this->modelAddChildRole($model, $childRole);
        }
    }

    /**
     * Adds an inheritModel to a specific model.
     *
     * @param string $model
     * @param string $inharitModel
     * @throws ModelNotFoundException
     */
    public function modelAddInheritModel(string $model, string $inharitModel) {
        if(!$this->modelExists($model)) {
            throw new ModelNotFoundException($model);
        }

        if(!$this->modelExists($inharitModel)) {
            throw new ModelNotFoundException($inharitModel);
        }

        $this->models[$model]['inheritModels'][] = $inharitModel;
    }

    /**
     * Adds multiple inheritModels to a specific model.
     *
     * @param string $model
     * @param iterable $inharitModels
     * @throws ModelNotFoundException
     */
    public function modelAddInheritModels(string $model, iterable $inharitModels) {
        foreach ($inharitModels as $inharitModel) {
            $this->modelAddInheritModel($model, $inharitModel);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GRAPH GETTERS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the internally stored array of the specific model.
     *
     * @param string $model
     * @return array
     * @throws ModelNotFoundException
     */
    public function modelGetArray($model) {
        if(!$this->modelExists($model)) {
            throw new ModelNotFoundException($model);
        }
        return array_get($this->models, $model, []);
    }

    /**
     * Returns the value that was stored with the model.
     *
     * @param $model
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function modelGetValue($model) {
        return array_get($this->modelGetArray($model), 'value', []);
    }

    /**
     * Returns the internally stored array of the specific role.
     *
     * @param string $role
     * @return array
     * @throws RoleNotFoundException
     */
    public function roleGetArray($role) {
        if(!$this->roleExists($role)) {
            throw new RoleNotFoundException($role);
        }
        return array_get($this->roles, $role, []);
    }

    /**
     * Returns the value that was stored with the role.
     *
     * @param $role
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function roleGetValue($role) {
        return array_get($this->roleGetArray($role), 'value', []);
    }

    /**
     * Returns the internally stored array of the specific permission.
     *
     * @param string $permission
     * @return array
     * @throws PermissionNotFoundException
     */
    public function permissionGetArray($permission) {
        if(!$this->permissionExists($permission)) {
            throw new PermissionNotFoundException($permission);
        }
        return array_get($this->permissions, $permission, []);
    }

    /**
     * Returns the value that was stored with the permission.
     *
     * @param string $permission
     * @return mixed
     * @throws PermissionNotFoundException
     */
    public function permissionGetValue($permission) {
        return array_get($this->permissionGetArray($permission), 'value', []);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: RbacGraph ------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function modelExists($model, $modelType = null): bool
    {
        return array_key_exists($model, $this->models);
    }

    /**
     * @inheritdoc
     */
    public function modelGetId($model, $modelType = null): string
    {
        if(!$this->modelExists($model)) {
            throw new ModelNotFoundException($model);
        }
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function modelGetType($model, $modelType = null)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function modelGetChildRoles($model, $modelType = null): iterable
    {
        return array_get($this->modelGetArray($model), 'childRoles', []);
    }

    /**
     * @inheritdoc
     */
    public function modelGetInheritModels($model, $modelType = null): iterable
    {
        return array_get($this->modelGetArray($model), 'inheritModels', []);
    }

    /**
     * @inheritdoc
     */
    public function roleEquals($role, $compareTo): bool
    {
        return $role == $compareTo;
    }

    /**
     * @inheritdoc
     */
    public function roleExists($role): bool
    {
        return array_key_exists($role, $this->roles);
    }

    /**
     * @inheritdoc
     */
    public function roleGetId($role): string
    {
        if(!$this->roleExists($role)) {
            throw new RoleNotFoundException($role);
        }
        return $role;
    }

    /**
     * @inheritdoc
     */
    public function roleGetChildRoles($role): iterable
    {
        return array_get($this->roleGetArray($role), 'childRoles', []);
    }

    /**
     * @inheritdoc
     */
    public function roleGetChildPermissions($role): iterable
    {
        return array_get($this->roleGetArray($role), 'childPermissions', []);
    }

    /**
     * @inheritdoc
     */
    public function permissionEquals($permission, $compareTo): Bool
    {
        return $permission == $compareTo;
    }

    /**
     * @inheritdoc
     */
    public function permissionExists($permission): Bool
    {
        return array_key_exists($permission, $this->permissions);
    }

    /**
     * @inheritdoc
     */
    public function permissionGetId($permission): string
    {
        if(!$this->permissionExists($permission)) {
            throw new PermissionNotFoundException($permission);
        }
        return $permission;
    }

    /**
     * @inheritdoc
     */
    public function permissionGetChildPermissions($permission): iterable
    {
        return array_get($this->permissionGetArray($permission), 'childPermissions', []);
    }
}