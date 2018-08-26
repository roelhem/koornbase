<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 04:01
 */

namespace App\Http\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class CountryCodeField extends Field
{

    protected $attributes = [
        'name' => 'country_code',
        'description' => 'The two letter country code of the country where this object belongs to.'
    ];

    public function type()
    {
        return Type::nonNull(\GraphQL::type('CountryCode'));
    }

}