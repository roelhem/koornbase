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
 * A contract for all the classes that represent the assignment of a node to an assignable object.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Assignment
{


    /**
     * Returns the graph of this assignment.
     *
     * @return Graph
     */
    public function getGraph();

    /**
     * Returns the node that is assigned to an authorizable object.
     *
     * @return Node
     */
    public function getNode();

    /**
     * Returns the assignable object that have a node assigned to it.
     *
     * @return Assignable
     */
    public function getAssignable();


}