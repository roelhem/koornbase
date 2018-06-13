<?php

namespace Roelhem\RbacGraph\Edges;



use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class SimpleEdge implements Edge
{

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var int
     */
    protected $childId;

    /**
     * SimpleEdge constructor.
     * @param Graph $graph
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @throws NodeNotFoundException
     */
    public function __construct(Graph $graph, $parent, $child)
    {
        $this->graph = $graph;
        $this->parentId = $graph->getNodeId($parent);
        $this->childId = $graph->getNodeId($child);
    }

    /**
     * @return Graph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @return string
     * @throws NodeNotFoundException
     */
    public function getParentName()
    {
        return $this->graph->getNodeName($this->parentId);
    }

    /**
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getParent()
    {
        return $this->graph->getNodeById($this->parentId);
    }

    /**
     * @return int
     */
    public function getChildId()
    {
        return $this->childId;
    }

    /**
     * @return string
     * @throws NodeNotFoundException
     */
    public function getChildName()
    {
        return $this->graph->getNodeName($this->childId);
    }

    /**
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getChild()
    {
        return $this->graph->getNodeById($this->childId);
    }

}