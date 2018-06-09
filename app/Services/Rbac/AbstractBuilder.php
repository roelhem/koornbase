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

    protected $prefix = '';

    /**
     * @inheritdoc
     */
    public function role(string $id, $name = null, $description = null) : RbacRole
    {
        $role = $this->service->getRoleById($this->prefix.$id);
        if($role === null) {
            return $this->createRole($this->prefix.$id, $name, $description);
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
        $permission = $this->service->getPermissionById($this->prefix.$id);
        if ($permission === null) {
            return $this->createPermission($this->prefix.$id, $name, $description);
        } else {

            if (is_string($name)) {
                $permission->name($name);
            }

            if (is_string($description)) {
                $permission->description($description);
            }

            return $permission;
        }
    }

    /**
     * @param string $prefix
     * @param callable $definitions
     */
    public function group(string $prefix, callable $definitions)
    {
        $oldPrefix = $this->prefix;
        $this->prefix .= $prefix;

        $definitions();

        $this->prefix = $oldPrefix;
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