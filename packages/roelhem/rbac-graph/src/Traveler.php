<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 10:21
 */

namespace Roelhem\RbacGraph;


use Roelhem\RbacGraph\Contracts\Graph;

class Traveler
{

    protected $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @param $node
     * @throws Exceptions\NodeNotFoundException
     */
    public function depthFirst( $node )
    {
        $node = $this->graph->getNode($node);
        yield $node->getId() => $node;

        foreach ($this->graph->getChildren($node) as $child) {
            yield from $this->depthFirst($child);
        }
    }


}