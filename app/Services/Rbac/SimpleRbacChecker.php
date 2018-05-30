<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-05-18
 * Time: 23:05
 */

namespace App\Services\Rbac;

use App\Contracts\Rbac\RbacAccessible;
use App\Contracts\Rbac\RbacChecker;
use App\Contracts\Rbac\RbacGraph;
use App\Permission;
use App\Role;

/**
 * Class SimpleRbacChecker
 *
 * Implements the RbacChecker Contract in the most simple, but inefficient way.
 *
 * @package App\Services\Rbac
 */
class SimpleRbacChecker implements RbacChecker
{

    /**
     * @var RbacGraph
     */
    protected $graph;

    /**
     * SimpleRbacChecker constructor.
     *
     * @param RbacGraph $graph
     */
    public function __construct(RbacGraph $graph) {
        $this->graph = $graph;
    }

    /**
     * @inheritdoc
     */
    public function roleHasRole($role, $searchedRole): bool
    {

        if($this->graph->roleEquals($role, $searchedRole)) {
            return true;
        }

        foreach ($this->graph->roleGetChildRoles($role) as $childRole) {
            if($this->roleHasRole($childRole, $searchedRole)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function permissionHasPermission($permission, $searchedPermission): bool
    {

        if($this->graph->permissionEquals($permission, $searchedPermission)) {
            return true;
        }

        foreach ($this->graph->permissionGetChildPermissions($permission) as $childPermission) {
            if($this->permissionHasPermission($childPermission, $searchedPermission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function roleHasPermission($role, $searchedPermission): bool
    {
        foreach ($this->graph->roleGetChildPermissions($role) as $childPermission) {
            if($this->permissionHasPermission($childPermission, $searchedPermission)) {
                return true;
            }
        }

        foreach ($this->graph->roleGetChildRoles($role) as $childRole) {
            if($this->roleHasPermission($childRole, $searchedPermission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function modelHasRole($model, $searchedRole): bool
    {
        foreach($this->graph->modelGetChildRoles($model) as $childRole) {
            if($this->roleHasRole($childRole, $searchedRole)) {
                return true;
            }
        }

        foreach($this->graph->modelGetInheritModels($model) as $inheritModel) {
            if($this->modelHasRole($inheritModel, $searchedRole)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function modelHasPermission($model, $searchedPermission): bool
    {
        foreach($this->graph->modelGetChildRoles($model) as $assignedRole) {
            if($this->roleHasPermission($assignedRole, $searchedPermission)) {
                return true;
            }
        }

        foreach ($this->graph->modelGetInheritModels($model) as $inheritModel) {
            if($this->modelHasPermission($inheritModel, $searchedPermission)) {
                return true;
            }
        }

        return false;
    }
}