<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserResource;
use App\User;
use Illuminate\Http\Request;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class MeController extends Controller
{

    /**
     * Return the current user.
     *
     * @param Request $request
     * @return UserResource
     * @throws
     */
    public function me(Request $request) {

        $this->authorize('view-me');

        $user = User::findOrFail(\Auth::id());

        $user->load([
            'person' => RbacQueryFilter::eagerLoadingConstraintClosure(),
            'person.groups' => RbacQueryFilter::eagerLoadingConstraintClosure(),
            'person.certificates' => RbacQueryFilter::eagerLoadingConstraintClosure(),
            'person.activeCards' => RbacQueryFilter::eagerLoadingConstraintClosure(),
            'accounts' => RbacQueryFilter::eagerLoadingConstraintClosure(),
        ]);

        return new UserResource($user);
    }

}
