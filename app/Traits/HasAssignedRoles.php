<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-04-18
 * Time: 09:02
 */

namespace App\Traits;

use App\Contracts\Rbac\RbacChecker;
use App\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait HasAssignedRoles
 * @package App\Traits
 *
 * @property-read Collection $assignedRoles
 */
trait HasAssignedRoles
{

    /**
     * @return RbacChecker
     */
    protected static function rbacChecker() {
        return resolve(RbacChecker::class);
    }

    /**
     * Returns if this model as the given role.
     *
     * @param Role|string $searchRole
     * @return boolean
     */
    public function hasRole($searchRole) {
        return self::rbacChecker()->modelHasRole($this, $searchRole);
    }

    /**
     * Returns if this model
     *
     * @param $searchPermission
     * @return boolean
     */
    public function hasPermission($searchPermission) {
        return self::rbacChecker()->modelHasPermission($this, $searchPermission);
    }

    /**
     * Convenient way to assign roles to this Model.
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignRole($role) {
        $this->assignedRoles()->attach($role);
        return $this;
    }

    /**
     * Relation that gives the directly assigned Roles of this Model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
     public function assignedRoles() {
        return $this->morphToMany(Role::class, 'assignable', 'role_assignments');
     }
}