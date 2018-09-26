<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 11:12
 */

namespace App\Http\GraphQLNew\Scalars;


use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

class URLType extends ScalarType
{
    public $name = 'URL';

    public $description = "The `URL` scalar-type represents a valid URL-string that can be used to point to other
                           data-pieces outside of the GraphQL-schema. This type is used to guarantee that a string
                           value has the right format to link to 'websites', 'images', 'e-mail addresses', etc.
                           
                           \n\nFurthermore, values of this type should *only be used to link, but never to identify*
                           thinks they link to or the objects that refer to those thinks. (The only exception is the
                           usage of a callback-URL in the OAuth2 protocol.)";

    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
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
        if($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }

        throw new Error("The value for an `URL`-type has to be a string!", [$valueNode]);
    }
}