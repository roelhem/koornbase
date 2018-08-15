<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\GroupEmailAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class GroupEmailAddressController extends Controller
{

    protected $eagerLoadForShow = ['group'];

    /**
     * Creates a new GroupEmailAddress.
     *
     * @param Request $request
     * @param FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function store(Request $request, FinderCollection $finders) {

        $this->authorize('create', GroupEmailAddress::class);

        $validatedData = $request->validate([
            'email_address' => 'required|string|email|unique:group_email_addresses|max:255',
            'remarks' => 'nullable|string',
            'group' => 'required|finds:group'
        ]);

        $group = $finders->find($validatedData['group'],'group');

        /** @var GroupEmailAddress $emailAddress */
        $emailAddress = $group->emailAddresses()->create($validatedData);

        $emailAddress->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($emailAddress);
    }

    /**
     * Updates a GroupEmailAddress.
     *
     * @param Request $request
     * @param GroupEmailAddress $emailAddress
     * @return JsonResource
     * @throws \Throwable
     */
    public function update(Request $request, GroupEmailAddress $emailAddress) {

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

        $emailAddress->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($emailAddress);
    }

}
