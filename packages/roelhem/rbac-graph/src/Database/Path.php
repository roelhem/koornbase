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
use Roelhem\RbacGraph\Contracts\Rules\GateRule;
use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Database\Traits\Path\PathContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Path\PathRelations;
use Roelhem\RbacGraph\Database\Traits\Path\PathScopes;
use Roelhem\RbacGraph\Database\Traits\Path\PathStaticCreators;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Rules\BaseRule;

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
 * @property-read integer $rules_count
 * @property-read array $rules
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

    protected $fillable = ['id','size','first_node_id','last_node_id', 'path','rules'];

    public $timestamps = false;

    /**
     * @var RuleSerializer
     */
    protected $ruleSerializer;

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        $this->ruleSerializer = resolve(RuleSerializer::class);
        parent::__construct($attributes);
    }

    /**
     * @inheritdoc
     */
    public function count($columns = null)
    {
        if($columns === null) {
            return $this->size;
        }
        return parent::count($columns);
    }

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
     * @throws NodeNotFoundException
     */
    public function setPathAttribute($newValue) {
        if(is_string($newValue)) {
            $this->attributes['path'] = $newValue;
        } else {
            $nodes = collect($newValue)->map(function($node) {
                return $this->getGraph()->getNode($node);
            });
            $this->attributes['path'] = $nodes->map(function(Node $node) {
                return $node->id;
            })->values()->toJson();



            $lastNode = $this->getGraph()->getNode($this->last_node_id);

            $rules = $nodes->filter(function(Node $node) use ($lastNode) {

                if($node->getType()->is(NodeType::GATE)) {
                    return true;
                }

                if($node->getType()->is(NodeType::MODEL_GATE)) {

                    if($this->getGraph()->nodeEquals($node, $lastNode)) {
                        return true;
                    }

                    $for = $node->getOption('for', []);
                    if($lastNode->getType()->is(NodeType::MODEL_ABILITY) || $lastNode->getType()->is(NodeType::CRUD_ABILITY_SET)) {
                        $modelClass = $lastNode->getOption('modelClass');
                        if(in_array($modelClass, $for)) {
                            return true;
                        }
                    }
                }

                return false;

            })->map(function(Node $node) {
                return $node->getOption('rule');
            });

            $this->attributes['rules_count'] = $rules->count();
            $this->attributes['rules'] = $rules->values()->toJson();
        }
    }

    public function getRulesAttribute($value) {
        $res = [];
        foreach(json_decode($value, true) as $rule) {
            $res[] = $this->ruleSerializer->rule($rule);
        };
        return $res;
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