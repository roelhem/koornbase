<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonEmailAddressStoreRequest;
use App\Http\Requests\Api\PersonEmailAddressUpdateRequest;
use App\Http\Resources\Api\PersonEmailAddressResource;
use App\Http\Resources\Api\Resource;
use App\PersonEmailAddress;
use Illuminate\Http\Request;

class PersonEmailAddressController extends Controller
{

    protected $eagerLoadForShow = ['person'];

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonEmailAddressStoreRequest  $request
     * @param  FinderCollection                $finders
     * @return Resource
     * @throws
     */
    /*public function store(PersonEmailAddressStoreRequest $request, FinderCollection $finders)
    {
        $person = $finders->find($request->validated()['person'], 'person');
        $personEmailAddress = $person->emailAddresses()->create($request->validated());

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personEmailAddress->moveToIndex($index);
        }

        return $this->prepare($personEmailAddress, $request);
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonEmailAddressUpdateRequest  $request
     * @param  \App\PersonEmailAddress  $personEmailAddress
     * @return Resource
     * @throws
     */
    /*public function update(PersonEmailAddressUpdateRequest $request, PersonEmailAddress $personEmailAddress)
    {
        $personEmailAddress->fill($request->validated());
        $personEmailAddress->saveOrFail();

        $index = array_get($request->validated(), 'index');
        if($index !== null) {
            $personEmailAddress->moveToIndex($index);
        }

        return $this->prepare($personEmailAddress, $request);
    }*/
}
