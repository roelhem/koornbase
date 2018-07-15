<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\GraphQL\Queries;

use App\Membership;

class MembershipsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'memberships'
    ];

    protected $typeName = 'Membership';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return Membership::query();
    }

}