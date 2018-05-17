<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-05-18
 * Time: 14:09
 */

namespace App\Helpers;


class NestedRequestHelper
{

    protected $inputArray;

    protected $options;

    /**
     * NestedRequestHelper constructor.
     * @param array $inputArray
     * @param array $options
     */
    public function __construct($inputArray, $options = [])
    {
        $this->inputArray = $inputArray;

        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    /**
     * Returns the default options.
     *
     * @return array
     */
    protected function getDefaultOptions() {
        return [
            'idKey' => 'id',
            'deletedKey' => '_deleted',
            'childClass' => null,
            'parent' => null,
            'relation' => null,
            'findModel' => [$this, 'findModel'],
            'makeModel' => [$this, 'makeModel'],
            'createModel' => [$this, 'createModel'],
            'updateModel' => [$this, 'updateModel'],
            'getFillableArray' => [$this, 'getFillableArray'],
            'only' => null,
            'except' => null,
        ];
    }

    public function findModel($item, $index, $id) {
        $childClass = $this->getOption('childClass');
        return $childClass::findOrFail($id);
    }

    public function makeModel($item, $index) {
        $childClass = $this->getOption('childClass');

        $getFillableArray = $this->getOption('getFillableArray');
        $fillable = $getFillableArray($item, $index);

        return $childClass::make($fillable);
    }

    public function createModel($item, $index) {
        $makeModel = $this->getOption('makeModel');
        $model = $makeModel($item, $index);

        $parent = $this->getOption('parent');
        $relation = $parent->getRelation($this->getOption('relation'));

        $relation->save($model);

        return $model;
    }

    public function updateModel($item, $index, $id) {
        $findModel = $this->getOption('findModel');

    }

    public function getFillableArray($item, $index) {
        $only = $this->getOption('only');

        if(is_array($only)) {
            return array_only($item, $only);
        } else {
            $except = $this->getOption('except', [
                $this->getOption('idKey'),
                $this->getOption('deletedKey')
            ]);
            return array_except($item, $except);
        }
    }

    /**
     * Returns the option from the given key or key path.
     *
     * @param string $keyPath
     * @param mixed $default
     * @return mixed
     */
    protected function getOption($keyPath, $default = null) {
        return array_get($this->options, $keyPath, $default);
    }

    /**
     * Returns an array of input $items that want an element to be created.
     *
     * @return array
     */
    public function getCreateItems() : array
    {
        return array_where($this->inputArray, function($value) {
            return $this->shouldCreate($value);
        });
    }

    /**
     * Returns an array of input $items that want an element to be deleted.
     *
     * @return array
     */
    public function getDeleteItems() : array
    {
        return array_where($this->inputArray, function($value) {
            return $this->shouldDelete($value);
        });
    }

    /**
     * Returns an array of input $items that want an element to be updated.
     *
     * @return array
     */
    public function getUpdateItems() : array
    {
        return array_where($this->inputArray, function($value) {
            return $this->shouldUpdate($value);
        });
    }


    /**
     * Returns the id in the given input $item.
     *
     * @param array $item
     * @return string|integer|null
     */
    protected function getId(array $item)
    {
        return array_get($item, $this->getOption('idKey'), null);
    }

    /**
     * Returns if the element should be deleted according to the input $item.
     *
     * @param array $item
     * @return bool
     */
    protected function getDeleted(array $item) : bool
    {
        $deleted = array_get($item, $this->getOption('deletedKey'), false);
        if($deleted) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns if a new element should be created from the input $item.
     *
     * @param array $item
     * @return bool
     */
    public function shouldCreate(array $item) : bool
    {
        return !$this->getDeleted($item) && $this->getId($item) === null;
    }

    /**
     * Returns if an element should be deleted by using this input $item.
     *
     * @param array $item
     * @return bool
     */
    public function shouldDelete(array $item) : bool
    {
        return $this->getDeleted($item) && $this->getId($item) !== null;
    }

    /**
     * Returns if an element should be updated by using this input $item.
     *
     * @param array $item
     * @return bool
     */
    public function shouldUpdate(array $item) : bool
    {
        return !$this->getDeleted($item) && $this->getId($item) !== null;
    }
}