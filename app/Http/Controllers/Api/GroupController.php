<?php

namespace App\Http\Controllers\Api;


use App\Group;
use App\Http\Resources\Api\GroupResource;
use Illuminate\Http\Request;
use Roelhem\RbacGraph\Services\RbacQueryFilter;


class GroupController extends Controller
{

    protected $eagerLoadForShow = [
        'emailAddresses', 'persons'
    ];


    /*
    public function store(Request $request, FinderCollection $finders)
    {
        $this->authorize('create', Group::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|finds:group_category',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $groupCategory = $finders->find($validatedData['category'], 'group_category');
        $group = $groupCategory->groups()->create($validatedData);

        return $this->prepare($group, $request);
    }

    public function update(Request $request, Group $group, FinderCollection $finders)
    {
        $this->authorize('update', $group);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|finds:group_category',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $group->fill($validatedData);

        if(array_has($validatedData, 'category')) {
            $category = $finders->find($validatedData['category'], 'group_category');
            $group->category()->associate($category);
        }

        $group->saveOrFail();

        return $this->prepare($group, $request);
    }


    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        if($group->is_required) {
            abort(403, 'Deze groep kan niet worden verwijderd omdat de groep nodig is voor het goed functioneren van dit systeem.');
        } else {
            $group->delete();
        }
    }


    public function attach(Request $request, Group $group, FinderCollection $finders) {
        $validatedData = $request->validate([
            'persons' => 'nullable|array',
            'persons.*' => [
                'bail',
                'finds:person',
                function($attribute, $value, $fail) use ($group, $finders) {
                    $person = $finders->find($value,'person');
                    if (\DB::table('person_group')->where([
                        ['person_id', '=', $person->id],
                        ['group_id', '=', $group->id]
                    ])->exists()) {
                        return $fail("De persoon '{$person->name}' zit al in de groep '{$group->name_short}'. ");
                    }
                }
            ],
        ]);

        $personInputs = array_get($validatedData, 'persons');
        if($personInputs !== null) {
            foreach($personInputs as $personInput) {
                $person = $finders->find($personInput, 'person');
                $group->persons()->attach($person->id);
            }
        }

        return $this->prepare($group, $request);
    }


    public function detach(Request $request, Group $group, FinderCollection $finders) {
        $validatedData = $request->validate([
            'persons' => 'nullable|array',
            'persons.*' => [
                'bail',
                'finds:person',
                function($attribute, $value, $fail) use ($group, $finders) {
                    $person = $finders->find($value, 'person');
                    if (!\DB::table('person_group')->where([
                        ['person_id', '=', $person->id],
                        ['group_id', '=', $group->id]
                    ])->exists()) {
                        return $fail("De persoon '{$person->name}' zit niet in de groep '{$group->name_short}'. ");
                    }
                }
            ],
        ]);

        $personInputs = array_get($validatedData, 'persons');
        if($personInputs !== null) {
            foreach($personInputs as $personInput) {
                $person = $finders->find($personInput, 'person');
                $group->persons()->detach($person->id);
            }
        }

        return $this->prepare($group, $request);
    }


    public function sync(Request $request, Group $group, FinderCollection $finders) {
        $validatedData = $request->validate([
            'persons' => 'array',
            'persons.*' => 'finds:person',
            'withoutDetaching' => 'boolean'
        ]);

        $withoutDetaching = boolval(array_get($validatedData, 'withoutDetaching', false));

        $personInputs = array_get($validatedData, 'persons');
        if($personInputs !== null) {
            $syncIds = [];
            foreach ($personInputs as $personInput) {
                $person = $finders->find($personInput, 'person');
                $syncIds[] = $person->id;
            }
            $group->persons()->sync($syncIds, !$withoutDetaching);
        }

        return $this->prepare($group, $request);
    }*/
}
