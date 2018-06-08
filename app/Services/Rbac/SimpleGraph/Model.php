<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 05:18
 */

namespace App\Services\Rbac\SimpleGraph;


use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;
use App\Interfaces\Rbac\RbacModel;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAssignable;
use Illuminate\Support\Collection;

class Model implements RbacModel
{

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var RbacModel[]
     */
    protected $inheritFrom = [];

    /**
     * @var RbacRole[]
     */
    protected $assignedRoles = [];

    /**
     * Model constructor.
     * @param Graph $graph
     * @param string $id
     * @param string $type
     */
    public function __construct(Graph $graph, string $id, string $type = 'default')
    {
        $this->graph = $graph;
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * Returns if this object has the provided rbac-node.
     *
     * @param RbacNode $node
     * @return boolean
     */
    public function has(RbacNode $node)
    {
        $this->graph->authorizer()->has($this, $node);
    }

    /**
     * Gives a string that, in combination with the value if getRbacType(), uniquely defines this model.
     *
     * @return string
     */
    public function getRbacId()
    {
        return $this->id;
    }

    /**
     * Gives a string that, in combination with the value if getRbacId(), uniquely defines this model.
     *
     * @return string
     */
    public function getRbacType()
    {
        return $this->type;
    }

    /**
     * Returns an array of all the roles that were inherited from other RbacModel's
     *
     * @return RbacRole[]
     */
    public function getInheritedRoles()
    {
        $res = [];
        $ids = [];
        foreach ($this->inheritFrom as $model) {
            if($model instanceof RbacModel) {
                foreach ($model->getInheritableRoles() as $role) {
                    if($role instanceof RbacRole) {
                        $roleId = $role->getId();
                    } else {
                        $roleId = strval($role);
                    }

                    if(!in_array($roleId, $ids)) {
                        $res[] = $role;
                        $ids[] = $roleId;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * Returns an array of all the roles that this model has and were computed based on the other attributes of
     * this model.
     *
     * @return RbacRole[]
     */
    public function getComputedRoles()
    {
        return [];
    }

    /**
     * Returns an array of all roles that are allowed to be inherited by other RbacModels
     *
     * @return RbacRole[]
     */
    public function getInheritableRoles()
    {
        return $this->getChildRoles();
    }

    /**
     * Returns an array of other RbacModels. This RbacModel should inherit the roles from these RbacModels
     *
     * @return RbacModel[]
     */
    public function inheritsRolesFrom()
    {
        return $this->inheritFrom;
    }

    /**
     * Adds a new source for inheriting models.
     *
     * @param RbacModel $model
     * @return $this
     */
    public function addInheritSource(RbacModel $model)
    {
        foreach ($this->inheritFrom as $item) {
            if($item->getRbacId() === $model->getRbacId() && $item->getRbacType() === $model->getRbacType()) {
                return $this;
            }
        }
        $this->inheritFrom[] = $model;
        return $this;
    }

    /**
     * Returns if the current object has the provided permission.
     *
     * @param RbacPermission|string $permission
     * @return boolean
     * @throws PermissionNotFoundException
     */
    public function hasPermission($permission)
    {
        return $this->graph->authorizer()->hasPermission($this, $permission);
    }

    /**
     * Returns if the current object has all the permissions in the given array.
     *
     * @param iterable $permissions
     * @param boolean $hasOne
     * @return boolean
     */
    public function hasPermissions(iterable $permissions, $hasOne = false)
    {
        return $this->graph->authorizer()->hasPermissions($this, $permissions, $hasOne);
    }

    /**
     * Returns all the direct child Permissions of this object.
     *
     * @return RbacPermission[]|Collection
     */
    public function getChildPermissions()
    {
        return [];
    }

    /**
     * Returns all the permissions that this object has.
     *
     * @return RbacPermission[]|Collection
     */
    public function getPermissions()
    {
        return $this->graph->authorizer()->getPermissions($this);
    }

    /**
     * Assigns the provided role to this object.
     *
     * @param RbacRole|string $role an instance or id of the Role to be assigned.
     * @return $this
     *
     * @throws RoleNotFoundException
     */
    public function assignRole($role)
    {
        if($role instanceof RbacRole) {
            $roleId = $role->getId();
        } elseif(is_string($role)) {
            $roleId = $role;
        } else {
            throw new RoleNotFoundException;
        }

        foreach ($this->assignedRoles as $assignedRole) {
            if($roleId === $assignedRole->getId()) {
                return $this;
            }
        }

        $role = $this->graph->getRole($roleId);

        $this->assignedRoles[] = $role;
        return $this;
    }

    /**
     * Returns a collection of all the roles that are directly assigned to this object.
     *
     * @return RbacRole[]|Collection
     */
    public function getAssignedRoles()
    {
        return $this->assignedRoles;
    }

    /**
     * Returns if this object has the provided role.
     *
     * @param RbacRole|string $role
     * @return boolean
     * @throws RoleNotFoundException
     */
    public function hasRole($role)
    {
       return $this->graph->authorizer()->hasRole($this, $role);
    }

    /**
     * Returns if this object has all the roles in the given array.
     *
     * @param iterable $roles
     * @param boolean $hasOne
     * @return boolean
     * @throws RoleNotFoundException
     */
    public function hasRoles(iterable $roles, $hasOne = false)
    {
        return $this->graph->authorizer()->hasRoles($this, $roles, $hasOne);
    }

    /**
     * Returns a list of all roles that are direct children of this object.
     *
     * @return RbacRole[]|Collection
     */
    public function getChildRoles()
    {
        $ids = [];
        $res = [];

        foreach ($this->getAssignedRoles() as $assignedRole) {
            if(!in_array($assignedRole->getId(), $ids)) {
                $ids[] = $assignedRole->getId();
                $res[] = $assignedRole;
            }
        }

        foreach ($this->getInheritedRoles() as $inheritedRole) {
            if(!in_array($inheritedRole->getId(), $ids)) {
                $ids[] = $inheritedRole->getId();
                $res[] = $inheritedRole;
            }
        }

        foreach ($this->getComputedRoles() as $computedRole) {
            if(!in_array($computedRole->getId(), $ids)) {
                $ids[] = $computedRole->getId();
                $res[] = $computedRole;
            }
        }

        return $res;
    }

    /**
     * Returns a list of all the roles that this object has.
     *
     * @return RbacRole[]|Collection
     */
    public function getRoles()
    {
        return $this->graph->authorizer()->getRoles($this);
    }
}