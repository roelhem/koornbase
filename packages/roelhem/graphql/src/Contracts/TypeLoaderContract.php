<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:52
 */

namespace Roelhem\GraphQL\Contracts;


use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

interface TypeLoaderContract
{

    /**
     * Returns the repository that get all the types that can be loaded by this TypeLoader.
     *
     * @return TypeRepositoryContract
     */
    public function repository();


    /**
     * Tries to load the type that is described in the provided argument.
     *
     * @param string|Type $type
     * @return Type
     */
    public function load($type);

    /**
     * Returns the non-null type of the type described in the argument.
     *
     * @param string|Type $type
     * @return NonNull
     */
    public function nonNull($type);

    /**
     * Returns the list-type of the type described in the argument.
     *
     * @param string|Type $type
     * @return ListOfType
     */
    public function listOf($type);

    /**
     * Invokes the TypeLoader load function trough magic methods.
     *
     * @param string|Type $type
     * @return Type
     */
    public function __invoke($type);

}