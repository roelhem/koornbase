<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:12
 */

namespace App\GraphQL\Queries;


use App\Membership;

class MembershipQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'membership'
    ];

    protected $typeName = 'Membership';

    public function query($args, $selectFields)
    {
        return Membership::query();
    }

}