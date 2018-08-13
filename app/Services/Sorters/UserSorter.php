<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:07
 */

namespace App\Services\Sorters;


use App\Enums\SortOrderDirection;
use Illuminate\Database\Eloquent\Builder;

class UserSorter extends Sorter
{

    protected $columns = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];

    /**
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortPerson($query, $direction) {

        $query->leftJoin('persons','persons.id','=','users.person_id')
            ->orderBy('persons.name_first',$direction)
            ->orderBy('persons.name_prefix',$direction)
            ->orderBy('persons.name_last',$direction);

    }

}