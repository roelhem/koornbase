<?php

namespace Roelhem\RbacGraph\Database\Traits\Node;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this->belongsToMany(Node::class,'rbac_edges','child_id','parent_id')
            ->as('edge')->using(Edge::class);
    }
}