<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 12:31
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class PersonFilterType extends FilterType
{

    public function filters()
    {
        return [
            'membershipStatus' => [
                'type' => \GraphQL::type('MembershipStatus'),
                'description' => 'Filters the Persons that currently have the provided membership status.'
            ],

            'anyMembershipStatus' => [
                'type' => Type::listOf(\GraphQL::type('MembershipStatus')),
                'description' => 'Filters the Persons that have one of the provided membership statuses.'
            ],

            'birthDateBefore' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters the persons who were born before the provided date.',
            ],

            'birthDateAfter' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters the persons who were born after the provided date.',
            ],

            'inAnyGroup' => [
                'type' => Type::listOf(Type::id()),
                'description' => 'Filters the persons that are in at least one of the provided groups.'
            ],

            'notInGroup' => [
                'type' => Type::id(),
                'description' => 'Filters the persons that are not in the group with the provided `ID`.'
            ]
        ];
    }

}