<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-06-18
 * Time: 03:25
 */

namespace App\Services\Rbac;


use App\Interfaces\Rbac\RbacConstraint;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Permission;
use App\Role;
use Illuminate\Database\Eloquent\Collection;

class RbacPostgres
{

    const ROLE_PATH_EXISTS_SINGLE = <<<SQL
        WITH RECURSIVE role_graph(id, child_id) AS(
            SELECT r.id, r.id
            FROM roles r
            WHERE r.id = ?
          UNION
            SELECT rg.id, rr.child_id
            FROM role_graph rg, role_role rr
            WHERE rr.parent_id = rg.child_id
        ) SELECT EXISTS(SELECT FROM role_graph WHERE child_id = ?);
SQL;

    public function roleHasRole(string $role_id, string $searched_id) {
        return \DB::select(self::ROLE_PATH_EXISTS_SINGLE, [$role_id, $searched_id])[0]->exists;
    }


    const ROLE_ALL_CHILDREN_SINGLE = <<<SQL
        WITH RECURSIVE role_graph(id, child_id) AS(
            SELECT r.id, r.id
            FROM roles r
            WHERE r.id = ?
          UNION
            SELECT rg.id, rr.child_id
            FROM role_graph rg, role_role rr
            WHERE rr.parent_id = rg.child_id
        ) SELECT child_id as role_id FROM role_graph
SQL;

    /**
     * Returns an array with the id's of all the roles of the provided role.
     *
     * @param string $role_id
     * @return Collection
     */
    public function roleGetRoleIds(string $role_id) {
        return collect(\DB::select(self::ROLE_ALL_CHILDREN_SINGLE, [$role_id]))->map(function(\stdClass $obj) {
            return $obj->role_id;
        });
    }

    /**
     * Returns all the roles of a role with the given $role_id.
     *
     * @param string $role_id
     * @return Collection|static[]
     */
    public function roleGetRoles(string $role_id) {
        $subquery = self::ROLE_ALL_CHILDREN_SINGLE;
        return Role::query()->whereRaw("roles.id IN ({$subquery})", [$role_id])->get();
    }


    const ROLE_ALL_CHILDREN_MULTIPLE = <<<SQL
        WITH RECURSIVE role_graph(id, child_id) AS(
            SELECT r.id, r.id
            FROM roles r
            WHERE r.id = ANY(?)
          UNION
            SELECT rg.id, rr.child_id
            FROM role_graph rg, role_role rr
            WHERE rr.parent_id = rg.child_id
        ) SELECT child_id as role_id FROM role_graph
SQL;

    /**
     * Returns all the roles of the roles in the provided parameter.
     *
     * @param iterable $roles
     * @return Collection
     */
    public function roleGetAllRoleIds(iterable $roles) {

        $postgresArray = collect($roles)->map(function($role) {
            if(is_string($role)) {
                return $role;
            } elseif($role instanceof RbacRole) {
                return $role->getId();
            }
        })->implode(',');

        return collect(\DB::select(self::ROLE_ALL_CHILDREN_MULTIPLE, [
            "{{$postgresArray}}"
        ]))->map(function($obj) {
            return $obj->role_id;
        });
    }

    /**
     * @param iterable $roles
     * @return Collection
     */
    public function roleGetAllRoles(iterable $roles) {
        $postgresArray = collect($roles)->map(function($role) {
            if(is_string($role)) {
                return $role;
            } elseif($role instanceof RbacRole) {
                return $role->getId();
            }
        })->implode(',');
        $subquery = self::ROLE_ALL_CHILDREN_MULTIPLE;

        return Role::query()->whereRaw("roles.id IN ({$subquery})", ["{{$postgresArray}}"])->get();
    }


    const PERMISSION_PATH_EXISTS_SINGLE = <<<SQL
            
        WITH RECURSIVE permission_graph(id, child_id) AS (
          WITH avoiding_constraints AS (
            SELECT permission_id FROM permission_constraint
            WHERE NOT constraint_id = ANY(?)
          )
          SELECT p.id,
            p.id
          FROM permissions p
          WHERE p.id = ? AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = p.id)
        
          UNION ALL
        
          SELECT pg.id,
            pp.child_id
          FROM permission_graph pg, permission_permission pp
          WHERE pp.parent_id = pg.child_id AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = pp.child_id)
        
        )
        SELECT EXISTS(SELECT FROM permission_graph WHERE child_id = ?);
