<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:25
 */

namespace Roelhem\GraphQL\Repositories;



use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;
use Roelhem\GraphQL\Repositories\Traits\GetAllFromGetNames;
use Roelhem\GraphQL\Types\ModelInterfaceType;
use Roelhem\GraphQL\Types\ModelType;

class ModelTypeRepository extends TypeRepository
{
    use GetAllFromGetNames;


    /**
     * ModelTypeRepository constructor.
     * @param array $modelTypes
     */
    public function __construct($modelTypes = [])
    {
        parent::__construct();

        foreach ($modelTypes as $key => $value) {
            if(is_string($key)) {
                $name = $key;
            } elseif(is_int($key)) {
                if(is_string($value)) {
                    $name = $this->typeNameFromClassName($value);
                } elseif ($value instanceof Type) {
                    $name = $value->name;
                } elseif(is_array($value) && isset($value['name'])) {
                    $name = $value['name'];
                } else {
                    throw new \InvalidArgumentException("Can't get a name for the ModelType.");
                }
            } else {
                throw new \InvalidArgumentException("Invalid Key type.");
            }
            /** @var ModelType $type */
            $this->addType($value, $name);
        }
    }

    protected function typeNameFromClassName($className)
    {
        try {
            $reflection = new \ReflectionClass($className);
            $name = $reflection->getShortName();
            if(ends_with($name, 'Type')) {
                return str_before($name, 'Type');
            }
            return $name;
        } catch (\ReflectionException $reflectionException) {
            throw new \InvalidArgumentException("Has to be a valid class name, not '$className'.", 0, $reflectionException);
        }
    }

    protected function resolveType($type)
    {
        $type = parent::resolveType($type);
        if(!($type instanceof ModelType)) {
            throw new \InvalidArgumentException("Can't resolve to a valid " . ModelType::class . " instance...");
        }
        $this->registerModelTypeSubRepositories($type);
        return $type;
    }

    protected function registerModelTypeSubRepositories($modelType)
    {
        if($modelType instanceof ModelType) {
            $this->addRepository(new ConnectionTypeRepository($modelType->getConnectionTypes()));
        }
    }

    public function get($typeName)
    {
        if(parent::has($typeName)) {
            return parent::get($typeName);
        }

        if(ends_with($typeName,'_orderByType')) {
            $modelTypeName = str_before($typeName, '_orderByType');
            if(parent::has($modelTypeName)) {
                $modelType = parent::get($modelTypeName);
                if($modelType instanceof ModelType) {
                    return $modelType->getOrderByInputType();
                }
            }
        }

        return parent::get($typeName);
    }

    public function has($typeName)
    {
        if(parent::has($typeName)) {
            return true;
        }

        if(ends_with($typeName,'_orderByType')) {
            $modelTypeName = str_before($typeName, '_orderByType');
            if(parent::has($modelTypeName)) {
                $modelType = parent::get($modelTypeName);
                if($modelType instanceof ModelType) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getNames()
    {
        $res = [];
        foreach ($this->types as $typeName => $type) {
            $res[] = $typeName;
            $res[] = $typeName.'_orderByType';
        }

        foreach ($this->subRepositories as $subRepository) {
            foreach ($subRepository->getNames() as $typeName) {
                if(!array_has($res, $typeName)) {
                    $res[] = $typeName;
                }
            }
        }

        return $res;
    }

}