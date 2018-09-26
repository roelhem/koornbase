<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 14:01
 */

namespace Roelhem\GraphQL\Paginators;


use Illuminate\Database\Eloquent\Builder;
use Roelhem\GraphQL\Contracts\OrderableContract;
use Roelhem\GraphQL\Enums\PaginationType;

class CursorPaginator extends AbstractPaginator {

    protected $cursor;

    /**
     * CursorPaginator constructor.
     * @param Builder $query
     * @param OrderableContract $orderable
     * @param int $limit
     * @param null $cursor
     */
    public function __construct($query, $orderable = null, $limit = 15, $cursor = null)
    {
        $this->query = $query;
        $this->perPage = $limit;
        $this->orderable = $orderable;
        $this->cursor = $cursor;

        $query = $this->prepareNewQuery();
        if($this->orderable instanceof OrderableContract) {
            $query = $this->orderable->applyAfterCursor($query, $this->cursor);
        } else {
            $query->where('id','>', $this->cursor);
        }
        $this->initItemsFromQuery($query);

    }

    public function type()
    {
        return PaginationType::CURSOR_BASED();
    }

    /**
     * @return integer|null
     */
    public function startIndex()
    {
        return null;
    }

    /**
     * @return integer|null
     */
    public function endIndex()
    {
        return null;
    }

    /**
     * Returns the number of the current page.
     *
     * @return integer|null
     */
    public function currentPage()
    {
        return null;
    }

    /**
     * Returns if the items in the list couldn't fit on just one page.
     *
     * @return boolean
     */
    public function hasPages()
    {
        if($this->cursor !== null) {
            return true;
        }

        if($this->total() <= $this->perPage) {
            return false;
        }

        return true;
    }
}