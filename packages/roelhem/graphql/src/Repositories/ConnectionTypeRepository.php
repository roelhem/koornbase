<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 01:20
 */

namespace Roelhem\GraphQL\Repositories;


use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;
use Roelhem\GraphQL\Fields\ConnectionField;
use Roelhem\GraphQL\Types\Connections\ConnectionType;

class ConnectionTypeRepository extends TypeRepository
{

    /** @inheritdoc */
    public function resolveType($type)
    {
        if(is_string($type)) {
            $res = resolve($type);
        } elseif($type instanceof ConnectionField) {
            $res = $type->type();
        } else {
            $res = new ConnectionType($type);
        }

        if($res instanceof ConnectionType) {
            return $res;
        }
        throw new \InvalidArgumentException("Can't resolve a ConnectionType based on the input ".Utils::printSafe($type));
    }

    /**
     * @param ConnectionField $field
     * @return Type
     */
    public function addField(ConnectionField $field)
    {
        return $this->addType($field, $field->typeName());
    }

    /**
     * @param ConnectionField[] $fields
     * @return Type[]
     */
    public function addFields($fields)
    {
        $res = [];
        foreach ($fields as $field) {
            $res[] = $this->addField($field);
        }
        return $res;
    }

    /** @inheritdoc */
    public function get($typeName)
    {
        if(parent::has($typeName)) {
            return parent::get($typeName);
        }

        $connectionType = $this->connectionTypeFromEdgeTypeName($typeName);
        if($connectionType instanceof ConnectionType) {
            if($connectionType->getEdgeType()->name === $typeName) {
                return $connectionType->getEdgeType();
            }
        }

        return parent::get($typeName);
    }

    /** @inheritdoc */
    public function has($typeName)
    {
        if(parent::has($typeName)) {
            return true;
        }

        $connectionType = $this->connectionTypeFromEdgeTypeName($typeName);
        if($connectionType instanceof ConnectionType) {
            return $connectionType->getEdgeType()->name === $typeName;
        }

        return false;
    }

    /** @inheritdoc */
    public function getNames()
    {
        $res = [];
        foreach (parent::getNames() as $name) {
            $res[] = $name;
            $res[] = $name . 'Edge';
        }
        return $res;
    }


    /**
     * Helper function that tries to
     *
     * @param string $typeName;
     * @return null|ConnectionType
     */
    protected function connectionTypeFromEdgeTypeName($typeName)
    {
        $edgeSuffix = 'Edge';
        if(ends_with($typeName, $edgeSuffix)) {
            $connectionTypeName = substr($typeName,0,strlen($typeName) - strlen($edgeSuffix));
            if(parent::has($connectionTypeName)) {
                return $this->get($connectionTypeName);
            }
        }

        return null;
    }

}