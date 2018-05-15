<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-04-18
 * Time: 09:02
 */

namespace App\Traits;

use App\Role;

trait HasAssignedRoles
{

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    abstract public function assignedRoles();

}