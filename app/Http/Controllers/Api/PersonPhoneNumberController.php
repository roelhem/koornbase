<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonPhoneNumberStoreRequest;
use App\Http\Requests\Api\PersonPhoneNumberUpdateRequest;
use App\Http\Resources\Api\PersonPhoneNumberResource;
use App\Http\Resources\Api\Resource;
use App\PersonPhoneNumber;
use Illuminate\Http\Request;

class PersonPhoneNumberController extends Controller
{

    protected $modelClass = PersonPhoneNumber::class;
    protected $resourceClass = PersonPhoneNumberResource::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonPhoneNumberStoreRequest  $request
     * @param  FinderCollection               $finders
     * @return Resource
     * @throws
     */
    public function store(PersonPhoneNumberStoreRequest $request, FinderCollection $finders)
    {
        $person = $finders->find($request->validated()['person'], 'person');
        $personPhoneNumber = $person->phoneNumbers()->create($request->validated());

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personPhoneNumber->moveToIndex($index);
        }

        return $this->prepare($personPhoneNumber, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @return Resource
     * @throws
     */
    public function show(Request $request, PersonPhoneNumber $personPhoneNumber)
    {
        $this->authorize('view', $personPhoneNumber);

        return $this->prepare($personPhoneNumber, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonPhoneNumberUpdateRequest  $request
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @return Resource
     * @throws
     */
    public function update(PersonPhoneNumberUpdateRequest $request, PersonPhoneNumber $personPhoneNumber)
    {
        $personPhoneNumber->fill($request->validated());
        $personPhoneNumber->saveOrFail();

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personPhoneNumber->moveToIndex($index);
        }

        return $this->prepare($personPhoneNumber, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PersonPhoneNumber  $personPhoneNumber
     * @throws
     */
    public function destroy(PersonPhoneNumber $personPhoneNumber)
    {
        $this->authorize('delete', $personPhoneNumber);

        $personPhoneNumber->delete();
    }
}
