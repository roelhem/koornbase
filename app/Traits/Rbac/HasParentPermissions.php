<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 20:26
 */

namespace App\Traits\Rbac;
use App\Interfaces\Rbac\RbacPermission as PermissionInterface;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasParentPermissions
 *
 * @package App\Traits\Rbac
 *
 * @property-read Collection $parentPermissions
 */
trait HasParentPermissions
{

    /**
     * Assigns this model to an permission
     *
     * @param PermissionInterface|string $permission
     * @return $this
     */
    public function assignToPermission($permission) {

        if($permission instanceof PermissionInterface)
        {
            $permission = $permission->getId();
        }

        if(is_string($permission)) {
            $this->parentPermissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Returns a collection of all the permission that have this model assigned to it.
     *
     * @return Collection
     */
    public function getParentPermissions()
    {
        return $this->parentPermissions;
    }

    /**
     * The abstract relation that should give all the direct parents of this permission.
     *
     * @return BelongsToMany
     */
    abstract public function parentPermissions();

}