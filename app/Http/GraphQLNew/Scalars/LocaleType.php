<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 09:52
 */

namespace App\Http\GraphQLNew\Scalars;


use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

class LocaleType extends ScalarType
{
    public $name = 'Locale';

    public $description = "The `Locale` scalar type represents language and/or localization setting-groups. It's value
            is a short string.";

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public function serialize($value)
    {
        return $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param string $value
     * @return string
     */
    public function parseValue($value)
    {
        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     * @throws Error
     */
    public function parseLiteral($valueNode)
    {
        if(($valueNode instanceof StringValueNode)) {
            return $valueNode->value;
        }

        throw new Error("A `Locale` has to be a string", [$valueNode]);
    }
}