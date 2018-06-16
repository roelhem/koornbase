<?php

namespace Roelhem\RbacGraph\Database\Traits\Node;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Roelhem\RbacGraph\Database\Assignment;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;

/**
 * Trait NodeRelations
 *
 * @package Roelhem\RbacGraph\Database\Traits\Node
 *
 * @property-read Collection|Edge[] $incomingEdges
 * @property-read Collection|Edge[] $outgoingEdges
 * @property-read Collection|Node[] $parents
 * @property-read Collection|Node[] $children
 * @property-read Collection|Assignment[] $assignments
 */
trait NodeRelations
{
    /**
     * Relation with all the incoming edges to this `Node` in the database.
     *
     * @return HasMany
     */
    public function incomingEdges()
    {
        return $this->hasMany(Edge::class,'child_id','id');
    }

    /**
     * Relation to all the parent-nodes of this node.
     *
     * @return BelongsToMany
     */
    public function parents()
    {
        return $this->belongsToMany(Node::class, 'rbac_edges','child_id','parent_id')
            ->as('edge')->using(Edge::class);
    }

    /**
     * Relation with all the outgoing edges from this `Node` in the database.
     *
     * @return HasMany
     */
    public function outgoingEdges()
    {
        return $this->hasMany(Edge::class, 'parent_id','id');
    }

    /**
     * Relation to all the child-nodes of this node.
     *
     * @return BelongsToMany
     */
    public function children()
    {
        return $this->belongsToMany(Node::class,'rbac_edges','parent_id','child_id')
            ->as('edge')->using(Edge::class);
    }

    /**
     * Relation to all the assignments of this node.
     *
     * @return HasMany
     */
    public function assignments() {
        return $this->hasMany(NodeRelations::class, 'node_id');
    }

    /**
     * @param string $className
     * @return MorphToMany
     */
    protected function assignableRelation($className) {
        return $this->morphedByMany($className, 'assignable','rbac_assignments','node_id')
            ->as('assignment')->using(Assignment::class);
    }
}