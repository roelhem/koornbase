<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:57
 */

namespace App\Http\GraphQL\Mutations\Crud\Create;

use App\Person;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;



class CreatePersonMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createPerson',
        'description' => 'Adds a new `Person` to the database.'
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
                'description' => 'The first name (Dutch:"voornaam") of the new `Person`.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255']
            ],
            'name_middle' => [
                'description' => 'The additional names (Dutch:"tussennamen") of the new `Person`.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:255'],
            ],
            'name_prefix' => [
                'description' => 'The prefix of the last name (Dutch:"tussenvoegsel") of the new `Person`.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_last' => [
                'description' => 'The last name (Dutch:"achternaam") of the new `Person`. This should NOT include the prefix that is common in Dutch last names (like "van", "de", "der", etc. )',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255']
            ],
            'name_initials' => [
                'description' => 'The initials (Dutch:"voorletters") of the new `Person`.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_nickname' => [
                'description' => 'The nickname (Dutch:"bijnaam")of the new `Person`. This should be the nickname of this `Person` that is commonly used by the members of the Koornbeurs.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'birth_date' => [
                'description' => 'The date on which the new `Person` was born.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','before:now']
            ],
            'remarks' => [
                'description' => 'Some extra remarks about this `Person`.',
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