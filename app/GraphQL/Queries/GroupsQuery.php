<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\Group;

class GroupsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'groups'
    ];

    protected $typeName = 'Group';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return Group::query();
    }

}