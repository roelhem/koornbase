<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 19:13
 */

namespace App\Actions\Relations\Attach;


use App\Actions\AbstractAction;
use App\Group;
use App\Pivots\PersonGroup;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class AttachPersonToGroupAction extends AbstractAction
{

    protected $description = 'Attaches a `Person` to a `Group`.';

    protected $type = 'AttachPersonToGroupResult';

    /** @inheritdoc */
    public function args()
    {
        return [
            'personId' => [
                'description' => 'The `ID` of the `Person` that you want to attach to a `Group`.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id']
            ],
            'groupId' => [
                'description' => 'The `ID` of the `Group` to which you want to attach the `Person`',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:groups,id']
            ]
        ];
    }

    /** @inheritdoc */
    public function handle($validArgs = [], ?ActionContext $context = null)
    {
        // Get the group
        $groupId = array_get($validArgs,'groupId');
        $group = Group::findOrFail($groupId);

        // Get the person
        $personId = array_get($validArgs,'personId');

        // Attach the person to the group.
        $group->persons()->attach($personId);

        // Return the pivot as the result.
        return PersonGroup::query()->where([
            'group_id' => $groupId,
            'person_id' => $personId,
        ])->firstOrFail();
    }

}