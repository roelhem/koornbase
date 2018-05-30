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
     */
    public function me(Request $request) {

        $user = \Auth::user();
        $user->load('person');
        $user->load($this->getAskedRelations($request));

        return new UserResource($user);
    }

}
