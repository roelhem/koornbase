<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 00:41
 */

namespace App\Services\Rbac\Traits;


use App\Facades\Rbac;
use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;

trait DefaultRbacAuthorizable
{

    use DefaultRbacRoleAuthorizable, DefaultRbacPermissionAuthorizable;

    public function has(RbacNode $node) {
        if($this instanceof RbacAuthorizable) {
            return Rbac::authorizer()->has($this, $node);
        }
        return false;
    }

    public function hasAll(iterable $nodes) {
        if($this instanceof RbacAuthorizable) {
            return Rbac::authorizer()->hasAll($this, $nodes);
        }
        return false;
    }

    public function hasSome(iterable $nodes) {
        if($this instanceof RbacAuthorizable) {
            return Rbac::authorizer()->hasSome($this, $nodes);
        }
        return false;
    }
}