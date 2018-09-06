<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 08:11
 */

namespace App\Http\GraphQL\Queries;


use App\Logs\LogGraphQLOperation;
use GraphQL\Type\Definition\Type;

class LogGraphQLOperationsQuery extends ModelListQuery
{

    protected $typeName = 'LogGraphQLOperation';

}