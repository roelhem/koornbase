<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-09-18
 * Time: 22:12
 */

namespace Roelhem\GraphQL\Types\Connections;
use Carbon\Carbon;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;


/**
 * Class CursorType
 * @package Roelhem\GraphQL\Types\Connections
 */
class CursorType extends ScalarType
{

    public $name = "Cursor";

    public $description = "The `Cursor` scalar type is a string that indicates a certain point in a list of a
                           `Connection`.";


    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return base64_encode($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        return base64_decode($value);
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     */
    public function parseLiteral($valueNode)
    {
        if($valueNode instanceof StringValueNode) {
            return base64_decode($valueNode->value);
        }

        return strval($valueNode);
    }
}