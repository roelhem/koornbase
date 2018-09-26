<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 01:08
 */

namespace Roelhem\GraphQL\Repositories\Traits;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;

trait GetAllFromGetNames
{

    /**
     * Returns the `GraphQL\Type\Definition\Type` instance that defines the provided type.
     *
     * @param string $typeName
     * @return Type
     * @throws TypeNotFoundException
     */
    abstract public function get($typeName);

    /**
     * Returns a list of all the typeNames that the repository has.
     *
     * @return string[]
     */
    abstract public function getNames();

    /**
     * Returns all the types by calling `$this->get($typeName)` for all the types in getNames().
     *
     * @return Type[]
     */
    public function getAll()
    {
        $res = [];

        foreach ($this->getNames() as $typeName) {
            $res[$typeName] = $this->get($typeName);
        }

        return $res;
    }

}