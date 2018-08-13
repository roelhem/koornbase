<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 00:12
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;


use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

trait HasSuperGraph
{

    use HasGraphProperty;

    /**
     * Returns the id of the provided node.
     *
     * @param Node|string|integer $node
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId($node)
    {
        return $this->getGraph()->getNodeId($node);
    }

    /**
     * Returns the name of the provided node.
     *
     * @param Node|string|integer $node
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName($node)
    {
        return $this->getGraph()->getNodeName($node);
    }

}