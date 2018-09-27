<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 15:08
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\Group;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class GroupType extends ModelType
{

    public $modelClass = Group::class;

    public $name = 'Group';

    public $description = 'The `Group`-type models the grouping of persons. Besides making searching trough the 
                           `Person`-typed models clearer, `Group`s also manages settings for the `Persons` in it\'s
                           group.';

    public function fields()
    {
        return [
            'memberName' => [
                'description' => 'Gives how you should call a `Person` that is a member of this `Group`.',
                'type' => GraphQL::type('String'),
                'importance' => 180,
            ],
            'category' => [
                'description' => 'Gives the `GroupCategory` where this `Group` belongs to.',
                'type' => GraphQL::type('GroupCategory'),
                'importance' => 210,
            ],
            'emailAddresses' => [
                'description' => 'Gives a list of all the `GroupEmailAddresses` that are attached to this `Group`.',
                'type' => GraphQL::type('[GroupEmailAddress]'),
                'importance' => -1,
            ]
        ];
    }

    public function interfaces()
    {
        return array_merge([GraphQL::type('Category')], parent::interfaces());
    }

    public function connections()
    {
        return [
            'persons' => [
                'to' => 'Person',
                'description' => 'A list of all the persons in this group.'
            ]
        ];
    }

    protected function orderables()
    {
        return array_merge(parent::orderables(), [
            'name' => [
                'description' => 'Orders the groups by the name.',
                'query' => ['name','name_short','id'],
                'cursorPattern' => ['name' => 'a','name_short' => 'a','id' => 'n'],
            ],
            'shortName' => [
                'description' => 'Orders the groups by the short name.',
                'query' => ['name_short','name','id'],
                'cursorPattern' => ['name_short' => 'a','name' => 'a','id' => 'n'],
            ],
            'category' => [
                'description' => 'Orders the groups by the `GroupCategory` where they belong to.',
                'query' => ['category_id','name','name_short','id'],
                'cursorPattern' => ['category_id' => 'n','name' => 'a','name_short' => 'a','id' => 'n'],
            ]
        ]);
    }

}