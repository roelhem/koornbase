@extends('layouts.app')

@section('title','Mijn Account')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-lg-4">
                @include('cards.users.small', ['user' => $user])

                @include('cards.users.external',['user' => $user])
            </div>

            <div class="col-lg-8">
                @if($user->person)
                    @include('cards.persons.me', ['person' => $user->person])

                @else
                    <p class="text-muted">Geen persoon gekoppeld aan dit account.</p>
                @endif
            </div>

        </div>

    </div>

@endsection