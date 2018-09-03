<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 08:18
 */

namespace App\Services\Sorters;


use App\Enums\SortOrderDirection;
use Illuminate\Database\Eloquent\Builder;

class LogGraphQLOperationSorter extends Sorter
{

    protected $columns = [
        'id','schema','type','operation_name','requested_at','created_at','updated_at','client_id'
    ];

    /**
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortClient($query, $direction)
    {
        return $query->orderBy('client_id',$direction);
    }

}