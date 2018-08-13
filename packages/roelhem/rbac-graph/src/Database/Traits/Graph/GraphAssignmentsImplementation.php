<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 06:39
 */

namespace Roelhem\RbacGraph\Database\Traits\Graph;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Models\Assignable;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Assignment;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Exceptions\AssignmentNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait GraphAssignmentsImplementation
{

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
     */
    public function getAssignableAssignments($assignable)
    {
        if(!($assignable instanceof RbacDatabaseAssignable)) {
            throw new \InvalidArgumentException('Only models with the RbacDatabaseAssignable contract can use DatabaseGraph.');
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
     */
    public function getAssignedNodes($assignable)
    {
        if(!($assignable instanceof RbacDatabaseAssignable)) {
            throw new \InvalidArgumentException('Only models with the RbacDatabaseAssignable contract can use DatabaseGraph.');
        }
        return $assignable->assignedNodes;
    }
}