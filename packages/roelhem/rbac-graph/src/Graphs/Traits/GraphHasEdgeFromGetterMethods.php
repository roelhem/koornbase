<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 00:31
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;

trait GraphHasEdgeFromGetterMethods
{

    /**
     * @inheritdoc
     */
    public function hasEdge($parent, $child)
    {
        try {
            $edge = $this->getEdge($parent, $child);
            if($edge instanceof Edge) {
                return true;
            } else {
                return false;
            }
        } catch (EdgeNotFoundException $exception) {
            return false;
        }
    }

}