<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{

    /**
     * Action that gives the current user an overview of his account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        $user = $request->user();

        return view('me.overview', ['user' => $user]);
    }

}
