<?php

namespace App\Http\Controllers\People;

use App\Enums\MembershipStatus;
use App\Http\Resources\Display\MembershipResource;
use App\Membership;
use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MembershipController extends Controller
{

    /**
     * Gives all the memberships of one person.
     *
     * @param Person $person
     * @return ResourceCollection
     */
    public function index(Person $person) {
        $query = $person->memberships();

        return MembershipResource::collection($query->get());
    }

    /**
     * Creates a new membership for the current person.
     *
     * @param Person $person
     * @param Request $request
     * @return MembershipResource
     */
    public function new(Person $person, Request $request) {
        $at = Carbon::parse($request->get('at'));

        foreach($person->memberships as $membership) {
            if($membership->status !== MembershipStatus::FormerMember) {
                abort(400, 'There is an other membership currently active.');
            }

            if($membership->end > $at) {
                abort(400, 'Next membership must be after previous memberships.');
            }
        }

        $person->memberships()->create([
            'application' => $at
        ]);

        return new MembershipResource($person);
    }

    /**
     * Starts the given membership.
     *
     * @param Person $person
     * @param Membership $membership
     * @param Request $request
     * @throws \Throwable
     * @return MembershipResource
     */
    public function start(Person $person, Membership $membership, Request $request) {
        $at = Carbon::parse($request->get('at'));

        if($membership->start !== null) {
            abort(400, 'Membership has already started.');
        }

        if($membership->end !== null) {
            abort(400, 'Membership has already ended.');
        }

        if($membership->application !== null && $membership->application > $at) {
            abort(400, 'Start date must be after application date.');
        }

        $membership->start = $at;
        $membership->saveOrFail();

        return new MembershipResource($membership);
    }

    /**
     * Ends the given membership.
     *
     * @param Person $person
     * @param Membership $membership
     * @param Request $request
     * @throws \Throwable
     * @return MembershipResource
     */
    public function end(Person $person, Membership $membership, Request $request) {
        $at = Carbon::parse($request->get('at'));

        if($membership->end !== null) {
            abort(400, 'Membership has already ended.');
        }

        if($membership->application !== null && $membership->application > $at) {
            abort(400, 'End date must be after application date.');
        }

        if($membership->start !== null && $membership->start > $at) {
            abort(400, 'End date must be after start date.');
        }

        $membership->end = $at;
        $membership->saveOrFail();

        return new MembershipResource($membership);
    }

}
