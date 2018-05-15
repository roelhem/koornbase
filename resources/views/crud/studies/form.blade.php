@extends('layouts.app')

@section('title', 'Nieuwe Studie Aanmaken')

@section('content')

    <div class="container">

        <crud-form action="{{ route('studies.store') }}" title="Studie Toevoegen">

            @csrf

            <f-name-input label="Naam van de studie."></f-name-input>

            <f-simple-input name="institution" label="Onderwijsaanbieder/Instituut." required></f-simple-input>
            <f-simple-input name="city" label="Stad waar de studie hoofdzakelijk wordt gegeven." required></f-simple-input>

            <!--<div class="card-footer text-right">
                <div class="d-flex">
                    <button type="submit" class="btn btn-success ml-auto">Toevoegen</button>
                </div>
            </div>-->

            <f-textarea name="description" label="Omschrijving"></f-textarea>

            <f-textarea name="remarks" label="Opmerkingen"></f-textarea>

        </crud-form>

    </div>

@endsection