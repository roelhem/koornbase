<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PersonStoreRequest;
use App\Http\Resources\Api\PersonResource;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonController extends Controller
{
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
        $person = new Person($request->only([
            'name','name_short','name_formal','nickname','birth_date','remarks'
        ]));
        $person->saveOrFail();

        foreach ($request->get('emailAddresses', []) as $index => $emailAddressInput) {
            $emailAddress = $person->emailAddresses()->make([
                    'index' => $index,
                    'label' => $emailAddressInput['label'],
                    'email_address' => $emailAddressInput['email_address'],
                    'remarks' => array_get($emailAddressInput, 'remarks', null),
                    'options' => array_get($emailAddressInput, 'options', [])
                ]);
            $emailAddress->saveOrFail();
        }

        $person->load($this->getAskedRelations($request));
        return new PersonResource($person);
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
        $person->load($this->getAskedRelations($request));
        return new PersonResource($person);
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
