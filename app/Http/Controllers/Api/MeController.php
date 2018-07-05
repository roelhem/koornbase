<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserResource;
use App\User;
use Illuminate\Http\Request;

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

        $user->load($this->getEagerLoadingRelations($request));

        return new UserResource($user);
    }

}
