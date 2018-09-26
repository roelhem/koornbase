<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 19:00
 */

namespace Roelhem\GraphQL\Paginators;


use Roelhem\GraphQL\Enums\PaginationType;

class PagePaginator extends OffsetPaginator
{
    protected $page = 1;

    /**
     * PagePaginator constructor.
     * @param $query
     * @param null $orderable
     * @param int $limit
     * @param int $page
     */
    public function __construct($query, $orderable = null, int $limit = 15, int $page = 1)
    {
        $offset = intval(($page - 1) * $limit);

        parent::__construct($query, $orderable, $limit, $offset);
    }

    /**
     * @return PaginationType
     */
    public function type()
    {
        return PaginationType::PAGE_BASED();
    }

    /**
     * @return int|null
     */
    public function currentPage()
    {
        return $this->page;
    }

    /**
     * @return bool
     */
    public function hasPages()
    {
        if ($this->page > 1) {
            return true;
        }

        if($this->perPage() > $this->total()) {
            return false;
        }

        return true;
    }
}