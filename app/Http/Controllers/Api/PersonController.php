<?php

namespace App\Http\Controllers\Api;




use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonStoreRequest;
use App\Http\Requests\Api\PersonUpdateRequest;
use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{


    protected $eagerLoadForShow = [
        'memberships','groups','certificates','users','cards','addresses','emailAddresses','phoneNumbers'
    ];


    /**
     * An action that stores a new Person in the database.
     *
     * @param PersonStoreRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(PersonStoreRequest $request)
    {
        $person = Person::create($request->validated());

        return $this->createResource($person);
    }

    /**
     * An action that updates the values of an existing Person.
     *
     * @param PersonUpdateRequest $request
     * @param Person $person
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Throwable
     */
    public function update(PersonUpdateRequest $request, Person $person)
    {
        $person->fill($request->validated());
        $person->saveOrFail();

        $person->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($person);
    }


    /**
     * Attach some objects to the current person.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return \Illuminate\Http\Resources\Json\JsonResource
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

        $person->load($this->createEagerLoadDefinition(['groups']));

        return $this->createResource($person);
    }

    /**
     * Detaches some other models from this person via many-to-many relations.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return \Illuminate\Http\Resources\Json\JsonResource
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

        $person->load($this->createEagerLoadDefinition(['groups']));

        return $this->createResource($person);
    }

    /**
     * Syncs the related models of an many-to-many relation.
     *
     * @param Request $request
     * @param Person $person
     * @param FinderCollection $finders
     * @return \Illuminate\Http\Resources\Json\JsonResource
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

        $person->load($this->createEagerLoadDefinition(['groups']));
        return $this->createResource($person);
    }
}
