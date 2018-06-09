<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 01:07
 */

namespace App\Traits\Rbac;


use App\Role;
use App\Services\Rbac\Traits\DefaultRbacAuthorizable;
use App\Services\Rbac\Traits\DefaultRbacModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasChildRoles
{

    use HasAssignedRoles;

    public function getChildRoles()
    {
        return $this->childRoles()->get();
    }

    public function getChildPermissions()
    {
        return [];
    }

    /**
     * @return Builder
     */
    abstract public function childRoles();

    /**
     * @return MorphToMany
     */
    public function assignedRoles()
    {
        return $this->morphToMany(Role::class, 'assignable','role_assignments');
    }

}