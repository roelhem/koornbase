<?php

namespace Roelhem\RbacGraph\Database;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Assignment as AssignmentContract;
use Roelhem\RbacGraph\Contracts\Authorizable;
use Roelhem\RbacGraph\Contracts\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphAssignmentsImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\MutableGraphContractImplementation;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;


/**
 * Class DatabaseGraph
 *
 * The graph object that handles the graph in the database.
 *
 * @package Roelhem\RbacGraph\Database
 */
class DatabaseGraph implements MutableGraph, AuthorizableGraph
{

    use GraphContractImplementation;
    use MutableGraphContractImplementation;
    use GraphAssignmentsImplementation;
    use GraphDefaultEquals;

    /**
     * Returns the nodes that are authorized for the given authorizable object in the initial state.
     *
     * (These are the the nodes that are granted to the authorizable before walking trough the graph.)
     *
     * @param Authorizable $authorizable
     * @return Collection|Node[]
     */
    public function getEntryNodes($authorizable)
    {
        if($authorizable instanceof RbacDatabaseAssignable) {
            return $this->getAssignedNodes($authorizable);
        } else {
            return collect([]);
        }
    }

}