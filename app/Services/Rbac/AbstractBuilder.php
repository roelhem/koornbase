<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 09:47
 */

namespace App\Services\Rbac;


use App\Contracts\Rbac\RbacBuilder;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;

abstract class AbstractBuilder extends AbstractServiceComponent implements RbacBuilder
{

    /**
     * @inheritdoc
     */
    public function role(string $id, $name = null, $description = null) : RbacRole
    {
        $role = $this->service->getRoleById($id);
        if($role === null) {
            return $this->createRole($id, $name, $description);
        } else {

            if(is_string($name)) {
                $role->name($name);
            }

            if(is_string($description)) {
                $role->description($description);
            }

            return $role;
        }
    }

    /**
     * @inheritdoc
     */
    public function permission(string $id, $name = null, $description = null): RbacPermission
    {
        $permission = $this->service->getPermissionById($id);
        if($permission === null) {
            return $this->createPermission($id, $name, $description);
        } else {

            if(is_string($name)) {
                $permission->name($name);
            }

            if(is_string($description)) {
                $permission->description($description);
            }

            return $permission;
        }
    }

    /**
     * A methods that should create and return a new role with the provided $id, $name and $description.
     *
     * @param string $id
     * @param null $name
     * @param null $description
     * @return RbacRole
     */
    abstract protected function createRole(string $id, $name = null, $description = null) : RbacRole;

    /**
     * A methods that should create a new permission with the provided $id, $name and $description.
     *
     * @param string $id
     * @param null $name
     * @param null $description
     * @return RbacPermission
     */
    abstract protected function createPermission(string $id, $name = null, $description = null) : RbacPermission;

}