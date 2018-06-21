<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 04:59
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Contracts\Traits\HasAdjacencyNodes;
use Roelhem\RbacGraph\Contracts\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Contracts\Traits\HasNodeDictionaries;

class AdjacencyGraph implements Graph
{

    use GraphDefaultContains;
    use HasNodeDictionaries;
    use HasAdjacencyNodes;
    use HasAssignmentArray;

}