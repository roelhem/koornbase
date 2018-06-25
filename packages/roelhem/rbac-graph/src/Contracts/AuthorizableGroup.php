<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 07:06
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Support\Collection;

interface AuthorizableGroup extends Authorizable
{

    /**
     * Returns a collection of all the authorizables that inherit the authorizations of this group.
     *
     * @return Collection|Authorizable[]
     */
    public function getAuthorizables();

}