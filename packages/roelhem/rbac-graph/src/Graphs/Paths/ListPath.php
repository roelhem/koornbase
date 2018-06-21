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

    /**
     * ListPath constructor.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->initGraph($graph);
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



}