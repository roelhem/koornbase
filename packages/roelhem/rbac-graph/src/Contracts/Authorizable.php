<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 19:35
 */

namespace Roelhem\RbacGraph\Contracts;
use Illuminate\Support\Collection;

/**
 * Interface Authorizable
 *
 * For all classes where you can preform algorithms.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Authorizable
{

    /**
     * Returns a collection of all the authorizable groups where this authorizable object belongs to.
     *
     * @return Collection|AuthorizableGroup[]
     */
    public function getAuthorizableGroups();

}