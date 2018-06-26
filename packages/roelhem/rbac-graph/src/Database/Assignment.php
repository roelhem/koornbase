<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 21:59
 */

namespace Roelhem\RbacGraph\Database;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Roelhem\RbacGraph\Contracts\Models\Assignable;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Contracts\Assignments\Assignment as AssignmentContract;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

/**
 * Class Assignment
 *
 * @package Roelhem\RbacGraph\Database
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $assignable_type
 * @property integer $assignable_id
 *
 * @property-read Node $node
 * @property-read RbacDatabaseAssignable $assignable
 */
class Assignment extends MorphPivot implements AssignmentContract
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rbac_assignments';

    protected $dates = ['created_at','updated_at'];

    protected $fillable = ['node_id','assignable_id','assignable_type'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPE ---------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that handles the standard $assignable and $node parameters commonly used in the RbacGraph package.
     *
     * @param Builder $query
     * @param Assignable $assignable
     * @param NodeContract|string|integer $node
     * @return Builder
     * @throws NodeNotFoundException
     * @throws WrongGraphException
     */
    public function scopeAssignment(Builder $query, $assignable, $node) {
        if(!($assignable instanceof Assignable)) {
            throw new \InvalidArgumentException();
        } else if(!($assignable instanceof RbacDatabaseAssignable)) {
            throw new WrongGraphException();
        }
        $assignableType = $assignable->getType();
        $assignableId = $assignable->getId();
        $nodeId = $this->getGraph()->getNodeId($node);

        return $query->where([
            ['assignable_id', '=', $assignableId],
            ['assignable_type', '=', $assignableType],
            ['node_id', '=', $nodeId]
        ]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Node of this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function node() {
        return $this->belongsTo(Node::class, 'node_id');
    }

    /**
     * Gives the Assignable model of this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function assignable() {
        return $this->morphTo('assignable');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- AssignmentContract IMPLEMENTATION ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return DatabaseGraph
     */
    public function getGraph()
    {
        return resolve(DatabaseGraph::class);
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return RbacDatabaseAssignable
     * @throws RbacGraphException
     */
    public function getAssignable()
    {
        $res = $this->assignable;
        if($res instanceof RbacDatabaseAssignable) {
            return $res;
        }
        throw new RbacGraphException("The assignment with id {$this->id} points to a model that does not implement the RbacDatabaseAssignable interface.");
    }
}