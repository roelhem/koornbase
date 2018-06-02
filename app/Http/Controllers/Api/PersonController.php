<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PersonStoreRequest;
use App\Http\Resources\Api\PersonResource;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonController extends Controller
{

    protected function prepare($person, Request $request) {
        $person->load($this->getAskedRelations($request));
        return new PersonResource($person);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = Person::query();
        $query->with($this->getAskedRelations($request));

        return PersonResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonStoreRequest  $request
     * @return PersonResource
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
     * @return PersonResource
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
     * @return \Illuminate\Http\Response
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
}
