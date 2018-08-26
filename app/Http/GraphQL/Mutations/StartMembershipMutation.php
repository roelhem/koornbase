<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 14:15
 */

namespace App\Http\GraphQL\Mutations;


use App\Membership;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class StartMembershipMutation extends Mutation
{

    protected $attributes = [
        'name' => 'startMembership',
        'description' => 'Sets a `Membership` that is in a `NOVICE` status to a membership in a `MEMBER` status.'
    ];

    public function type()
    {
        return \GraphQL::type('Membership');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that you want to set to the `MEMBER` status.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:memberships'],
            ],
            'date' => [
                'description' => 'The date on which the `Membership` should be registered to change to the `MEMBER` status. If this argument is omitted or set to `null`, the current date will be used.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'The new value of the remarks for this membership.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return Membership
     * @throws
     */
    public function resolve($root, $args)
    {
        // Find the membership.
        $id = array_get($args, 'id');
        /** @var Membership $membership */
        $membership = Membership::findOrFail($id);

        // Check if the membership is in a `NOVICE` state.
        if($membership->start !== null || $membership->end !== null) {
            throw new ValidationError("You can only start memberships that are in the `NOVICE` state. This means that the start and end date should not have been set.");
        }

        // Get the date.
        $date = \Parse::date(array_get($args, 'date'), true);

        // Check if the start date after the application date.
        if($membership->application > $date) {
            throw new ValidationError("The start-date has to be after the application date.");
        }

        // Fill the membership with the new data and save it in the database.
        $membership->start = $date;
        if(array_has($args,'remarks')) {
            $membership->remarks = array_get($args, 'remarks');
        }
        $membership->saveOrFail();

        // Return the updated membership
        return $membership;
    }

}