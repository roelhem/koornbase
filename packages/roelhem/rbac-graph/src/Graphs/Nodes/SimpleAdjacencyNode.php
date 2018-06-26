<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 05:00
 */

namespace Roelhem\RbacGraph\Graphs\Nodes;


use Roelhem\RbacGraph\Contracts\Nodes\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\Nodes\MutableNode;
use Roelhem\RbacGraph\Graphs\Nodes\Traits\HasAdjacencyLists;
use Roelhem\RbacGraph\Graphs\Nodes\Traits\HasNodeProperties;

class SimpleAdjacencyNode implements AdjacencyNode, MutableNode
{

    use HasNodeProperties;
    use HasAdjacencyLists;

}