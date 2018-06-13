<?php

namespace Roelhem\RbacGraph\Database\Traits\Edge;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Roelhem\RbacGraph\Database\Node;

/**
 * Trait EdgeRelations
 * @package Roelhem\RbacGraph\Database\Traits\Edge
 *
 * @property-read Node $parent
 * @property-read Node $child
 */
trait EdgeRelations
{

    /**
     * Gives the `Node` on the parent side of this `Edge`.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id','id');
    }

    /**
     * Gives the `Node` on the child side of this `Edge`.
     *
     * @return BelongsTo
     */
    public function child()
    {
        return $this->belongsTo(Node::class, 'child_id','id');
    }

}