<?php

namespace Roelhem\RbacGraph\Contracts;


/**
 * Contract Authorizer
 *
 * A contract for objects that handle an authorization with a the authorization-graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Authorizer
{

    /**
     * The graph on which the authorizer should preform the authorization.
     *
     * @return Graph
     */
    public function getGraph();

}