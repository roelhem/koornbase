<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:09
 */

namespace App\Http\GraphQL\Queries;


use App\GroupEmailAddress;

class GroupEmailAddressQuery extends ModelViewQuery
{

    protected $modelClass = GroupEmailAddress::class;

}