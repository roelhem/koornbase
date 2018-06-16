<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 15:52
 */

namespace Roelhem\RbacGraph\Database\Traits\Graph;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotUniqueException;

trait MutableGraphContractImplementation
{
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
     * Adds multiple nodes to this Graph.
     *
     * @param iterable|Collection|NodeContract[] $nodes
     * @return Collection|Node[]
     * @throws NodeNotUniqueException
     */
    public function addNodes(iterable $nodes)
    {
        $nodes = collect($nodes)->flatten();

        $names = $nodes->map(function(NodeContract $node) {
            return $node->getName();
        })->values();

        if(Node::query()->whereIn('name', $names)->exists()) {
            $names_str = Node::query()->whereIn('name', $names)->pluck('name')->implode(', ');
            throw new NodeNameNotUniqueException("Some names already exist in this graph. Conflicting names: $names_str.");
        }

        return $nodes->map(function(NodeContract $node) {
            return $this->addNode($node);
        })->values();
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
        $parent_name = $edge->getParentName();
        $parent = $this->getNodeByName($parent_name);

        $child_name = $edge->getChildName();
        $child = $this->getNodeByName($child_name);

        if($this->hasEdge($parent, $child)) {
            throw new EdgeNotUniqueException("There already exists an Edge from node '$parent_name' to node '$child_name'.");
        }

        if(!$parent->type->allowChild($child)) {
            throw new EdgeNotAllowedException("The NodeType {$parent->type->getName()} is not allowed to have child-nodes of the type {$child->type->getName()}.");
        }

        return Edge::create([
            'parent_id' => $parent->id,
            'child_id' => $child->id
        ]);
    }

    /**
     * Adds multiple edges to this Graph.
     *
     * @param iterable|Collection $edges
     *
     * @throws NodeNotFoundException
     * @throws EdgeNotUniqueException
     * @throws EdgeNotAllowedException
     *
     * @return Collection
     */
    public function addEdges(iterable $edges)
    {
        $edges = collect($edges)->flatten();

        // Check names
        $names = $edges->flatMap(function(EdgeContract $edge) {
            return [$edge->getChildName(),$edge->getParentName()];
        });
        $missingNames = $names->filter(function($name) {
            return !$this->hasNodeName($name);
        });
        if($missingNames->count() > 0) {
            $missingNamesStr = $missingNames->implode(', ');
            throw new NodeNotFoundException("Some edges refer to node-names that don't exist in the graph yet. Missing names: $missingNamesStr.");
        }

        // Check uniqueness
        $existingEdges = $edges->filter(function(EdgeContract $edge) {
            return $this->hasEdge($edge->getParentName(), $edge->getChildName());
        });

        if($existingEdges->count() > 0) {
            $existingEdgesStr = $existingEdges->map(function(EdgeContract $edge) {
                return "[{$edge->getParentName()}]->[{$edge->getChildName()}]";
            })->implode(', ');
            throw new EdgeNotUniqueException("Some of the edges already exists in the graph. Conflicting edges: $existingEdgesStr.");
        }

        // Check node types
        $invalidTypes = $edges->filter(function(EdgeContract $edge) {
            $child  = $this->getNodeByName($edge->getChildName());
            $parent = $this->getNodeByName($edge->getParentName());
            return !$parent->type->allowChild($child);
        });
        if($invalidTypes->count() > 0) {
            $invalidTypesStr = $invalidTypes->map(function(EdgeContract $edge) {
                $child  = $this->getNodeByName($edge->getChildName());
                $parent = $this->getNodeByName($edge->getParentName());
                return "[{$parent->type->getName()}: {$parent->name}]->[{$child->type->getName()}: {$child->name}]";
            })->implode(', ');
            throw new EdgeNotAllowedException("There are some edges that don't respect the ordering rules of the node-types. Problems: $invalidTypesStr.");
        }

        // Create edges and the result
        return $edges->map(function(EdgeContract $edge) {
            return $this->addEdge($edge);
        });

    }
}