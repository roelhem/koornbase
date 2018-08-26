<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 14:31
 */

namespace App\GraphQL\Mutations;


use App\Membership;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class StopMembershipMutation extends Mutation
{

    protected $attributes = [
        'name' => 'stopMembership',
        'description' => 'Sets a `Membership` that is in a `NOVICE` or `MEMBER` status to a membership in a `FORMER_MEMBER` status.'
    ];

    public function type()
    {
        return \GraphQL::type('Membership');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that you want to set to the `FORMER_MEMBER` status.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:memberships'],
            ],
            'date' => [
                'description' => 'The date on which the `Membership` should be registered to change to the `FORMER_MEMBER` status. If this argument is omitted or set to `null`, the current date will be used.',
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

        // Make sure that the membership has not already been stopped
        if($membership->end !== null) {
            throw new ValidationError("This membership has already been stopped.");
        }

        // Get the date.
        $date = \Parse::date(array_get($args, 'date'), true);

        // Check if the end date is after the application date and start-date.
        if($membership->application > $date) {
            throw new ValidationError("The end-date has to be after the application-date.");
        }
        if($membership->start > $date) {
            throw new ValidationError("The end-date has to be after the start-date.");
        }

        // Fill the membership with the new data and save it in the database.
        $membership->end = $date;
        if(array_has($args,'remarks')) {
            $membership->remarks = array_get($args, 'remarks');
        }
        $membership->saveOrFail();

        // Return the updated membership
        return $membership;
    }

}