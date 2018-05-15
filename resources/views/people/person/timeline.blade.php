@extends('layouts.app')

@section('content')

    @include('people.person.header')

    <div class="container my-4">

        <div class="row">

            <div class="col-lg-4">

                <div class="card">
                    <table class="table card-table table-sm">
                        <tbody>
                            <tr>
                                <th><i class="fa fa-user text-muted"></i></th>
                                <td>
                                    <span class="data-display" title="Initialen">{{ $person->name_initials }}</span>
                                    <span class="text-muted font-italic">
                                        (
                                        <span class="data-display text-muted-dark" title="Voornaam">{{ $person->name_first }}</span>
                                        <span class="data-display" title="Extra Namen">{{ $person->name_middle }}</span>
                                        )
                                    </span>
                                    <span class="data-display" title="Tussenvoegsel">{{ $person->name_prefix }}</span>
                                    <span class="data-display" title="Achternaam">{{ $person->name_last }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-book text-muted"></i></th>
                                <td>
                                    <span class="data-display" title="Status Lidmaatschap">
                                        <membership-status :value="{{ $person->membership_status }}"></membership-status>
                                    </span>
                                    @if($person->membership_status_since)
                                        <small class="text-muted mx-1">
                                            [ sinds
                                            <span class="data-display text-muted-dark" title="Datum van huidige Lidmaatschapstatus">
                                                {{ $person->membership_status_since->formatLocalized('%e %B %Y') }}
                                            </span>
                                            ]
                                        </small>
                                    @endif
                                </td>
                            </tr>
                            @if($person->address)
                                <tr>
                                    <th><i class="fa fa-map-marker"></i></th>
                                    <td>
                                        <span class="data-display" title="Woonplaats">{{ $person->address->locality }}</span>
                                        <small class="text-muted mx-1">
                                            (
                                            <span class="data-display text-muted-dark" title="Land">{{ $person->address->country }}</span>
                                            )
                                        </small>
                                    </td>
                                </tr>
                            @endif
                            @if($person->birth_date)
                                <tr>
                                    <th><i class="fa fa-birthday-cake text-muted"></i></th>
                                    <td>
                                        <span class="data-display" title="Geboortedatum">{{ $person->birth_date->formatLocalized('%e %B %Y') }}</span>
                                        <small class="{{ $person->age >= 18 ? 'text-muted' : 'text-danger' }} mx-1">
                                            (
                                            <span class="data-display" title="Leeftijd">{{ $person->age }}</span>
                                            )
                                        </small>
                                    </td>
                                </tr>
                            @endif
                            @if($person->email_address)
                            <tr>
                                <th><i class="fa fa-at text-muted"></i></th>
                                <td>
                                    <span class="data-display" title="E-mailadres">
                                        {{ $person->email_address }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @if($person->phone_number)
                            <tr>
                                <th><i class="fa fa-phone text-muted"></i></th>
                                <td>
                                    <span class="data-display tracking-wide" title="Telefoonnummer">
                                        {{ $person->phone_number }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @if($person->cardOwnership)
                            <tr>
                                <th><i class="fa fa-id-card-o text-muted"></i></th>
                                <td>
                                    <span class="data-display tracking-wide" title="Koornbeurs-pas nummer">
                                        {{ $person->cardOwnership->card->id }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">Groepen</h3>
                    </div>

                    <div class="card-body">
                        <div class="tags">
                            @foreach($person->groupMemberships as $groupMembership)
                                <group-tag id="{{ $groupMembership->group_id }}"></group-tag>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-8">

            </div>

        </div>
    </div>
@endsection
