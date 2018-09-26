<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 00:02
 */

namespace Roelhem\GraphQL\Repositories;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;

class InternalTypeRepository implements TypeRepositoryContract
{

    /** @inheritdoc */
    public function get($typeName)
    {
        $internalTypes = Type::getInternalTypes();

        if(!isset($internalTypes[$typeName])) {
            throw new TypeNotFoundException($typeName, $this);
        }

        return $internalTypes[$typeName];
    }

    /** @inheritdoc */
    public function has($typeName)
    {
        return array_key_exists($typeName, Type::getInternalTypes());
    }

    /** @inheritdoc */
    public function getNames()
    {
        return array_keys(Type::getInternalTypes());
    }

    /** @inheritdoc */
    public function getAll()
    {
        return Type::getInternalTypes();
    }
}