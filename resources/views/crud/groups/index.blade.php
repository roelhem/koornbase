@extends('layouts.app')

@section('title', 'Groepen')

@section('content')

    <div class="container">

        <p>
            <a href="{{ route('groups.create') }}" class="btn btn-outline-success">Groep Toevoegen</a>
        </p>


        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Groepen</h3>
            </div>

            <table class="table card-table">
                <thead>
                <tr>
                    <th>Naam</th>
                    <th>Categorie</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->category->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>



    </div>

@endsection