<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\GroupEmailAddress;

class GroupEmailAddressesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'group_email_addresses'
    ];

    protected $typeName = 'GroupEmailAddress';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return GroupEmailAddress::query();
    }

}