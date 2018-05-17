@extends('layouts.app')

@section('content')

    <div class="container">

        <person-form action="{{ $action }}"></person-form>

        @csrf

    </div>

    @if($errors->any())
    <div class="container">
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $key => $error)
                    <li><strong>{{ $key }}:</strong>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <div class="alert alert-secondary">
            {{ var_dump(old('phoneNumbers')) }}
            {{ var_dump(old('emailAddresses')) }}
        </div>
    </div>
    @endif

@endsection