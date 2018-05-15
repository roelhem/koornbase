@extends('layouts.app')

@section('content')

    @include('people.person.header')

    <div class="container">

        <div class="row">
            <div class="col-xl-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Contactgegevens</h3>
                    </div>


                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-xl-12 my-2">
                                <h3>E-mailadressen</h3>
                                @foreach($person->emailAddresses as $emailAddress)
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <strong class="{{ $emailAddress->for_emergency ? 'text-danger' : ($emailAddress->is_primary ? 'text-primary' : null) }}">{{ $emailAddress->label }}</strong>
                                        </div>
                                        <div class="col-lg-9 {{ $emailAddress->for_emergency ? 'text-danger' : ($emailAddress->is_primary ? null : 'text-muted-dark') }}">
                                            <span>{{ $emailAddress->email_address }}</span>
                                            <a href="mailto:{{ $person->name }} <{!! $emailAddress->email_address !!}>" class="btn btn-sm">
                                                <i class="fa fa-envelope-square btn-icon"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-xl-12 my-2">
                                <h3>Telefoonnummers</h3>
                                @foreach($person->phoneNumbers as $phoneNumber)
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <strong class="{{ $phoneNumber->for_emergency ? 'text-danger' : ($phoneNumber->is_primary ? 'text-primary' : null) }}">{{ $phoneNumber->label }}</strong>

                                        </div>
                                        <div class="col-lg-9 {{ $phoneNumber->for_emergency ? 'text-danger' : ($phoneNumber->is_primary ? null : 'text-muted-dark') }}">
                                            <span class="tracking-wide">{{ $phoneNumber }}</span>
                                            <a href="tel:{{ $phoneNumber->phone_number->formatForMobileDialingInCountry('NL') }}" class="btn btn-sm">
                                                <i class="fa fa-phone-square btn-icon"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-xl-12 my-2">
                                <h3>Adressen</h3>

                                @inject('formatter', 'CommerceGuys\Addressing\Formatter\FormatterInterface')

                                <?php
                                $formatter->setLocale('NL');
                                ?>

                                @foreach($person->addresses as $address)
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <strong class="{{ $address->for_emergency ? 'text-danger' : ($address->is_primary ? 'text-primary' : null) }}">{{ $address->label }}</strong>
                                        </div>
                                        <div class="col-lg-9 {{ $address->for_emergency ? 'text-danger' : ($address->is_primary ? null : 'text-muted-dark') }}">
                                            {!! $formatter->format($address) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-6">
                <card-flexible title="Google Maps">
                    <div class="card-map" style="position:relative; overflow:hidden; height: 30rem;">
                        <gmap-map :center="{lat:52.0118137, lng:4.3569288}" :zoom="15" style="width: 100%; height: 100%; position: absolute; top:0px; left:0px"></gmap-map>
                    </div>
                </card-flexible>
            </div>

        </div>

    </div>

@endsection