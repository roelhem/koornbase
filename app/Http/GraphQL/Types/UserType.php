<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:47
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\AvatarField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class UserType extends GraphQLType
{

    protected $attributes = [
        'name' => 'User',
        'model' => User::class
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

        return [
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'person_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the Person that was associated with this User (or `null` if this account isn\'t associated with any Person.)'
            ],
            'person' => [
                'type' => GraphQL::type('Person'),
                'description' => 'The Person associated with this User.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The username of this User.'
            ],

            'name_display' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Returns a string that can be used to refer to this user.',
                'selectable' => false,
                'always' => ['name']
            ],

            'name_short' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'A shorter version of the name_display field.',
                'selectable' => false,
                'always' => ['name']
            ],

            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The e-mailaddress of this User'
            ],

            'accounts' => [
                'type' => Type::listOf(GraphQL::type('UserAccount')),
                'description' => 'An OAuth account from an external server.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],

            'facebookAccount' => [
                'type' => GraphQL::type('UserAccount'),
                'description' => 'The Facebook-account of this user.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],

            'githubAccount' => [
                'type' => GraphQL::type('UserAccount'),
                'description' => 'The GitHub-account of this user.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],

            'googleAccount' => [
                'type' => GraphQL::type('UserAccount'),
                'description' => 'The Google-account of this user.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],

            'twitterAccount' => [
                'type' => GraphQL::type('UserAccount'),
                'description' => 'The Twitter-account of this user.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],

            'avatar' => AvatarField::class,

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