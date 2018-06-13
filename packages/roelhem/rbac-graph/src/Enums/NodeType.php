<?php

namespace Roelhem\RbacGraph\Enums;


use Roelhem\RbacGraph\Exceptions\NodeTypeNotFoundException;

final class NodeType
{
    const ROLE = 0;
    const PERMISSION = 4;

    /**
     * Returns the name of the node-type that can be used in node names.
     *
     * @param integer $value
     * @return string
     * @throws NodeTypeNotFoundException
     */
    public static function getName( $value )
    {
        switch ($value) {
            case self::ROLE:
                return 'role';
            case self::PERMISSION:
                return 'permission';
            default:
                throw new NodeTypeNotFoundException("Can't find a NodeType with value '$value''.");
        }
    }

    /**
     * @param integer $value
     * @return bool
     */
    public static function isValid( $value )
    {
        switch ($value) {
            case self::ROLE: case self::PERMISSION:
                return true;
            default:
                return false;
        }
    }

    /**
     * @param integer $value
     * @throws NodeTypeNotFoundException
     */
    public static function ensureValid( $value )
    {
        if(!self::isValid($value)) {
            throw new NodeTypeNotFoundException("Can't find a NodeType with value '$value''.");
        }
    }

    /**
     * Returns the value of the default NodeType.
     *
     * @return int
     */
    public static function defaultValue() {
        return self::PERMISSION;
    }

}