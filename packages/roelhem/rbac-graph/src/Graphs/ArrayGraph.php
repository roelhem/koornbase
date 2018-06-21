<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 02:44
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Contracts\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Contracts\Traits\HasEdgeArray;
use Roelhem\RbacGraph\Contracts\Traits\HasNodeArray;

class ArrayGraph implements Graph
{

    use GraphDefaultContains;
    use GraphDefaultEquals;
    use HasNodeArray;
    use HasEdgeArray;
    use HasAssignmentArray;

}