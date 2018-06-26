<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 12:54
 */

namespace Roelhem\RbacGraph\Graphs\Tools\Iterators;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Tools\GraphIterator;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Graphs\Tools\Iterators\Traits\HasVisitedGraphNodes;

abstract class AbstractGraphIterator implements GraphIterator
{

    use HasVisitedGraphNodes;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var Node
     */
    protected $startNode;


    /**
     * @inheritdoc
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * @inheritdoc
     */
    public function __construct(Graph $graph, $startNode = null)
    {
        $this->graph = $graph;
        $this->initVisited();

        if($startNode === null) {
            $startNode = $this->nextNotVisitedId();
        }

        if ($startNode === null) {
            $this->startNode = null;
        } else {
            $this->startNode = $this->graph->getNode($startNode);
        }

    }



}