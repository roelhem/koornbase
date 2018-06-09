<?php

namespace App;

use App\Pivots\PermissionConstraint;
use App\Traits\HasDescription;
use App\Interfaces\Rbac\RbacPermission;
use App\Traits\Rbac\ImplementRbacPermission;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package App
 *
 * @property string $id The primary identifier of this Permission.
 * @property string|null $name A descriptive name of this Permission.
 * @property string|null $route
 *
 * @method static Permission|null find(string $id);
 * @method static Permission      create(array $attributes);
 */
class Permission extends Model implements RbacPermission
{

    use HasDescription, ImplementRbacPermission;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'permissions';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['id','name','description'];


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
    public function assignedPermissions() {
        return $this->belongsToMany(Permission::class, 'permission_permission',
            'parent_id','child_id');
    }

    public function constraints() {
        return $this->belongsToMany(Constraint::class, 'permission_constraint',
            'permission_id','constraint_id')->using(PermissionConstraint::class);
    }

}
