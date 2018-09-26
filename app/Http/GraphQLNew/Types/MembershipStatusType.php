<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 10:45
 */

namespace App\Http\GraphQLNew\Types;


use App\Enums\MembershipStatus;
use App\Helpers\MembershipStatusChange;
use App\Membership;
use App\Person;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class MembershipStatusType extends ObjectType
{

    public $name = 'MembershipStatus';

    public $description = 'This type represents a certains status at which a `Person` can be regarding it\'s membership
                           at O.J.V. de Koornbeurs.';

    public function fields() {
        return [
            'type' => [
                'description' => 'The type of this `MembershipStatus`.',
                'type' => GraphQL::type('MembershipStatusType!'),
                'resolve' => function($status) {
                    if($status instanceof MembershipStatusChange) {
                        return $status->status;
                    }
                    return MembershipStatus::OUTSIDER();
                }
            ],
            'since' => [
                'description' => 'The first `Date` on which the status of this `Person` changed to this 
                                  `MembershipStatus`.',
                'type' => GraphQL::type('Date'),
                'resolve' => function($status) {
                    if($status instanceof MembershipStatusChange) {
                        return $status->date;
                    }
                    return null;
                }
            ],
            'person' => [
                'description' => 'The `Person` to which this `MembershipStatus` applies',
                'type' => GraphQL::type('Person!'),
                'resolve' => function($status) {
                    if($status instanceof MembershipStatusChange) {
                        return $status->person;
                    } elseif($status instanceof Person) {
                        return $status;
                    } else {
                        return $status->person;
                    }
                }
            ],
            'membership' => [
                'description' => 'The `Membership` that caused the change to this `MembershipStatus`.',
                'type' => GraphQL::type('Membership'),
                'resolve' => function($status) {
                    if($status instanceof Membership) {
                        return $status;
                    } elseif($status instanceof MembershipStatusChange) {
                        return $status->membership;
                    } else {
                        return null;
                    }
                }
            ]
        ];
    }
}