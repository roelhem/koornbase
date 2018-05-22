<?php

namespace App\Http\Controllers\Select;

use App\GroupCategory;
use App\Http\Resources\Select\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupCategoryController extends Controller
{

    public function index() {
        $query = GroupCategory::query();
        $query->orderBy('name');

        return Resource::collection($query->get());
    }

}
