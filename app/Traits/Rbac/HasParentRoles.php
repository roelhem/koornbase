<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:25
 */

namespace App\Traits\Rbac;
use App\Interfaces\Rbac\RbacRole as RoleInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasParentRoles
 *
 * Helper methods for models that have parent roles.
 *
 * @package App\Traits\Rbac
 *
 * @property-read Collection $parentRoles
 */
trait HasParentRoles
{

    /**
     * Assigns this role to another role. This role will be the child of this other role.
     *
     * @param RoleInterface|string $role
     * @return $this
     */
    public function assignToRole($role)
    {
        if($role instanceof RoleInterface) {
            $role = $role->getId();
        }

        if(is_string($role)) {
            $this->parentRoles()->attach($role);
        }
        return $this;
    }

    /**
     * Returns a collection of all the roles that have this model assigned to it.
     *
     * @return Collection
     */
    public function getParentRoles()
    {
        return $this->parentRoles;
    }

    /**
     * Abstract relation that should give the parent roles of this model.
     *
     * @return BelongsToMany
     */
    abstract public function parentRoles();

}