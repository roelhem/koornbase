<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:57
 */

namespace App\GraphQL\Mutations\Crud\Create;

use App\Person;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;



class CreatePersonMutation extends Mutation
{

    protected $attributes = [
        'name' => 'CreatePerson'
    ];

    /** @inheritdoc */
    public function type()
    {
        return GraphQL::type('Person');
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'name_first' => [
                'name' => 'name_first',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255']
            ],
            'name_middle' => [
                'name' => 'name_middle',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:255'],
            ],
            'name_prefix' => [
                'name' => 'name_prefix',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_last' => [
                'name' => 'name_last',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255']
            ],
            'name_initials' => [
                'name' => 'name_initials',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'birth_date' => [
                'name' => 'birth_date',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','before:now']
            ],
            'remarks' => [
                'name' => 'remarks',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ]
        ];
    }

    /** Creates a new person */
    public function resolve($root, $args)
    {
        return Person::create($args);
    }

}