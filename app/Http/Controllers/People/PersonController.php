<?php

namespace App\Http\Controllers\People;

use App\Http\Resources\Display\PersonResource;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{

    function show(Person $person, Request $request) {

        if(!$request->wantsJson()) {
            return view('people.person.show');
        }

        $person->load('groupMemberships.group.category');

        return new PersonResource($person);
    }

    function timeline(Person $person) {
        return view('people.person.timeline', ['person' => $person]);
    }

    function contact(Person $person) {
        return view('people.person.contact', ['person' => $person]);
    }

}
