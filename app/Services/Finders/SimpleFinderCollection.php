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
     * @var ModelFinder[]
     */
    protected $array = [];


    /**
     * @inheritdoc
     */
    public function add(ModelFinder $finder)
    {
        $className = $finder->modelClass();
        $this->array[$className] = $finder;
    }

    /**
     * @inheritdoc
     */
    public function canFind($className): bool
    {
        return array_key_exists($className, $this->array);
    }

    /**
     * @inheritdoc
     */
    public function accepts($input, $className): bool
    {
        $this->array[$className]->accepts($input);
    }

    /**
     * @inheritdoc
     */
    public function find($input, $className)
    {
        return $this->array[$className]->find($input);
    }
}