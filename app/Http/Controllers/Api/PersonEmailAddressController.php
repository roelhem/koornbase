<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonEmailAddressStoreRequest;
use App\Http\Requests\Api\PersonEmailAddressUpdateRequest;
use App\Person;
use App\PersonEmailAddress;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonEmailAddressController extends Controller
{

    protected $eagerLoadForShow = ['person'];

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonEmailAddressStoreRequest  $request
     * @param  FinderCollection                $finders
     * @return JsonResource
     * @throws
     */
    public function store(PersonEmailAddressStoreRequest $request, FinderCollection $finders)
    {
        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index','person']);

        // Get the Person and the PersonAddress
        $personInput = array_get($data,'person');
        /** @var Person $person */
        $person = $finders->find($personInput, 'person');
        /** @var PersonEmailAddress $emailAddress */
        $emailAddress = $person->emailAddress()->create($values);

        // Handle the selected index if it is set.
        $index = array_get($data, 'index');
        if($index !== null) {
            $emailAddress->moveToIndex($index);
        }

        // Prepare and return the response.
        $emailAddress->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($emailAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonEmailAddressUpdateRequest  $request
     * @param  PersonEmailAddress  $personEmailAddress
     * @return JsonResource
     * @throws
     */
    public function update(PersonEmailAddressUpdateRequest $request, PersonEmailAddress $personEmailAddress)
    {
        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index']);

        // Update the emailAddress and save the changes
        $personEmailAddress->fill($values);
        $personEmailAddress->saveOrFail();

        $index = array_get($data, 'index');
        if($index !== null) {
            $personEmailAddress->moveToIndex($index);
        }

        // Prepare and return the response.
        $personEmailAddress->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($personEmailAddress);
    }
}
