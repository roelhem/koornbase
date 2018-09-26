<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-09-18
 * Time: 23:16
 */

namespace Roelhem\GraphQL\Types\Connections;


use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class ConnectionEdgeType extends ObjectType
{

    /**
     * The type that represents the connection of this edge.
     * @var ConnectionType
     */
    protected $connectionType;

    /**
     * ConnectionEdgeType constructor.
     * @param ConnectionType $connectionType
     * @param array $config
     */
    public function __construct(ConnectionType $connectionType, $config = [])
    {
        $this->connectionType = $connectionType;
        $this->name = $this->connectionType->name.'Edge';

        // Default description
        if(empty($this->description)) {
            $this->description = $this->getDefaultDescription();
        }

        parent::__construct($config);
    }

    /**
     * The needed interfaces for an ConnectionEdge type.
     *
     * @return array
     */
    public function interfaces()
    {
        return [
            GraphQL::type('ConnectionEdge'),
        ];
    }

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'node' => [
                'type' => GraphQL::type($this->connectionType->toType),
                'description' => 'The `'.$this->connectionType->toType.'` on the end of this edge.',
            ],
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTERS FOR DEFAULT VALUES ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The default description of this connection-edge.
     *
     * @return string
     */
    protected function getDefaultDescription()
    {
        $res = "This type represents the edges in the `{$this->connectionType->name}` connection. ";
        if(!empty($this->connectionType->fromType) && !empty($this->connectionType->toType)) {
            $res .= "It contains the information of a edge from a `{$this->connectionType->fromType}` to a 
                     `{$this->connectionType->toType}` in the context of the connection.";
        } elseif(!empty($this->connectionType->toType)) {
            $res .= "It contains the information of a `{$this->connectionType->toType}` in the context of the connection.";
        }
        return $res;
    }

}