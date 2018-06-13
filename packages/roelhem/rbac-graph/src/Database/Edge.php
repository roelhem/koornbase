<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 21:24
 */

namespace Roelhem\RbacGraph\Database;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Roelhem\RbacGraph\Database\Traits\Edge\EdgeContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Edge\EdgeRelations;
use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

/**
 * Class Edge
 * @package Roelhem\RbacGraph\Database
 *
 * @property integer $parent_id
 * @property integer $child_id
 */
class Edge extends Pivot implements EdgeContract
{

    use EdgeRelations, EdgeContractImplementation;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rbac_edges';

    protected $dates = ['created_at','updated_at'];

    public $incrementing = false;

    protected $fillable = ['parent_id','child_id'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that handle the standard $parent and $child node parameters.
     *
     * @param Builder $query
     * @param NodeContract|string|integer $parent
     * @param NodeContract|string|integer $child
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeEdge(Builder $query, $parent, $child) {
        $graph = $this->getGraph();
        $parent_id = $graph->getNodeId($parent);
        $child_id = $graph->getNodeId($child);
        return $query->where([
            ['parent_id', '=',$parent_id],
            ['child_id','=',$child_id]
        ]);
    }

}