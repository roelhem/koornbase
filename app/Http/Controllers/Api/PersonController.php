<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonStoreRequest;
use App\Http\Resources\Api\PersonResource;
use App\Http\Resources\Api\Resource;
use App\Person;
use App\Services\Finders\GroupFinder;
use App\Services\Sorters\PersonSorter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonController extends Controller
{

    protected $modelClass = Person::class;
    protected $resourceClass = PersonResource::class;
    protected $sorterClass = PersonSorter::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonStoreRequest  $request
     * @return Resource
     * @throws
     */
    public function store(PersonStoreRequest $request)
    {
        $validated = $request->validated();

        $person = new Person($validated);
        $person->saveOrFail();

        collect(array_get($validated, 'emailAddresses', []))->each(function($input) use ($person) {
            $person->emailAddresses()->create($input);
        });
        collect(array_get($validated, 'phoneNumbers', []))->each(function($input) use ($person) {
            $person->phoneNumbers()->create($input);
        });
        collect(array_get($validated, 'addresses', []))->each(function($input) use ($person) {
            $person->addresses()->create($input);
        });

        return $this->prepare($person, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @param  Request      $request
     * @return Resource
     */
    public function show(Person $person, Request $request)
    {
        return $this->prepare($person, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return Resource
     */
    public function update(Request $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @throws
     */
    public function destroy(Person $person)
    {
        $person->delete();
    }

    /**
     * Attaches some other models to this person via many-to-many relations.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    public function attach(Request $request, Person $person, FinderCollection $finders)
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
    public function detach(Request $request, Person $person, FinderCollection $finders)
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
    public function sync(Request $request, Person $person, FinderCollection $finders)
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
    }
}
