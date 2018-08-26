<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 13:51
 */

namespace App\Http\GraphQL\Enums;

use libphonenumber\PhoneNumberFormat;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PhoneNumberFormatEnum extends GraphQLType
{

    protected $enumObject = true;

    protected $attributes = [
        'name' => 'PhoneNumberFormat',
        'description' => 'A way to present a phone number.',
        'values' => [
            'E164' => PhoneNumberFormat::E164,
            'INTERNATIONAL' => PhoneNumberFormat::INTERNATIONAL,
            'NATIONAL' => PhoneNumberFormat::NATIONAL,
            'RFC3966' => PhoneNumberFormat::RFC3966,
            'FOR_FIXED' => 'FOR_FIXED',
            'FOR_MOBILE' => 'FOR_MOBILE',
            'FOR_MOBILE_COMPACT' => 'FOR_MOBILE_COMPACT'
        ]
    ];

}