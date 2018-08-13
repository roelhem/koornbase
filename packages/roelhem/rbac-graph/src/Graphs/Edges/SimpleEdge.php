<?php

namespace Roelhem\RbacGraph\Graphs\Edges;



use Roelhem\RbacGraph\Contracts\Edges\Edge;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

class SimpleEdge implements Edge
{

    use HasGraphProperty;

    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var Node
     */
    protected $child;

    /**
     * SimpleEdge constructor.
     * @param Graph $graph
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @throws NodeNotFoundException
     * @throws EdgeNotAllowedException
     * @throws EdgeNotUniqueException
     */
    public function __construct(Graph $graph, $parent, $child)
    {
        if($graph->hasEdge($parent, $child)) {
            $parentName = $graph->getNodeName($parent);
            $childName = $graph->getNodeName($child);
            throw new EdgeNotUniqueException("There already exists an edge from $parentName to $childName in the graph.");
        }

        $this->initGraph($graph);
        $this->parent = $graph->getNode($parent);
        $this->child = $graph->getNode($child);

        if(!$this->parent->getType()->allowChild($this->child)) {
            $parentTypeName = $this->parent->getType()->getName();
            $childTypeName = $this->child->getType()->getName();
            throw new EdgeNotAllowedException("A node of type $parentTypeName is not allowed to have a child of type $childTypeName.");
        }
    }

    /**
     * @inheritdoc
     */
    public function getParentId()
    {
        return $this->getGraph()->getNodeId($this->parent);
    }

    /**
     * @inheritdoc
     */
    public function getParentName()
    {
        return $this->getGraph()->getNodeName($this->parent);
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function getChildId()
    {
        return $this->getGraph()->getNodeId($this->child);
    }

    /**
     * @inheritdoc
     */
    public function getChildName()
    {
        return $this->getGraph()->getNodeName($this->child);
    }

    /**
     * @inheritdoc
     */
    public function getChild()
    {
        return $this->child;
    }

}