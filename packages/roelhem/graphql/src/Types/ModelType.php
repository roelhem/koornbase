<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:17
 */

namespace Roelhem\GraphQL\Types;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;
use Roelhem\GraphQL\Types\Traits\HasConnectionFields;

abstract class ModelType extends ObjectType
{

    use HasConnectionFields;

    public $modelClass;

    /** @inheritdoc */
    protected function interfaces()
    {
        return [GraphQL::type('Model')];
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ORDER-ABLES ---------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

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

}