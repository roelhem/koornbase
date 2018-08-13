@extends('rbac-graph::layouts.base')

@section('page')

    <!-- THE NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <a class="navbar-brand" href="{{ route('rbac-graph.index') }}">
            <strong>RBAC</strong>Graph Dashboard
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('rbac-graph.nodes.index') }}">Nodes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">B</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">C</a>
                </li>


            </ul>
        </div>

    </nav>

    @yield('content')

@endsection