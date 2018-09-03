<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 08:09
 */

namespace App\Http\GraphQL\Queries;


use App\Logs\LogGraphQLOperation;

class LogGraphQLOperationQuery extends ModelViewQuery
{

    protected $modelClass = LogGraphQLOperation::class;
}