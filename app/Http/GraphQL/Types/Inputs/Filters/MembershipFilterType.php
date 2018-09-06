<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:22
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class MembershipFilterType extends FilterType
{

    public function filters()
    {
        return [
            'personId' => [
                'type' => Type::id(),
                'description' => 'Filters all the Memberships that belong to the person with the provided id.'
            ],

            'application' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that applied on the provided date.'
            ],

            'applicationBefore' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that applied before the provided date.'
            ],

            'applicationAfter' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that applied after the provided date.'
            ],


            'start' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that started on the provided date.'
            ],

            'startBefore' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that started before the provided date.'
            ],

            'startAfter' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that started after the provided date.'
            ],



            'end' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that ended on the provided date.'
            ],

            'endBefore' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that ended before the provided date.'
            ],

            'endAfter' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that ended after the provided date.'
            ],



            'status' => [
                'type' => \GraphQL::type('MembershipStatus'),
                'description' => 'Filters all the memberships that are currently in the provided MembershipStatus'
            ],

            'outsiderAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that had the outsider status at the given date.'
            ],

            'noviceAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that had the novice status at the given date.'
            ],

            'memberAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that had the member status at the given date.'
            ],

            'formerMemberAt' => [
                'type' => \GraphQL::type('Date'),
                'description' => 'Filters all the memberships that had the formerMember status at the given date.'
            ],
        ];
    }

}