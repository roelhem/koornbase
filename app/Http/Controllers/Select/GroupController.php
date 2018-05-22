<?php

namespace App\Http\Controllers\Select;

use App\Group;
use App\Http\Resources\Select\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function index() {
        $query = Group::query();
        $query->orderBy('name');

        return Resource::collection($query->get());
    }
}
