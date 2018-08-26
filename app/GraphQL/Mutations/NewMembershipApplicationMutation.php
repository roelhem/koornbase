<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 13:47
 */

namespace App\GraphQL\Mutations;


use App\Membership;
use App\Person;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class NewMembershipApplicationMutation extends Mutation
{

    protected $attributes = [
        'name' => 'newMembershipApplication',
        'description' => 'Adds a new `Membership` to a `Person` and initializes it such that the person will directly have the `NOVICE` membership status.'
    ];

    public function type()
    {
        return \GraphQL::type("Membership");
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the `Person` to which the new membership should belong.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons,id'],
            ],
            'date' => [
                'description' => 'The date on which the application should be registered. If this argument is ommitted, the current date will be used.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'Some optional remarks about the new membership.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
        ];
    }

    /**
     * @param $root
     * @param array $args
     * @throws ValidationError
     * @return Membership
     */
    public function resolve($root, $args)
    {
        // Get the person
        $person_id = array_get($args, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);

        // Get the ID.
        $date = \Parse::date(array_get($args, 'date'),true);

        // Check if there is no overlap with existing memberships of this person.
        foreach($person->memberships as $membership) {
            if($membership->upper_bound === null || $membership->upper_bound >= $date) {
                throw new ValidationError("There already exists a Membership of this Person that overlaps with the application date of the new membership.");
            }
        }

        // Creating the new membership
        /** @var Membership $membership */
        $membership = $person->memberships()->create([
            'application' => $date,
            'remarks' => array_get($args, 'remarks'),
        ]);

        // Return the new membership
        return $membership;
    }

}