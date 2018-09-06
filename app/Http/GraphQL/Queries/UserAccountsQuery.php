<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:15
 */

namespace App\Http\GraphQL\Queries;

use App\UserAccount;
use GraphQL\Type\Definition\Type;

class UserAccountsQuery extends ModelListQuery
{

    protected $typeName = 'UserAccount';

}