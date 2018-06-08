<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 21:32
 */

namespace App\Interfaces\Rbac;

/**
 * Interface RoleAuthorizable
 *
 * A interface for objects that have roles and permissions
 *
 * @package App\Interfaces\Rbac
 */
interface RbacAuthorizable extends RbacPermissionAuthorizable, RbacRoleAuthorizable
{

    /**
     * Returns if this object has the provided rbac-node.
     *
     * @param RbacNode $node
     * @return boolean
     */
    public function has(RbacNode $node);

    /**
     * Returns if this object has all the provided rbac-nodes.
     *
     * @param iterable $nodes
     * @return mixed
     */
    public function hasAll(iterable $nodes);

    /**
     * Returns if this object has at least one rbac-node in the provided $nodes parameter.
     *
     * @param iterable $nodes
     * @return mixed
     */
    public function hasSome(iterable $nodes);

}