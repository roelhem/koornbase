<?php

namespace Roelhem\RbacGraph\Database;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Roelhem\RbacGraph\Contracts\Nodes\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\Nodes\MutableNode;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Illuminate\Database\Eloquent\Model;
use Roelhem\RbacGraph\Database\Traits\Node\NodeContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Node\NodeRelations;
use Roelhem\RbacGraph\Enums\NodeType;

/**
 * Class Node
 * @package Roelhem\RbacGraph\Database
 *
 * @property integer $id
 * @property string $name
 * @property NodeType $type
 * @property string|null $title
 * @property string|null $description
 *
 * @property array $options
 *
 * @method static Builder type(NodeType|integer|string $type)
 */
class Node extends Model implements AdjacencyNode, MutableNode
{

    use NodeRelations, NodeContractImplementation;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rbac_nodes';

    protected $fillable = ['name','type','title','description', 'options'];

    protected $dates = ['created_at','updated_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS and MUTATORS ---------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Casts the type attribute to a NodeType instance.
     *
     * @param integer $value
     * @return NodeType
     */
    public function getTypeAttribute($value) {
        return NodeType::get($value);
    }

    /**
     * Formats the input value of the type attribute.
     *
     * @param NodeType|integer $newValue
     */
    public function setTypeAttribute($newValue) {
        $type = NodeType::get($newValue);
        $this->attributes['type'] = $type->getValue();
    }

    /**
     * @param string $value
     * @return array|\stdClass
     */
    public function getOptionsAttribute($value) {
        if($value === null) {
            return [];
        } else {
            return json_decode($value, true);
        }
    }

    /**
     * @param $newValue
     * @throws \ErrorException
     */
    public function setOptionsAttribute($newValue) {

        if(is_scalar($newValue)) {
            throw new \ErrorException('Options cannot be a scalar.');
        }

        if($newValue === null) {
            $this->attributes['options'] = null;
            return;
        }

        if($newValue instanceof Jsonable) {
            $this->attributes['options'] = $newValue->toJson();
            return;
        }

        if($newValue instanceof Arrayable) {
            $newValue = $newValue->toArray();
        }

        if(is_array($newValue) || $newValue instanceof \ArrayAccess) {
            if(count($newValue) === 0) {
                $this->attributes['options'] = null;
            } else {
                $this->attributes['options'] = json_encode($newValue);
            }
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- NODE OPTIONS IMPLEMENTATION ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param array|int|string $key
     * @param null $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value)
    {
        $options = $this->options;
        array_set($options, $key, $value);
        $this->options = $options;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * This scope automatically parses a Node parameter and only gives the models that refer to the same Node.
     *
     * @param Builder $query
     * @param NodeContract|string|integer $node   an instance, name or id of the searched node.
     * @return Builder
     */
    public function scopeNode(Builder $query, $node)
    {
        if(is_string($node)) {
            return $query->where('name','=',$node);
        }
        if($node instanceof NodeContract) {
            $node = $node->getId();
        }
        if(is_integer($node)) {
            return $query->where('id','=',$node);
        }
        return $query->whereRaw('FALSE');
    }

    /**
     * This scope only gives the nodes of one specific NodeType.
     *
     * @param Builder $query
     * @param NodeType|string|integer $type
     * @return Builder
     */
    public function scopeType(Builder $query, $type)
    {
        if(!is_integer($type)) {
            $type = NodeType::by($type)->getValue();
        }
        return $query->where('type','=',$type);
    }


    public function __toString()
    {
        return $this->type->getName()."({$this->id}:'{$this->name}')";
    }


}