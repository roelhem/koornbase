<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\Http\GraphQL\Queries;

use App\Membership;
use GraphQL\Type\Definition\Type;

class MembershipsQuery extends ModelListQuery
{
    protected $typeName = 'Membership';

}