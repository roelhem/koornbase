<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 23:49
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasContainsNodeMethod
{

    /**
     * @return Graph
     */
    abstract public function getGraph();

    /**
     * Returns if the provided node (which is an element of the super-graph) should be an element of this sub-graph.
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    abstract public function containsNode( $node );

    /**
     * Returns all the nodes of this sub-graph.
     *
     * @return Collection|Node[]
     */
    public function getNodes()
    {
        return $this->getGraph()->getNodes()->filter(function(Node $node) {
            return $node;
        });
    }

    /**
     * Returns if the super-graph has a node with the specified $id and this node is an element of this sub-graph.
     *
     * @param integer $id
     * @return bool
     */
    public function hasNodeId($id)
    {
        return $this->getGraph()->hasNodeId($id) && $this->containsNode(intval($id));
    }

    /**
     * Returns if the super-graph has a node with the specified $name and this node is an element of this sub-graph.
     *
     * @param string $name
     * @return bool
     */
    public function hasNodeName($name)
    {
        return $this->getGraph()->hasNodeId($name) && $this->containsNode(strval($name));
    }

    /**
     * Returns if the super-graph has a node with the specified $name and if this node is an element of this sub-graph.
     *
     * @param Node|string|integer $node
     * @return bool
     */
    public function hasNode($node)
    {
        return $this->getGraph()->hasNode($node) && $this->containsNode($node);
    }

    /**
     * Returns the node with the provided ID if it exists in this sub-graph.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id)
    {
        $node = $this->getNodeById($id);
        if($this->containsNode($node)) {
            return $node;
        }
        throw new NodeNotFoundException("Can't find a node with the id $id. (The node does exist in the super-graph.)");
    }

    /**
     * Returns the node with the provided name if it exists in this sub-graph.
     *
     * @param string $name
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName($name)
    {
        $node = $this->getNodeByName($name);
        if($this->containsNode($node)) {
            return $node;
        }
        throw new NodeNotFoundException("Can't find a node with the name $name. (The node does exist in the super-graph.)");
    }

    /**
     * Returns the node instance of the node in the provided parameter if it exists in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        $node = $this->getNode($node);
        if($this->containsNode($node)) {
            return $node;
        }
        throw new NodeNotFoundException("Can't find the provided node. (The node does exist in the super-graph.)");
    }


}