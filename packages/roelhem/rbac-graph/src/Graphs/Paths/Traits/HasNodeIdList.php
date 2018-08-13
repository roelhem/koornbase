<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 05:05
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

trait HasNodeIdList
{

    use HasRandomNodeAccess;

    /** @return integer[] */
    abstract protected function getNodeIdList();

    /** @return Graph */
    abstract public function getGraph();

    /**
     * Returns the nodes in this path in the right order.
     *
     * (The keys of the path are the index values of the node in the path.)
     *
     * @return Node[]
     */
    public function getNodeList()
    {
        return collect($this->getNodeIdList())->map(function($id) {
            return $this->getGraph()->getNodeById($id);
        })->all();
    }

    /** @return integer */
    public function count()
    {
        return count($this->getNodeIdList());
    }

    /**
     * Returns if the $node (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    public function containsNode($node)
    {
        try {
            $nodeId = $this->getGraph()->getNodeId($node);
            return in_array($nodeId, $this->getNodeIdList());
        } catch (NodeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * Returns the node based on the given $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        $res = $this->getGraph()->getNode($node);
        if(!$this->containsNode($res)) {
            throw new NodeNotFoundException("The node was not found in this Path.");
        }
        return $res;
    }

    /**
     * Returns if there exists a node wit
     *
     * @param integer $id
     * @return bool
     */
    public function hasNodeId($id)
    {
        return in_array(intval($id), $this->getNodeIdList());
    }

    /**
     * Returns whether or not this graph has a node with the provided $name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNodeName($name)
    {
        try {
            $id = $this->getGraph()->getNodeId($name);
            return $this->hasNodeId($id);
        } catch (NodeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * Returns whether or not this graph has a node from the provided $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return boolean
     */
    public function hasNode($node)
    {
        return $this->getGraph()->hasNode($node) && $this->containsNode($node);
    }

    /**
     * Returns the node with the provided id.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id) {
        $res = $this->getGraph()->getNodeById($id);
        if(!$this->containsNode($id)) {
            throw new NodeNotFoundException("The node with id $id was not found in this Path.");
        }
        return $res;
    }

    /**
     * Returns the node with the provided name.
     *
     * @param string $name
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName($name) {
        $res = $this->getGraph()->getNodeByName($name);
        if(!$this->containsNode($res)) {
            throw new NodeNotFoundException("The node with name $name was not found in this Path.");
        }
        return $res;
    }

    /**
     * Returns the id of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId($node) {
        return $this->getGraph()->getNodeId($node);
    }

    /**
     * Returns the name of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName($node) {
        return $this->getGraph()->getNodeName($node);
    }

    /**
     * Returns if the two provided nodes are equal to each other.
     *
     * @param Node|string|integer $nodeA
     * @param Node|string|integer $nodeB
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function nodeEquals($nodeA, $nodeB)
    {
        return $this->getGraph()->nodeEquals($nodeA, $nodeB);
    }

    /**
     * Returns the node at the given index
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     * @throws NodeNotFoundException
     */
    public function getNodeAt($index)
    {
        $list = $this->getNodeIdList();
        if(isset($list[$index])) {
            return $this->getGraph()->getNodeById($list[$index]);
        } else {
            throw new PathIndexException("There is no node at the index $index.");
        }
    }

    /**
     * Returns the index of the node with the provided id in this path.
     *
     * @param  integer $id
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexById($id)
    {
        $list = $this->getNodeIdList();
        $res = array_search(intval($id), $list);
        if($res === false) {
            throw new NodeNotFoundException("Can't find the node with id $id in the id-list of this path.");
        } else {
            return $res;
        }
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
        return $this->getNodeIndexById($this->getNodeId($name));
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
        return $this->getNodeIndexById($this->getNodeId($node));
    }



}