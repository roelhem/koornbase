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

    /**
     * A function that returns a string that can uniquely identify this assignable object in combination with the
     * id.
     *
     * @return string
     */
    public function getType();

    /**
     * A function that returns the id of this authorizable object. In combination with the type, this should be
     * able to uniquely identify this assignable object from all other assignable objects.
     *
     * @return string|integer
     */
    public function getId();

}