<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-09-18
 * Time: 23:50
 */

namespace Roelhem\GraphQL\Contracts;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;

interface TypeRepositoryContract
{

    /**
     * Returns the `GraphQL\Type\Definition\Type` instance that defines the provided type.
     *
     * @param string $typeName
     * @return Type
     * @throws TypeNotFoundException
     */
    public function get($typeName);

    /**
     * Returns whether or not the repository can deliver the type with this typeName.
     *
     * @param  string $typeName
     * @return boolean
     */
    public function has($typeName);

    /**
     * Returns a list of all the typeNames that the repository has.
     *
     * @return string[]
     */
    public function getNames();

    /**
     * Returns a list of all the types that the repository has.
     *
     * @return Type[]
     */
    public function getAll();

}