SQL;

    protected function constraintsToPostgresArray(iterable $constraints) {
        return '{'.collect($constraints)->map(function($constraint) {
            if(is_string($constraint)) {
                return $constraint;
            } elseif($constraint instanceof RbacConstraint) {
                return $constraint->getId();
            }
        })->implode(',').'}';
    }

    public function permissionHasPermission(string $permission_id, string $search_id, iterable $allowedConstraints = []) {

        return \DB::selectOne(self::PERMISSION_PATH_EXISTS_SINGLE, [
            $this->constraintsToPostgresArray($allowedConstraints),
            $permission_id,
            $search_id
        ])->exists;
    }



    const PERMISSION_CHILDREN_SINGLE = <<<SQL
            
        WITH RECURSIVE permission_graph(id, child_id) AS (
          WITH avoiding_constraints AS (
            SELECT permission_id FROM permission_constraint
            WHERE NOT constraint_id = ANY(?)
          )
          SELECT p.id,
            p.id
          FROM permissions p
          WHERE p.id = ? AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = p.id)
        
          UNION ALL
        
          SELECT pg.id,
            pp.child_id
          FROM permission_graph pg, permission_permission pp
          WHERE pp.parent_id = pg.child_id AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = pp.child_id)
        
        )
        SELECT child_id AS permission_id FROM permission_graph
SQL;

    public function permissionGetPermissionIds(string $permission_id, iterable $allowedConstraints = []) {
        return collect(\DB::select(self::PERMISSION_CHILDREN_SINGLE, [
            $this->constraintsToPostgresArray($allowedConstraints),
            $permission_id
        ]))->map(function ($obj) {
            return $obj->permission_id;
        });
    }

    public function permissionGetPermissions(string $permission_id, iterable $allowedConstraints = []) {
        $subQuery = self::PERMISSION_CHILDREN_SINGLE;
        return Permission::query()->whereRaw(" id IN ($subQuery)", [
            $this->constraintsToPostgresArray($allowedConstraints),
            $permission_id
        ])->get();
    }



    const PERMISSION_CHILDREN_MULTIPLE = <<<SQL
            
        WITH RECURSIVE permission_graph(id, child_id) AS (
          WITH avoiding_constraints AS (
            SELECT permission_id FROM permission_constraint
            WHERE NOT constraint_id = ANY(?)
          )
          SELECT p.id,
            p.id
          FROM permissions p
          WHERE p.id = ANY(?) AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = p.id)
        
          UNION ALL
        
          SELECT pg.id,
            pp.child_id
          FROM permission_graph pg, permission_permission pp
          WHERE pp.parent_id = pg.child_id AND NOT EXISTS(SELECT FROM avoiding_constraints WHERE permission_id = pp.child_id)
        
        )
        SELECT child_id AS permission_id FROM permission_graph
SQL;


    protected function permissionsToPostgresArray(iterable $permissions) {
        return '{'.collect($permissions)->map(function($permission) {
                if(is_string($permission)) {
                    return $permission;
                } elseif($permission instanceof RbacPermission) {
                    return $permission->getId();
                }
            })->implode(',').'}';
    }

    public function permissionGetAllPermissionIds(iterable $permissions, iterable $allowedConstraints = []) {
        return collect(\DB::select(self::PERMISSION_CHILDREN_MULTIPLE, [
            $this->constraintsToPostgresArray($allowedConstraints),
            $this->permissionsToPostgresArray($permissions)
        ]))->map(function ($obj) {
            return $obj->permission_id;
        });
    }

    public function permissionGetAllPermissions(iterable $permissions, iterable $allowedConstraints = []) {
        $subQuery = self::PERMISSION_CHILDREN_MULTIPLE;
        return Permission::query()->whereRaw(" id IN ($subQuery)", [
            $this->constraintsToPostgresArray($allowedConstraints),
            $this->permissionsToPostgresArray($permissions)
        ])->get();
    }








    const ROLE_GRAPH_QUERY = <<<SQL
        WITH RECURSIVE roles_graph(id, child_id, path_length, path, cycle) AS (
        
            SELECT
              r.id,
              r.id,
              0,
              ARRAY[]::VARCHAR[],
              false
            FROM roles r
            WHERE r.id = ? 
        
          UNION ALL
        
            SELECT
              rg.id,
              rr.child_id,
              path_length + 1,
              rg.path || rr.child_id,
              rr.child_id = ANY(path)
            FROM roles_graph rg, role_role rr
            WHERE rr.parent_id = rg.child_id AND NOT cycle
        
        )
        SELECT
          rg.id as start_id,
          rg.child_id as end_id,
          rg.path_length,
          array_to_json(rg.path) as path
        FROM roles_graph rg;
SQL;

}