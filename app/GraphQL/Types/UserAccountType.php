<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:01
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\UserAccount;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class UserAccountType extends GraphQLType
{

    protected $attributes = [
        'name' => 'UserAccount',
        'model' => UserAccount::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => IdField::class,

            'user_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The `ID` of the User where this UserAccount belongs to.'
            ],
            'user' => [
                'type' => Type::nonNull(GraphQL::type('User')),
                'description' => 'The User where this UserAccount belongs to.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],
            'provider' => [
                'type' => GraphQL::type('OAuthProvider'),
                'description' => 'The OAuth2-provider of this UserAccount.'
            ],
            'token' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The OAuth2 access-token of this UserAccount.'
            ],
            'refresh_token' => [
                'type' => Type::string(),
                'description' => 'The OAuth2 refresh-token on this UserAccount.'
            ],
            'expires_in' => [
                'type' => Type::int(),
                'description' => 'Describes when the access-token will be expired.'
            ],
            'ref_id' => [
                'type' => Type::string(),
                'description' => 'The `ID` (or other reference type) of this UserAccount that is used by the OAuth-server.'
            ],
            'nickname' => [
                'type' => Type::string(),
                'description' => 'The nickname of the OAuth-account.'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The e-mail address of the OAuth-account.'
            ],
            'avatar' => [
                'type' => Type::string(),
                'description' => 'A link to the avatar image of this OAuth-account.'
            ],

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