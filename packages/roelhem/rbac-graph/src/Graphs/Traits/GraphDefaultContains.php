<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 00:26
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;

trait GraphDefaultContains
{

    /**
     * Returns if this graph is able to contain the provided $other object.
     *
     * @param mixed $other
     * @return bool
     */
    public function contains( $other ) {

        if($this === $other) {
            return true;
        }

        if($other instanceof BelongsToGraph) {
            return $this->contains($other->getGraph());
        }

        return false;
    }

}