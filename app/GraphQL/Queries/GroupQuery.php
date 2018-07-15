<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:11
 */

namespace App\GraphQL\Queries;


use App\Group;

class GroupQuery extends ModelViewQuery
{


    protected $attributes = [
        'name' => 'groupCategory'
    ];

    protected $typeName = 'GroupCategory';
    protected $slug = true;

    public function query($args, $selectFields)
    {
        return Group::query();
    }

}