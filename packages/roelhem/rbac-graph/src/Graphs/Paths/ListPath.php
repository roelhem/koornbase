<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 03:07
 */

namespace Roelhem\RbacGraph\Graphs\Paths;


use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\MutablePath;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Exceptions\PathEmptyException;
use Roelhem\RbacGraph\Exceptions\PathWrongEdgeException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;
use Roelhem\RbacGraph\Graphs\Paths\Traits\HasEdgesArray;
use Roelhem\RbacGraph\Graphs\Paths\Traits\HasNodesArray;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasSuperGraph;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\SubGraphDefaultContains;

class ListPath implements MutablePath
{

    use HasSuperGraph;
    use HasNoAssignments;
    use HasNodesArray;
    use HasEdgesArray;
    use SubGraphDefaultContains;
    use GraphDefaultEquals;

    /**
     * ListPath constructor.
     *
     * @param Graph $graph
     * @param iterable|array $nodes
     */
    public function __construct(Graph $graph, $nodes = [])
    {
        $this->initGraph($graph);

        foreach ($nodes as $node) {
            $this->pushNode($node);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  MUTABLE PATH METHODS  -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function pushNode($node)
    {
        $node = $this->getGraph()->getNode($node);

        if($this->count() > 0) {
            $lastNode = $this->getLastNode();
            $edge = $this->getGraph()->getEdge($lastNode, $node);
            array_push($this->edges, $edge);
        }

        array_push($this->nodes, $node);
    }

    /** @inheritdoc */
    public function popNode()
    {
        if($this->count() <= 0) {
            throw new PathEmptyException("Can't pop a node from the path because the path is already empty.");
        }

        $node = array_pop($this->nodes);

        if(count($this->edges) > 0) {
            array_pop($this->edges);
        }

        return $node;
    }

    /** @inheritdoc */
    public function unshiftNode($node)
    {
        $node = $this->getGraph()->getNode($node);

        if($this->count() > 0) {
            $firstNode = $this->getFirstNode();
            $edge = $this->getGraph()->getEdge($node, $firstNode);
            array_unshift($this->edges, $edge);
        }

        array_unshift($this->nodes, $node);
    }

    /** @inheritdoc */
    public function shiftNode()
    {
        if($this->count() <= 0) {
            throw new PathEmptyException("Can't shift a node from the path because the path is already empty.");
        }

        $node = array_shift($this->nodes);

        if(count($this->edges) > 0) {
            array_shift($this->edges);
        }

        return $node;
    }

    /** @inheritdoc */
    public function pushEdge($edge)
    {
        if(!($edge instanceof Edge)) {
            throw new \InvalidArgumentException("The edge argument has to be an instance of Edge.");
        }

        if(!$this->getGraph()->contains($edge)) {
            throw new WrongGraphException("The edge argument doesn't belong to the super-graph of this path.");
        }

        if($this->count() === 0) {
            array_push($this->nodes, $edge->getParent());
        } else {
            $lastNode = $this->getLastNode();

            if(!($this->getNodeId($lastNode) === $edge->getParentId())) {
                throw new PathWrongEdgeException("The parent node of the pushed edge is different than the last node of this path.");
            }
        }

        array_push($this->edges, $edge);
        array_push($this->nodes, $edge->getChild());
    }

    public function __toString()
    {
        return "ListPath(size={$this->count()}): { ".
            collect($this->getNodeList())->map(function(Node $node) {
                return $node->getType()->getName().'['.$node->getName().']';
            })->implode('  >  ').
            " }";
    }


}