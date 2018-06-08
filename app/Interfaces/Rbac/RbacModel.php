<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 23:36
 */

namespace App\Interfaces\Rbac;


interface RbacModel extends RbacAuthorizable, RbacRoleAssignable
{

    /**
     * Gives a string that, in combination with the value if getRbacType(), uniquely defines this model.
     *
     * @return string
     */
    public function getRbacId();

    /**
     * Gives a string that, in combination with the value if getRbacId(), uniquely defines this model.
     *
     * @return string
     */
    public function getRbacType();

    /**
     * Returns an array of all the roles that were inherited from other RbacModel's
     *
     * @return RbacRole[]
     */
    public function getInheritedRoles();

    /**
     * Returns an array of all the roles that this model has and were computed based on the other attributes of
     * this model.
     *
     * @return RbacRole[]
     */
    public function getComputedRoles();

    /**
     * Returns an array of all roles that are allowed to be inherited by other RbacModels
     *
     * @return RbacRole[]
     */
    public function getInheritableRoles();

    /**
     * Returns an array of other RbacModels. This RbacModel should inherit the roles from these RbacModels
     *
     * @return RbacModel[]
     */
    public function inheritsRolesFrom();



}