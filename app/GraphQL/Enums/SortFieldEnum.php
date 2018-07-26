<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:14
 */

namespace App\GraphQL\Enums;


use App\Services\Sorters\Sorter;
use App\Services\Sorters\SorterRepository;
use Illuminate\Database\Eloquent\Model;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SortFieldEnum extends GraphQLType
{

    protected $enumObject = true;

    protected $model;

    protected $sorter;

    /**
     * SortFieldEnum constructor.
     * @param Model|string $model
     * @param SorterRepository $sorterRepository
     * @param array $attributes
     */
    public function __construct($model, SorterRepository $sorterRepository, $attributes = [])
    {
        $this->model = $model;
        $this->sorter = $sorterRepository->getSorter($model);
        parent::__construct($attributes);
    }

    /** @inheritdoc */
    public function attributes()
    {
        try {
            $shortName = (new \ReflectionClass($this->model))->getShortName();
        } catch (\ReflectionException $exception) {
            $shortName = strval($this->model);
        }

        return [
            'name' => $shortName.'_sortField',
            'description' => 'An enum with the fields that are able to sort an '.$shortName.'.',
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