<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 02:43
 */

namespace App\Actions\Models;


use App\Membership;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class StartMembershipAction extends AbstractModelAction
{
    protected $description = 'Sets a `Membership` that is in a `NOVICE` status to a membership in a `MEMBER` status.';

    protected $type = 'Membership';

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that you want to set to the `MEMBER` status.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:memberships'],
            ],
            'date' => [
                'description' => 'The date on which the `Membership` should be registered to change to the `MEMBER` status. If this argument is omitted or set to `null`, the current date will be used.',
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

        // Check if the membership is in a `NOVICE` state.
        if($membership->start !== null || $membership->end !== null) {
            throw new \Exception("You can only start memberships that are in the `NOVICE` state. This means that the start and end date should not have been set.");
        }

        // Get the date.
        $date = \Parse::date(array_get($validArgs, 'date'), true);

        // Check if the start date after the application date.
        if($membership->application > $date) {
            throw new \Exception("The start-date has to be after the application date.");
        }

        // Fill the membership with the new data and save it in the database.
        $membership->start = $date;
        if(array_has($validArgs,'remarks')) {
            $membership->remarks = array_get($validArgs, 'remarks');
        }
        $membership->saveOrFail();

        // Return the updated membership
        return $membership;
    }
}