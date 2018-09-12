<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 06:19
 */

namespace App\Http\GraphQL\Mutations\Crud\Update;

use App\Person;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UpdatePersonMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updatePerson',
        'description' => 'Changes the values of the properties of a specific `Person`.'
    ];

    public function type()
    {
        return GraphQL::type('Person');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required']
            ],
            'name_first' => [
                'name' => 'name_first',
                'type' => Type::string(),
                'rules' => ['sometimes','required','string','max:255']
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
                'type' => Type::string(),
                'rules' => ['sometimes','required','string','max:255']
            ],
            'name_initials' => [
                'name' => 'name_initials',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_nickname' => [
                'type' => Type::string(),
                'rules' => ['nullable','string','max:255'],
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

    /**
     * Updates the person.
     *
     * @param mixed $root
     * @param array $args
     * @return Person
     * @throws
     */
    public function resolve($root, $args)
    {
        $id = array_get($args,'id');
        if($id === null) {
            return null;
        }
        /** @var Person $person */
        $person = Person::findOrFail($id);

        $person->fill($args);
        $person->saveOrFail();
        return $person;
    }

}