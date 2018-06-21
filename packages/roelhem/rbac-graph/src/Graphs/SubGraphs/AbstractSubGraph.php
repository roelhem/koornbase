<?php

namespace Roelhem\RbacGraph\Graphs\SubGraphs;


use Roelhem\RbacGraph\Contracts\SubGraph;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasSuperGraph;


abstract class AbstractSubGraph implements SubGraph
{

    use GraphDefaultContains;
    use HasSuperGraph;
    use HasNoAssignments;



}