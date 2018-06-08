<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 02:21
 */

namespace App\Services\Rbac\SimpleGraph;


use App\Contracts\Rbac\RbacAuthorizer;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;
use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacPermissionAssignable;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAssignable;
use Illuminate\Support\Collection;

class Node implements RbacNode, RbacRoleAssignable, RbacPermissionAssignable, RbacAuthorizable
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
     * @var null|string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $description;

    /**
     * @var array
     */
    protected $assignedRoles = [];

    /**
     * @var array
     */
    protected $assignedPermissions = [];

    /**
     * Node constructor.
     * @param Graph $graph
     * @param string $id
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(Graph $graph, $id, $name = null, $description = null)
    {
        $this->graph = $graph;

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Assigns a Role or Permission to this Role.
     *
     * The assigned node will be a child of this role.
     *
     * @param RbacNode $node
     * @return $this
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
     */
    public function assign($node)
    {
        if($node instanceof RbacRole) {
            $this->assignRole($node);
        } elseif($node instanceof RbacPermission) {
            $this->assignPermission($node);
        }

        return $this;
    }

    /**
     * Assigns multiple Roles or Permissions to this Role.
     *
     * @param iterable $nodes
     * @return $this
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
     */
    public function assignAll(iterable $nodes)
    {
        foreach ($nodes as $node) {
            if($node instanceof RbacNode) {
                $this->assign($node);
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assignPermission($permission)
    {

        if ($permission instanceof RbacPermission) {
            $id = $permission->getId();
        } elseif(is_string($permission)) {
            $id = $permission;
        } else {
            throw new PermissionNotFoundException;
        }

        $permission = $this->graph->getPermission($id);

        if($permission instanceof Permission) {

            foreach ($this->assignedPermissions as $assignedPermission) {
                if ($assignedPermission->getId() === $permission->getId()) {
                    return $this;
                }
            }

            $this->assignedPermissions[] = $permission;
        } else {
            throw new PermissionNotFoundException;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAssignedPermissions()
    {
        return $this->assignedPermissions;
    }

    /**
     * @inheritdoc
     */
    public function assignRole($role)
    {
        if($role instanceof RbacRole) {
            $id = $role->getId();
        } elseif(is_string($role)) {
            $id = $role;
        } else {
            throw new RoleNotFoundException;
        }

        $role = $this->graph->getRole($id);

        if($role instanceof Role) {
            foreach ($this->assignedRoles as $assignedRole) {
                if($assignedRole->getId() === $role->getId()) {
                    return $this;
                }
            }

            $this->assignedRoles[] = $role;
        } else {
            throw new RoleNotFoundException;
        }
    }

    /**
     * @inheritdoc
     */
    public function getAssignedRoles()
    {
        return $this->assignedRoles;
    }

    /**
     * @inheritdoc
     */
    public function has(RbacNode $node)
    {
        return $this->graph->authorizer()->has($this, $node);
    }

    /**
     * @inheritdoc
     */
    public function hasPermission($permission)
    {
        return $this->graph->authorizer()->hasPermission($this, $permission);
    }

    /**
     * @inheritdoc
     */
    public function hasPermissions(iterable $permissions, $hasOne = false)
    {
        return $this->graph->authorizer()->hasPermissions($this, $permissions, $hasOne);
    }

    /**
     * @inheritdoc
     */
    public function getChildPermissions()
    {
        return $this->getAssignedPermissions();
    }

    /**
     * @inheritdoc
     */
    public function getPermissions()
    {
        return $this->graph->authorizer()->getPermissions($this);
    }

    /**
     * @inheritdoc
     */
    public function hasRole($role)
    {
        return $this->graph->authorizer()->hasRole($this, $role);
    }

    /**
     * @inheritdoc
     */
    public function hasRoles(iterable $roles, $hasOne = false)
    {
        return $this->graph->authorizer()->hasRoles($this, $roles, $hasOne);
    }

    /**
     * @inheritdoc
     */
    public function getChildRoles()
    {
        return $this->getAssignedRoles();
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->graph->authorizer()->getRoles($this);
    }
}