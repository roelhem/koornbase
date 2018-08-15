<?php

namespace App\Http\Controllers\Api;




class PersonController extends Controller
{


    protected $eagerLoadForShow = [
        'memberships','groups','certificates','users','cards','addresses','emailAddresses','phoneNumbers'
    ];


    /*public function store(PersonStoreRequest $request)
    {
        $validated = $request->validated();

        $person = new Person($validated);
        $person->saveOrFail();

        return $this->prepare($person, $request);
    }*/



    /*public function update(PersonUpdateRequest $request, Person $person)
    {
        $person->fill($request->validated());
        $person->saveOrFail();

        return $this->prepare($person, $request);
    }*/


    /*public function destroy(Person $person)
    {
        $this->authorize('delete', $person);

        $person->delete();
    }*/

    /*public function attach(Request $request, Person $person, FinderCollection $finders)
    {
        $validatedData = $request->validate([
            'groups' => 'nullable|array',
            'groups.*' => [
                'bail',
                'finds:group',
                function($attribute, $value, $fail) use ($person, $finders) {
                    $group = $finders->find($value,'group');
                    if(\DB::table('person_group')->where([
                        ['person_id','=',$person->id],
                        ['group_id','=',$group->id]
                    ])->exists()) {
                        return $fail("De persoon '{$person->name}' zit al in de groep '{$group->name_short}'.");
                    }
                }
            ],
        ]);

        $groupInputs = array_get($validatedData, 'groups');
        if($groupInputs !== null) {
            foreach ($groupInputs as $groupInput) {
                $group = $finders->find($groupInput,'group');
                $person->groups()->attach($group->id);
            }
        }

        return $this->prepare($person, $request);
    }

    /**
     * Detaches some other models from this person via many-to-many relations.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    /*public function detach(Request $request, Person $person, FinderCollection $finders)
    {
        $validatedData = $request->validate([
            'groups' => 'nullable|array',
            'groups.*' => [
                'bail',
                'finds:group',
                function($attribute, $value, $fail) use ($person, $finders) {
                    $group = $finders->find($value, 'group');
                    if(!\DB::table('person_group')->where([
                        ['person_id','=',$person->id],
                        ['group_id','=',$group->id]
                    ])->exists()) {
                        return $fail("De persoon '{$person->name}' zit niet in de groep '{$group->name_short}'.");
                    }
                }
            ],
        ]);

        $groupInputs = array_get($validatedData, 'groups');
        if($groupInputs !== null) {
            foreach ($groupInputs as $groupInput) {
                $group = $finders->find($groupInput,'group');
                $person->groups()->detach($group->id);
            }
        }

        return $this->prepare($person, $request);
    }

    /**
     * Syncs the related models of an many-to-many relation.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    /*public function sync(Request $request, Person $person, FinderCollection $finders)
    {
        $validatedData = $request->validate([
            'groups' => 'array',
            'groups.*' => 'finds:group',
            'withoutDetaching' => 'boolean'
        ]);

        $withoutDetaching = boolval(array_get($validatedData, 'withoutDetaching', false));

        $groupInputs = array_get($validatedData, 'groups');
        if($groupInputs !== null) {
            $syncIds = [];
            foreach ($groupInputs as $groupInput) {
                $group = $finders->find($groupInput,'group');
                $syncIds[] = $group->id;
            }
            $person->groups()->sync($syncIds, !$withoutDetaching);
        }

        return $this->prepare($person, $request);
    }*/
}
