<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:17
 */

namespace Roelhem\GraphQL\Types;


use EloquentFilter\Filterable;
use Laravel\Scout\Searchable;
use Roelhem\GraphQL\Contracts\ModelTypeContract;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Resolvers\ModelTypeResolver;
use Roelhem\GraphQL\Types\Filters\FilterInputType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;
use Roelhem\GraphQL\Types\Traits\HasConnectionFields;

abstract class ModelType extends ObjectType implements ModelTypeContract
{

    use HasConnectionFields;


    public function __construct(array $config = [])
    {
        parent::__construct(array_merge([
            'resolveField' => new ModelTypeResolver(),
        ], $config));
    }


    public $modelClass;

    public function getModelClass()
    {
        return $this->modelClass;
    }


    /** @inheritdoc */
    protected function interfaces()
    {
        return [GraphQL::type('Model')];
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ORDER-ABLES ---------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $orderable = true;

    public function orderable() {
        return $this->orderable;
    }

    /**
     * Defines all the things where you can order this model by.
     *
     * @return array
     */
    protected function orderables()
    {
        return [
            'id' => [
                'description' => 'Orders by the `ID` of the `Model`.',
                'query' => ['id'],
                'cursorPattern' => ['id' => 'n'],
            ],
            'createdAt' => [
                'description' => 'Orders the models by there creation-date.',
                'query' => ['created_at','id'],
                'cursorPattern' => ['created_at' => 'datetime','id' => 'n'],
            ],
            'updatedAt' => [
                'description' => 'Orders the models by the last time they were updated.',
                'query' => ['updated_at', 'id'],
                'cursorPattern' => ['updated_at' => 'datetime','id' => 'n'],
            ]
        ];
    }

    protected $orderByInputType;

    /**
     * @return OrderByInputType
     */
    public function getOrderByInputType()
    {
        if($this->orderByInputType === null) {
            $this->orderByInputType = new OrderByInputType([
                'orderables' => $this->orderables(),
                'modelType' => $this,
            ]);
        }
        return $this->orderByInputType;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FILTERS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The definition of the filter-object
     *
     * @return array
     */
    public function filters() {
        return [];
    }

    protected $filterInputType;

    public function getFilterInputType()
    {
        if ($this->filterInputType === null) {
            $this->filterInputType = new FilterInputType([
                'filters' => $this->filters(),
                'modelType' => $this,
            ]);
        }
        return $this->filterInputType;
    }

    protected $filterable = true;

    public function filterable()
    {
        if(in_array(Filterable::class, class_uses($this->modelClass))) {
            return $this->filterable;
        }
        return false;
    }

    protected $searchable = false;

    public function searchable()
    {
        if(in_array(Searchable::class, class_uses($this->modelClass))) {
            return $this->searchable;
        }
        return false;
    }

}