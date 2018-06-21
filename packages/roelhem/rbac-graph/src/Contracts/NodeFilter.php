<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 20:12
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Support\Collection;

interface NodeFilter extends BelongsToGraph
{

    /**
     * Returns whether or not the provided node is passed by this NodeFilter
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    public function includeNode( $node );

    /**
     * Filters an array or collection of nodes.
     *
     * @param iterable|Collection|Node[] $nodes
     * @return Collection|Node[]
     */
    public function filter( $nodes );

    /**
     * Applies the filter on the argument.
     *
     * @param Node|iterable|Collection|Node[] $arg
     * @return boolean|Collection|Node[]
     */
    public function __invoke( $arg );

}