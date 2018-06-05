<?php

namespace App\Http\Controllers\Api;


use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonAddressStoreRequest;
use App\Http\Requests\Api\PersonAddressUpdateRequest;
use App\Http\Resources\Api\PersonAddressResource;
use App\PersonAddress;
use Illuminate\Http\Request;

class PersonAddressController extends Controller
{

    protected $modelClass = PersonAddress::class;
    protected $resourceClass = PersonAddressResource::class;


    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonAddressStoreRequest $request
     * @param  FinderCollection $finders
     * @return Resource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    public function store(PersonAddressStoreRequest $request, FinderCollection $finders)
    {

        $person = $finders->find($request->validated()['person'], 'person');
        $personAddress = $person->addresses()->create($request->validated());

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personAddress->moveToIndex($index);
        }

        return $this->prepare($personAddress, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  \App\PersonAddress  $personAddress
     * @return Resource
     */
    public function show(Request $request, PersonAddress $personAddress)
    {
        return $this->prepare($personAddress, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonAddressUpdateRequest  $request
     * @param  PersonAddress  $personAddress
     * @return Resource
     * @throws
     */
    public function update(PersonAddressUpdateRequest $request, PersonAddress $personAddress)
    {
        $personAddress->fill($request->validated());
        $personAddress->saveOrFail();

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personAddress->moveToIndex($index);
        }

        return $this->prepare($personAddress, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PersonAddress  $personAddress
     * @throws
     */
    public function destroy(PersonAddress $personAddress)
    {
        $personAddress->delete();
    }
}
