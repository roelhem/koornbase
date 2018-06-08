<?php

namespace App;

use App\Traits\HasDescription;
use App\Interfaces\Rbac\RbacRole;
use App\Traits\Rbac\ImplementRbacRole;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class Role
 * @package App
 *
 * @property string $id The id of the role
 * @property string|null $name The name of the role
 * @property boolean $is_required
 * @property boolean $is_visible
 *
 * @method static Role|null find(string $id)
 * @method static Role      create(array $attributes)
 */
class Role extends Model implements RbacRole
{

    use Userstamps;

    use HasDescription, ImplementRbacRole;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'roles';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id','name','description','is_required','is_visible'];

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
     * Gives all the directly assigned Roles of this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedRoles() {
        return $this->belongsToMany(Role::class, 'role_role',
                                'parent_id','child_id');
    }

    /**
     * Gives all the directly assigned Permissions that are directly assigned to this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedPermissions() {
        return $this->belongsToMany(Permission::class, 'role_permission',
                                'role_id','permission_id');
    }

}
