@extends('layouts.app')

@section('title','Mijn Account')

@section('content')

    <b-container>

        <b-row>

            <b-col lg="4">



                <b-card>
                    <b-media>
                        <user-avatar slot="aside" class="avatar-xxl mr-5" image="{{$user->avatar}}" letters="{{$user->avatar_letters}}"></user-avatar>
                        <h4 class="m-0">{{ $user->name_display }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }} <span class="small text-muted-dark">(<em>{{ $user->name }}</em>)</span></p>
                    </b-media>
                </b-card>




                @include('cards.users.external',['user' => $user])

            </b-col>

            <b-col>

                @if($user->person)
                    @include('cards.persons.me', ['person' => $user->person])

                @else
                    <p class="text-muted">Geen persoon gekoppeld aan dit account.</p>
                @endif

            </b-col>

        </b-row>

    </b-container>


@endsection