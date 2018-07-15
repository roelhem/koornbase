<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 14:46
 */

namespace App\GraphQL\Queries;

use App\PersonPhoneNumber;
use GraphQL;
use GraphQL\Type\Definition\Type;

class PersonPhoneNumbersQuery extends ModelListQuery
{


    protected $attributes = [
        'name' => 'PersonPhoneNumbers query'
    ];

    protected $typeName = 'PersonPhoneNumber';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return PersonPhoneNumber::query();
    }

}