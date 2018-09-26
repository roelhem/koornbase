<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-09-18
 * Time: 23:44
 */

namespace Roelhem\GraphQL\Repositories;

use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;
use Roelhem\GraphQL\Repositories\Traits\GetAllFromGetNames;


class TypeRepository implements TypeRepositoryContract
{

    use GetAllFromGetNames;

    /**
     * @var Type[]
     */
    protected $types = [];

    /**
     * @var TypeRepositoryContract[]
     */
    protected $subRepositories = [];

    /**
     * TypeRepository constructor.
     * @param TypeRepositoryContract|string[] $sources
     */
    public function __construct($sources = [])
    {
        foreach ($sources as $key => $value) {
            if($value instanceof TypeRepositoryContract) {
                $this->addRepository($value);
            } else {
                $this->addType($value, $key);
            }
        }
    }

    /**
     * Adds a sub-repository to this repository. This way, all the types in the sub-repository are automatically
     * in this repository
     *
     * @param TypeRepositoryContract|mixed $repository
     */
    public function addRepository($repository)
    {
        if($repository instanceof TypeRepositoryContract) {
            $this->subRepositories[] = $repository;
        } else {
            throw new \InvalidArgumentException("Can't add the Repository based on the provided arguments.");
        }
    }

    /**
     * Adds an type to the repository. It returns the instance that was added.
     *
     * @param mixed $type
     * @param mixed $name
     * @return mixed
     */
    public function addType($type, $name = null)
    {
        if($type instanceof Type) {
            $this->types[$type->name] = $type;
            return $type;
        }

        if(is_string($name)) {
            $this->types[$name] = $type;
            return $type;
        } else {
            return $this->addType($this->resolveType($type));
        }
    }

    /**
     * @param $type
     * @return Type
     */
    protected function resolveType($type)
    {
        $res = resolve($type);
        if($res instanceof Type) {
            return $res;
        }
        throw new \InvalidArgumentException("Can't resolve a type from the provided parameter.");
    }


    /** @inheritdoc */
    public function get($typeName)
    {
        // Check if the $this->types[] array has the type.
        if(array_key_exists($typeName, $this->types)) {
            $type = $this->types[$typeName];
            if($type instanceof Type) {
                return $type;
            } else {
                $type = $this->resolveType($type);
                $this->types[$typeName] = $type;
                return $type;
            }
        }


        // Check if one of the sub-repositories has the type.
        foreach ($this->subRepositories as $subRepository) {
            if($subRepository->has($typeName)) {
                $type = $subRepository->get($typeName);
                $this->types[$typeName] = $type;
                return $type;
            }
        }


        // Throw an exception
        throw new TypeNotFoundException($typeName, $this);
    }

    /** @inheritdoc */
    public function has($typeName)
    {
        // Always return true if the the $typeName exists in the $this->types[] array.
        if(array_key_exists($typeName, $this->types)) {
            return true;
        }

        // Check if the subRepositories have the $typeName.
        foreach ($this->subRepositories as $subRepository) {
            if($subRepository->has($typeName)) {
                return true;
            }
        }

        // Couldn't find the result.
        return false;
    }

    /** @inheritdoc */
    public function getNames()
    {
        $res = array_keys($this->types);

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