<?php

namespace App\GraphQL\Queries;

use App\Person;
use App\PersonEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class PersonsQuery extends ModelListQuery
{

    protected $modelClass = Person::class;


    protected function filterArgs()
    {

        return array_merge(parent::filterArgs(), [


            'membershipStatus' => [
                'type' => GraphQL::type('MembershipStatus'),
                'description' => 'Filters the Persons that currently have the provided membership status.'
            ],

            'birthDateBefore' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters the persons who were born before the provided date.',
            ],

            'birthDateAfter' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters the persons who were born after the provided date.',
            ]

        ]);
    }

}