<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 02:49
 */

namespace App\Actions\Models;


use App\Membership;
use App\Person;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class NewMembershipApplicationAction extends AbstractModelAction
{
    protected $description = 'Adds a new `Membership` to a `Person` and initializes it such that the person will directly have the `NOVICE` membership status.';

    protected $type = 'Membership';

    public function args()
    {
        return [
            'personId' => [
                'description' => 'The `ID` of the `Person` to which the new membership should belong.',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id'],
            ],
            'date' => [
                'description' => 'The date on which the application should be registered. If this argument is ommitted, the current date will be used.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'Some optional remarks about the new membership.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
        ];
    }

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return mixed
     * @throws
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        // Get the person
        $person_id = array_get($validArgs, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);

        // Get the ID.
        $date = \Parse::date(array_get($validArgs, 'date'),true);

        // Check if there is no overlap with existing memberships of this person.
        foreach($person->memberships as $membership) {
            if($membership->upper_bound === null || $membership->upper_bound >= $date) {
                throw new \Exception("There already exists a Membership of this Person that overlaps with the application date of the new membership.");
            }
        }

        // Creating the new membership
        /** @var Membership $membership */
        $membership = $person->memberships()->create([
            'application' => $date,
            'remarks' => array_get($validArgs, 'remarks'),
        ]);

        // Return the new membership
        return $membership;
    }
}