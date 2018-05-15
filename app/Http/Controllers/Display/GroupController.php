<?php

namespace App\Http\Controllers\Display;

use App\Group;
use App\Http\Resources\Display\GroupResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{

    public function group(Group $group) {

        $group->load('category');

        return new GroupResource($group);
    }

}
