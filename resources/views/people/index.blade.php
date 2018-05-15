@extends('layouts.app')

@section('title', 'Personen Beheren')

@section('content')
    <people-search-page src="{{ route('people.search') }}"></people-search-page>
@endsection
