@extends('layouts.app')

@section('title', 'Personen')

@section('content')

    <div class="container">

        <p>
            <a href="{{ route('persons.create') }}" class="btn btn-outline-success">Persoon Toevoegen</a>
        </p>


        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Personen</h3>
            </div>

            <table class="table card-table">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Naam</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($persons as $person)
                    <tr>
                        <td><span class="avatar avatar-cyan d-block">{{ $person->avatar_letters }}</span></td>
                        <td>{{ $person->name }}</td>
                        <td>
                            <membership-status :value="{{ $person->membership_status }}"></membership-status>

                            @if($person->membership_status > 0)
                                <span class="text-muted small">
                                    [ sinds
                                    <span class="data-display text-muted-dark" title="Status Lidmaatschap Sinds">
                                        {{ $person->membership_status_since->formatLocalized('%e %B %Y') }}
                                    </span>
                                    ]
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>



    </div>

@endsection