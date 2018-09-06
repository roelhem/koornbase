<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\Http\GraphQL\Queries;

use App\GroupCategory;
use GraphQL\Type\Definition\Type;

class GroupCategoriesQuery extends ModelListQuery
{

    protected $typeName = 'GroupCategory';

}