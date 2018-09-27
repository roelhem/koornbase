<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 21:04
 */

namespace Roelhem\GraphQL\Resolvers\Helpers;


use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\FragmentDefinitionNode;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\InlineFragmentNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\WrappingType;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class FieldNodeHelper
{
    /** @var ResolveStore */
    public $store;

    /** @var FieldNode */
    public $fieldNode;

    /** @var int */
    public $localDepth = 0;

    /** @var string[] */
    public $path = [];

    /** @var FieldDefinition */
    public $field;

    /**
     * FieldNodeHelper constructor.
     * @param ResolveStore $store
     * @param FieldNode $fieldNode
     * @param string[] $path
     * @param int $localDepth
     * @param FieldDefinition $field
     * @throws
     */
    public function __construct(ResolveStore $store, FieldNode $fieldNode, array $path = [], $localDepth = 0, $field = null)
    {
        $this->store = $store;
        $this->fieldNode = $fieldNode;
        $this->path = $path;
        $this->localDepth = $localDepth;

        if($field !== null) {
            $this->field = $field;
        } else {
            $this->field = $this->store->field;
        }
    }

    /** @return string */
    public function getName()
    {
        return $this->fieldNode->name->value;
    }

    public function getAlias()
    {
        $alias = $this->fieldNode->alias;
        if($alias === null) {
            return $this->getName();
        } else {
            return $alias->value;
        }
    }

    /** @return Type */
    public function getType()
    {
        return $this->field->getType();
    }

    /** @return Type */
    public function getUnwrappedType()
    {
        $type = $this->getType();
        if($type instanceof WrappingType) {
            return $type->getWrappedType(true);
        } else {
            return $type;
        }
    }

    /**
     * @return FieldNodeHelper[]
     */
    public function getChildren()
    {
        return $this->selectionSet($this->fieldNode->selectionSet);
    }

    /**
     * @param $selectionSetNode
     * @return FieldNodeHelper[]
     */
    protected function selectionSet($selectionSetNode)
    {
        $res = [];
        if($selectionSetNode instanceof SelectionSetNode) {
            foreach ($selectionSetNode->selections as $selectionNode) {
                // Handle the different nodes-types.
                if($selectionNode instanceof FieldNode) {
                    // When a field-node was found, add it to the list.
                    $res[] = $this->createChild($selectionNode);
                } elseif($selectionNode instanceof FragmentSpreadNode) {
                    // When a fragment-spread was found, handle the new selectionSetNode.
                    $fragment = $this->store->getFragment($selectionNode);
                    if($fragment instanceof FragmentDefinitionNode) {
                        $res = array_merge($res, $this->selectionSet($fragment->selectionSet));
                    }
                } elseif($selectionNode instanceof InlineFragmentNode) {
                    // When an inline fragment was found, handle the new selectionSetNode.
                    $res = array_merge($res, $this->selectionSet($selectionNode->selectionSet));
                }
            }
        }
        return $res;
    }

    /**
     * @param FieldNode $fieldNode
     * @return FieldNodeHelper
     * @throws
     */
    protected function createChild(FieldNode $fieldNode)
    {
        $name = $fieldNode->name->value;
        
        $path = $this->path;
        $path[] = $name;

        /** @var ObjectType $unwrappedType */
        $unwrappedType = $this->getUnwrappedType();

        return new FieldNodeHelper(
            $this->store,
            $fieldNode,
            $path,
            $this->localDepth + 1,
            $unwrappedType->getField($name)
        );
    }

}