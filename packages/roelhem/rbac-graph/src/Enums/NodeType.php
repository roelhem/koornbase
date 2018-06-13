<?php

namespace Roelhem\RbacGraph\Enums;


use MabeEnum\EnumSet;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeTypeNotFoundException;
use MabeEnum\Enum;

/**
 * Class NodeType
 * @package Roelhem\RbacGraph\Enums
 *
 * @method static NodeType ROLE();
 * @method static NodeType PERMISSION();
 */
final class NodeType extends Enum
{
    const ROLE = 0;
    const PERMISSION = 1;


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  EXTRA METHODS  --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns a set of the types that are allowed to be child-nodes of this node-type.
     *
     * @return EnumSet
     */
    public function getAllowedChildTypes() {
        $enumSet = new EnumSet(self::class);

        // ROLE allowed as a child-node.
        if ($this->is(self::ROLE)) {
            $enumSet->attach(self::ROLE);
        }

        // PERMISSION allowed as a child-node.
        if($this->is(self::ROLE) || $this->is(self::PERMISSION)) {
            $enumSet->attach(self::PERMISSION);
        }

        return $enumSet;
    }

    /**
     * Returns if the $otherType is allowed to be a child of this node-type.
     *
     * @param NodeType|integer $otherType
     * @return bool
     */
    public function allowChildType( $otherType ) {
        return $this->getAllowedChildTypes()->contains($otherType);
    }

    /**
     * Returns if the $otherType is allowed to be a parent of this node-type.
     *
     * @param NodeType|integer $otherType
     * @return bool
     */
    public function allowParentType( $otherType ) {
        return self::get($otherType)->allowChildType($this);
    }

    /**
     * Returns if the provided $node is allowed to be the child of a node of this node-type.
     *
     * @param Node $node
     * @return bool
     */
    public function allowChildNode( Node $node ) {
        return $this->allowChildType($node->getType());
    }

    /**
     * Returns if the provided $node is allowed to be the parent of a node of this node-type.
     *
     * @param Node $node
     * @return bool
     */
    public function allowParentNode( Node $node ) {
        return $this->allowParentType($node->getType());
    }

}