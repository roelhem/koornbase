<?php

namespace App\Http\Controllers\Api;

use App\GroupEmailAddress;
use App\Http\Resources\Api\GroupEmailAddressResource;
use App\Services\Finders\GroupFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupEmailAddressController extends Controller
{
    /**
     * Prepares a group-email-address to be send by an action.
     *
     * @param Model $groupEmailAddress
     * @param Request $request
     * @return GroupEmailAddressResource
     */
    protected function prepare($groupEmailAddress, Request $request) {
        $groupEmailAddress->load($this->getAskedRelations($request));
        return new GroupEmailAddressResource($groupEmailAddress);
    }

    /**
     * Shows a list of GroupEmailAddresses.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request) {
        $query = GroupEmailAddress::query();
        $query->with($this->getAskedRelations($request));

        return GroupEmailAddressResource::collection($query->paginate());
    }

    /**
     * Creates a new GroupEmailAddress.
     *
     * @param Request $request
     * @param GroupFinder $finder
     * @return GroupEmailAddressResource
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    public function store(Request $request, GroupFinder $finder) {
        $validatedData = $request->validate([
            'email_address' => 'required|string|email|unique:group_email_addresses|max:255',
            'remarks' => 'nullable|string',
            'group' => 'required|finds:App\Group'
        ]);

        $group = $finder->find($validatedData['group']);

        $emailAddress = $group->emailAddresses()->create($validatedData);

        return $this->prepare($emailAddress, $request);
    }

    /**
     * Shows one particular GroupEmailAddress.
     *
     * @param Request $request
     * @param GroupEmailAddress $emailAddress
     * @return GroupEmailAddressResource
     */
    public function show(Request $request, GroupEmailAddress $emailAddress) {
        return $this->prepare($emailAddress, $request);
    }

    /**
     * Updates a GroupEmailAddress.
     *
     * @param Request $request
     * @param GroupEmailAddress $emailAddress
     * @return GroupEmailAddressResource
     * @throws \Throwable
     */
    public function update(Request $request, GroupEmailAddress $emailAddress) {
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
    }

    /**
     * Deletes a PersonEmailAddress
     *
     * @param GroupEmailAddress $emailAddress
     * @throws \Exception
     */
    public function destroy(GroupEmailAddress $emailAddress) {
        $emailAddress->delete();
    }
}
