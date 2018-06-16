<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 20:11
 */

namespace Roelhem\RbacGraph\Contracts;

/**
 * Interface Assignable
 *
 * Models that can have assignments.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Assignable extends Authorizable
{

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