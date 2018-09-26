<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 12:42
 */

namespace Roelhem\GraphQL\Enums;


use Illuminate\Database\Eloquent\Builder;
use MabeEnum\Enum;
use Roelhem\GraphQL\Contracts\PaginatorContract;
use Roelhem\GraphQL\Paginators\CursorPaginator;
use Roelhem\GraphQL\Paginators\OffsetPaginator;
use Roelhem\GraphQL\Paginators\PagePaginator;

/**
 * Class PaginationType
 * @package Roelhem\GraphQL\Enums
 *
 * @method static PaginationType PAGE_BASED()
 * @method static PaginationType OFFSET_BASED()
 * @method static PaginationType CURSOR_BASED()
 */
class PaginationType extends Enum
{

    const PAGE_BASED = 'page';

    const OFFSET_BASED = 'offset';

    const CURSOR_BASED = 'cursor';

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param array $args
     * @return PaginatorContract
     */
    public function fromQuery($query, $args = []) {

        $limit = array_get($args, 'first', array_get($args,'limit', 15));
        $orderable = array_get($args,'orderBy');

        switch ($this->getValue()) {
            case self::PAGE_BASED:
                return new PagePaginator($query, $orderable, $limit, array_get($args, 'page', 1));
                break;
            case self::OFFSET_BASED:
                return new OffsetPaginator($query, $orderable, $limit, array_get($args, 'offset', 0));
                break;
            case self::CURSOR_BASED:
                return new CursorPaginator($query, $orderable, $limit, array_get($args, 'after', null));
                break;
        }
    }

}