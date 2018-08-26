<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 18:56
 */

namespace App\Enums\Traits;


use Rebing\GraphQL\Support\Type as GraphQLType;

trait HasGraphQLEnumType
{

    /**
     * The instance of the GraphQLType that belongs to this GraphQL Enum.
     *
     * @var GraphQLType|null
     */
    protected static $graphQLType;

    /**
     * Returns the type-name that will be used for the GraphQL Enum representation of this Enum.
     *
     * @return string
     */
    protected static function getTypeName()
    {
        try {
            $reflection = new \ReflectionClass(static::class);
            return $reflection->getShortName();
        } catch (\ReflectionException $reflectionException) {
            throw with($reflectionException);
        }
    }

    /**
     * Returns the description that will be used for the GraphQL Enum representation of this Enum.
     *
     * @return string
     */
    protected static function getDescription()
    {
        return "This `Enum`-type represents the values of the Enum `".static::class."` on the server.";
    }

    /**
     * Returns an array with all the options for the Enum-values in the GraphQL-Enum representation of this Enum.
     *
     * @return array
     */
    protected static function getEnumValues()
    {
        $res = [];
        /** @var static[] $enumerators */
        $enumerators = static::getEnumerators();
        foreach ($enumerators as $enumerator) {
            $res[$enumerator->getGraphQLName()] = [
                'value' => $enumerator,
                'description' => $enumerator->getGraphQLDescription(),
            ];
        }
        return $res;
    }

    /**
     * Returns an array with all the attributes for the creation of the GraphQL-Enum representation of this Enum.
     *
     * @return array
     */
    protected static function getAttributes()
    {
        return [
            'name' => static::getTypeName(),
            'description' => static::getDescription(),
            'values' => static::getEnumValues(),
        ];
    }

    /**
     * Creates a new GraphQLType based on this Enum.
     *
     * @return GraphQLType
     */
    public static function createGraphQLType()
    {
        return new class(static::getAttributes()) extends GraphQLType {
            protected $enumObject = true;
        };
    }

    /**
     * Returns the GraphQL-Enum type that represents this Enum-class.
     *
     * @return GraphQLType
     */
    public static function getGraphQLType()
    {
        if(static::$graphQLType === null) {
            static::$graphQLType = static::createGraphQLType();
        }
        return static::$graphQLType;
    }

    /**
     * Returns a string that can be used as the name in the GraphQL-Enum representation of this Enum.
     *
     * @return string
     */
    protected function getGraphQLName()
    {
        return $this->getName();
    }

    /**
     * Returns a string that can be used as the description of a GraphQL element.
     *
     * @return string|null
     */
    protected function getGraphQLDescription()
    {
        if(isset($this->description)) {
            return $this->description;
        }
        return null;
    }

}