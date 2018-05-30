<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 03:13
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacGraph;
use App\Exceptions\Rbac\ModelNotFoundException;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;
use App\Group;
use App\GroupCategory;
use App\Permission;
use App\Person;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatabaseRbacGraph
 *
 * This class models the RbacGraph that is stored in the main database.
 *
 * @package App\Services\Rbac
 */
class DatabaseRbacGraph implements RbacGraph
{

    /**
     * Returns an instance of the model.
     *
     * @param $model
     * @param null $modelType
     * @return Model
     * @throws ModelNotFoundException
     */
    public function modelGet($model, $modelType = null) {
        if($model instanceof Model) {
            return $model;
        } elseif(is_string($model) && is_subclass_of($modelType, Model::class)) {
            $model = $modelType::find($model);
            if($model) {
                return $model;
            } else {
                throw new ModelNotFoundException();
            }

        } else {
            throw new ModelNotFoundException();
        }
    }

    /**
     * Returns an instance of the Role model.
     *
     * @param Role|string $role
     * @return Role
     * @throws RoleNotFoundException
     */
    public function roleGet($role) {
        if($role instanceof Role) {
            return $role;
        } elseif(is_string($role)) {
            $res = Role::find($role);
            if($res) {
                return $res;
            } else {
                throw new RoleNotFoundException();
            }
        } else {
            throw new RoleNotFoundException();
        }
    }

    /**
     * Returns an instance of the Permission model.
     *
     * @param $permission
     * @return Permission
     * @throws PermissionNotFoundException
     */
    public function permissionGet($permission) {
        if($permission instanceof Permission) {
            return $permission;
        } elseif(is_string($permission)) {
            $res = Permission::find($permission);
            if($res) {
                return $res;
            } else {
                throw new PermissionNotFoundException();
            }
        } else {
            throw new PermissionNotFoundException();
        }
    }

    /**
     * @inheritdoc
     */
    public function modelExists($model, $modelType = null): bool
    {
        try {

            $model = $this->modelGet($model, $modelType);

            if(
                ($model instanceof GroupCategory) ||
                ($model instanceof Group) ||
                ($model instanceof Person) ||
                ($model instanceof User)
            ) {
                return true;
            }

            return false;

        } catch (ModelNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function modelGetId($model, $modelType = null): string
    {
        if($model instanceof Model) {
            return $model->id;
        } elseif(is_string($model)) {
            return $model;
        } else {
            throw new ModelNotFoundException;
        }
    }

    /**
     * @inheritdoc
     */
    public function modelGetType($model, $modelType = null)
    {
        if($model instanceof Model) {
            return get_class($model);
        } elseif(is_subclass_of($modelType, Model::class)) {
            return $modelType;
        } else {
            throw new ModelNotFoundException;
        }
    }

    /**
     * @inheritdoc
     */
    public function modelGetChildRoles($model, $modelType = null): iterable
    {
        return $this->modelGet($model, $modelType)->assignedRoles ?? [];
    }

    /**
     * @inheritdoc
     */
    public function modelGetInheritModels($model, $modelType = null): iterable
    {
        $model = $this->modelGet($model, $modelType);

        if($model instanceof Group) {
            return [$model->category];
        } elseif($model instanceof Person) {
            return $model->groups;
        } elseif($model instanceof User) {
            if($model->person) {
                return $model->person->groups;
            } else {
                return [];
            }
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function roleEquals($role, $compareTo): bool
    {
        try {
            return $this->roleGetId($role) === $this->roleGetId($compareTo);
        } catch (RoleNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function roleExists($role): bool
    {
        if($role instanceof Role) {
            return true;
        } elseif(is_string($role) && Role::where(['id' => $role])->exists()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function roleGetId($role): string
    {
        if($role instanceof Role) {
            return $role->id;
        } elseif(is_string($role)) {
            return $role;
        } else {
            throw new RoleNotFoundException();
        }
    }

    /**
     * @inheritdoc
     */
    public function roleGetChildRoles($role): iterable
    {
        return $this->roleGet($role)->childRoles;
    }

    /**
     * @inheritdoc
     */
    public function roleGetChildPermissions($role): iterable
    {
        return $this->roleGet($role)->childPermissions;
    }

    /**
     * @inheritdoc
     */
    public function permissionEquals($permission, $compareTo): Bool
    {
        try {
            return $this->permissionGetId($permission) === $this->permissionGetId($compareTo);
        } catch (PermissionNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function permissionExists($permission): Bool
    {
        if($permission instanceof Permission) {
            return true;
        } elseif(is_string($permission) && Permission::where(['id' => $permission])->exists()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function permissionGetId($permission): string
    {
        if($permission instanceof Permission) {
            return $permission->id;
        } elseif(is_string($permission)) {
            return $permission;
        } else {
            throw new PermissionNotFoundException();
        }
    }

    /**
     * @inheritdoc
     */
    public function permissionGetChildPermissions($permission): iterable
    {
        return $this->permissionGet($permission)->childPermissions;
    }
}