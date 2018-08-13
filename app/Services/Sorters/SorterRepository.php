<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:50
 */

namespace App\Services\Sorters;


use App\GraphQL\Enums\SortFieldEnum;
use Illuminate\Database\Eloquent\Model;

class SorterRepository
{

    protected $sorters = [];

    /**
     * Returns the className of the sorter that belongs to the provided model.
     *
     * @param Model|string $model
     * @return string
     */
    protected function defaultSorterClass($model)
    {
        try {
            $reflection = new \ReflectionClass($model);
            $shortName = $reflection->getShortName();
            $sorterClass = 'App\\Services\\Sorters\\'.$shortName.'Sorter';
            return $sorterClass;
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException("The provided model was not a valid class or className.");
        }
    }

    /**
     * Checks if the provided sorter class is a valid sorter class.
     *
     * @param string $sorterClass
     */
    protected function validateSorterClass($sorterClass)
    {
        if(!class_exists($sorterClass)) {
            throw new \InvalidArgumentException("The class $sorterClass does not exist.");
        }

        if(!is_subclass_of($sorterClass, Sorter::class)) {
            throw new \InvalidArgumentException("The class $sorterClass is not a subclass of ".Sorter::class.".");
        }
    }

    /**
     * Initiate and returns a new sorter object based on the provided sorter class.
     *
     * @param string $sorterClass
     * @return Sorter
     */
    protected function resolveSorterClass($sorterClass)
    {
        $this->validateSorterClass($sorterClass);
        return resolve($sorterClass);
    }

    /**
     * Returns a sorter based on the given input.
     *
     * @param Sorter|string $sorter
     * @return Sorter
     */
    protected function parseSorter($sorter)
    {
        if($sorter instanceof Sorter) {
            return $sorter;
        }

        return $this->resolveSorterClass($sorter);
    }

    /**
     * Tries to always return the class of the provided model.
     *
     * @param string|Model $model
     * @return string
     */
    protected function getModelClass($model)
    {
        if(!is_string($model)) {
            return get_class($model);
        } else {
            return $model;
        }
    }

    /**
     * Sets the sorter for the provided model type.
     *
     * @param Model|string $model
     * @param null|string|Sorter $sorter
     * @return Sorter
     */
    public function setSorter($model, $sorter = null)
    {
        $modelClass = $this->getModelClass($model);

        if($sorter === null) {
            $sorter = $this->defaultSorterClass($modelClass);
        }
        $sorter = $this->parseSorter($sorter);

        $this->sorters[$modelClass] = $sorter;

        return $sorter;
    }

    /**
     * Gets the sorter that belongs to the provided model type.
     *
     * @param Model|string $model
     * @return Sorter
     */
    public function getSorter($model) {
        $modelClass = $this->getModelClass($model);

        if(array_key_exists($modelClass, $this->sorters)) {
            return $this->sorters[$modelClass];
        }

        return $this->setSorter($modelClass);
    }

}