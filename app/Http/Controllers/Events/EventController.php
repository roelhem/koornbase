<?php

namespace App\Http\Controllers\Events;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{

    public function show(Event $event) {
        return view('events.event.show', ['event' => $event]);
    }

}
