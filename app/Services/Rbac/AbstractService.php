<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:11
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacAuthorizer;
use App\Contracts\Rbac\RbacBuilder;
use App\Contracts\Rbac\RbacProvider;
use App\Contracts\Rbac\RbacRequestHandler;
use App\Contracts\Rbac\RbacService;
use App\Interfaces\Rbac\RbacConstraint;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;

abstract class AbstractService implements RbacService
{
    /**
     * @var RbacBuilder
     */
    protected $builder;

    /**
     * @var RbacProvider
     */
    protected $provider;

    /**
     * @var RbacRequestHandler
     */
    protected $handler;

    /**
     * @var RbacAuthorizer
     */
    protected $authorizer;

    // -------------------------------------------------------------------------------------------------------- //
    // -------- SERVICE GETTERS ------------------------------------------------------------------------------- //
    // -------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function authorizer(): RbacAuthorizer
    {
        return $this->authorizer;
    }

    /**
     * @inheritdoc
     */
    public function builder(): RbacBuilder
    {
        return $this->builder;
    }

    /**
     * @inheritdoc
     */
    public function provider(): RbacProvider
    {
        return $this->provider;
    }

    /**
     * @inheritdoc
     */
    public function handler(): RbacRequestHandler
    {
        return $this->handler;
    }

    // -------------------------------------------------------------------------------------------------------- //
    // -------- BUILDER METHODS ------------------------------------------------------------------------------- //
    // -------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function permission(string $id, $name = null, $description = null): RbacPermission
    {
        return $this->builder->permission($id, $name, $description);
    }

    /**
     * @inheritdoc
     */
    public function role(string $id, $name = null, $description = null): RbacRole
    {
        return $this->builder->role($id, $name, $description);
    }

    /**
     * @inheritdoc
     */
    public function constraint(string $id, $name = null, $description = null): RbacConstraint
    {
        return $this->builder->constraint($id, $name, $description);
    }

    /**
     * @inheritdoc
     */
    public function group(string $prefix, callable $definitions)
    {
        return $this->builder->group($prefix, $definitions);
    }

    // -------------------------------------------------------------------------------------------------------- //
    // -------- REQUEST HANDLER METHODS ----------------------------------------------------------------------- //
    // -------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function hasPermission($permission): bool
    {
        return $this->handler->hasPermission($permission);
    }

    /**
     * @inheritdoc
     */
    public function hasRole($role): bool
    {
        return $this->handler->hasRole($role);
    }

    public function userRoles()
    {
        return $this->handler->userRoles();
    }

    public function userPermissions()
    {
        return $this->handler->userPermissions();
    }

    // -------------------------------------------------------------------------------------------------------- //
    // -------- PROVIDER METHODS ------------------------------------------------------------------------------ //
    // -------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function getPermission($permission)
    {
        return $this->provider->getPermission($permission);
    }

    /**
     * @inheritdoc
     */
    public function getPermissionId($permission)
    {
        return $this->provider->getPermissionId($permission);
    }

    /**
     * @inheritdoc
     */
    public function getPermissionById(string $id)
    {
        return $this->provider->getPermissionById($id);
    }

    /**
     * @inheritdoc
     */
    public function getPermissionByRoute($route)
    {
        return $this->provider->getPermissionByRoute($route);
    }

    /**
     * @inheritdoc
     */
    public function getRole($role)
    {
        return $this->provider->getRole($role);
    }

    /**
     * @inheritdoc
     */
    public function getRoleId($role)
    {
        return $this->provider->getRoleId($role);
    }

    /**
     * @inheritdoc
     */
    public function getRoleById(string $id)
    {
        return $this->provider->getRoleById($id);
    }
}