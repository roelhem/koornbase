<?php

namespace Roelhem\RbacGraph\Database\Traits\Graph;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait GraphContractImplementation
{

    /**
     * Returns if this graph is able to contain the provided $other object.
     *
     * @param mixed $other
     * @return boolean
     */
    public function contains( $other )
    {
        if($other instanceof DatabaseGraph) {
            return true;
        }

        if($other instanceof BelongsToGraph) {
            return $this->contains($other->getGraph());
        }

        return false;
    }

    /**
     * Returns a collection of all the nodes in this graph.
     *
     * @return Collection|Node[]
     */
    public function getNodes()
    {
        return Node::query()->get();
    }

    /**
     * Returns a collection of all the edges in this graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges()
    {
        return Edge::query()->get();
    }

    /**
     * Returns whether or not this graph has a node with the provided $id.
     *
     * @param integer $id
     * @return boolean
     */
    public function hasNodeId($id)
    {
        return Node::query()->where('id','=',$id)->exists();
    }

    /**
     * Returns whether or not this graph has a node with the provided $name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNodeName($name)
    {
        return Node::query()->where('name','=',$name)->exists();
    }

    /**
     * Returns whether or not this graph has a node from the provided $node parameter.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return boolean
     */
    public function hasNode($node)
    {
        return Node::query()->node($node)->exists();
    }

    /**
     * Returns the node with the provided id.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id)
    {
        $res = Node::query()->where('id', '=', $id)->first();
        if($res instanceof Node) {
            return $res;
        }
        throw new NodeNotFoundException("Can't find a node with id '$id'.");
    }

    /**
     * Returns the node with the provided name.
     *
     * @param string $name
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName($name)
    {
        $res = Node::query()->where('name','=', $name)->first();
        if($res instanceof Node) {
            return $res;
        }
        throw new NodeNotFoundException("Can't find a node with name '$name'.");
    }

    /**
     * Returns the node based on the given $node parameter.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        if($node instanceof Node) {
            return $node;
        }

        $res = Node::query()->node($node)->first();
        if($res instanceof Node) {
            return $res;
        }

        throw new NodeNotFoundException("Can't initiate a node based on the given parameter.");
    }

    /**
     * Returns the id of the node that was referenced by the $node parameter.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId($node)
    {
        if($node instanceof Node) {
            return $node->id;
        }

        if(is_integer($node)) {
            return $node;
        }

        return $this->getNode($node)->id;
    }

    /**
     * Returns the name of the node that was referenced by the $node parameter.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName($node)
    {
        if($node instanceof Node) {
            return $node->name;
        }

        if(is_string($node) && $this->hasNodeName($node)) {
            return $node;
        }

        return $this->getNode($node)->name;
    }

    /**
     * Returns whether or not this graph has a edge between the nodes $parent and $child.
     *
     * @param NodeContract|string|integer $parent An instance, name or id of the searched node.
     * @param NodeContract|string|integer $child An instance, name or id of the searched node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child)
    {
        return Edge::query()->edge($parent, $child)->exists();
    }

    /**
     * Returns the edge between the nodes $from and $to.
     *
     * @param NodeContract|string|integer $parent An instance, name or id of the searched node.
     * @param NodeContract|string|integer $child An instance, name or id of the searched node.
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        $res = Edge::query()->edge($parent, $child)->first();
        if($res instanceof Edge) {
            return $res;
        }
        throw new EdgeNotFoundException("Can't find a edge based on the provided parameters.");
    }

    /**
     * Returns all the outgoing edges of a specific node
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdges($node)
    {
        if(is_integer($node)) {
            return Edge::query()->where('parent_id','=',$node)->get();
        } else {
            return $this->getNode($node)->outgoingEdges;
        }
    }

    /**
     * Returns all the incoming edges of a specific node
     *
     * @param NodeContract|string|integer $node An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getIncomingEdges($node)
    {
        if(is_integer($node)) {
            return Edge::query()->where('child_id','=',$node)->get();
        } else {
            return $this->getNode($node)->incomingEdges;
        }
    }

    /**
     * Returns all the children of a specific node
     *
     * @param $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getChildren($node)
    {
        return $this->getNode($node)->children;
    }

    /**
     * Returns all the parents of a specific node
     *
     * @param $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getParents($node)
    {
        return $this->getNode($node)->parents;
    }

}