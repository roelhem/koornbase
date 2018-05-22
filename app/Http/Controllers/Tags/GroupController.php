<?php

namespace App\Http\Controllers\Tags;

use App\Group;
use App\Http\Resources\Tags\GroupResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{


    public function index() {

        $query = Group::query();

        return GroupResource::collection($query->get());
    }

    public function show(Group $group) {
        return new GroupResource($group);
    }

}
