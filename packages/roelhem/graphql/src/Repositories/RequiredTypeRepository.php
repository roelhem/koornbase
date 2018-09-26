<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 02:15
 */

namespace Roelhem\GraphQL\Repositories;


use Roelhem\GraphQL\Enums\PaginationType;
use Roelhem\GraphQL\Types\Connections\ConnectionEdgeInterfaceType;
use Roelhem\GraphQL\Types\Connections\ConnectionInterfaceType;
use Roelhem\GraphQL\Types\Connections\CursorType;
use Roelhem\GraphQL\Types\Connections\PageInfoType;
use Roelhem\GraphQL\Types\MabeEnumType;
use Roelhem\GraphQL\Types\ModelInterfaceType;

class RequiredTypeRepository extends TypeRepository
{


    public function __construct()
    {
        parent::__construct([
            new InternalTypeRepository(),

            'Model' => ModelInterfaceType::class,

            'Cursor' => CursorType::class,
            'PageInfo' => PageInfoType::class,
            'Connection' => ConnectionInterfaceType::class,
            'ConnectionEdge' => ConnectionEdgeInterfaceType::class,
            'PaginationType' => new MabeEnumType([
                'name' => 'PaginationType',
                MabeEnumType::CLASS_CONFIG_NAME => PaginationType::class,
            ]),
        ]);
    }
}