<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 04:51
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathEmptyException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

trait HasRandomNodeAccess
{

    use PathChildAndParentCollections;

    /**
     * Returns the total number of nodes in this path.
     *
     * @return integer
     */
    abstract public function count();

    /**
     * Returns the node at the given index
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     */
    abstract public function getNodeAt( $index );

    /**
     * Returns the index of the node in this path.
     *
     * @param  Node|string|integer $node
     * @return integer
     * @throws NodeNotFoundException
     */
    abstract public function getNodeIndex( $node );

    /**
     * Returns the first node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getFirstNode() {
        try {
            return $this->getNodeAt(0);
        } catch (PathIndexException $indexException) {
            throw new PathEmptyException("Can't find the first node of this path because the path is empty.", 0, $indexException);
        }
    }

    /**
     * Returns the last node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getLastNode() {
        try {
            return $this->getNodeAt($this->count() - 1);
        } catch (PathIndexException $indexException) {
            throw new PathEmptyException("Can't find the last node of this path because the path is empty.", 0, $indexException);
        }
    }

    /**
     * Returns the node that comes after the provided node.
     *
     * @param Node|string|integer $node
     * @return Node|null
     * @throws NodeNotFoundException
     */
    public function getNextNode( $node ) {
        $index = $this->getNodeIndex($node) + 1;

        try {
            return $this->getNodeAt($index);
        } catch (PathIndexException $indexException) {
            return null;
        }
    }

    /**
     * Returns the node that came before the provided node.
     *
     * @param Node|string|integer $node
     * @return Node|null
     * @throws NodeNotFoundException
     */
    public function getPrevNode( $node ) {
        $index = $this->getNodeIndex($node) - 1;

        try {
            return $this->getNodeAt($index);
        } catch (PathIndexException $indexException) {
            return null;
        }
    }

}