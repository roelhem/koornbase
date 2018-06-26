<?php

namespace Roelhem\RbacGraph\Graphs\Nodes;

use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\MutableNode;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeIdNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Graphs\Nodes\Traits\HasNodeProperties;

class SimpleNode implements MutableNode
{

    use HasNodeProperties;

    /**
     * SimpleNode constructor.
     *
     * @param Graph $graph
     * @param int $id
     * @param string $name
     * @param NodeType $type
     * @param array $options
     * @throws NodeIdNotUniqueException
     * @throws NodeNameNotUniqueException
     */
    public function __construct(Graph $graph, NodeType $type, int $id, string $name, array $options = [])
    {
        $this->graph = $graph;
        $this->type = $type;

        if($this->graph->hasNodeId($id)) {
            throw new NodeIdNotUniqueException("There already exists a node in the graph with the id $id.");
        }
        $this->id = $id;

        if($this->graph->hasNodeName($name)) {
            throw new NodeNameNotUniqueException("There already exists a node in the graph with the name $name.");
        }
        $this->name = $name;

        $this->options = $options;
    }

}