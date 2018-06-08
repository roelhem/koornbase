<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 18:56
 */

namespace App\Traits\Rbac;


use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacRole as RoleInterface;
use App\Interfaces\Rbac\RbacPermission as PermissionInterface;
use App\Interfaces\Rbac\RbacRoleAssignable;
use App\Services\Rbac\Traits\DefaultRbacAuthorizable;

/**
 * Trait ImplementRbacRole
 *
 * Implements the methods for the RoleInterface
 *
 * @package App\Traits\Rbac
 */
trait ImplementRbacRole
{

    use ImplementRbacNode, DefaultRbacAuthorizable, HasParentRoles, HasAssignedRoles, HasAssignedPermissions;

    /**
     * Sets the is_required attribute in a fluent way and saves the model.
     *
     * @param bool $is_required
     * @return $this
     */
    public function required($is_required = true) {
        $this->is_required = $is_required;
        $this->save();
        return $this;
    }

    /**
     * Assigns this role to the target RoleAssignable object.
     *
     * @param RbacRoleAssignable $target
     * @return $this
     * @throws
     */
    public function assignTo(RbacRoleAssignable $target)
    {
        $target->assignRole($this);
        return $this;
    }

    /**
     * Assigns a Permission or Role to this Role.
     *
     * @param RbacNode $node
     * @return $this
     */
    public function assign($node)
    {
        if ($node instanceof RoleInterface) {
            $this->assignRole($node);
        } elseif ($node instanceof PermissionInterface) {
            $this->assignPermission($node);
        }
        return $this;
    }

    /**
     * Assigns multiple Roles or Permissions to this Role.
     *
     * @param iterable $nodes
     * @return $this
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
    public function getChildRoles()
    {
        return $this->getAssignedRoles();
    }

    /**
     * @inheritdoc
     */
    public function getChildPermissions()
    {
        return $this->getAssignedPermissions();
    }


}