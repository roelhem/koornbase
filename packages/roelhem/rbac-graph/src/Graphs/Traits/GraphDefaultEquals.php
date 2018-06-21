<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 15:10
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


use Roelhem\RbacGraph\Contracts\Node;

trait GraphDefaultEquals
{

    /**
     * Returns if the two provided nodes are equal to each other according to this graph.
     *
     * @param Node|string|integer $nodeA
     * @param Node|string|integer $nodeB
     * @return boolean
     */
    public function nodeEquals($nodeA, $nodeB) {
        if($nodeA instanceof Node) {

            if(is_string($nodeB)) {
                return $this->getNodeName($nodeA) === $nodeB;
            }

            $nodeAId = $this->getNodeId($nodeA);

            if(is_integer($nodeB)) {
                return $nodeAId === $nodeB;
            }

            if($nodeB instanceof Node) {
                return $nodeAId === $this->getNodeId($nodeB);
            }

            return false;

        } elseif($nodeB instanceof Node) {

            return $this->nodeEquals($nodeB, $nodeA);

        } elseif(is_string($nodeA)) {
            if(!is_string($nodeB)) {
                $nodeB = $this->getNodeName($nodeB);
            }
            return $nodeA === $nodeB;

        } elseif(is_integer($nodeA)) {
            if(!is_integer($nodeB)) {
                $nodeB = $this->getNodeId($nodeB);
            }
            return $nodeA === $nodeB;
        }
        return false;
    }

}