<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-05-18
 * Time: 21:01
 */

namespace App\Contracts\Rbac;
use App\Permission;
use App\Role;

/**
 * Interface RbacCheckerContract
 *
 * This is a Contract for services that handle the logic to check if a certain model has a specific Role or Permission.
 *
 * @package App\Contracts\Rbac
 */
interface RbacChecker
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CHECK FOR A SINGLE RBAC-OBJECT --------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Checks if the provided role can pass for the searched role.
     *
     *
     *
     * This method should take the whole Rbac-graph structure in consideration. Therefore, it should return true in
     * the following cases (and only in the following cases):
     *
     *   [1]  The role is equal to the searched role.
     *   [2]  The role has a (direct/assigned) child role that has the searched role according to this method.
     *        (Notice that this case is defined recursively.)
     *
     *
     *
     * (You can think of this method in the following way: 'This method returns if the given role is the searched
     *  role or has a child somewhere in the whole Rbac-graph that is equal to the searched role.')
     *
     *
     *
     * @param Role|string  $role          An instance or role_id of the Role that should be checked.
     * @param Role|string  $searchedRole  An instance or role_id of the Role to search for.
     * @return bool
     */
    public function roleHasRole($role, $searchedRole) : bool;

    /**
     * Checks if the provided permission contains the searched permission.
     *
     *
     *
     * This method should take the whole Rbac-graph structure in consideration. Therefore, it should return true in
     * the following cases (and only in the following cases):
     *
     *   [1]  The permission is equal to the searched permission.
     *   [2]  The permission has a (direct/assigned) child permission that has the searched permission according
     *        to this method. (Notice that this case is defined recursively.)
     *
     *
     *
     * (You can think of this method in the following way: 'This method returns if the given permission is the
     *  searched permission or has a child somewhere in the whole Rbac-graph that is equal to the searched
     *  permission.')
     *
     *
     *
     * @param Permission|string  $permission          An instance or permission_id of the Permission that should
     *                                                be checked.
     * @param Permission|string  $searchedPermission  An instance or permission_id of the Permission to search for.
     * @return bool
     * @throws
     */
    public function permissionHasPermission($permission, $searchedPermission) : bool;

    /**
     * Checks if the provided role has the provided searched permission.
     *
     *
     *
     * This method should take the whole Rbac-graph in consideration. This is the case if the following logic
     * statement is true:
     *
     * There exist a Role $R and a Permission $P such that all statements below are true:
     *   (1)   $role `has` $R (according to the `roleHasRole` method).
     *   (2)   $P `has` $searchedPermission (according to the `permissionHasPermission` method).
     *   (3)   $P is directly assigned to $R.
     *
     *
     *
     * (You can think of this method in the following way: 'This method returns if the given role has the
     *  searched permission as a child permission.')
     *
     *
     * @param Role|string       $role                An instance or role_id of the Role that should be checked.
     * @param Permission|string $searchedPermission  An instance or permission_id of the Permission that the role
     *                                               checked by this method should have.
     * @return bool
     * @throws
     */
    public function roleHasPermission($role, $searchedPermission) : bool;

    /**
     * Checks if the provided model has the provided role.
     *
     *
     *
     * This method should take inherited roles and the whole Rbac-graph in consideration. Therefore, it should
     * return true in the following cases (and only the following cases):
     *
     *   [1]  This model has a role $R directly assigned to it (in the return value of the `$model->getAssignedRoles()`
     *        method) that `has` the $searchedRole (according to the `roleHasRole` method).
     *   [2]  This model inherits the roles from another model that `has` the searched role according to this
     *        method. (Notice that this case is defined recursively.)
     *
     *
     * (You can think of this method in the following way: 'This method returns if the model has a role or inherits
     *  a role that is equal to the searched role or has the searched role as a child.')
     *
     *
     *
     * @param mixed            $model         The model that should be checked.
     * @param Role|string      $searchedRole  An instance or role_id of the Role to search for.
     * @return bool
     * @throws
     */
    public function modelHasRole($model, $searchedRole) : bool;

    /**
     * Checks if the provided model has the provided permission.
     *
     *
     *
     * This method should take inherited roles and the whole Rbac-graph in consideration. This is the case if
     * the following logic statement is true:
     *
     * There exist a Role $R such that the following statements are true:
     *   (1)   $model 'has' $R (according to the `modelHasRole` method).
     *   (2)   $R 'has' $searchedPermission (according to the `roleHasPermission` method).
     *
     *
     *
     * @param mixed              $model               The model that should be checked.
     * @param Permission|string  $searchedPermission  An instance or permission_id of the searched permission
     * @return bool
     * @throws
     */
    public function modelHasPermission($model, $searchedPermission) : bool;


}