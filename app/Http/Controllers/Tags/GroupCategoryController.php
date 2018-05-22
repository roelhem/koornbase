<?php

namespace App\Http\Controllers\Tags;

use App\GroupCategory;
use App\Http\Resources\Tags\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupCategoryController extends Controller
{

    public function index() {

        $query = GroupCategory::query();

        return Resource::collection($query->get());
    }

}
