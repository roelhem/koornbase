<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 17:13
 */

namespace App\Facades;


use App\Contracts\Rbac\RbacBuilder;
use App\Contracts\Rbac\RbacService;
use App\Contracts\Rbac\RbacProvider;
use App\Contracts\Rbac\RbacRequestHandler;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacPermission;
use Illuminate\Support\Facades\Facade;
use \App\Permission;
use \App\Role;
use App\Contracts\Rbac\RbacAuthorizer;
/**
 * Class Rbac
 *
 * @package App\Facades
 *
 * @method static Permission permission(string $id, string $name = null, string $description = null)
 * @method static Role role(string $id, string $name = null, string $description = null)
 *
 * @method static RbacAuthorizer authorizer()
 * @method static RbacBuilder builder()
 * @method static RbacProvider provider()
 * @method static RbacRequestHandler handler()
 *
 * @method static bool hasRole(RbacRole|string $id);
 * @method static bool hasPermission(RbacPermission|string $id)
 * @method static iterable userRoles();
 * @method static iterable userPermissions();
 */
class Rbac extends Facade
{

    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return RbacService::class;
    }

}