<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:00
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacRequestHandler;
use App\Interfaces\Rbac\RbacAuthorizable;

abstract class AbstractRequestHandler extends AbstractServiceComponent implements RbacRequestHandler
{

    /**
     * @inheritdoc
     */
    public function hasPermission($permission): bool
    {
        $authorizer = $this->service->authorizer();

        $authorizable = $this->getAuthorizableObject();

        return $authorizer->hasPermission($authorizable, $permission);
    }

    /**
     * @inheritdoc
     */
    public function hasRole($role): bool
    {
        $authorizer = $this->service->authorizer();

        $authorizable = $this->getAuthorizableObject();

        return $authorizer->hasRole($authorizable, $role);
    }

    public function userRoles()
    {
        $authorizer = $this->service->authorizer();

        $authorizable = $this->getAuthorizableObject();

        return $authorizer->getRoles($authorizable);
    }

    public function userPermissions()
    {
        $authorizer = $this->service->authorizer();

        $authorizable = $this->getAuthorizableObject();

        return $authorizer->getPermissions($authorizable);
    }

    /**
     * Returns the RbacAuthorizable object that needs to have all the requirements for this request.
     *
     * @return RbacAuthorizable
     */
    abstract protected function getAuthorizableObject() : RbacAuthorizable;

}