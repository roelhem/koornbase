<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 15:06
 */

namespace App\GraphQL\Interfaces;

use App\PersonAddress;
use App\PersonPhoneNumber;
use GraphQL;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class BelongsToCountryInterface extends InterfaceType
{



    protected $attributes = [
        'name' => 'BelongsToCountry',
        'description' => 'A interface for objects that belong to a country.'
    ];


    /** @inheritdoc */
    public function fields()
    {
        return [
            'country_code' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The two letter country code of the country where this object belongs to.'
            ],
            'country' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The full name of the country where his object belongs to.'
            ]
        ];
    }

    /** @inheritdoc */
    public function resolveType($root) {
        if ($root instanceof PersonAddress) {
            return GraphQL::type('PersonAddress');
        } elseif ($root instanceof PersonPhoneNumber) {
            return GraphQL::type('PersonPhoneNumber');
        }
    }




}