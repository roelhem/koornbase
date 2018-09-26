<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 00:32
 */

namespace Roelhem\GraphQL;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;

class GraphQL
{

    /** @var TypeLoaderContract */
    protected $typeLoader;

    /**
     * GraphQL constructor.
     * @param TypeLoaderContract $typeLoader
     */
    public function __construct(TypeLoaderContract $typeLoader)
    {
        $this->typeLoader = $typeLoader;
    }

    /**
     * Returns the instance of the typeLoader.
     *
     * @return TypeLoaderContract
     */
    public function typeLoader()
    {
        return $this->typeLoader;
    }

    /**
     * Returns the instance of the Type that belongs to the provided $type.
     *
     * @param string|Type $type
     * @return Type
     */
    public function type($type)
    {
        return $this->typeLoader()->load($type);
    }

    /**
     * Returns all the (base) types
     *
     * @return Type[]
     */
    public function types()
    {
        return $this->typeLoader()->repository()->getAll();
    }


}