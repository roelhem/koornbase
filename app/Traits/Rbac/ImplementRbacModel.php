<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 01:07
 */

namespace App\Traits\Rbac;


use App\Role;
use App\Services\Rbac\Traits\DefaultRbacModel;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait ImplementRbacModel
{

    use DefaultRbacModel, HasAssignedRoles;

    public function getRbacId() {
        return $this->id;
    }

    public function getRbacType() {
        return get_class($this);
    }

    public function getComputedRoles()
    {
        return [];
    }

    public function inheritsRolesFrom()
    {
        return [];
    }

    /**
     * @return MorphToMany
     */
    public function assignedRoles()
    {
        return $this->morphToMany(Role::class, 'assignable','role_assignments');
    }

}