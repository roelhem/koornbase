<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-09-18
 * Time: 22:24
 */

namespace Roelhem\GraphQL\Types\Connections;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\InterfaceType;

class ConnectionEdgeInterfaceType extends InterfaceType
{

    public $name = "ConnectionEdge";

    public $description = "The `ConnectionEdge`-type represents the edge in the connection to the connected object.";


    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'cursor' => [
                'type' => GraphQL::type('Cursor'),
                'description' => "The cursor to the position of this edge in the list of edges in this `Connection`.",
            ],
            'index' => [
                'type' => GraphQL::type('Int'),
                'description' => "The number of the position of the edge in the list.",
            ]
        ];
    }
}