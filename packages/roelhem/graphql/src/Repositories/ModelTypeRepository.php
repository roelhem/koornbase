<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:25
 */

namespace Roelhem\GraphQL\Repositories;



use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Repositories\Traits\GetAllFromGetNames;
use Roelhem\GraphQL\Types\ModelType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;

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

    /**
     * Returns the default type-name, based on a classname.
     *
     * @param string $className
     * @return string
     */
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

    /**
     * @param $type
     * @return Type
     */
    protected function resolveType($type)
    {
        $type = parent::resolveType($type);
        if(!($type instanceof ModelType)) {
            throw new \InvalidArgumentException("Can't resolve to a valid " . ModelType::class . " instance...");
        }
        return $type;
    }

    public function get($typeName)
    {
        if(parent::has($typeName)) {
            return parent::get($typeName);
        }

        // Get derived types.
        $pieces = explode('_',$typeName);
        if(count($pieces) > 1 && parent::has($pieces[0])) {
            // Get the modelType on which the typeName was derived
            $modelType = parent::get($pieces[0]);
            if($modelType instanceof ModelType) {

                // Check the OrderByInputType.
                if(ends_with($typeName,OrderByInputType::SUFFIX)) {
                    return $modelType->getOrderByInputType();
                }

                // Check the connections
                $connectionTypeRepository = $modelType->getConnectionTypeRepository();
                if($connectionTypeRepository->has($typeName)) {
                    return $connectionTypeRepository->get($typeName);
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

        // Get derived types.
        $pieces = explode('_',$typeName);
        if(count($pieces) > 1 && parent::has($pieces[0])) {
            // Get the modelType on which the typeName was derived
            $modelType = parent::get($pieces[0]);
            if($modelType instanceof ModelType) {

                // Check the OrderByInputType.
                if(ends_with($typeName,OrderByInputType::SUFFIX)) {
                    return true;
                }

                // Check the connections
                $connectionTypeRepository = $modelType->getConnectionTypeRepository();
                if($connectionTypeRepository->has($typeName)) {
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
            $res[] = $typeName.OrderByInputType::SUFFIX;
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