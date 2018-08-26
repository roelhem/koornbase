<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:12
 */

namespace App\Http\GraphQL\Queries;


use App\Membership;

class MembershipQuery extends ModelViewQuery
{

    protected $modelClass = Membership::class;

}