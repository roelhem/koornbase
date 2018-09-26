<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 21:32
 */

namespace App\Http\GraphQL\Types\Scalars;



use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;

class EmailType extends ScalarType
{


    protected $attributes = [
        'name' => 'Email',
        'description' => 'The `Email` scalar type represents an e-mail address only (without names, brackets, etc.) It\'s JSON-value is a string that can directly be used to send e-mails.',
    ];

    /**
     * A function that takes php-value of the type and serializes it to represent in the json response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        $res = filter_var(trim($value), FILTER_SANITIZE_EMAIL);
        if($res === false) {
            throw new \LogicException("Invalid E-mailadres provided for an `Email`-type field.");
        }
        return $res;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     *
     * @param string|integer|float|null|boolean|array $value
     * @return mixed
     * @throws Error
     */
    public function parseValue($value)
    {
        if(!is_string($value)) {
            throw new Error("The input for an `Email` type has to be a string.");
        }

        $res = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
        if($res === false) {
            throw new Error("Invalid E-mail address given at an `Email`-type field.");
        }

        return $res;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     * @throws Error
     */
    public function parseLiteral($valueNode)
    {
        if($valueNode instanceof StringValueNode) {
            $res = filter_var(trim($valueNode->value), FILTER_VALIDATE_EMAIL);
            if($res === false) {
                throw new Error("Invalid E-mail address given at an `Email`-type field.", [$valueNode]);
            }
            return $res;
        }

        throw new Error("The input for an `Email`-type field has to be a string.", [$valueNode]);
    }

}