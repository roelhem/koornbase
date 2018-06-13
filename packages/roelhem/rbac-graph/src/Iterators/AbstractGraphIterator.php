<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 12:54
 */

namespace Roelhem\RbacGraph\Iterators;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\GraphIterator;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Iterators\Traits\HasVisitedGraphNodes;

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