<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-05-18
 * Time: 23:56
 */

namespace App\Contracts\Rbac;
use App\Exceptions\Rbac\ModelNotFoundException;
use App\Exceptions\Rbac\PermissionNotFoundException;
use App\Exceptions\Rbac\RoleNotFoundException;

/**
 * Interface RbacGraph
 *
 * A Contract for classes that help to navigate trough a RbacGraph.
 *
 * @package App\Contracts\Rbac
 */
interface RbacGraph
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODELS --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if the model represented by the parameters can exist in this graph.
     *
     * (i.e. The model can have some roles assigned to it.)
     *
     * @param mixed $model
     * @param string|null $modelType
     * @return bool
     */
    public function modelExists($model, $modelType = null) : bool;

    /**
     * Returns a string representation of the model provided by the parameters.
     *
     * This string alone does not guaranty an unique mapping to a model. This will be the case if the string
     * is used in combination with the model type.
     *
     * @param mixed $model
     * @param string|null $modelType
     * @return string
     * @throws ModelNotFoundException
     */
    public function modelGetId($model, $modelType = null) : string;

    /**
     * Returns a string representing the type of the model provided by the parameters.
     *
     * If this graph doesn't use modelTypes, null is returned.
     *
     * @param mixed $model
     * @param string|null $modelType
     * @return string|null
     * @throws ModelNotFoundException
     */
    public function modelGetType($model, $modelType = null);

    /**
     * Returns the roles that are assigned to this model.
     *
     * @param mixed $model
     * @param string|null $modelType
     * @return iterable
     * @throws ModelNotFoundException
     */
    public function modelGetChildRoles($model, $modelType = null) : iterable;

    /**
     * Returns the models  of which the assigned roles should be inherited by the model from the parameters.
     *
     * @param $model
     * @param null $modelType
     * @return iterable
     * @throws ModelNotFoundException
     */
    public function modelGetInheritModels($model, $modelType = null) : iterable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ROLES ---------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Checks if the arguments of this method represent the same role.
     *
     * @param mixed $role
     * @param mixed $compareTo
     * @return bool
     */
    public function roleEquals($role, $compareTo) : bool;

    /**
     * Checks if the argument represents a role known in this graph.
     *
     * @param mixed $role
     * @return bool
     */
    public function roleExists($role) : bool;

    /**
     * Returns a string representation of given role.
     *
     * @param mixed $role
     * @return string
     * @throws RoleNotFoundException
     */
    public function roleGetId($role) : string;

    /**
     * Returns all the (direct) child roles of the given role.
     *
     * @param mixed $role
     * @return iterable
     * @throws RoleNotFoundException
     */
    public function roleGetChildRoles($role) : iterable;

    /**
     * Returns all the (direct) child permissions of the given role.
     *
     * @param mixed $role
     * @return iterable
     * @throws RoleNotFoundException
     */
    public function roleGetChildPermissions($role) : iterable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- PERMISSIONS ---------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Checks if the arguments of this method represent the same permission.
     *
     * @param $permission
     * @param $compareTo
     * @return bool
     */
    public function permissionEquals($permission, $compareTo) : Bool;

    /**
     * Checks if the argument of this method represents a permission known in this graph.
     *
     * @param $permission
     * @return bool
     */
    public function permissionExists($permission) : Bool;

    /**
     * Returns a string representation of the given permission.
     *
     * @param $permission
     * @return string
     * @throws PermissionNotFoundException
     */
    public function permissionGetId($permission) : string;

    /**
     * Returns all the (direct) child permissions of the given permission.
     *
     * @param $permission
     * @return iterable
     */
    public function permissionGetChildPermissions($permission) : iterable;

}