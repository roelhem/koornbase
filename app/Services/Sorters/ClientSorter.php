<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-07-18
 * Time: 16:51
 */

namespace App\Services\Sorters;



use App\Enums\SortOrderDirection;
use Illuminate\Database\Eloquent\Builder;

class ClientSorter extends Sorter
{

    protected $columns = [
        'id',
        'name',
        'revoked',
        'created_at',
        'updated_at'
    ];

    /**
     * Sorts by the type of client
     *
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortType($query, $direction) {
        return $query
            ->orderBy('personal_access_client', $direction->invert())
            ->orderBy('password_client', $direction->invert())
            ->orderBy('redirect', $direction);
    }

}