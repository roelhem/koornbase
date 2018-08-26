<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 09:07
 */

namespace App\GraphQL\Mutations\Crud;


use App\Membership;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateMembershipMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateMembership',
        'description' => 'Changes the dates and remarks of a membership entry.'
    ];

    public function type()
    {
        return \GraphQL::type("Membership");
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that should be updated.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:memberships'],
            ],
            'application' => [
                'description' => 'The newly updated date on which the owner of this membership applied for the Koornbeurs Membership (and thus started its `NOVICE` phase of the membership.)',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'start' => [
                'description' => 'The newly updated date on which the membership was officially started.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application'],
            ],
            'end' => [
                'description' => 'The newly updated date on which the membership was ended.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application,start']
            ],
            'remarks' => [
                'description' => 'The new remarks regarding the membership.',
                'type' => Type::string(),
                'rules' => ['nullable','string']
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return Membership
     * @throws \Throwable
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var Membership $membership */
        $membership = Membership::findOrFail($id);

        // Checking the dates
        $application = array_get($args, 'application', $membership->application);
        $start = array_get($args, 'start', $membership->start);
        $end = array_get($args, 'end', $membership->end);

        // Check if there is at least one date filled in
        if($application === null && $start === null && $end === null) {
            throw new ValidationError("There has to be at least one date in a Membership.");
        }

        // Getting the upper and lower bounds of the memberships
        $lowerBound = $application ?? $start;
        $upperBound = $end;

        // Check if the other memberships of the person do not conflict the bounds of this membership.
        $person = $membership->person;
        foreach($person->memberships as $otherMembership) {
            if($membership->id !== $otherMembership->id) {
                $otherLowerBound = $otherMembership->lower_bound;
                $otherUpperBound = $otherMembership->upper_bound;
                $belowOther = ($upperBound !== null && $otherLowerBound !== null && $upperBound < $otherLowerBound );
                $aboveOther = ($lowerBound !== null && $otherUpperBound !== null && $lowerBound > $otherUpperBound );
                if(!$belowOther && !$aboveOther) {
                    throw new ValidationError("There is another membership of the target Person that overlaps with the new values. This overlapping membership has the `ID` '{$otherMembership->id}'.");
                }
            }
        }

        $membership->fill(array_except($args, ['id']));
        $membership->saveOrFail();

        return $membership;
    }

}