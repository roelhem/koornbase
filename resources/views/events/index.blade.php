@extends('layouts.app')

@section('title', 'Evenementenbeheer')

@section('content')

    <div class="container">
        <card-calendar :sources="['/calendar/birthdays','/calendar/events']"></card-calendar>
    </div>

@endsection
