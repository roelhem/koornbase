<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 12:42
 */

namespace Roelhem\RbacGraph\Contracts\Tools;



use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Illuminate\Support\Collection;

/**
 * Interface GraphIterator
 *
 * A contract for iterators that iterates over every node in a graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface GraphIterator extends \Iterator, BelongsToGraph
{

    /**
     * GraphIterator constructor.
     *
     * @param Graph $graph
     * @param Node|string|integer|null $startNode
     */
    public function __construct(Graph $graph, $startNode = null);

    /**
     * Returns if the provided node was visited by the iterator.
     *
     * @param Node|string|integer $node
     * @return bool
     */
    public function isVisited( $node );

    /**
     * Returns a list of all nodes that are marked as visited.
     *
     * @return Collection|Node[]
     */
    public function getVisited();

    /**
     * Returns a list of all nodes that were not marked as visited.
     *
     * @return Collection|Node[]
     */
    public function getNotVisited();

}