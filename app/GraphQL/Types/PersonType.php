<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 08:13
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\AvatarField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\RemarksField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\DeletedAtField;
use App\GraphQL\Fields\Stamps\DeletedByField;
use App\GraphQL\Fields\Stamps\DestroyerField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\Person;
use App\PersonEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

/**
 * Class PersonType
 * @package App\GraphQL\Types
 */
class PersonType extends GraphQLType
{

    /** @inheritdoc */
    protected $attributes = [
        'name' => 'Person',
        'description' => 'A person',
        'model' => Person::class
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
        $queryCallback = RbacQueryFilter::eagerLoadingContraintGraphQLClosure();
        $ownedByPerson = GraphQL::type('OwnedByPerson');

        return [
            'id' => IdField::class,
            $ownedByPerson->getField('owner_id'),
            $ownedByPerson->getField('owner'),
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of this Person that can be used for human display.',
                'selectable' => false,
                'always' => ['name_first','name_prefix','name_last']
            ],
            'name_short' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'A short name for this Person that can be used for human display.',
                'selectable' => false,
                'always' => ['name_first','name_nickname'],
            ],
            'name_full' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The full name (including the middle names) of this Person that can be used vor human display.',
                'selectable' => false,
                'always' => ['name_first','name_middle','name_prefix','name_last']
            ],
            'name_formal' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of this Person in a format suitable for formal conversation.',
                'selectable' => false,
                'always' => ['name_first','name_initials','name_prefix','name_last']
            ],
            'name_first' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The first name of the person.'
            ],
            'name_middle' => [
                'type' => Type::string(),
                'description' => 'The middle names or other less important names of this Person.'
            ],
            'name_prefix' => [
                'type' => Type::string(),
                'description' => 'The first part of the last name, often written in lowercase letters, that shouldn\'t be used when sorting on the last name. (Dutch: "tussenvoegsel")'
            ],
            'name_last' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The last name of this Person.'
            ],
            'name_initials' => [
                'type' => Type::string(),
                'description' => 'The initials of this Person.'
            ],
            'name_nickname' => [
                'type' => Type::string(),
                'description' => 'The nickname of this Person that is used in the Koornbeurs.'
            ],
            'birth_date' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The day on which this Person was born.'
            ],
            'age' => [
                'type' => Type::int(),
                'args' => [
                    'at' => [
                        'type' => GraphQL::type('Date')
                    ]
                ],
                'resolve' => function(Person $root, $args) {
                    return $root->getAge(array_get($args, 'at'));
                },
                'selectable' => false,
                'always' => ['birth_date']
            ],
            'birth_anniversary' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The day on which this person becomes a certain age. If no age was given, the first birthday in the future will be shown.',
                'args' => [
                    'age' => [
                        'type' => Type::int(),
                        'description' => 'The age that this Person become on the searched anniversary.'
                    ]
                ],
                'resolve' => function(Person $person, $args) {
                    $age = array_get($args, 'age', null);
                    return $person->getBirthDay($age);
                },
                'selectable' => false,
                'always' => ['birth_date']
            ],


            // RELATIONS

            'groups' => [
                'type' => Type::listOf(GraphQL::type('Group')),
                'description' => 'The groups of this person.',
                'query' => $queryCallback,
                'always' => ['id']
            ],

            'certificates' => [
                'type' => Type::listOf(GraphQL::type('Certificate')),
                'description' => 'All the certificates of this person.',
                'query' => $queryCallback
            ],

            'debtors' => [
                'type' => Type::listOf(GraphQL::type('Debtor')),
                'description' => 'All the debtors of this person.',
                'query' => $queryCallback
            ],

            'cards' => [
                'type' => Type::listOf(GraphQL::type('KoornbeursCard')),
                'description' => 'All the Koornbeurs-cards of this person.',
                'args' => [
                    'active' => [
                        'type' => Type::boolean(),
                        'description' => 'Only shows the active cards when set to `true`, and only the inactive cards when set to `false`.'
                    ]
                ],
                'query' => function($args, $query) {

                    $activeFilter = array_get($args, 'active');
                    if($activeFilter === true) {
                        $query->active();
                    } elseif($activeFilter === false) {
                        $query->inactive();
                    }

                    return RbacQueryFilter::eagerLoadingContraintGraphQLClosure()($args, $query);
                }
            ],

            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'All the users that are linked to this person.',
                'query' => $queryCallback
            ],

            'addresses' => [
                'type' => Type::listOf(GraphQL::type('PersonAddress')),
                'description' => 'All the addresses of this person',
                'query' => $queryCallback
            ],
            'address' => [
                'type' => GraphQL::type('PersonAddress'),
                'description' => 'The address that can be used as primary address for this Person.',
                'query' => $queryCallback
            ],

            'emailAddresses' => [
                'type' => Type::listOf(GraphQL::type('PersonEmailAddress')),
                'description' => 'All the e-mail addresses of this person',
                'query' => $queryCallback
            ],
            'emailAddress' => [
                'type' => GraphQL::type('PersonEmailAddress'),
                'description' => 'The e-mail address that can be used as a primary e-mail address for this Person.',
                'query' => $queryCallback
            ],

            'phoneNumbers' => [
                'type' => Type::listOf(GraphQL::type('PersonPhoneNumber')),
                'description' => 'All the phone numbers of this person',
                'query' => $queryCallback
            ],

            'phoneNumber' => [
                'type' => GraphQL::type('PersonPhoneNumber'),
                'description' => 'THe phone number that can be used as a primary phone number for this Person.',
                'query' => $queryCallback
            ],

            'memberships' => [
                'type' => Type::listOf(GraphQL::type('Membership')),
                'description' => 'All the memberships of this person',
                'query' => $queryCallback
            ],

            'avatar' => AvatarField::class,


            // CALCULATED

            'membership_status' => [
                'type' => GraphQL::type('MembershipStatus'),
                'description' => 'The current membership status of this person.',
                'selectable' => false,
            ],
            'membership_status_since' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the `membership_status` changed to the current value.',
                'selectable' => false,
            ],

            // OTHER FIELDS

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class,


            'viewable' => ViewableField::class
        ];
    }

    /** @inheritdoc */
    public function resolveAgeField(Person $root, $args) {
        $at = array_get($args,'at');
        return $root->getAge($at);
    }

}