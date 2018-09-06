<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:22
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class KoornbeursCardFilterType extends FilterType
{

    public function filters()
    {
        return [
            'version' => [
                'type' => Type::string(),
                'description' => 'Filters the KoornbeursCards with the (identical) provided version.'
            ],

            'active' => [
                'type' => Type::boolean(),
                'description' => 'Filters the KoornbeursCards that are active if the value is `true`, or inactive if the value is `false`.',
            ],

            'activeAt' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are active at the given moment.',
            ],

            'inactiveAt' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are inactive at the given moment.'
            ],

            'activatedBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCarts that were activated before the provided moment.'
            ],

            'activatedAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were activated after the provided moment.'
            ],

            'deactivatedBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated before the provided moment.'
            ],

            'deactivatedAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated after the provided moment.'
            ],
        ];
    }

}