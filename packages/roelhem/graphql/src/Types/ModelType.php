<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:17
 */

namespace Roelhem\GraphQL\Types;


use Carbon\Carbon;
use GraphQL\Error\InvariantViolation;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\Connections\ConnectionType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;

abstract class ModelType extends ObjectType
{

    public $modelClass;

    /** @inheritdoc */
    protected function interfaces()
    {
        return [GraphQL::type('Model')];
    }

    /** @inheritdoc */
    protected function fieldSources()
    {
        return array_merge(parent::fieldSources(), [
            $this->getConnectionFields(),
        ]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CONNECTIONS ---------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * An array that defines the connections of this model.
     *
     * @return array
     */
    protected function connections()
    {
        return [];
    }

    protected $connections;

    protected function parseConnections()
    {
        if($this->connections === null) {
            $this->connections = [];
            foreach ($this->connections() as $key => $value) {
                if(is_string($value)) {
                    $value = ['toType' => $value];
                }

                if (!is_array($value)) {
                    throw new InvariantViolation("Connection-definitions of {$this} not in right format.");
                }

                $toType = array_get($value,'toType', array_get($value,'to'));
                $res = array_merge([
                    'toType' => $toType,
                    'baseName' => is_string($key) ? $key : camel_case(str_plural($toType)),
                    'description' => "A paginated list of connections to `{$toType}`."
                ], $value);

                $res['args'] = array_merge([
                    'first' => [
                        'type' => GraphQL::type('Int'),
                        'description' => 'The maximum amount of items you want to display.',
                        'default' => 15,
                    ],
                    'after' => [
                        'type' => GraphQL::type('Cursor'),
                        'description' => 'The cursor to the position that should be the start of the page.',
                    ],
                    'offset' => [
                        'type' => GraphQL::type('Int'),
                        'description' => 'The number of items in the list that should be skipped.',
                    ]
                ], array_get($value,'args', []));

                $res['type'] = new ConnectionType([
                    'connectionName' => array_get($res, 'baseName', array_get($res, 'name')),
                    'fromType' => $this,
                    'toType' => array_get($res,'toType'),
                ]);

                $res['name'] = array_get($res, 'name', array_get($res, 'baseName').'Connection');


                $this->connections[array_get($res,'name')] = $res;
            }
        }
        return $this->connections;
    }

    /**
     * Returns an array of all the connection-types that need to be added to the type-repositories.
     *
     * @return array
     */
    public function getConnectionTypes()
    {
        $res = [];
        foreach ($this->parseConnections() as $name => $connection) {
            $res[] = array_get($connection, 'type');
        }
        return $res;
    }


    /**
     * Returns an array of fields-definitions that define the connections-fields.
     *
     * @return array
     */
    protected function getConnectionFields()
    {
        return $this->parseConnections();
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