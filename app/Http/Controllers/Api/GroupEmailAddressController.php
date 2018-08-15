<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\GroupEmailAddress;
use App\Http\Resources\Api\GroupEmailAddressResource;
use App\Services\Sorters\GroupEmailAddressSorter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupEmailAddressController extends Controller
{

    protected $eagerLoadForShow = ['group'];

    /**
     * Creates a new GroupEmailAddress.
     *
     * @param Request $request
     * @param FinderCollection $finders
     * @return Resource
     * @throws
     */
    /*public function store(Request $request, FinderCollection $finders) {

        $this->authorize('create', GroupEmailAddress::class);

        $validatedData = $request->validate([
            'email_address' => 'required|string|email|unique:group_email_addresses|max:255',
            'remarks' => 'nullable|string',
            'group' => 'required|finds:group'
        ]);

        $group = $finders->find($validatedData['group'],'group');

        $emailAddress = $group->emailAddresses()->create($validatedData);

        return $this->prepare($emailAddress, $request);
    }*/

    /**
     * Updates a GroupEmailAddress.
     *
     * @param Request $request
     * @param GroupEmailAddress $emailAddress
     * @return Resource
     * @throws \Throwable
     */
    /*public function update(Request $request, GroupEmailAddress $emailAddress) {

        $this->authorize('update', $emailAddress);

        $validatedData = $request->validate([
            'email_address' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('group_email_addresses')->ignore($emailAddress->id),
            ],
            'remarks' => 'nullable|string'
        ]);

        $emailAddress->fill($validatedData);
        $emailAddress->saveOrFail();

        return $this->prepare($emailAddress, $request);
    }*/

}
