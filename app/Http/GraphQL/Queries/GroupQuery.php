<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:11
 */

namespace App\Http\GraphQL\Queries;


use App\Group;

class GroupQuery extends ModelViewQuery
{

    protected  $modelClass = Group::class;

}