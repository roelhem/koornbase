<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:19
 */

namespace App\Services\Rbac;


use App\Constraint;
use App\Interfaces\Rbac\RbacConstraint;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Permission;
use App\Role;

class DatabaseBuilder extends AbstractBuilder
{

    /**
     * @inheritdoc
     */
    protected function createRole(string $id, $name = null, $description = null): RbacRole
    {
        return Role::create([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function createPermission(string $id, $name = null, $description = null) : RbacPermission
    {
        return Permission::create([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * @inheritdoc
     */
    public function constraint(string $id, $name = null, $description = null) : RbacConstraint
    {
        $constraint = Constraint::firstOrNew(['id' => $id]);
        if(!($constraint instanceof Constraint)) {
            abort(500);
        }
        if ($name !== null) {
            $constraint->name = $name;
        }
        if ($description !== null) {
            $constraint->description = $description;
        }
        $constraint->save();
        return $constraint;
    }
}