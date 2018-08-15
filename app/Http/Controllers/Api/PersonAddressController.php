<?php

namespace App\Http\Controllers\Api;


use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonAddressStoreRequest;
use App\Http\Requests\Api\PersonAddressUpdateRequest;
use App\Person;
use App\PersonAddress;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonAddressController extends Controller
{

    protected $eagerLoadForShow = ['person'];

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonAddressStoreRequest $request
     * @param  FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function store(PersonAddressStoreRequest $request, FinderCollection $finders)
    {

        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index','person']);

        // Get the Person and the PersonAddress
        $personInput = array_get($data,'person');
        /** @var Person $person */
        $person = $finders->find($personInput, 'person');
        /** @var PersonAddress $address */
        $address = $person->addresses()->create($values);

        // Handle the selected index if it is set.
        $index = array_get($data, 'index');
        if($index !== null) {
            $address->moveToIndex($index);
        }

        // Prepare and return the response.
        $address->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonAddressUpdateRequest  $request
     * @param  PersonAddress  $personAddress
     * @return JsonResource
     * @throws
     */
    public function update(PersonAddressUpdateRequest $request, PersonAddress $personAddress)
    {
        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index']);

        // Update the address and save the changes
        $personAddress->fill($values);
        $personAddress->saveOrFail();

        $index = array_get($data, 'index');
        if($index !== null) {
            $personAddress->moveToIndex($index);
        }

        // Prepare and return the response.
        $personAddress->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($personAddress);
    }
}
