<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:29
 */

namespace App\Traits\Rbac;


use App\Interfaces\Rbac\RbacPermission;
use App\Services\Rbac\SimpleGraph\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait HasChildPermissions
 *
 * @package App\Traits\Rbac
 *
 * @property-read Collection $assignedPermissions
 */
trait HasAssignedPermissions
{


    /**
     * Assigns a permission to this model as a child.
     *
     * @param RbacPermission|string $permission
     * @return $this
     */
    public function assignPermission($permission) {
        if($permission instanceof RbacPermission) {
            $permission = $permission->getId();
        }

        if(is_string($permission)) {
            $this->assignedPermissions()->syncWithoutDetaching([$permission]);
        }

        return $this;
    }

    /**
     * Assigns this object to multiple permissions.
     *
     * @param iterable $permissions
     * @return  $this
     */
    public function assignPermissions(iterable $permissions) {
        $this->assignedPermissions()->syncWithoutDetaching(collect($permissions)->map(function($permission) {
            if(is_string($permission)) {
                return $permission;
            } elseif($permission instanceof RbacPermission) {
                return $permission->getId();
            } else {
                return null;
            }
        })->values());

        return $this;
    }

    /**
     * Returns a collection of all the permissions which are directly assigned to this model.
     *
     * @return RbacPermission[]|Collection
     */
    public function getAssignedPermissions() {
        return $this->assignedPermissions;
    }

    /**
     * Abstract relation that should give all the permissions that are direct children of this permission.
     *
     * @return BelongsToMany
     */
    abstract public function assignedPermissions();

}