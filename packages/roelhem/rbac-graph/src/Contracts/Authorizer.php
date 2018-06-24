<?php

namespace Roelhem\RbacGraph\Contracts;


/**
 * Contract Authorizer
 *
 * A contract for objects that handle an authorization with an authorization-graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Authorizer extends BelongsToGraph
{

    /**
     * Returns the authorizable graph on which this authorizer performs its authorization.
     *
     * @return AuthorizableGraph
     */
    public function getGraph();

    /**
     * Returns the authorizable object which is authorized by this authorizer.
     *
     * @return Authorizable
     */
    public function getAutorizable();

    /**
     * Authorizes the provided node and returns the verdict.
     *
     * @param Node|string|integer $node
     * @param array $attributes
     * @return boolean
     */
    public function authorize($node, $attributes);

}