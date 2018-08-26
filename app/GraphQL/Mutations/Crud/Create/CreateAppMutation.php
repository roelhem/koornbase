<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 22-07-18
 * Time: 15:25
 */

namespace App\GraphQL\Mutations\Crud\Create;


use App\OAuth\App;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateAppMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createApp',
        'description' => 'Creates a new App.'
    ];

    public function type()
    {
        return \GraphQL::type('App');
    }

    public function args()
    {
        return [
            'name' => [
                'description' => 'The name of the App.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255'],
            ],
            'name_short' => [
                'description' => 'A shorter name to describe the App.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63']
            ],
            'description' => [
                'description' => 'A long text description of what the App does.',
                'type' => Type::string(),
                'rules' => ['nullable','string']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return App::create($args);
    }

}