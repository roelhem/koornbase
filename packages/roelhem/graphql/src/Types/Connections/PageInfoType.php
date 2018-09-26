<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-09-18
 * Time: 22:01
 */

namespace Roelhem\GraphQL\Types\Connections;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Roelhem\GraphQL\Contracts\PaginatorContract;
use Roelhem\GraphQL\Enums\PaginationType;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Paginators\OffsetPaginator;
use Roelhem\GraphQL\Types\ObjectType;

class PageInfoType extends ObjectType
{

    public $name = "PageInfo";

    public $description = "The `PageInfo`-type object contains the info of a pagination-page in a `Connection`.
                           
                           \n\nAn `PageInfo`-typed object is mainly used when more data is needed from te pagination.";

    public function fields()
    {
        return [
            'endCursor' => [
                'type' => GraphQL::type('Cursor'),
                'description' => 'Gives the cursor of the last item on the current pagination-page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->endCursor();
                }
            ],
            'startCursor' => [
                'type' => GraphQL::type('Cursor'),
                'description' => 'Gives the cursor of the first item on the current pagination-page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->startCursor();
                }
            ],
            'hasNextPage' => [
                'type' => GraphQL::type('Boolean!'),
                'description' => 'Whether or not the current `Connection`-pagination has a page after this page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->hasMorePages();
                }
            ],
            'hasMultiplePages' => [
                'type' => GraphQL::type('Boolean!'),
                'description' => 'Whether or not the current `Connection`-pagination needs more than one page to show the whole list.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->hasPages();
                }
            ],
            'perPage' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Gives the maximum amount of items shown per page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->perPage();
                }
            ],
            'currentPage' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Gives the number of the current page, when using the `Paginator`.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->currentPage();
                }
            ],
            'lastPage' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Gives the index of the last page of this paginator.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->lastPage();
                }
            ],
            'pageCount' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The amount of items on the current page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->count();
                }
            ],
            'startIndex' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Gives the index-number of the first item on the page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->startIndex();
                }
            ],
            'endIndex' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Gives the index-number of the last item on the page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->endIndex();
                }
            ],
            'paginationType' => [
                'type' => GraphQL::type('PaginationType!'),
                'description' => 'Gives the type of pagination method used to create this page.',
                'resolve' => function(PaginatorContract $source) {
                    return $source->type();
                }
            ]

        ];
    }

}