@extends('layouts.app')

@section('content')

    <div class="container">

        <!-- Event header -->
        <div class="content">
            <h3 class="text-muted"><i class="fa fa-{{$event->category->options->icons['fa']}}"></i> {{ $event->category->name }}</h3>
            <h1>{{ $event->name }}</h1>
        </div>

        <p>{!! nl2br(e($event->description)) !!}</p>

    </div>

@endsection