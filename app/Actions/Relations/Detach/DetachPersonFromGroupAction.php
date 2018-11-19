<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 19:34
 */

namespace App\Actions\Relations\Detach;


use App\Actions\AbstractAction;
use App\Pivots\PersonGroup;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class DetachPersonFromGroupAction extends AbstractAction
{
    protected $description = 'Detaches a `Person` from a `Group`';

    protected $type = 'PersonGroupPivot';

    public function args()
    {
        return [
            'personId' => [
                'description' => 'The `ID` of the `Person` that you want to detach from the `Group`.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id']
            ],
            'groupId' => [
                'description' => 'The `ID` of the `Group` from which you want to detach the `Person`',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:groups,id']
            ]
        ];
    }

    public function handle($validArgs = [], ?ActionContext $context = null)
    {
        // Get the group
        $groupId = array_get($validArgs,'groupId');

        // Get the person
        $personId = array_get($validArgs,'personId');

        // Get the pivot
        $pivot = PersonGroup::query()->where([
            'group_id' => $groupId,
            'person_id' => $personId,
        ])->firstOrFail();

        // Delete the pivot
        PersonGroup::query()->where([
            'group_id' => $groupId,
            'person_id' => $personId,
        ])->delete();

        // Return the pivot as the result
        return $pivot;
    }
}