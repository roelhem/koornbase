<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 04:22
 */

namespace App\Services\Sorters;


use Illuminate\Database\Query\Builder;

class PersonSorter extends Sorter
{

    public function sortNameFirst($builder, $desc = false) {
        return $builder
            ->orderBy('name_first', $desc ? 'desc' : 'asc')
            ->orderBy('name_middle', $desc ? 'desc' : 'asc');
    }

    public function sortNameLast($builder, $desc = false) {
        return $builder
            ->orderBy('name_last', $desc ? 'desc' : 'asc')
            ->orderBy('name_prefix', $desc ? 'desc' : 'asc');
    }

    public function sortId($builder, $desc = false) {
        return $builder->orderBy('id', $desc ? 'desc' : 'asc');
    }

    public function sortBirthDate($builder, $desc = false) {
        return $builder->orderBy('birth_date', $desc ? 'desc' : 'asc');
    }

    public function sortNameNickname($builder, $desc = false) {
        return $this->sortNameFirst($builder->orderBy('name_nickname', $desc ? 'desc' : 'asc'), $desc);
    }

    public function sortMembershipStatus($builder, $desc = false) {
        return $builder->leftJoinSub("
                        SELECT DISTINCT ON(person_id) person_id, status, date
                        FROM membership_status_changes
                        WHERE date <= NOW()
                        ORDER BY person_id, date DESC
                    ", 'last_membership_status_sorting',
            'last_membership_status_sorting.person_id', '=' ,'persons.id')
            ->orderBy('last_membership_status_sorting.status', $desc ? 'desc' : 'asc')
            ->orderBy('last_membership_status_sorting.date', $desc ? 'desc' : 'asc');
    }

}