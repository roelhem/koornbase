<?php

namespace Roelhem\RbacGraph\Nodes;

use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeTypeNotFoundException;
use Roelhem\RbacGraph\Nodes\Traits\HasNodeProperties;

class SimpleNode implements MutableNode
{

    use HasNodeProperties;

    /**
     * SimpleNode constructor.
     *
     * @param Graph $graph
     * @param integer|NodeType $type
     * @param null|string $name
     * @param null|int $id
     * @throws NodeTypeNotFoundException
     */
    public function __construct(Graph $graph, $type, ?string $name = null, ?int $id = null)
    {
        $this->graph = $graph;

        $this->type = NodeType::get($type);

        if($id === null) {
            $id = $this->graph->getNodes()->count();
            while ($this->graph->hasNodeId($id)) {
                $id++;
            }
        }
        $this->id = $id;

        if($name === null) {
            $name = mb_strtolower($this->type->getName()).'.id'.$this->id;
        }
        $this->name = $name;
    }

}