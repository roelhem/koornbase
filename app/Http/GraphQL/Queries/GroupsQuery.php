<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\Http\GraphQL\Queries;

use App\Group;
use GraphQL\Type\Definition\Type;

class GroupsQuery extends ModelListQuery
{
    protected $typeName = 'Group';

}