<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 00:04
 */

namespace Roelhem\RbacGraph\Graphs\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasNodeArray
{
    use GraphDefaultNodeGetters;
    use GraphHasNodeFromGetterMethods;

    protected $nodes = [];

    /**
     * Returns all the nodes of this graph.
     *
     * @return Collection|Node[]
     */
    public function getNodes() {
        return collect($this->nodes)->flatten()->filter(function($item) {
            return $item instanceof Node;
        })->unique(function(Node $node) {
            return $node->getId();
        })->values();
    }

    /**
     * Returns the first node in the node list that has the same id as the provided `$id` parameter.
     *
     * @param integer $id      the id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById( $id ) {
        $id = intval($id);
        $node = collect($this->nodes)->flatten()->first(function($node) use ($id) {
            return ($node instanceof Node) && $node->getId() === $id;
        });

        if($node instanceof Node) {
            return $node;
        }

        throw new NodeNotFoundException("Can't find a node with the id '$id'.");
    }


    /**
     * Returns the first node in the node list that has the same name as the provided `$name` parameter.
     *
     * @param string $name      the name of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName( $name ) {
        $name = strval($name);
        $node = collect($this->nodes)->flatten()->first(function($node) use ($name) {
            return ($node instanceof Node) && $node->getName() === $name;
        });

        if($node instanceof Node) {
            return $node;
        }

        throw new NodeNotFoundException("Can't find a node with the id '$name'.");
    }

}