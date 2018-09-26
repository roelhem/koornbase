<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 11:06
 */

namespace Roelhem\GraphQL\Paginators;


use Roelhem\GraphQL\Enums\PaginationType;

class OffsetPaginator extends AbstractPaginator
{
    /**
     * @var int
     */
    protected $offset = 0;

    public function __construct($query, $orderable = null, $limit = 15, $offset = 0)
    {
        $this->query = $query;
        $this->orderable = $orderable;
        $this->perPage = $limit;
        $this->offset = $offset;

        $query = $this->prepareNewQuery()->offset($offset);
        $this->initItemsFromQuery($query);
    }

    public function type()
    {
        return PaginationType::OFFSET_BASED();
    }

    /**
     * @return integer|null
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @return integer|null
     */
    public function startIndex()
    {
        if($this->count() > 0) {
            return $this->offset() + 1;
        }
        return null;
    }

    /**
     * @return integer|null
     */
    public function endIndex()
    {
        if($this->count() > 0) {
            return $this->offset() + $this->count();
        }
        return null;
    }

    /**
     * Returns the number of the current page.
     *
     * @return integer|null
     */
    public function currentPage()
    {
        return floor($this->offset() / $this->perPage()) + 1;
    }

    /**
     * Returns if the items in the list couldn't fit on just one page.
     *
     * @return boolean
     */
    public function hasPages()
    {
        if($this->offset() > 0) {
            return true;
        }

        if($this->total() < $this->perPage()) {
            return false;
        }

        return true;
    }

    /**
     * Returns if there are more pages for this list.
     *
     * @return bool
     */
    public function hasMorePages()
    {
        return $this->perPage() + $this->offset() < $this->total();
    }
}