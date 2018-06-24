<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 24-06-18
 * Time: 20:27
 */

namespace Roelhem\RbacGraph\Contracts;
use Illuminate\Support\Collection;


/**
 * Contract AuthorizableGraph
 *
 * A contract for graphs that you can use to authorize authorizable objects.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface AuthorizableGraph extends Graph
{

    /**
     * Returns the nodes that are authorized for the given authorizable object in the initial state.
     *
     * (These are the the nodes that are granted to the authorizable before walking trough the graph.)
     *
     * @param Authorizable $authorizable
     * @return Collection|Node[]
     */
    public function getEntryNodes($authorizable);

}