<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\MembershipStoreRequest;
use App\Http\Requests\Api\MembershipUpdateRequest;
use App\Http\Resources\Api\MembershipResource;
use App\Http\Resources\Api\Resource;
use App\Membership;
use App\Services\Sorters\MembershipSorter;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Finder;

class MembershipController extends Controller
{

    protected $modelClass = Membership::class;
    protected $resourceClass = MembershipResource::class;
    protected $sorterClass = MembershipSorter::class;


    /**
     * Endpoint that is used to store new
     *
     * @param MembershipStoreRequest $request
     * @param FinderCollection $finders
     * @return Resource
     * @throws
     */
    public function store(MembershipStoreRequest $request, FinderCollection $finders)
    {
        $personInput = array_get($request->validated(),'person');
        $person = $finders->find($personInput, 'person');
        $membership = $person->memberships()->create(
            array_only($request->validated(), ['application','start','end','remarks'])
        );
        return $this->prepare($membership, $request);
    }


    /**
     * Endpoint that shows a specific membership
     *
     * @param Request $request
     * @param Membership $membership
     * @return Resource
     */
    public function show(Request $request, Membership $membership)
    {
        return $this->prepare($membership, $request);
    }


    /**
     * @param MembershipUpdateRequest $request
     * @param Membership $membership
     * @throws
     * @return Resource
     */
    public function update(MembershipUpdateRequest $request, Membership $membership)
    {
        $membership->fill($request->validated());
        $membership->saveOrFail();

        return $this->prepare($membership, $request);
    }


    /**
     * Endpoint that deletes a membership
     *
     * @param Membership $membership
     * @throws
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();
    }

}
