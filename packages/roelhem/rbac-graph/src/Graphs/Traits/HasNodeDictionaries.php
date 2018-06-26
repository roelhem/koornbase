<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 00:03
 */

namespace Roelhem\RbacGraph\Graphs\Traits;


use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeIdNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

trait HasNodeDictionaries
{

    use HasNodeArray;

    protected $nodeNamesToIds = [];

    /**
     * @inheritdoc
     */
    protected function storeNode( Node $node ) {
        if($this->contains($node)) {

            $id = $node->getId();
            $name = $node->getName();

            if ($this->hasNodeId($id)) {
                throw new NodeIdNotUniqueException("There already exists a node with the id '$id' in the graph.");
            }
            if($this->hasNodeName($name)) {
                throw new NodeNameNotUniqueException("There already exists a node with the name '$name' in the graph.");
            }

            $this->nodes[$id] = $node;
            $this->nodeNamesToIds[$name] = $id;

        } else {
            throw new WrongGraphException;
        }
    }

    /**
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id)
    {
        $id = intval($id);
        if(array_key_exists($id, $this->nodes)) {
            $node = $this->nodes[$id];
            if ($node instanceof Node) {
                return $node;
            } else {
                throw new NodeNotFoundException("Can't find a node with id '$id'. There existed a key '$id' in the node-array, but it's value wasn't an instance of Node.");
            }
        } else {
            throw new NodeNotFoundException("Can't find a node with id '$id'.");
        }
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function hasNodeId($id)
    {
        return array_key_exists($id, $this->nodes) && ($this->nodes[$id] instanceof Node);
    }

    /**
     * Returns the input as an integer that could represent an id of a node.
     *
     * @param mixed $input
     * @return integer|null
     */
    private function parseNodeId( $input ) {
        if (is_integer($input)) {
            return $input;
        } elseif (is_string($input)) {
            return intval($input);
        } elseif ($input instanceof Node) {
            return $input->getId();
        } else {
            return null;
        }
    }

    /**
     * Returns the id of a node by using a node name.
     * @param string $name
     * @return integer
     * @throws NodeNotFoundException
     */
    protected function getNodeIdByName( $name )
    {
        $name = strval($name);
        if (array_key_exists($name, $this->nodeNamesToIds)) {
            $result = $this->parseNodeId($this->nodeNamesToIds[$name]);

            if($result !== null && is_integer($result)) {
                return $result;
            }

            throw new NodeNotFoundException("Can't find an id for the node with name '$name'. There existed a key '$name' in the nodeNames-array, but it's value was not an integer.");
        }
        throw new NodeNotFoundException("Can't find a node with name '$name'.");
    }

    /**
     * @inheritdoc
     */
    public function hasNodeName($name)
    {
        if(array_key_exists($name, $this->nodeNamesToIds) && $this->parseNodeId($name) !== null) {
            return $this->hasNodeId($this->getNodeIdByName($name));
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function getNodeByName($name)
    {
        return $this->getNodeById($this->getNodeIdByName($name));
    }

    /**
     * @inheritdoc
     */
    public function getNodeId($node)
    {
        if(is_integer($node)) {
            if($this->hasNodeId($node)) {
                return $node;
            } else {
                throw new NodeNotFoundException("Node with id '$node' does not exist in the graph.");
            }
        } elseif(is_string($node)) {
            return $this->getNodeIdByName($node);
        } else {
            return $this->getNode($node)->getId();
        }
    }


}