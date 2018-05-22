<?php

namespace App\Http\Controllers\People;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupCategoryController extends Controller
{

    public function create() {
        return view('people.groups.categories.form', ['action' => 'create']);
    }

    public function store() {
        return [];
    }

}
