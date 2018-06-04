<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\Api\GroupResource;
use App\Services\Finders\GroupCategoryFinder;
use App\Services\Finders\PersonFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * Prepares a group to be send by an action.
     *
     * @param Model $group
     * @param Request $request
     * @return GroupResource
     */
    protected function prepare($group, Request $request) {
        $group->load($this->getAskedRelations($request));
        return new GroupResource($group);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = Group::query();
        $query->with($this->getAskedRelations($request));

        return GroupResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GroupResource
     * @throws
     */
    public function store(Request $request, GroupCategoryFinder $groupCategoryFinder)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|finds:App\GroupCategory',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $groupCategory = $groupCategoryFinder->find($validatedData['category']);
        $group = $groupCategory->groups()->create($validatedData);

        return $this->prepare($group, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @param  Request     $request
     * @return GroupResource
     */
    public function show(Group $group, Request $request)
    {
        return $this->prepare($group, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @param  GroupCategoryFinder $categoryFinder
     * @return GroupResource
     * @throws
     */
    public function update(Request $request, Group $group, GroupCategoryFinder $categoryFinder)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|finds:App\GroupCategory',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $group->fill($validatedData);

        if(array_has($validatedData, 'category')) {
            $category = $categoryFinder->find($validatedData['category']);
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
     * @param PersonFinder $personFinder
     * @return GroupResource
     * @throws
     */
    public function attach(Request $request, Group $group, PersonFinder $personFinder) {
        $validatedData = $request->validate([
            'persons' => 'nullable|array',
            'persons.*' => [
                'bail',
                'finds:App\Person',
                function($attribute, $value, $fail) use ($group, $personFinder) {
                    $person = $personFinder->find($value);
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
                $person = $personFinder->find($personInput);
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
     * @param PersonFinder $personFinder
     * @return GroupResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    public function detach(Request $request, Group $group, PersonFinder $personFinder) {
        $validatedData = $request->validate([
            'persons' => 'nullable|array',
            'persons.*' => [
                'bail',
                'finds:App\Person',
                function($attribute, $value, $fail) use ($group, $personFinder) {
                    $person = $personFinder->find($value);
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
                $person = $personFinder->find($personInput);
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
     * @param PersonFinder $personFinder
     * @return GroupResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    public function sync(Request $request, Group $group, PersonFinder $personFinder) {
        $validatedData = $request->validate([
            'persons' => 'array',
            'persons.*' => 'finds:App\Person',
            'withoutDetaching' => 'boolean'
        ]);

        $withoutDetaching = boolval(array_get($validatedData, 'withoutDetaching', false));

        $personInputs = array_get($validatedData, 'persons');
        if($personInputs !== null) {
            $syncIds = [];
            foreach ($personInputs as $personInput) {
                $person = $personFinder->find($personInput);
                $syncIds[] = $person->id;
            }
            $group->persons()->sync($syncIds, !$withoutDetaching);
        }

        return $this->prepare($group, $request);
    }
}
