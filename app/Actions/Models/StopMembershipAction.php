<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 02:38
 */

namespace App\Actions\Models;


use App\Membership;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class StopMembershipAction extends AbstractModelAction
{
    protected $description = 'Sets a `Membership` that is in a `NOVICE` or `MEMBER` status to a membership in a `FORMER_MEMBER` status.';

    protected $type = 'Membership';

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that you want to set to the `FORMER_MEMBER` status.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:memberships'],
            ],
            'date' => [
                'description' => 'The date on which the `Membership` should be registered to change to the `FORMER_MEMBER` status. If this argument is omitted or set to `null`, the current date will be used.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'The new value of the remarks for this membership.',
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
        // Find the membership.
        $id = array_get($validArgs, 'id');
        /** @var Membership $membership */
        $membership = Membership::findOrFail($id);

        // Make sure that the membership has not already been stopped
        if($membership->end !== null) {
            throw new \Exception("This membership has already been stopped.");
        }

        // Get the date.
        $date = \Parse::date(array_get($validArgs, 'date'), true);

        // Check if the end date is after the application date and start-date.
        if($membership->application > $date) {
            throw new \Exception("The end-date has to be after the application-date.");
        }
        if($membership->start > $date) {
            throw new \Exception("The end-date has to be after the start-date.");
        }

        // Fill the membership with the new data and save it in the database.
        $membership->end = $date;
        if(array_has($validArgs,'remarks')) {
            $membership->remarks = array_get($validArgs, 'remarks');
        }
        $membership->saveOrFail();

        // Return the updated membership
        return $membership;
    }
}