<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 05:00
 */

namespace Roelhem\RbacGraph\Nodes;


use Roelhem\RbacGraph\Contracts\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Nodes\Traits\HasAdjacencyLists;
use Roelhem\RbacGraph\Nodes\Traits\HasNodeProperties;

class SimpleAdjacencyNode implements AdjacencyNode, MutableNode
{

    use HasNodeProperties;
    use HasAdjacencyLists;

}