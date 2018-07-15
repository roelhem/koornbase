<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\GraphQL\Queries;

use App\PersonEmailAddress;

class PersonEmailAddressesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'person_email_addresses'
    ];

    protected $typeName = 'PersonEmailAddress';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return PersonEmailAddress::query();
    }

}