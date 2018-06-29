<?php

namespace Roelhem\RbacGraph\Enums;


use Illuminate\Contracts\Support\Arrayable;
use MabeEnum\EnumSet;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use MabeEnum\Enum;
use Roelhem\RbacGraph\Enums\Traits\HasConsoleFormatStyle;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

/**
 * Class NodeType
 * @package Roelhem\RbacGraph\Enums
 *
 * @method static NodeType DEFAULT_NODE()
 * @method static NodeType ROLE()
 * @method static NodeType SUPER_ROLE()
 * @method static NodeType ABSTRACT_ROLE()
 * @method static NodeType DYNAMIC_ROLE()
 * @method static NodeType TASK()
 * @method static NodeType PERMISSION()
 * @method static NodeType ROUTE_PERMISSION()
 * @method static NodeType ABILITY()
 * @method static NodeType MODEL_ABILITY()
 * @method static NodeType PERMISSION_SET()
 * @method static NodeType CRUD_ABILITY_SET()
 * @method static NodeType RULE()
 * @method static NodeType SCOPE_RULE()
 * @method static NodeType MODEL_RULE()
 */
final class NodeType extends Enum implements Arrayable
{

    use HasConsoleFormatStyle;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    // NODES
    public const DEFAULT_NODE = 0;


    // ROLES
    public const ROLE = 10;
    public const SUPER_ROLE = 11;
    public const ABSTRACT_ROLE = 12;
    public const DYNAMIC_ROLE = 13;


    public const TASK = 20;


    // PERMISSIONS
    public const PERMISSION = 30;
    public const ROUTE_PERMISSION = 31;


    public const ABILITY = 40;
    public const MODEL_ABILITY = 41;


    public const PERMISSION_SET = 50;
    public const CRUD_ABILITY_SET = 51;

    // GATES
    public const GATE = 100;


    // ---------------------------------------------------------------------------------------------------------- //
    // -------- LOAD THE CONFIG FILE ---------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @var array|null
     */
    protected static $nodeTypesConfig = null;

    /**
     * @return array
     */
    protected static function getConfigs() {
        if(self::$nodeTypesConfig === null) {
            self::$nodeTypesConfig = Yaml::parseFile(__DIR__.'/node-types.yaml', Yaml::PARSE_CUSTOM_TAGS);
        }
        return self::$nodeTypesConfig;
    }

    /**
     * Returns a NodeType instance from the provided $nodeType parameter.
     *
     * @param NodeType|integer|string|Node $value
     * @return NodeType
     */
    public static function by($value) {
        if(is_string($value)) {
            return self::byName($value);
        } elseif($value instanceof Node) {
            return $value->getType();
        } else {
            return self::get($value);
        }
    }

    /**
     * @param NodeType|integer|string|Node $parent
     * @param NodeType|integer|string|Node $child
     * @return bool
     */
    public static function allowEdge( $parent, $child ) {
        $parent = self::by($parent);
        $child = self::by($child);

        $parentChildren = $parent->getEdgeRules('children');
        $childParents = $child->getEdgeRules('parents');

        if($parentChildren === false || $childParents === false) {
            return false;
        }

        if(is_array($parentChildren) && in_array($child->getName(), $parentChildren)) {
            return true;
        }
        if(is_array($childParents) && in_array($parent->getName(), $childParents)) {
            return true;
        }

        if($parentChildren instanceof NodeType) {
            return self::allowEdge($parentChildren, $child);
        }
        if($childParents instanceof NodeType) {
            return self::allowEdge($parent, $childParents);
        }

        return false;
    }

    protected function getEdgeRules($key = null) {
        $edge = $this->conf('edges');

        if($key === null) {
            return [
                'children' => $this->getEdgeRules('children'),
                'parents'  => $this->getEdgeRules('parents')
            ];
        }

        if(is_array($edge)) {
            $res = array_get($edge, $key);
        } else {
            $res = $edge;
        }

        if(is_string($res)) {
            return [$res];
        } elseif($res === null) {
            return [];
        } elseif($res instanceof TaggedValue && $res->getTag() === 'like') {
            return self::by($res->getValue());
        } else {
            return $res;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  EXTRA METHODS  --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    public function conf($key = null, $default = null)
    {
        $res = array_get(self::getConfigs(), $this->getName(), []);
        if($key === null) {
            return $res;
        } else {
            return array_get($res, $key, $default);
        }
    }

    public function getLabel() {
        $res = $this->conf('label');

        if($res === null) {
            $res = $this->getName();
            $res = str_replace('_',' ', $res);
            $res = mb_convert_case($res, MB_CASE_TITLE);
        }

        return $res;
    }

    /**
     * Returns the description of this NodeType
     *
     * @return string|null
     */
    public function getDescription() {
        return $this->conf('description', null);
    }

    /**
     * Returns if this type is allowed to be directly assigned to an Assignable object.
     *
     * @return bool
     */
    public function allowAssignment() {
        return $this->conf('assignable', false);
    }

    /**
     * Returns if the $other node-type is allowed to be a child of this node-type.
     *
     * @param NodeType|integer|string|Node $other
     * @return bool
     */
    public function allowChild( $other ) {
        return self::allowEdge($this, $other);
    }

    /**
     * Returns a set of the types that are allowed to be child-nodes of this node-type.
     *
     * @return EnumSet
     */
    public function getAllowedChildTypes()
    {
        $res = new EnumSet(self::class);
        foreach (self::getEnumerators() as $nodeType) {
            if($this->allowChild($nodeType)) {
                $res->attach($nodeType);
            }
        }
        return $res;
    }

    /**
     * Returns if the $other node-type is allowed to be a parent of this node-type.
     *
     * @param NodeType|integer|string|Node $other
     * @return bool
     */
    public function allowParent( $other ) {
        return self::allowEdge($other, $this);
    }

    /**
     * Returns a set of the types that are allowed to be parent-nodes of this node-type.
     *
     * @return EnumSet
     */
    public function getAllowedParentTypes()
    {
        $res = new EnumSet(self::class);
        foreach (self::getEnumerators() as $nodeType) {
            if($this->allowParent($nodeType)) {
                $res->attach($nodeType);
            }
        }
        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  IMPLEMENTS: Arrayable  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    public function toArray()
    {
        return [
            'value' => $this->getValue(),
            'name' => $this->getName()
        ];
    }

}