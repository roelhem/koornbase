<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 05:17
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

trait HasNodesArray
{

    use HasRandomNodeAccess;

    /**
     * Stores the nodes.
     *
     * @var Node[]
     */
    protected $nodes = [];

    /**
     * Returns the number of nodes in this path.
     *
     * @return int
     */
    public function count()
    {
        return count($this->nodes);
    }

    /**
     * Returns the nodes in this path in the right order.
     *
     * (The keys of the path are the index values of the node in the path.)
     *
     * @return Node[]
     */
    public function getNodeList()
    {
        return $this->nodes;
    }

    /**
     * Returns a collection of all the nodes in this path.
     *
     * @return Collection|Node[]
     */
    public function getNodes()
    {
        return collect($this->getNodeList());
    }

    /**
     * Returns if the $node (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    public function containsNode($node)
    {
        return $this->hasNode( $node );
    }

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  CHECKING FOR EXISTENCE  ------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns whether or not this graph has a node with the provided $id.
     *
     * @param integer $id
     * @return boolean
     */
    public function hasNodeId($id)
    {
        foreach ($this->nodes as $node) {
            if(intval($id) === $node->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns whether or not this graph has a node with the provided $name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNodeName($name)
    {
        foreach ($this->nodes as $node) {
            if(strval($name) === $node->getName()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns whether or not this graph has a node from the provided $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return boolean
     */
    public function hasNode($node)
    {
        if(is_integer($node)) {
            return $this->hasNodeId($node);
        } elseif(is_string($node)) {
            return $this->hasNodeName($node);
        } else {
            $nodeId = $this->getNodeId($node);
            return $this->hasNodeId($nodeId);
        }
    }

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  GETTING NODE INSTANCES  ------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns the node with the provided id.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id)
    {
        foreach ($this->nodes as $node) {
            if(intval($id) === $node->getId()) {
                return $node;
            }
        }
        throw new NodeNotFoundException("Can't find a node with the id $id in this path.");
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
        foreach ($this->nodes as $node) {
            if(strval($name) === $node->getName()) {
                return $node;
            }
        }
        throw new NodeNotFoundException("Can't find a node with the name $name in this path.");
    }

    /**
     * Returns the node based on the given $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        if(is_integer($node)) {
            return $this->getNodeById($node);
        } elseif(is_string($node)) {
            return $this->getNodeByName($node);
        } else {
            $nodeId = $this->getNodeId($node);
            return $this->getNodeById($nodeId);
        }
    }

    /**
     * Returns the node at the given index
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     */
    public function getNodeAt($index)
    {
        if(array_key_exists(intval($index), $this->nodes)) {
            return $this->nodes[intval($index)];
        }
        throw new PathIndexException("There is no node at index $index in this path.");
    }

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  GETTING NODE INDEXES  --------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns the index of the node with the provided id in this path.
     *
     * @param  integer $id
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexById($id)
    {
        foreach ($this->nodes as $index => $node) {
            if(intval($id) === $node->getId()) {
                return $index;
            }
        }
        throw new NodeNotFoundException("Can't find a node with the id $id in this path.");
    }

    /**
     * Returns the index of the node with the provided name in this path.
     *
     * @param  string $name
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexByName($name)
    {
        foreach ($this->nodes as $index => $node) {
            if(strval($name) === $node->getName()) {
                return $index;
            }
        }
        throw new NodeNotFoundException("Can't find a node with the name $name in this path.");
    }

    /**
     * Returns the index of the node in this path.
     *
     * @param  Node|string|integer $node
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndex($node)
    {
        if(is_integer($node)) {
            return $this->getNodeIndexById($node);
        } elseif(is_string($node)) {
            return $this->getNodeIndexByName($node);
        } else {
            $nodeId = $this->getNodeId($node);
            return $this->getNodeIndexById($nodeId);
        }
    }

}