<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\MembershipStoreRequest;
use App\Http\Requests\Api\MembershipUpdateRequest;
use App\Membership;
use App\Person;
use Illuminate\Http\Resources\Json\JsonResource;



class MembershipController extends Controller
{

    protected $eagerLoadForShow = ['person'];

    /**
     * Endpoint that is used to store new
     *
     * @param MembershipStoreRequest $request
     * @param FinderCollection $finders
     * @return JsonResource
     * @throws
     */
    public function store(MembershipStoreRequest $request, FinderCollection $finders)
    {
        $personInput = array_get($request->validated(),'person');
        /** @var Person $person */
        $person = $finders->find($personInput, 'person');
        /** @var Membership $membership */
        $membership = $person->memberships()->create(
            array_only($request->validated(), ['application','start','end','remarks'])
        );

        $membership->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($membership);
    }



    /**
     * @param MembershipUpdateRequest $request
     * @param Membership $membership
     * @throws
     * @return JsonResource
     */
    public function update(MembershipUpdateRequest $request, Membership $membership)
    {
        $membership->fill($request->validated());
        $membership->saveOrFail();

        $membership->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($membership);
    }

}
