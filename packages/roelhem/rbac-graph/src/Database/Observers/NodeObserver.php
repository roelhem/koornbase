<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 24-06-18
 * Time: 21:17
 */

namespace Roelhem\RbacGraph\Database\Observers;


use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class NodeObserver
{


    public function created(Node $node) {
        try {
            Path::createSingleton($node);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            throw new \LogicException("The newly created node is not found by the graph!",0,$nodeNotFoundException);
        }
    }

}