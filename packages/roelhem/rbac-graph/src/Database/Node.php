<?php

namespace Roelhem\RbacGraph\Database;

use Illuminate\Database\Eloquent\Builder;
use Roelhem\RbacGraph\Contracts\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Illuminate\Database\Eloquent\Model;
use Roelhem\RbacGraph\Database\Traits\Node\NodeContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Node\NodeRelations;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotUniqueException;

/**
 * Class Node
 * @package Roelhem\RbacGraph\Database
 *
 * @property integer $id
 * @property string $name
 * @property NodeType $type
 * @property string|null $title
 * @property string|null $description
 */
class Node extends Model implements AdjacencyNode, MutableNode
{

    use NodeRelations, NodeContractImplementation;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rbac_nodes';

    protected $fillable = ['name','type','title','description'];

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
    protected function getTypeAttribute($value) {
        return NodeType::get($value);
    }

    /**
     * Formats the input value of the type attribute.
     *
     * @param NodeType|integer $newValue
     */
    protected function setTypeAttribute($newValue) {
        $type = NodeType::get($newValue);
        $this->attributes['type'] = $type->getValue();
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
    public function scopeNode(Builder $query, $node) {
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




}