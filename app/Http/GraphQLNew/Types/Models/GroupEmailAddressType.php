<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 15:33
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\GroupEmailAddress;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class GroupEmailAddressType extends ModelType
{

    public $modelClass = GroupEmailAddress::class;

    public $name = 'GroupEmailAddress';

    public $description = "The `GroupEmailAddress` models attaches E-mail addresses to `Group`s that can be used to
                           reach all the `Person`s in the group.";

    public function fields()
    {
        return [
            'group' => [
                'description' => 'The `Group` to which this `GroupEmailAddress` is attached.',
                'type' => GraphQL::type('Group!'),
            ],
            'emailAddress' => [
                'description' => 'The `EmailAddress` that was attached using this `GroupEmailAddress`.',
                'type' => GraphQL::type('EmailAddress'),
                'resolve' => function(GroupEmailAddress $groupEmailAddress) {
                    return $groupEmailAddress->getEmailAddress();
                }
            ]
        ];
    }

}