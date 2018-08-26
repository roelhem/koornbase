<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 09:18
 */

namespace App\Http\GraphQL\Mutations\Crud\Create;


use App\Membership;
use App\Person;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class CreateMembershipMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createMembership',
        'description' => 'Creates a new membership entry for a person.'
    ];

    public function type()
    {
        return \GraphQL::type('Membership');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person to which the newly created Membership belongs.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons,id']
            ],
            'application' => [
                'description' => 'The date on which the person applied for the membership. This is also the start of the `NOVICE` phase of a Membership.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date','required_without_all:start,end']
            ],
            'start' => [
                'description' => 'The date on which the membership officially starts. (After this date, the `MEMBER` phase of a Membership begins).',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application']
            ],
            'end' => [
                'description' => 'The date on which the membership is ended.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application,start']
            ],
            'remarks' => [
                'description' => 'Some remarks regarding the Membership.',
                'type' => Type::string(),
                'rules' => ['nullable','string']
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return Membership
     * @throws ValidationError
     */
    public function resolve($root, $args)
    {
        $person_id = array_get($args, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);

        // Checking the dates
        $application = array_get($args, 'application');
        $start = array_get($args, 'start');
        $end = array_get($args, 'end');

        // Getting the upper and lower bounds of the memberships
        $lowerBound = $application ?? $start;
        $upperBound = $end;

        // Check if the other memberships of the person do not conflict the bounds of this membership.
        foreach($person->memberships as $otherMembership) {
            $otherLowerBound = $otherMembership->lower_bound;
            $otherUpperBound = $otherMembership->upper_bound;
            $belowOther = ($upperBound !== null && $otherLowerBound !== null && $upperBound < $otherLowerBound );
            $aboveOther = ($lowerBound !== null && $otherUpperBound !== null && $lowerBound > $otherUpperBound );
            if(!$belowOther && !$aboveOther) {
                throw new ValidationError("There is another membership of the target Person overlapping this new membership. This overlapping membership has the `ID` '{$otherMembership->id}'.");
            }
        }

        /** @var Membership $membership */
        $membership = $person->memberships()->create(array_except($args, ['person_id']));

        return $membership;
    }

}