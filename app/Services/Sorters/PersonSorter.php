<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 04:22
 */

namespace App\Services\Sorters;


use App\Enums\SortOrderDirection;
use Illuminate\Database\Eloquent\Builder;

class PersonSorter extends Sorter
{

    protected $columns = [
        'id',
        'name_first',
        'name_last',
        'name_middle',
        'name_prefix',
        'name_initials',
        'name_nickname',
        'birth_date',
        'created_at',
        'updated_at'
    ];

    /**
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortName($query, $direction) {
        return $query
            ->orderBy('name_first',$direction)
            ->orderBy('name_prefix',$direction)
            ->orderBy('name_last',$direction);
    }

    /**
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortNameFull($query, $direction) {
        return $query
            ->orderBy('name_first',$direction)
            ->orderBy('name_middle',$direction)
            ->orderBy('name_prefix',$direction)
            ->orderBy('name_last',$direction);
    }

    /**
     * @param Builder $query
     * @param SortOrderDirection $direction
     * @return Builder
     */
    public function sortMembershipStatus($query, $direction) {
        return $query->leftJoinSub("
                        SELECT DISTINCT ON(person_id) person_id, status, date
                        FROM membership_status_changes
                        WHERE date <= NOW()
                        ORDER BY person_id, date DESC
                    ", 'last_membership_status_sorting',
            'last_membership_status_sorting.person_id', '=' ,'persons.id')
            ->orderBy('last_membership_status_sorting.status', $direction)
            ->orderBy('last_membership_status_sorting.date', $direction);
    }

}