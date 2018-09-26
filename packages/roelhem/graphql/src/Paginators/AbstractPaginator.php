<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 18:05
 */

namespace Roelhem\GraphQL\Paginators;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Roelhem\GraphQL\Contracts\OrderableContract;
use Roelhem\GraphQL\Contracts\PaginatorContract;

abstract class AbstractPaginator implements PaginatorContract
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var integer
     */
    protected $total;

    /**
     * @var integer
     */
    protected $perPage;

    /**
     * @var OrderableContract|null
     */
    protected $orderable;

    /**
     * Clones the query-builder and applies the common SQL-rules.
     *
     * @return Builder $query
     */
    protected function prepareNewQuery()
    {
        $query = clone $this->query;
        $query->limit($this->perPage);
        if($this->orderable instanceof OrderableContract) {
            return $this->orderable->applyToQuery($query);
        } else {
            return $query;
        }
    }

    /**
     * Returns the collection of items from the query.
     *
     * @param Builder $query
     */
    protected function initItemsFromQuery($query)
    {
        $this->items = $query->get();
    }


    /**
     * Returns a collection of all the items on the current page.
     *
     * @return Collection
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Returns the first item of the page.
     *
     * @return mixed
     */
    public function startItem()
    {
        return $this->items()->first();
    }

    /**
     * Returns the last item of the page.
     *
     * @return mixed
     */
    public function endItem()
    {
        return $this->items()->last();
    }

    /**
     * Returns the total amount of items on the current page.
     *
     * @return integer|null
     */
    public function count()
    {
        return $this->items()->count();
    }

    /**
     * Returns the total number of items in the list.
     *
     * @return integer
     */
    public function total()
    {
        if($this->total === null) {
            $query = clone $this->query;
            $this->total = $query->count();
        }
        return $this->total;
    }

    /**
     * Returns the cursor of an item in this paginator.
     *
     * @param Model|array|mixed $item
     * @return string
     */
    public function getCursor($item) {
        if($this->orderable instanceof OrderableContract) {
            return $this->orderable->getCursor($item);
        } else {
            if(is_array($item)) {
                return array_get($item,'id');
            } else if(is_object($item)) {
                return object_get($item, 'id');
            } else {
                return strval($item);
            }
        }
    }

    /**
     * @return string|null
     */
    public function startCursor()
    {
        return $this->getCursor($this->startItem());
    }

    /**
     * @return string|null
     */
    public function endCursor()
    {
        return $this->getCursor($this->endItem());
    }

    /**
     * Returns the maximum amount of items on one page.
     *
     * @return integer
     */
    public function perPage()
    {
        return $this->perPage;
    }

    /**
     * Returns the number of the last page.
     *
     * @return integer|null
     */
    public function lastPage()
    {
        return floor($this->total() / $this->perPage()) + 1;
    }

    /**
     * Returns if there are more pages after the current one.
     *
     * @return boolean
     */
    public function hasMorePages()
    {
        return $this->count() < $this->perPage;
    }
}