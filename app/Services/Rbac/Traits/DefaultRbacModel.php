<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 00:53
 */

namespace App\Services\Rbac\Traits;


use App\Interfaces\Rbac\RbacModel;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use Illuminate\Support\Collection;

trait DefaultRbacModel
{

    use DefaultRbacAuthorizable;

    /**
     * @return RbacRole[]|Collection
     */
    public function getChildRoles()
    {
        return collect([
            $this->getAssignedRoles(),
            $this->getComputedRoles(),
            $this->getInheritedRoles()
        ])->flatten()->unique(function($role) {
            if(is_string($role)) {
                return $role;
            } elseif($role instanceof RbacRole) {
                return $role->getId();
            } else {
                return null;
            }
        });
    }

    /**
     * @return RbacRole[]|Collection
     */
    public function getInheritedRoles()
    {
        $res = collect([]);
        foreach ($this->inheritsRolesFrom() as $model) {
            if($model instanceof RbacModel) {
                $res->push($model->getInheritableRoles());
            }
        }
        return $res->flatten()->unique(function($role) {
            if(is_string($role)) {
                return $role;
            } elseif($role instanceof RbacRole) {
                return $role->getId();
            } else {
                return null;
            }
        });
    }

    /**
     * @return RbacRole[]|Collection
     */
    public function getInheritableRoles()
    {
        return $this->getChildRoles();
    }

    /**
     * @return RbacPermission[]|Collection
     */
    public function getChildPermissions()
    {
        return [];
    }

    /**
     * @return RbacModel[]|Collection
     */
    abstract public function inheritsRolesFrom();

    /**
     * @return RbacRole[]|Collection
     */
    abstract public function getComputedRoles();

    /**
     * @return RbacRole[]|Collection
     */
    abstract public function getAssignedRoles();



}