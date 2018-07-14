<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 08:13
 */

namespace App\GraphQL\Types;

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
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

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

        $ownedByPerson = GraphQL::type('OwnedByPerson');

        return [
            GraphQL::type('Model')->getField('id'),
            $ownedByPerson->getField('owner_id'),
            $ownedByPerson->getField('owner'),
            'name' => [
                'type' => Type::nonNull(Type::string())
            ],
            'name_short' => [
                'type' => Type::nonNull(Type::string())
            ],
            'name_full' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'name_formal' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'name_first' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The first name of the person.'
            ],
            'name_middle' => [
                'type' => Type::string()
            ],
            'name_prefix' => [
                'type' => Type::string()
            ],
            'name_last' => [
                'type' => Type::nonNull(Type::string())
            ],
            'name_initials' => [
                'type' => Type::string()
            ],
            'name_nickname' => [
                'type' => Type::string()
            ],
            'birth_date' => [
                'type' => GraphQL::type('Date')
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
                }
            ],
            'remarks' => [
                'type' => Type::string()
            ],


            // CERTIFICATES

            'certificates' => [
                'type' => Type::listOf(GraphQL::type('Certificate')),
                'description' => 'All the certificates of this person.'
            ],

            'debtors' => [
                'type' => Type::listOf(GraphQL::type('Debtor')),
                'description' => 'All the debtors of this person.'
            ],

            'cards' => [
                'type' => Type::listOf(GraphQL::type('KoornbeursCard')),
                'description' => 'All the Koornbeurs-cards of this person.'
            ],

            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'All the users that are linked to this person.'
            ],

            'addresses' => [
                'type' => Type::listOf(GraphQL::type('PersonAddress')),
                'description' => 'All the addresses of this person'
            ],

            'emailAddresses' => [
                'type' => Type::listOf(GraphQL::type('PersonEmailAddress')),
                'description' => 'All the e-mail addresses of this person'
            ],

            'phoneNumbers' => [
                'type' => Type::listOf(GraphQL::type('PersonPhoneNumber')),
                'description' => 'All the phone numbers of this person'
            ],

            'memberships' => [
                'type' => Type::listOf(GraphQL::type('Membership')),
                'description' => 'All the memberships of this person'
            ],

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class
        ];
    }

    /** @inheritdoc */
    public function resolveAgeField(Person $root, $args) {
        $at = array_get($args,'at');
        return $root->getAge($at);
    }

}