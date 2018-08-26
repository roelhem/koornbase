<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 13:51
 */

namespace App\Http\GraphQL\Enums;

use libphonenumber\PhoneNumberType;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PhoneNumberTypeEnum extends GraphQLType
{

    protected $enumObject = true;

    public function attributes()
    {
        return [
            'name' => 'PhoneNumberType',
            'description' => 'The type of phone number.',
            'values' => array_flip(PhoneNumberType::values()),
        ];
    }

}