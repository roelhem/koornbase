<?php

namespace Roelhem\RbacGraph\Database\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Roelhem\RbacGraph\Database\Assignment;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Exceptions\AssignmentNotAllowedException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


/**
 * Trait HasMorphedRbacAssignments
 *
 * Implements the RbacDatabaseAssignable contract by defining the morphed relation.
 *
 * @package Roelhem\RbacGraph\Database\Traits
 *
 * @property-read Collection|Assignment[] $assignments
 * @property-read Collection|Node[] $assignedNodes
 */
trait HasMorphedRbacAssignments
{

    /**
     * Returns the DatabaseGraph instance.
     *
     * @return DatabaseGraph
     */
    public function getGraph() {
        return resolve(DatabaseGraph::class);
    }

    /**
     * @return string
     */
    public function getType() {
        return get_class($this);
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param NodeContract|string|integer $node
     * @throws NodeNotFoundException
     * @throws AssignmentNotAllowedException
     */
    public function assignNode( $node ) {
        $node = $this->getGraph()->getNode($node);

        if(!$node->getType()->allowAssignment()) {
            throw new AssignmentNotAllowedException("You can't assign a Node of the type {$node->getType()}.");
        }

        $this->assignedNodes()->attach($node->getId());
    }

    /**
     * A relation to all the assignments of this model.
     *
     * @return MorphMany
     */
    public function assignments() {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    /**
     * A relation to all the assigned nodes of this model.
     *
     * @return MorphToMany
     */
    public function assignedNodes() {
        return $this->morphToMany(Node::class, 'assignable','rbac_assignments')
            ->as('assignment')->using(Assignment::class);
    }

}