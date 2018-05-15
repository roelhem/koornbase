@extends('layouts.app')

@section('title', 'Studies')

@section('content')

    <div class="container">

        <p>
        <a href="{{ route('studies.create') }}" class="btn btn-outline-success">Studie Toevoegen</a>
        </p>

        @foreach($studies as $study)
            <p>{{ $study->name }} ({{ $study->name_short }}) - <em>{{ $study->institution }}</em> </p>
        @endforeach

    </div>

@endsection