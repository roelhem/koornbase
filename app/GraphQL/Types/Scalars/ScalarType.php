<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 18:24
 */

namespace App\GraphQL\Types\Scalars;

use GraphQL\Type\Definition\CustomScalarType;
use Rebing\GraphQL\Support\Type as GraphQLType;


class ScalarType extends GraphQLType
{

    /**
     * A function that takes php-value of the type and serializes it to represent in the json response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value) {
        return strval($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     *
     * @param string|integer|float|null|boolean|array $value
     * @return mixed
     */
    public function parseValue($value) {
        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     */
    public function parseLiteral($valueNode) {
        return $valueNode->toArray();
    }

    /** @inheritdoc */
    public function attributes()
    {
        return [];
    }

    /** @inheritdoc */
    public function getAttributes()
    {
        $attributes =  $this->attributes();
        $attributes = array_merge($this->attributes, $attributes);

        foreach (['serialize','parseValue','parseLiteral'] as $callableNames) {
            if(!array_key_exists($callableNames, $attributes) || !is_callable($attributes[$callableNames])) {
                $attributes[$callableNames] = [$this, $callableNames];
            }
        }

        return $attributes;
    }

    /** @inheritdoc */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /** @inheritdoc */
    public function toType()
    {
        return new CustomScalarType($this->toArray());
    }

}