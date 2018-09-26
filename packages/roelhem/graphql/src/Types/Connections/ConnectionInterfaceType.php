<?php

namespace Roelhem\GraphQL\Types\Connections;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Roelhem\GraphQL\Contracts\PaginatorContract;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\InterfaceType;


class ConnectionInterfaceType extends InterfaceType
{

    public $name = 'Connection';

    public $description = "This interface type is for all the types that represent a pagination trough a list of connected types.";

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'pageInfo' => [
                'type' => GraphQL::type('PageInfo!'),
                'description' => 'Information about the page that is shown.',
                'resolve' => function($source) { return $source; }
            ],
            'totalCount' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The total number of items in the list that is paginated.',
                'resolve' => function($source) {
                    if($source instanceof PaginatorContract) {
                        return $source->total();
                    }
                    return null;
                }
            ]
        ];
    }
}