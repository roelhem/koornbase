<?php

namespace App;

use App\Contracts\Rbac\RbacChecker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class Role
 * @package App
 *
 * @property string $id The id of the role
 * @property string|null $name The name of the role
 * @property string|null $description
 * @property boolean $is_required
 * @property boolean $is_visible
 *
 * @property-read Collection $parentRoles
 * @property-read Collection $childRoles
 * @property-read Collection $childPermissions
 */
class Role extends Model
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'roles';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id','name','description','is_required','is_visible'];

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
     * Checks if this role is equal to the given role or if it has a childRole.
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role) {
        return $this->rbacChecker->roleHasRole($this, $role);
    }

    /**
     * Checks if the role has the given permission.
     *
     * @param $permission
     * @return boolean
     */
    public function hasPermission($permission) {
        return $this->rbacChecker->roleHasPermission($this, $permission);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- METHODS for assigning RBAC ENTITIES ---------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Assigns this role to an Model that can have assigned roles.
     *
     * @param $model
     * @return $this
     * @throws
     */
    public function assignTo($model) {
        if(is_string($model)) {
            return $this->assignToRole($model);
        } elseif($model instanceof Role) {
            return $this->assignToRole($model);
        } elseif(method_exists($model, 'assignRole')) {
            $model->assignRole($this);
            return $this;
        }

        throw new \Exception("Can't assign a role to the given model.");
    }

    /**
     * Assigns this Role to another Role as a Child. ($this will be the child and $role will be the parent.)
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignToRole($role) {
        $this->parentRoles()->attach($role);
        return $this;
    }

    /**
     * Assigns a provided Role or Permission to this Role as a child. ($this will be the parent and $model
     * will be the child). Only Roles and Permissions can be assigned with this method.
     *
     * @param array $model
     * @return $this
     */
    public function assign(...$models) {
        foreach($models as $model) {
            if ($model instanceof Role) {
                $this->assignRole($model);
            } elseif ($model instanceof Permission) {
                $this->assignPermission($model);
            }
        }
        return $this;
    }

    /**
     * Assigns the provided Role to this Role as a child. ($this will be the parent and $role will be the child).
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignRole($role) {
        $this->childRoles()->attach($role);
        return $this;
    }

    /**
     * Assigns the provided Permission to this Role.
     *
     * @param Permission|string $permission
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
     * Gives all the Users where this Role is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users() {
        return $this->morphedByMany(User::class, 'assignable');
    }

    /**
     * Gives all the Groups where this Role is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function groups() {
        return $this->morphedByMany(Group::class, 'assignable');
    }

    /**
     * Gives all the GroupCategories where this Role is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function groupCategories() {
        return $this->morphedByMany(GroupCategory::class, 'assignable');
    }

    /**
     * Gives all the direct parent Roles of this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentRoles() {
        return $this->belongsToMany(Role::class, 'role_role',
                                'child_id','parent_id');
    }

    /**
     * Gives all the direct child Roles of this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childRoles() {
        return $this->belongsToMany(Role::class, 'role_role',
                                'parent_id','child_id');
    }

    /**
     * Gives all the Permissions that are directly assigned to this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childPermissions() {
        return $this->belongsToMany(Permission::class, 'role_permission',
                                'role_id','permission_id');
    }

}
