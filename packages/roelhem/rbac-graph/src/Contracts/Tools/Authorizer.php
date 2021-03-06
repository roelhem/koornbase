<?php

namespace Roelhem\RbacGraph\Contracts\Tools;



use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;


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
     * @param RuleAttributeBag|null $bag
     * @return boolean
     */
    public function allows($node, $bag = null);

    /**
     * Returns if there is at least one node in the provided nodes for which the user is authorized.
     *
     * @param Collection|array|\Illuminate\Database\Eloquent\Builder $nodes
     * @param RuleAttributeBag|null $bag
     * @return boolean
     */
    public function any($nodes, $bag = null);

    /**
     * Returns if the authorizable in this authorizer is a super-user.
     *
     * @return boolean
     */
    public function isSuper();

}