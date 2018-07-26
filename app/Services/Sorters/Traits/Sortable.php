<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 21:13
 */

namespace App\Services\Sorters\Traits;


use App\Enums\SortOrderDirection;
use App\Services\Sorters\Sorter;
use App\Services\Sorters\SorterRepository;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{

    protected $sorter;

    /**
     * Returns the sorter that belongs to this sortable model.
     *
     * @return Sorter|null
     */
    protected function sorter()
    {
        if($this->sorter === false) {
            return null;
        }

        if($this->sorter === null) {
            /** @var SorterRepository $sorterRepository */
            $sorterRepository = app(SorterRepository::class);
            $this->sorter = $sorterRepository->getSorter($this);
        }

        return $this->sorter;
    }

    /**
     * Scope that orders the query.
     *
     * @param Builder $query
     * @param string $sortName
     * @param SortOrderDirection|string $direction
     * @return Builder
     */
    public function scopeSortBy($query, $sortName, $direction = SortOrderDirection::ASC)
    {
        $sorter = $this->sorter();
        if($sorter === null) {
            return $query;
        } else {
            return $this->sorter()->add($query, $sortName, $direction);
        }
    }

    /**
     * Scope that orders the query based on the provided array.
     *
     * @param Builder $query
     * @param array $sortList
     * @return Builder
     */
    public function scopeSortByList($query, $sortList)
    {
        $sorter = $this->sorter();
        if($sorter === null) {
            return $query;
        } else {
            return $this->sorter()->addList($query, $sortList);
        }
    }

}