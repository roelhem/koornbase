<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 21:43
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotUniqueException;

interface MutableGraph extends Graph
{

    /**
     * Adds the provided node to this Graph.
     *
     * @param Node $node
     * @return Node
     * @throws NodeNotUniqueException
     */
    public function addNode( Node $node );

    /**
     * Adds multiple nodes to this Graph.
     *
     * @param iterable|Collection|Node[] $nodes
     * @return Collection|Node[] $nodes
     * @throws NodeNotUniqueException
     */
    public function addNodes( iterable $nodes );

    /**
     * Adds the provided edge to this Graph.
     *
     * @param Edge $edge
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotUniqueException
     * @throws EdgeNotAllowedException
     */
    public function addEdge( Edge $edge );

    /**
     * Adds multiple edges to this Graph.
     *
     * @param iterable $edges
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     * @throws EdgeNotUniqueException
     * @throws EdgeNotAllowedException
     */
    public function addEdges( iterable $edges );

}