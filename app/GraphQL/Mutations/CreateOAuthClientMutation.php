<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 14:14
 */

namespace App\GraphQL\Mutations;


use App\Enums\OAuthClientType;
use GraphQL\Type\Definition\Type;
use Laravel\Passport\ClientRepository;
use Rebing\GraphQL\Support\Mutation;



class CreateOAuthClientMutation extends Mutation
{

    /**
     * @var ClientRepository
     */
    protected $clients;


    protected $attributes = [
        'name' => 'createOAuthClient',
        'description' => 'Creates a new OAuthClient for the current User.'
    ];

    /**
     * NewOAuthClientMutation constructor.
     * @param ClientRepository $clients
     * @param array $attributes
     */
    public function __construct(ClientRepository $clients, $attributes = [])
    {
        $this->clients = $clients;
        parent::__construct($attributes);
    }


    /** @inheritdoc */
    public function type()
    {
        return \GraphQL::type('OAuthClient');
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'type' => [
                'type' => \GraphQL::type('OAuthClientType'),
                'description' => 'The type of the new client. If this value is ommitted or set to `null`, the value will be set to `AUTH_CODE`.'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the new client. This will be displayed when the client requests an User authorize.',
                'rules' => ['required','string','max:255'],
            ],
            'redirect' => [
                'type' => Type::string(),
                'description' => 'The URL to which an User is redirected after it authorized the client.',
                'rules' => ['nullable','url']
            ],
            'user_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the User that should manage this client. If this value is ommitted or set to `null`, the `ID` of the current User will be used.',
                'rules' => ['nullable','exists:users,id']
            ],
        ];
    }

    /** @inheritdoc */
    public function resolve($root, $args)
    {
        /** @var OAuthClientType $type */
        $type = array_get($args, 'type', OAuthClientType::AUTH_CODE());
        return $type->create($args);
    }

}