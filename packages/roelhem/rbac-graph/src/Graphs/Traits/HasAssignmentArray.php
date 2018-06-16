<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 21:00
 */

namespace Roelhem\RbacGraph\Contracts\Traits;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Assignable;
use Roelhem\RbacGraph\Contracts\Assignment;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\AssignmentNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


/**
 * Trait HasAssignmentArray
 *
 * Implements the assignments in a Graph by storing an array of assignments.
 *
 * @package Roelhem\RbacGraph\Contracts\Traits
 */
trait HasAssignmentArray
{

    /**
     * @var Assignment[]
     */
    protected $assignments;

    /**
     * Returns all the assignments of this graph.
     *
     * @return Collection|Assignment[]
     */
    public function getAssignments() {
        return collect($this->assignments)->flatten()->filter(function($assignment) {
            return $assignment instanceof Assignment;
        })->values();
    }

    /**
     * @param Node|string|integer $node
     * @param Assignable $assignable
     * @return boolean
     */
    public function hasAssignment($assignable, $node) {
        try {
            $res = $this->getAssignment($assignable, $node);
            if($res instanceof Assignment) {
                return true;
            } else {
                return false;
            }
        } catch (AssignmentNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param Assignable $assignable
     * @param Node|string|integer $node
     * @return Assignment
     * @throws AssignmentNotFoundException
     */
    public function getAssignment($assignable, $node) {
        $nodeId = $this->getNodeId($node);
        if(!($assignable instanceof Assignable)) {
            throw new \InvalidArgumentException('The $assignable argument must be an instance of Assignable.');
        }
        $res = $this->getAssignments()->first(function(Assignment $assignment) use ($assignable, $nodeId) {
            if($assignment->getNode()->getId() !== $nodeId) {
                return false;
            }

            $otherAssignable = $assignment->getAssignable();
            if($otherAssignable->getType() !== $assignable->getType()) {
                return false;
            }
            if($otherAssignable->getId() !== $assignable->getId()) {
                return false;
            }
            return true;

        });

        if($res instanceof Assignment) {
            return $res;
        } else {
            throw new AssignmentNotFoundException("The assignment you tried to get doesn't exist in this graph.");
        }
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Assignment[]
     */
    public function getNodeAssignments( $node ) {
        $nodeId = $this->getNodeId($node);
        return $this->getAssignments()->filter(function(Assignment $assignment) use ($nodeId) {
            return $assignment->getNode()->getId() === $nodeId;
        });
    }

    /**
     * @param Assignable $assignable
     * @return Collection|Assignment[]
     */
    public function getAssignableAssignments( $assignable ) {
        if(!($assignable instanceof Assignable)) {
            throw new \InvalidArgumentException('The $assignable argument must be an instance of Assignable.');
        }
        return $this->getAssignments()->filter(function(Assignment $assignment) use ($assignable) {
            $otherAssignable = $assignment->getAssignable();
            if($otherAssignable->getType() !== $assignable->getType()) {
                return false;
            }
            if($otherAssignable->getId() !== $assignable->getId()) {
                return false;
            }
            return true;
        });
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Assignable[]
     */
    public function getNodeAssignables( $node ) {
        return $this->getNodeAssignments( $node )->map(function(Assignment $assignment) {
            return $assignment->getAssignable();
        });
    }

    /**
     * @param Assignable $assignable
     * @return Collection|Node[]
     */
    public function getAssignedNodes( $assignable ) {
        return $this->getAssignableAssignments( $assignable )->map(function(Assignment $assignment) {
            return $assignment->getNode();
        });
    }

}