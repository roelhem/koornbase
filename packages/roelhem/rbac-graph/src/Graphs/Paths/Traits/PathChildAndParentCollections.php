<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 04:31
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait PathChildAndParentCollections
{

    /**
     * Returns the node that comes of the provided node.
     *
     * @param $node
     * @return mixed
     * @throws NodeNotFoundException
     */
    abstract public function getNextNode( $node );

    /**
     * Returns all the children of a specific node.
     *
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getChildren( $node ) {
        $nextNode = $this->getNextNode($node);
        if($nextNode === null) {
            return collect([]);
        } else {
            return collect([$nextNode]);
        }
    }

    /**
     * Returns the node that comes after the provided node.
     *
     * @param $node
     * @return mixed
     * @throws NodeNotFoundException
     */
    abstract public function getPrevNode( $node );

    /**
     * Returns all the parents of a specific node.
     *
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getParents( $node ) {
        $prevNode = $this->getPrevNode($node);
        if($prevNode === null) {
            return collect([]);
        } else {
            return collect([$prevNode]);
        }
    }


}