<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 20:12
 */

namespace Roelhem\RbacGraph\Database\Traits\Edge;


use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;

trait EdgeContractImplementation
{

    use BelongsToDatabaseGraph;

    /**
     * Returns the node on the 'parent' side of the edge.
     *
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns the id of the node on the 'parent' side of the edge.
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Returns the name of the node on the 'parent' side of the edge.
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parent->name;
    }

    /**
     * Returns the the node on the 'child' side of the edge.
     *
     * @return Node
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Returns the id of the node on the 'child' side of the edge.
     *
     * @return integer
     */
    public function getChildId()
    {
        return $this->child_id;
    }

    /**
     * Returns the name of the node on the 'child' side of the edge.
     *
     * @return string
     */
    public function getChildName()
    {
        return $this->child->name;
    }

}