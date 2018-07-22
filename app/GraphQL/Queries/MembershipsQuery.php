<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:14
 */

namespace App\GraphQL\Queries;

use App\Membership;
use GraphQL\Type\Definition\Type;

class MembershipsQuery extends ModelListQuery
{
    protected $modelClass = Membership::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [


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

        ]);
    }

}