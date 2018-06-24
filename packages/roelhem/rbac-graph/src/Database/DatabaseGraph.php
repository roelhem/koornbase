<?php

namespace Roelhem\RbacGraph\Database;



use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Assignable;
use Roelhem\RbacGraph\Contracts\Assignment as AssignmentContract;
use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\MutableGraphContractImplementation;
use Roelhem\RbacGraph\Exceptions\AssignmentNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;


/**
 * Class DatabaseGraph
 *
 * The graph object that handles the graph in the database.
 *
 * @package Roelhem\RbacGraph\Database
 */
class DatabaseGraph implements MutableGraph
{

    use GraphContractImplementation;
    use MutableGraphContractImplementation;
    use GraphDefaultEquals;


    /**
     * Returns a collection of all the assignments in this graph.
     *
     * @return Collection|Assignment[]
     */
    public function getAssignments()
    {
        return Assignment::query()->get();
    }

    /**
     * Returns whether or not this graph has a assignment from the $node to the $assignable.
     *
     * @param Assignable $assignable
     * @param NodeContract|string|integer $node An instance, name or id of the node.
     * @return bool
     */
    public function hasAssignment($assignable, $node)
    {
        return Assignment::query()->assignment($assignable, $node)->exists();
    }

    /**
     * Returns the assignment of the $node to the $assignable object.
     *
     * @param Assignable $assignable
     * @param NodeContract|string|integer $node An instance, name or id of the node.
     * @return Assignment
     * @throws AssignmentNotFoundException
     */
    public function getAssignment($assignable, $node)
    {
        $res = Assignment::query()->assignment($assignable, $node)->first();
        if($res instanceof Assignment) {
            return $res;
        } else {
            throw new AssignmentNotFoundException("There exists no assignment in this graph between the provided assignable and node.");
        }
    }

    /**
     * Returns a collection of all the assignments of a specific $node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the node.
     * @return Collection|Assignment[]
     * @throws NodeNotFoundException
     */
    public function getNodeAssignments($node)
    {
        return $this->getNode($node)->assignments;
    }

    /**
     * Returns a collection of all the assignments of one sepecific $assignable object.
     *
     * @param Assignable $assignable
     * @return Collection|Assignment[]
     * @throws WrongGraphException
     */
    public function getAssignableAssignments($assignable)
    {
        if(!($assignable instanceof RbacDatabaseAssignable)) {
            throw new WrongGraphException('Only models with the RbacDatabaseAssignable contract can use DatabaseGraph.');
        }
        return $assignable->assignments;
    }

    /**
     * Returns a collection of all the assignable objects that are assigned to this node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the node.
     * @return Collection|RbacDatabaseAssignable[]
     * @throws NodeNotFoundException
     */
    public function getNodeAssignables($node)
    {
        return $this->getNode($node)->assignments->pluck('assignable');
    }

    /**
     * Returns a collection of all the nodes that were assigned to the $assignable object.
     *
     * @param Assignable $assignable
     * @return Collection|Node[]
     * @throws WrongGraphException
     */
    public function getAssignedNodes($assignable)
    {
        if(!($assignable instanceof RbacDatabaseAssignable)) {
            throw new WrongGraphException('Only models with the RbacDatabaseAssignable contract can use DatabaseGraph.');
        }
        return $assignable->assignedNodes;
    }
}