<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 24-06-18
 * Time: 21:05
 */

namespace Roelhem\RbacGraph\Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Graphs\Path as PathContract;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Database\Traits\Path\PathContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Path\PathRelations;
use Roelhem\RbacGraph\Database\Traits\Path\PathScopes;
use Roelhem\RbacGraph\Database\Traits\Path\PathStaticCreators;

/**
 * Model Path
 *
 *
 * @package Roelhem\RbacGraph\Database
 *
 * @property integer $id
 * @property integer $size
 * @property integer $first_node_id
 * @property integer $last_node_id
 * @property integer $first_path_id
 * @property integer $last_path_id
 * @property array $path
 *
 * @method static Path findOrFail(integer $id)
 *
 * @property-read Collection $nodes
 */
class Path extends Pivot implements PathContract
{

    use BelongsToDatabaseGraph;
    use PathRelations;
    use PathStaticCreators;
    use PathScopes;
    use PathContractImplementation;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rbac_paths';

    protected $fillable = ['id','size','first_node_id','last_node_id', 'path'];

    public $timestamps = false;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS and MUTATORS ---------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param string $value
     * @return array
     */
    public function getPathAttribute($value) {
        return json_decode($value, true);
    }

    /**
     * @param string|array $newValue
     */
    public function setPathAttribute($newValue) {
        if(is_string($newValue)) {
            $this->attributes['path'] = $newValue;
        } else {
            $this->attributes['path'] = json_encode($newValue);
        }
    }

    /**
     * Returns a collection of all the nodes in this path in the right order.
     *
     * @return Collection|Node[]
     */
    public function getNodesAttribute() {
        return collect($this->getNodeList());
    }

    /**
     * Returns a collection of all the edges in this path in the right order.
     *
     * @return Collection|Edge[]
     */
    public function getEdgesAttribute() {
        return collect($this->getEdgeList());
    }

    /**
     * Returns the edge that belongs to this path. Returns null if no edge belongs to this edge.
     *
     * @return Edge|null
     */
    public function getEdgeAttribute() {
        if($this->size === 1) {
            $res = $this->edgeQuery()->firstOrFail();
            if($res instanceof Edge) {
                return $res;
            } else {
                throw new \LogicException("The result is not an edge.");
            }
        } else {
            return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERIES -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the edge that belongs to this path.
     *
     * @return Builder
     */
    public function edgeQuery() {
        return Edge::query()
            ->where('parent_id','=',$this->first_node_id)
            ->where('child_id','=',$this->last_node_id);
    }

    public function __toString()
    {
        return '[ '.
            $this->nodes->map(function($node, $key) {
                return $key.': '.$node;
            })->implode(' -> ').
            ' ]';
    }


}