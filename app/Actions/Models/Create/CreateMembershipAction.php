<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:44
 */

namespace App\Actions\Models\Create;

use App\Person;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateMembershipAction extends AbstractCreateAction
{

    protected $description = 'Creates a new membership entry for a person.';

    /**
     * @param \Illuminate\Validation\Validator $validator
     */
    public function afterValidation($validator)
    {
        $data = $validator->getData();

        // Get the person instance to who the membership will be created.
        $person_id = array_get($data, 'person_id');
        $person = Person::find($person_id);
        if(!($person instanceof Person)) {
            $validator->errors()->add('person_id','Can\'t find a person with person_id: '.$person_id.'.');
            return;
        }

        // Get the dates from the input.
        $application = \Parse::try()->date(array_get($data, 'application'));
        $start       = \Parse::try()->date(array_get($data, 'start'));
        $end         = \Parse::try()->date(array_get($data, 'end'));

        // Getting the upper and lower bounds of the new membership
        $lowerBound = $application ?? $start;
        $upperBound = $end;

        // Check if none of the memberships of this person overlaps
        foreach ($person->memberships as $otherMembership) {
            $otherLowerBound = $otherMembership->lower_bound;
            $otherUpperBound = $otherMembership->upper_bound;
            $belowOther = ($upperBound !== null && $otherLowerBound !== null && $upperBound < $otherLowerBound );
            $aboveOther = ($lowerBound !== null && $otherUpperBound !== null && $lowerBound > $otherUpperBound );
            if(!$belowOther && !$aboveOther) {
                $firstTimeField = $application ? 'application' : $start ? 'start' : 'end';
                $validator->errors()->add($firstTimeField, "Can't create this membership for this person ({$person->id}), because it overlaps with another membership ({$otherMembership->id}) of this person.");
            }
        }

    }

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'personId' => [
                'description' => 'The `ID` of the Person to which the newly created Membership belongs.',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id']
            ],
            'application' => [
                'description' => 'The date on which the person applied for the membership. This is also the start of the `NOVICE` phase of a Membership.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','required_without_all:start,end']
            ],
            'start' => [
                'description' => 'The date on which the membership officially starts. (After this date, the `MEMBER` phase of a Membership begins).',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application']
            ],
            'end' => [
                'description' => 'The date on which the membership is ended.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application,start']
            ],
            'remarks' => [
                'description' => 'Some remarks regarding the Membership.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string']
            ],
        ];
    }
}