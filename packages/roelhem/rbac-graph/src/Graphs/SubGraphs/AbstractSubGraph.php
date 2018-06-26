<?php

namespace Roelhem\RbacGraph\Graphs\SubGraphs;


use Roelhem\RbacGraph\Contracts\Graphs\SubGraph;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasSuperGraph;


abstract class AbstractSubGraph implements SubGraph
{

    use GraphDefaultContains;
    use HasSuperGraph;
    use HasNoAssignments;



}