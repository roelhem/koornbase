<?php

namespace App\Http\Controllers\People;

use App\Group;
use App\Http\Resources\GroupSearchResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupSearchController extends Controller
{

    public function index() {
        return view('people.groups.index');
    }

    public function search(Request $request) {
        $query = Group::query();

        return GroupSearchResource::collection($query->paginate());
    }

}
