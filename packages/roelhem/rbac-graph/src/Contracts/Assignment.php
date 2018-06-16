<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 20:02
 */

namespace Roelhem\RbacGraph\Contracts;

/**
 * Contract Assignment
 *
 * A contract for all the classes that represent the assignment of a node to an authorizable object.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Assignment
{


    /**
     *
     *
     * @return Graph
     */
    public function getGraph();


}