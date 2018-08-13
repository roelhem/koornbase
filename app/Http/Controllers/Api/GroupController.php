<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use App\Group;
use App\Http\Resources\Api\GroupResource;
use App\Services\Sorters\GroupSorter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Finder;

class GroupController extends Controller
{

    protected $modelClass = Group::class;
    protected $resourceClass = GroupResource::class;
    protected $sorterClass = GroupSorter::class;


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param FinderCollection   $finders
     * @return Resource
     * @throws
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @param  Request     $request
     * @return Resource
     * @throws
     */
    public function show(Group $group, Request $request)
    {
        $this->authorize('view', $group);

        return $this->prepare($group, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @param  FinderCollection $finders
     * @return Resource
     * @throws
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @throws
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        if($group->is_required) {
            abort(403, 'Deze groep kan niet worden verwijderd omdat de groep nodig is voor het goed functioneren van dit systeem.');
        } else {
            $group->delete();
        }
    }

    /**
     * Endpoint to attach some persons.
     *
     * @param Request $request
     * @param Group $group
     * @param FinderCollection $finders
     * @return Resource
     * @throws
     */
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

    /**
     * Endpoint to detach attached persons from this group.
     *
     * @param Request $request
     * @param Group $group
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
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

    /**
     * Endpoint to sync attached persons with the input
     *
     * @param Request $request
     * @param Group $group
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
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
    }
}
