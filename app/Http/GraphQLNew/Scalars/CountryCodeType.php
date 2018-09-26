<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 09:07
 */

namespace App\Http\GraphQLNew\Scalars;


use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

class CountryCodeType extends ScalarType
{

    public $name = 'CountryCode';

    public $description = "The `CountryCode` scalar type represents a string that contains a CountryCode.
    
        \n\n This type is mainly used to easily identify countries in inputs from users or other applications.
        
        \n\n When sending an input to a `CountryCode`-argument, it will be read **case-insensively**.";

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public function serialize($value)
    {
        return \Parse::countryCode($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param string $value
     * @return string
     */
    public function parseValue($value)
    {
        return \Parse::countryCode($value, true);
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
            return \Parse::countryCode($valueNode->value, true);
        }

        throw new Error("A CountryCode has to be a string", [$valueNode]);
    }
}