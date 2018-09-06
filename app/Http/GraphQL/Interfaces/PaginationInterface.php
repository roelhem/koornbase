<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 00:25
 */

namespace App\Http\GraphQL\Interfaces;


use GraphQL\Type\Definition\Type;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Rebing\GraphQL\Support\InterfaceType;

class PaginationInterface extends InterfaceType
{
    protected $attributes = [
        'name' => 'Pagination',
        'description' => 'A Type that represents the pagination trough a list of `Models`.',
        'pagination' => true,
    ];


    public function fields()
    {
        return [
            'total' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The total number of items in the list.',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->total();
                },
                'selectable' => false,
            ],
            'per_page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The number of items that are represented per page.',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->perPage();
                },
                'selectable' => false,
            ],
            'current_page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The number of the current page. (first page is 1).',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->currentPage();
                },
                'selectable' => false,
            ],
            'last_page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The number of the last page in this pagination.',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->lastPage();
                },
                'selectable' => false,
            ],
            'from' => [
                'type' => Type::int(),
                'description' => 'The number of the first item on the page.',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->firstItem();
                },
                'selectable' => false,
            ],
            'to' => [
                'type' => Type::int(),
                'description' => 'The number of the last item on the page.',
                'resolve' => function(LengthAwarePaginator $paginator) {
                    return $paginator->lastItem();
                },
                'selectable' => false,
            ]
        ];
    }
}