<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:51
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Relations\PersonField;
use App\GraphQL\Fields\Relations\PersonIdField;
use App\GraphQL\Fields\RemarksField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\Membership;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MembershipType extends GraphQLType
{

    protected $attributes = [
        'name' => 'Membership',
        'model' => Membership::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');

        $atArgs = [
            'at' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date for which you want to check the value. You can leave this value empty or set to `null` if you want to check the value for the current date.',
            ]
        ];

        return [
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'person_id' => PersonIdField::class,
            'person' => PersonField::class,

            'application' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the person applied for this membership. (And thus became a novice.)'
            ],
            'applied' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'If there was an application for this membership.',
                'args' => $atArgs,
                'selectable' => false,
                'always' => ['application'],
                'resolve' => function(Membership $membership, $args) {
                    return $membership->getApplied(array_get($args, 'at'));
                }
            ],

            'start' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the person became a full member for this membership.',
            ],
            'started' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'If the membership has started.',
                'args' => $atArgs,
                'selectable' => false,
                'always' => ['start'],
                'resolve' => function(Membership $membership, $args) {
                    return $membership->getStarted(array_get($args, 'at'));
                }
            ],

            'end' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the person stopped being a member.'
            ],
            'ended' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'If the membership has ended',
                'args' => $atArgs,
                'selectable' => false,
                'always' => ['end'],
                'resolve' => function(Membership $membership, $args) {
                    return $membership->getEnded(array_get($args, 'at'));
                }
            ],

            'status' => [
                'type' => GraphQL::type('MembershipStatus'),
                'description' => 'The current status of this membership.',
                'args' => $atArgs,
                'selectable' => false,
                'always' => ['application','start','end'],
                'resolve' => function(Membership $membership, $args) {
                    return $membership->getStatus(array_get($args, 'at'));
                }
            ],
            'status_since' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the membership status was changed to the status at the provided date.',
                'args' => $atArgs,
                'selectable' => false,
                'always' => ['application','start','end'],
                'resolve' => function(Membership $membership, $args) {
                    return $membership->getStatusSince(array_get($args, 'at'));
                }
            ],


            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,

            'viewable' => ViewableField::class
        ];
    }

}