<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-09-18
 * Time: 22:36
 */

namespace Roelhem\GraphQL\Types\Connections;



use Illuminate\Contracts\Pagination\Paginator;
use Roelhem\GraphQL\Contracts\PaginatorContract;
use Roelhem\GraphQL\Enums\PaginationType;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Paginators\CursorPaginator;
use Roelhem\GraphQL\Types\ObjectType;

class ConnectionType extends ObjectType
{

    /**
     * @var string
     */
    public $toType;

    /**
     * @var string
     */
    public $fromType;

    /**
     * The type of the edge of this connection.
     *
     * @var \GraphQL\Type\Definition\Type|string
     */
    protected $edgeType;

    /**
     * ConnectionType constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->toType   = array_get($config, 'toType', $this->toType);
        $this->fromType = array_get($config, 'fromType', $this->fromType);
        $this->edgeType = array_get($config, 'edgeType', $this->edgeType);

        $connectionName = array_get($config,'connectionName', $this->toType);

        $this->name = $this->fromType.'_'.$connectionName.'Connection';

        parent::__construct($config);
    }


    /**
     * The interfaces for a connection
     *
     * @return array
     */
    public function interfaces()
    {
        return [
            GraphQL::type('Connection'),
        ];
    }

    /**
     * The fields for a connection
     *
     * @return array
     */
    protected function fields()
    {
        $tl = GraphQL::typeLoader();

        return [
            'edges' => [
                'type' => $tl->nonNull($tl->listOf($this->getEdgeType())),
                'description' => "The list of edges on the current pagination-page. In turn, these edges point to the
                                  connected `{$this->toType}`.",
                'resolve' => function($source) {
                    if($source instanceof PaginatorContract) {
                        return collect($source->items())->map(function($node, $key) use ($source) {
                            return [
                                'node' => $node,
                                'cursor' => $source->getCursor($node),
                                'index' => $source->startIndex() !== null ? $source->startIndex() + $key : null,
                            ];
                        });
                    }
                    return [];
                }
            ]
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTERS FOR DEFAULT NAMES -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The default name of this connection, based on the 'from', and 'to'-types.
     *
     * @return string
     */
    protected function getDefaultName()
    {
        $res = $this->fromType;
        $res .= ucfirst(array_get($this->config,'connectionName', $this->toType));
        $res .= "Connection";

        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTERS FOR CONNECTED TYPES ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the type that should be used for the edges of the connection
     *
     * @return ConnectionEdgeType
     */
    public function getEdgeType()
    {
        if(is_string($this->edgeType)) {
            $this->edgeType = resolve($this->edgeType);
        }

        if($this->edgeType === null) {
            $this->edgeType = new ConnectionEdgeType($this);
        }

        if(is_array($this->edgeType)) {
            $this->edgeType = new ConnectionEdgeType($this, $this->edgeType);
        }

        return $this->edgeType;
    }

}