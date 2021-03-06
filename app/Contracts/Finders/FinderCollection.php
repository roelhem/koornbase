<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 08:57
 */

namespace App\Contracts\Finders;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;

/**
 * Interface FinderCollection
 *
 * An interface for an service that collects multiple ModelFinders and uses them to find the different types of
 * models.
 *
 * @package App\Contracts\Finders
 */
interface FinderCollection
{

    /**
     * Adds a ModelFinder to the FinderCollection
     *
     * @param ModelFinder $finder
     * @return void
     */
    public function add(ModelFinder $finder);

    /**
     * Returns if this FinderCollection is able to find a model with the given class name.
     *
     * @param string $modelName
     * @return bool
     */
    public function canFind($modelName) : bool;

    /**
     * Returns if the FinderCollection accepts the type if the input to find the provided $className
     *
     * @param mixed $input
     * @param string $modelName
     * @return bool
     */
    public function accepts($input, $modelName) : bool;

    /**
     * Tries to find the model with the given $className based on the given $input.
     *
     * @param mixed $input
     * @param string $modelName
     * @return mixed
     * @throws InputNotAcceptedException
     * @throws ModelNotFoundException
     */
    public function find($input, $modelName);

    /**
     * Returns a list of finders in this collection with the name of these finders as keys.
     *
     * @return array
     */
    public function list() : array;

}