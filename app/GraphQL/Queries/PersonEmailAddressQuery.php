<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:20
 */

namespace App\GraphQL\Queries;


use App\PersonEmailAddress;

class PersonEmailAddressQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'personEmailAddress'
    ];

    protected $typeName = 'PersonEmailAddress';

    public function query($args, $selectFields)
    {
        return PersonEmailAddress::query();
    }

}