<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserResource;
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

        $user = \Auth::user();
        $user->setRelations([]);

        $user->load($this->getAskedRelations($request));

        return new UserResource($user);
    }

}
