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

    protected $typeName;

    protected $sorter;

    /**
     * SortFieldEnum constructor.
     * @param SorterRepository $sorterRepository
     * @param string|null $typeName
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(SorterRepository $sorterRepository, $typeName, $attributes = [])
    {
        $this->typeName = $typeName;

        $model = \GraphQL::getModelClassOfType($typeName);
        $this->sorter = $sorterRepository->getSorter($model);

        parent::__construct($attributes);
    }

    /** @inheritdoc */
    public function attributes()
    {
        return [
            'name' => $this->typeName.'_orderField',
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