<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\PersonPhoneNumberStoreRequest;
use App\Http\Requests\Api\PersonPhoneNumberUpdateRequest;
use App\Person;
use App\PersonPhoneNumber;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonPhoneNumberController extends Controller
{

    protected $eagerLoadForShow = ['person'];

    /**
     * Store a newly created resource in storage.
     *
     * @param  PersonPhoneNumberStoreRequest  $request
     * @param  FinderCollection                $finders
     * @return JsonResource
     * @throws
     */
    public function store(PersonPhoneNumberStoreRequest $request, FinderCollection $finders)
    {
        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index','person']);

        // Get the Person and the PersonAddress
        $personInput = array_get($data,'person');
        /** @var Person $person */
        $person = $finders->find($personInput, 'person');
        /** @var PersonPhoneNumber $phoneNumber */
        $phoneNumber = $person->phoneNumbers()->create($values);

        // Handle the selected index if it is set.
        $index = array_get($data, 'index');
        if($index !== null) {
            $phoneNumber->moveToIndex($index);
        }

        // Prepare and return the response.
        $phoneNumber->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($phoneNumber);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PersonPhoneNumberUpdateRequest  $request
     * @param  PersonPhoneNumber  $personPhoneNumber
     * @return JsonResource
     * @throws
     */
    public function update(PersonPhoneNumberUpdateRequest $request, PersonPhoneNumber $personPhoneNumber)
    {
        // Collecting the input
        $data = $request->validated();
        $values = array_except($data, ['index']);

        // Update the phoneNumber and save the changes
        $personPhoneNumber->fill($values);
        $personPhoneNumber->saveOrFail();

        $index = array_get($data, 'index');
        if($index !== null) {
            $personPhoneNumber->moveToIndex($index);
        }

        // Prepare and return the response.
        $personPhoneNumber->load($this->createEagerLoadDefinition($this->eagerLoadForShow));
        return $this->createResource($personPhoneNumber);
    }

}
