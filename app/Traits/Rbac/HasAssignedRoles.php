<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:29
 */

namespace App\Traits\Rbac;


use App\Interfaces\Rbac\RbacRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasChildRoles
 *
 * @package App\Traits\Rbac
 *
 * @property-read Collection $assignedRoles
 */
trait HasAssignedRoles
{

    /**
     * Assigns a role to this model as a child.
     *
     * @param RbacRole|string $role
     * @return $this
     */
    public function assignRole($role) {
        if($role instanceof RbacRole) {
            $role = $role->getId();
        }

        if(is_string($role)) {
            $this->assignedRoles()->syncWithoutDetaching([$role]);
        }

        return $this;
    }

    /**
     * Assigns multiple roles to this role.
     *
     * @param iterable $roles
     */
    public function assignRoles(iterable $roles) {
        $this->assignedRoles()->syncWithoutDetaching(collect($roles)->map(function($role) {
            if(is_string($role)) {
                return $role;
            } elseif($role instanceof RbacRole) {
                return $role->getId();
            } else {
                return null;
            }
        })->values());
    }

    /**
     * Returns a collection with all the roles that are directly assigned to this model.
     *
     * @return RbacRole[]|Collection
     */
    public function getAssignedRoles()
    {
        return $this->assignedRoles;
    }

    /**
     * Abstract relation that should give the direct child roles of this model.
     *
     * @return BelongsToMany|MorphToMany
     */
    abstract public function assignedRoles();
}