<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:51
 */

namespace Roelhem\GraphQL\Repositories;


use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;

class TypeLoader implements TypeLoaderContract
{

    /** @var TypeRepositoryContract */
    protected $typeRepository;


    /**
     * TypeLoader constructor.
     * @param TypeRepositoryContract $typeRepository
     */
    public function __construct(TypeRepositoryContract $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * Returns the repository that get all the types that can be loaded by this TypeLoader.
     *
     * @return TypeRepositoryContract
     */
    public function repository()
    {
        return $this->typeRepository;
    }

    /**
     * Tries to load the type that is described in the provided argument.
     *
     * @param string|Type $type
     * @return Type
     * @throws \Exception
     */
    public function load($type)
    {
        if($type instanceof Type) {
            return $type;
        }

        if(is_string($type)) {
            $type = trim($type);
            if(ends_with($type, '!')) {
                $wrappedType = substr($type, 0, strlen($type) - 1);
                return $this->nonNull($wrappedType);
            } else if(starts_with($type,'[') && ends_with($type, ']')) {
                $wrappedType = substr($type, 1, strlen($type) - 2);
                return $this->listOf($wrappedType);
            } else {
                return $this->repository()->get($type);
            }

        }

        throw new \InvalidArgumentException("The type needs to be a string or instance of ".Type::class);
    }

    /**
     * Returns the non-null type of the type described in the argument.
     *
     * @param string|Type $type
     * @return NonNull
     * @throws \Exception
     */
    public function nonNull($type)
    {
        return new NonNull($this->load($type));
    }

    /**
     * Returns the list-type of the type described in the argument.
     *
     * @param $type
     * @return ListOfType
     * @throws \Exception
     */
    public function listOf($type)
    {
        return new ListOfType($this->load($type));
    }

    /**
     * @param Type|string $type
     * @return Type
     * @throws \Exception
     */
    public function __invoke($type)
    {
        return $this->load($type);
    }

}