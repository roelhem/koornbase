<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:09
 */

namespace App\GraphQL\Queries;


use App\GroupEmailAddress;

class GroupEmailAddressQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'groupEmailAddress'
    ];

    protected $typeName = 'GroupEmailAddress';

    public function query($args, $selectFields)
    {
        return GroupEmailAddress::query();
    }

}