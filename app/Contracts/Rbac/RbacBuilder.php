<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 11:28
 */

namespace App\Contracts\Rbac;
use App\Interfaces\Rbac\RbacConstraint;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;


/**
 * Interface RbacBuilder
 *
 * This contract is for services that build the RBAC-tree.
 *
 * @package App\Contracts\Rbac
 */
interface RbacBuilder
{

    /**
     * Defines a permission.
     *
     * @param string $id
     * @param string|null $name
     * @param string|null $description
     * @return RbacPermission
     */
    public function permission(string $id, $name = null, $description = null) : RbacPermission;

    /**
     * Defines a role.
     *
     * @param string $id
     * @param string|null $name
     * @param string|null $description
     * @return RbacRole
     */
    public function role(string $id, $name = null, $description = null) : RbacRole;

    /**
     * Defines a constraint.
     *
     * @param string $id
     * @param string|null $name
     * @param string|null $description
     * @return RbacConstraint
     */
    public function constraint(string $id, $name = null, $description = null) : RbacConstraint;

    /**
     * @param string $prefix
     * @param callable $definitions
     */
    public function group(string $prefix, callable $definitions);

}