<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:09
 */

namespace App\Actions\Models\Update;


use App\Membership;
use App\Services\Validators\AfterValidation;
use Roelhem\GraphQL\Facades\GraphQL;

class UpdateMembershipAction extends AbstractUpdateAction
{

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that should be updated.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:memberships'],
            ],
            'application' => [
                'description' => 'The newly updated date on which the owner of this membership applied for the Koornbeurs Membership (and thus started its `NOVICE` phase of the membership.)',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'start' => [
                'description' => 'The newly updated date on which the membership was officially started.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application'],
            ],
            'end' => [
                'description' => 'The newly updated date on which the membership was ended.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application,start']
            ],
            'remarks' => [
                'description' => 'The new remarks regarding the membership.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string']
            ]
        ];
    }

    public function afterValidation($validator)
    {
        parent::afterValidation($validator);

        $after = new AfterValidation($validator);

        // Ensure the Chronology
        $id          = $after->getValue('id');
        /** @var Membership $membership */
        $membership  = Membership::findOrFail($id);
        $after->setDefaults($membership->only(['application','start','end']));
        $after->ensureChronology(['application','start','end']);


        // Check for overlap.
        $application = $after->getValue('application', $membership->application);
        $start       = $after->getValue('start', $membership->start);
        $end         = $after->getValue('end', $membership->end);

        // Getting the upper and lower bounds of the new membership
        $lowerBound = $application ?? $start;
        $upperBound = $end;

        // Check if none of the memberships of this person overlaps
        foreach ($membership->person->memberships as $otherMembership) {
            if($otherMembership->id !== $membership->id) {
                $otherLowerBound = $otherMembership->lower_bound;
                $otherUpperBound = $otherMembership->upper_bound;
                $belowOther = ($upperBound !== null && $otherLowerBound !== null && $upperBound < $otherLowerBound );
                $aboveOther = ($lowerBound !== null && $otherUpperBound !== null && $lowerBound > $otherUpperBound );
                if(!$belowOther && !$aboveOther) {
                    $firstTimeField = $application ? 'application' : $start ? 'start' : 'end';
                    $validator->errors()->add($firstTimeField, "Can't update this membership ({$membership->id}) for this person ({$membership->person->id}), because it overlaps with another membership ({$otherMembership->id}) of this person.");
                }
            }
        }


    }
}