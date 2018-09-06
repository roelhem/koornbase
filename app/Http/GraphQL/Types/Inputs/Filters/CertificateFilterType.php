<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:27
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class CertificateFilterType extends FilterType
{

    public function filters()
    {
        return [
            'isValid' => [
                'type' => Type::boolean(),
                'description' => 'Filters all the certificates that are valid if the value is true. If the value is false, only the invalid certificates are shown.'
            ],

            'validAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the certificates that are valid at the given date.',
            ],

            'invalidAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the certificates that are invalid at the given date.',
            ],

            'categoryId' => [
                'type' => Type::id(),
                'description' => 'Filters all the certificates that belong to the CertificateCategory with the given id.'
            ],

            'personId' => [
                'type' => Type::id(),
                'description' => 'Filters all the certificates that belong to the Person with the given id.'
            ],
        ];
    }

}