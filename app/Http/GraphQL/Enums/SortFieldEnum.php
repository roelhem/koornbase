<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:14
 */

namespace App\Http\GraphQL\Enums;


use App\Services\Sorters\SorterRepository;
use Illuminate\Database\Eloquent\Model;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SortFieldEnum extends GraphQLType
{

    protected $enumObject = true;

    protected $model;

    protected $typeName;

    protected $sorter;

    /**
     * SortFieldEnum constructor.
     * @param Model|string $model
     * @param SorterRepository $sorterRepository
     * @param string|null $typeName
     * @param array $attributes
     */
    public function __construct($model, SorterRepository $sorterRepository, $typeName = null, $attributes = [])
    {
        $this->model = $model;
        $this->sorter = $sorterRepository->getSorter($model);

        $this->typeName = $typeName;
        if($this->typeName === null) {
            $this->typeNameFromModel();
        }

        parent::__construct($attributes);
    }

    /**
     * Sets the value of `$this->typeName` based on the model.
     */
    protected function typeNameFromModel() {
        try {
            $this->typeName = (new \ReflectionClass($this->model))->getShortName();
        } catch (\ReflectionException $exception) {
            $this->typeName = strval($this->model);
        }
    }

    /** @inheritdoc */
    public function attributes()
    {
        return [
            'name' => $this->typeName.'_sortField',
            'description' => 'An enum with the fields that are able to sort an '.$this->typeName.'.',
            'values' => $this->values()
        ];
    }

    /**
     * Determines the values of this SortFieldEnum
     *
     * @return array
     */
    protected function values() {
        $res = [];
        foreach ($this->sorter->list() as $sortName) {
            $res[$sortName] = $sortName;
        }
        return $res;
    }

}