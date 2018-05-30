<?php

namespace App;

use App\Contracts\Rbac\RbacChecker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package App
 *
 * @property string $id The primary identifier of this Permission.
 * @property string|null $name A descriptive name of this Permission.
 * @property string|null $description A description of this Permission, to clarify it's function.
 *
 * @property-read Collection $parentRoles
 * @property-read Collection $parentPermissions
 * @property-read Collection $childPermissions
 */
class Permission extends Model
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'permissions';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['id','name','description'];

    /**
     * @var RbacChecker
     */
    protected $rbacChecker;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rbacChecker = resolve(RbacChecker::class);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- METHODS for checking the RBAC TREE ----------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Checks if the role has the given permission.
     *
     * @param $permission
     * @return boolean
     */
    public function hasPermission($permission) {
        return $this->rbacChecker->permissionHasPermission($this, $permission);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- METHODS for assigning RBAC ENTITIES ---------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Shortcut for assignPermission. Can also handle multiple assignments
     *
     * This function is added for convenience to make permissions work similar to the roles.
     *
     * @return $this
     */
    public function assign(...$permissions) {
        foreach ($permissions as $permission) {
            return $this->assignPermission($permission);
        }
    }

    /**
     * Assigns this permission to a model where an Permission can be assigned to.
     *
     * @param $model
     * @return $this
     * @throws
     */
    public function assignTo($model) {
        if($model instanceof Role) {
            return $this->assignToRole($model);
        } else if($model instanceof Permission) {
            return $this->assignToPermission($model);
        }

        throw new \Exception("Can't assign a permission to the given model.");
    }

    /**
     * Assigns this Permission to the provided role.
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignToRole($role) {
        $this->parentRoles()->attach($role);
        return $this;
    }

    /**
     * Assigns this Permission to the provided Permission. ($this is the child and $permission is the parent.)
     *
     * @param $permission
     * @return $this
     */
    public function assignToPermission($permission) {
        $this->parentPermissions()->attach($permission);
        return $this;
    }

    /**
     * Assigns the provided Permission to this Permission. ($permission is the child and $this is the parent.)
     *
     * @param $permission
     * @return $this
     */
    public function assignPermission($permission) {
        $this->childPermissions()->attach($permission);
        return $this;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Roles where this Permission is directly assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentRoles() {
        return $this->belongsToMany(Role::class, 'role_permission',
                                'permission_id','role_id');
    }

    /**
     * Gives the Permissions where this Permission is a direct child of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentPermissions() {
        return $this->belongsToMany(Permission::class, 'permission_permission',
                                    'child_id','parent_id');
    }

    /**
     * Gives all the direct child Permissions of this Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childPermissions() {
        return $this->belongsToMany(Permission::class, 'permission_permission',
            'parent_id','child_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAGIC METHODS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->id;
    }

}
