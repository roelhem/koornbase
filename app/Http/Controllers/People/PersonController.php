<?php

namespace App\Http\Controllers\People;

use App\Http\Requests\Person\StoreRequest;
use App\Http\Requests\Person\UpdateRequest;
use App\Http\Resources\Display\PersonResource;
use App\Person;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{

    function show(Person $person, Request $request) {

        if(!$request->wantsJson()) {
            return view('people.person.show');
        }

        $person->load([
            'groupMemberships.group.category',
            'emailAddresses',
            'phoneNumbers',
            'cardOwnership.card',
        ]);

        return new PersonResource($person);
    }







    function create(Request $request) {

        $person = new Person();
        $person->load(['emailAddresses', 'phoneNumbers']);

        if($request->wantsJson()) {
            return new PersonResource($person);
        } else {
            return view('people.person.form', ['action' => 'create', 'person' => $person]);
        }
    }

    /**
     * Action that stores a new Person in the database.
     *
     * @param StoreRequest $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    function store(StoreRequest $request) {

        $validated = $request->validated();

        \DB::transaction(function() use ($request, $validated) {
            // CREATE THE NEW PERSON
            $person = $this->fillPerson(new Person(), $validated);
            $person->saveOrFail();
            
            // CREATE THE EMAIL ADDRESSES
            foreach (array_get($validated, 'emailAddresses', []) as $index => $emailInput) {
                if($this->nestedShouldCreate($emailInput)) {
                    $emailAddress = $this->fillEmailAddress(new PersonEmailAddress(), $emailInput);
                    $person->emailAddresses()->save($emailAddress);
                }
            }

            // CREATE THE PHONE NUMBERS
            foreach (array_get($validated, 'phoneNumbers', []) as $index => $phoneInput) {
                if($this->nestedShouldCreate($phoneInput)) {
                    $phoneNumber = $this->fillPhoneNumber(new PersonPhoneNumber(), $phoneInput);
                    $person->phoneNumbers()->save($phoneNumber);
                }
            }
        });

        return ['ok'];
    }




    function edit(Person $person, Request $request) {
        $person->load(['emailAddresses','phoneNumbers']);

        if($request->wantsJson()) {
            return new PersonResource($person);
        } else {
            return view('people.person.form', ['action' => 'update', 'person' => $person]);
        }
    }

    /**
     * @param Person $person
     * @param UpdateRequest $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    function update(Person $person, UpdateRequest $request) {

        $validated = $request->validated();


        \DB::transaction(function() use ($person, $validated) {

            // UPDATE THE PERSON
            $this->fillPerson($person, $validated)->saveOrFail();


            // UPDATE THE EMAIL ADDRESSES
            foreach (array_get($validated, 'emailAddresses', []) as $index => $emailInput) {
                if($this->nestedShouldCreate($emailInput)) {
                    // We'll create a new email address.
                    $emailAddress = $this->fillEmailAddress(new PersonEmailAddress(), $emailInput);
                    $person->emailAddresses()->save($emailAddress);
                } elseif($this->nestedShouldUpdate($emailInput)) {
                    // We'll update the $emailAddress.
                    $id = array_get($emailInput, 'id');
                    $emailAddress = PersonEmailAddress::findOrFail($id);
                    $this->fillEmailAddress($emailAddress, $emailInput);
                    $person->emailAddresses()->save($emailAddress);
                } elseif($this->nestedShouldDelete($emailInput)) {
                    // We'll delete the email address from the database.
                    $id = array_get($emailInput, 'id');
                    $emailAddress = PersonEmailAddress::findOrFail($id);
                    $emailAddress->delete();
                }
            }

            // UPDATE THE PHONE NUMBERS
            foreach (array_get($validated, 'phoneNumbers', []) as $index => $phoneInput) {
                if($this->nestedShouldCreate($phoneInput)) {
                    // We'll create a new phone number.
                    $phoneNumber = $this->fillPhoneNumber(new PersonPhoneNumber(), $phoneInput);
                    $person->phoneNumbers()->save($phoneNumber);
                } elseif($this->nestedShouldUpdate($phoneInput)) {
                    // We'll update the phone number.
                    $id = array_get($phoneInput, 'id');
                    $phoneNumber = PersonPhoneNumber::findOrFail($id);
                    $this->fillPhoneNumber($phoneNumber, $phoneInput);
                    $person->phoneNumbers()->save($phoneNumber);
                } elseif($this->nestedShouldDelete($phoneInput)) {
                    // We'll delete the phoneNumber from the database.
                    $id = array_get($phoneInput, 'id');
                    $phoneNumber = PersonPhoneNumber::findOrFail($id);
                    $phoneNumber->delete();
                }
            }
        });

        return redirect()->route('people.person', ['person' => $person]);

    }

    protected function fillPhoneNumber(PersonPhoneNumber $phoneNumber, array $values) {
        $safeValues = array_only($values, ['label','is_primary','for_emergency','is_mobile','phone_number','remarks']);
        $safeValues = array_merge([
            'is_primary' => false,
            'for_emergency' => false,
            'is_mobile' => false,
        ], $safeValues);
        $phoneNumber->fill($safeValues);
        return $phoneNumber;
    }

    protected function fillEmailAddress(PersonEmailAddress $emailAddress, array $values) {
        $safeValues = array_only($values, ['label','is_primary','for_emergency','email_address','remarks']);
        $safeValues = array_merge([
            'is_primary' => false,
            'for_emergency' => false,
        ], $safeValues);
        $emailAddress->fill($safeValues);
        return $emailAddress;
    }

    /**
     * A helping function that fills the values of $person with the given array of $values.
     *
     * @param Person $person
     * @param array $values
     * @return Person
     */
    protected function fillPerson(Person $person, array $values) {
        $person->fill(array_only($values, ['name','remarks']));

        $birth_date = array_get($values, 'birth_date');
        if($birth_date === null) {
            $person->birth_date = null;
        } else {
            $person->birth_date = Carbon::createFromFormat('d-m-Y', $birth_date);
        }

        return $person;
    }

    protected function nestedShouldCreate($nestedValue) {
        return array_get($nestedValue, 'id') === null && !array_get($nestedValue, '_deleted');
    }

    protected function nestedShouldUpdate($nestedValue) {
        return array_get($nestedValue, 'id') !== null && !array_get($nestedValue, '_deleted');
    }

    protected function nestedShouldDelete($nestedValue) {
        return array_get($nestedValue, 'id') !== null && array_get($nestedValue, '_deleted');
    }

}
