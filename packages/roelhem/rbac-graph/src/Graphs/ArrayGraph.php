<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 02:44
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Graphs\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Graphs\Traits\HasEdgeArray;
use Roelhem\RbacGraph\Graphs\Traits\HasNodeArray;

class ArrayGraph implements Graph
{

    use GraphDefaultContains;
    use GraphDefaultEquals;
    use HasNodeArray;
    use HasEdgeArray;
    use HasAssignmentArray;

}