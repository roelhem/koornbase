<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 15:52
 */

namespace Roelhem\RbacGraph\Database\Traits\Graph;

use Roelhem\RbacGraph\Services\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Services\Builder;
use Roelhem\RbacGraph\Contracts\Edges\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\Graphs\MutableGraph;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotUniqueException;

trait MutableGraphContractImplementation
{
    /**
     * Returns a builder instance to build this graph.
     *
     * @return Builder
     */
    public function builder()
    {
        if($this instanceof MutableGraph) {
            return new RbacBuilder($this);
        }
        throw new \LogicException("This graph isn't an MutableGraph.");
    }

    /**
     * Creates a new node for this DatabaseGraph and adds the newly created node to the graph.
     *
     * @param integer|NodeType $type
     * @param string $name
     * @param array $options
     * @return Node
     * @throws NodeNameNotUniqueException
     */
    public function createNode($type, $name, $options = []) {
        $name = strval($name);

        if($this->hasNodeName($name)) {
            throw new NodeNameNotUniqueException("There already exists a node in the DatabaseGraph with the name $name.");
        }

        return Node::create([
            'name' => $name,
            'type' => NodeType::by($type),
            'options' => $options
        ]);
    }

    /**
     * Adds the provided node to this Graph.
     *
     * @param NodeContract $node
     * @return Node
     * @throws NodeNotUniqueException
     */
    public function addNode(NodeContract $node)
    {
        if($this->hasNodeName($node->getName())) {
            throw new NodeNameNotUniqueException("There already exists a node with the name '{$node->getName()}' in this graph.");
        }

        return Node::create([
            'name' => $node->getName(),
            'type' => $node->getType(),
            'title' => $node->getTitle(),
            'description' => $node->getDescription(),
            'options' => $node->getOptions(),
        ]);
    }

    /**
     * Creates a new edge for this MutableGraph and adds the newly created edge to the graph.
     *
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotUniqueException
     * @throws EdgeNotAllowedException
     */
    public function createEdge($parent, $child)
    {
        $parent = $this->getNode($parent);
        $child = $this->getNode($child);

        if(!($parent instanceof Node) || !($child instanceof Node)) {
            throw new \LogicException("Parent and child weren't able to become Node instances.");
        }

        if($this->hasEdge($parent, $child)) {
            throw new EdgeNotUniqueException("There already exists an Edge from node '{$parent->getName()}' to node '{$child->getName()}'.");
        }

        if (!$parent->getType()->allowChild($child)) {
            throw new EdgeNotAllowedException("A node of type {$parent->type->getName()} can't have a child-nodes of type {$child->type->getName()}.");
        }

        return Edge::create([
            'parent_id' => $parent->getId(),
            'child_id' => $child->getId()
        ]);
    }

    /**
     * Adds the provided edge to this Graph.
     *
     * @param EdgeContract $edge
     *
     * @throws NodeNotFoundException
     * @throws EdgeNotUniqueException
     * @throws EdgeNotAllowedException
     *
     * @return Edge
     */
    public function addEdge(EdgeContract $edge)
    {
        return $this->createEdge($edge->getParentName(), $edge->getChildName());
    }
}