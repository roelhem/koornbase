<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:47
 */

namespace Roelhem\RbacGraph\Contracts\Traits;

use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait GraphDefaultNodeGetters
{

    /**
     * Returns the node of this graph that is referenced by the $node parameter.
     *
     * If the $node parameter is a string, the getNodeByName method is used to find the node.
     * If the $node parameter is an integer, the getNodeById method is used to find the node.
     *
     * If $node is an instance of Node, it is checked if the $node is a part of this graph. If this is the case,
     * the parameter is returned. Otherwise, the getNodeFromExternal method is called.
     *
     * @param Node|string|integer $node       An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        if (is_string($node)) {
            return $this->getNodeByName($node);
        }

        if (is_integer($node)) {
            return $this->getNodeById($node);
        }

        if ($node instanceof Node) {
            if($this->contains($node)) {
                return $node;
            } else {
                return $this->getNodeByName($node->getName());
            }
        }

        throw new NodeNotFoundException("Can't find a node form the provided parameter type.");
    }

    /**
     * @param Node|string|integer $node       An instance, name or id of the searched node.
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId($node)
    {
        if(is_integer($node)) {
            if($this->hasNodeId($node)) {
                return $node;
            } else {
                throw new NodeNotFoundException("Node with id '$node' does not exist in the graph.");
            }
        } else {
            return $this->getNode($node)->getId();
        }
    }

    /**
     * @param Node|string|integer $node       An instance, name or id of the searched node.
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName($node)
    {
        if(is_string($node)) {
            if($this->hasNodeName($node)) {
                return $node;
            } else {
                throw new NodeNotFoundException("Node with name '$node' does not exist in the graph.");
            }
        } else {
            return $this->getNode($node)->getName();
        }
    }

}