<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 21:06
 */

namespace Roelhem\GraphQL\Resolvers;


use GraphQL\Language\AST\FragmentDefinitionNode;
use GraphQL\Language\AST\FragmentSpread;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\Helpers\FieldNodeBFIterator;
use Roelhem\GraphQL\Resolvers\Helpers\FieldNodeDFIterator;
use Roelhem\GraphQL\Resolvers\Helpers\FieldNodeHelper;
use Roelhem\GraphQL\Resolvers\Helpers\FieldNodeIterator;

/**
 * Class ResolveStore
 * @package Roelhem\GraphQL\Resolvers
 * @mixin ResolveInfo
 * @property FieldDefinition $field
 */
class ResolveStore extends Fluent
{

    public $info;

    /**
     * ResolveStore constructor.
     * @param ResolveInfo $info
     */
    public function __construct(ResolveInfo $info)
    {
        // initiate the parent
        parent::__construct(get_object_vars($info));

        // store the info-object
        $this->info = $info;

        // Add some extra attributes that are very useful.
        try {
            $this->field = $this->parentType->getField($this->info->fieldName);
        } catch (\Exception $exception) {};

    }


    public function getFieldSelection($depth = 0) {
        return $this->info->getFieldSelection($depth);
    }

    /**
     * @param bool $depthFirst
     * @param null|integer $maxDepth
     * @return FieldNodeIterator|FieldNodeHelper[]
     */
    public function fieldNodeIterator($depthFirst = false, $maxDepth = null)
    {
        if($depthFirst) {
            return new FieldNodeDFIterator($this, $maxDepth);
        } else {
            return new FieldNodeBFIterator($this, $maxDepth);
        }
    }

    /**
     * Returns the fragment with the provided name.
     *
     * @param FragmentSpreadNode|string $name
     * @return FragmentDefinitionNode|null
     */
    public function getFragment($name) {
        if($name instanceof FragmentSpreadNode) {
            $name = $name->name->value;
        }
        return array_get($this->fragments, $name);
    }
}