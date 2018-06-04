<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 09:03
 */

namespace App\Services\Finders;
use App\Contracts\Finders\FinderCollection;
use App\Contracts\Finders\ModelFinder;

/**
 * Class SimpleFinderCollection
 *
 * A simple finder collection that works by storing the added ModelFinders in an array with the classNames as the
 * keys of the array and the value as the ModelFinders.
 *
 * @package App\Services\Finders
 */
class SimpleFinderCollection implements FinderCollection
{

    /**
     * @var array
     */
    protected $array = [];


    /**
     * @inheritdoc
     */
    public function add(ModelFinder $finder)
    {
        $this->array[$finder->modelName()] = $finder;
    }

    /**
     * @inheritdoc
     */
    public function canFind($modelName): bool
    {
        return array_key_exists($modelName, $this->array);
    }

    /**
     * @inheritdoc
     */
    public function accepts($input, $modelName): bool
    {
        return $this->array[$modelName]->accepts($input);
    }

    /**
     * @inheritdoc
     */
    public function find($input, $modelName)
    {
        return $this->array[$modelName]->find($input);
    }

    /**
     * @inheritdoc
     */
    public function list():array {
        return $this->array;
    }
}