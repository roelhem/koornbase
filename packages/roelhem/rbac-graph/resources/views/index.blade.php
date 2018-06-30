@extends('rbac-graph::layouts.main')

@section('content')
    <div class="container my-2">
        <h1>Current Graph</h1>

        <h2>Class</h2>
        <p>{{ get_class($graph) }}</p>


        <svg></svg>

    </div>
@endsection