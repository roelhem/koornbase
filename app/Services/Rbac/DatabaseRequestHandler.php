<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 11:31
 */

namespace App\Services\Rbac;


use App\Interfaces\Rbac\RbacAuthorizable;
use App\User;

class DatabaseRequestHandler extends AbstractRequestHandler
{

    /**
     * @inheritdoc
     */
    protected function getAuthorizableObject(): RbacAuthorizable
    {
        $user = \Auth::user();
        if($user instanceof User) {
            return $user;
        } else {
            return $this->service->getRoleById('guest');
        }
    }
}