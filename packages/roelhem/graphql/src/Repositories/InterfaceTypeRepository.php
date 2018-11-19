<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 18:48
 */

namespace Roelhem\GraphQL\Repositories;


use GraphQL\Type\Definition\Type;

class InterfaceTypeRepository extends TypeRepository
{
    public function addType($type, $name = null)
    {
        if(!($type instanceof Type)) {
            $type = $this->resolveType($type);
        }

        if(method_exists($type,'getConnectionTypeRepository')) {
            $this->addRepository($type->getConnectionTypeRepository());
        }

        $this->types[$type->name] = $type;
        return $type;
    }
}