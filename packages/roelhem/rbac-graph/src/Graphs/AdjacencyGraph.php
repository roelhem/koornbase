<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 04:59
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Graphs\Traits\HasAdjacencyNodes;
use Roelhem\RbacGraph\Graphs\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Graphs\Traits\HasNodeDictionaries;

class AdjacencyGraph implements Graph
{

    use GraphDefaultContains;
    use GraphDefaultEquals;
    use HasNodeDictionaries;
    use HasAdjacencyNodes;
    use HasAssignmentArray;

}