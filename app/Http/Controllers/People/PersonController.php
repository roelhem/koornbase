<?php

namespace App\Http\Controllers\People;

use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{

    function timeline(Person $person) {
        return view('people.person.timeline', ['person' => $person]);
    }

    function contact(Person $person) {
        return view('people.person.contact', ['person' => $person]);
    }

}
