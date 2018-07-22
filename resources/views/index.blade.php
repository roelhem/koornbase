@extends('layouts.home')

@section('content')

    <!-- START: Cover -->
    <section class="probootstrap-cover">
        <div class="container">
            <div class="row probootstrap-vh-100 align-items-center text-center">
                <div class="col">
                    <img src="/images/koornbase/server.svg" class="koornbase-logo" alt="KoornBase Logo" />
                </div>
                <div class="col-12 col-lg-9">
                    <div class="probootstrap-text">
                        <h1 class="probootstrap-heading text-white mb-4">
                            De KoornBase
                        </h1>
                        <div class="probootstrap-subheading mb-5">
                            <p class="h4 font-weight-normal">
                                Het centrale database-systeem van
                                <a href="{{ config('about.organisation.homepage') }}" target="_blank">{{ config('about.organisation.name') }}</a>.
                            </p>
                        </div>
                        <p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary mr-2 mb-2">Inloggen</a>
                            @endguest
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary mr-2 mb-2">Open dashboard</a>
                            @endauth
                            <a href="{{ route('about') }}" class="btn btn-primary btn-outline-white mr-2 mb-2">Over de KoornBase</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Cover -->


    <section class="probootstrap-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="probootstrap-heading">Inleiding</h1>
                    <p>
                        De <em>KoornBase</em> is een systeem van
                        <a href="{{ config('about.organisation.homepage') }}" target="_blank">{{ config('about.organisation.name') }}</a>
                        dat verschillende belangrijke gegevens van de vereniging beheert. Daarnaast bied het systeem
                        een aantal <em>Web-services</em> voor <a href="{{ route('apps') }}">externe toepassingen</a>.
                    </p>

                    <p>
                        Alle applicaties waarbij je met een <em>Koornbeurs-accounts</em> kunt inloggen, maken gebruik
                        van de <em>Web-services</em> van de <em>KoornBase</em>. Doordat de belangrijkste
                        gegevens op &eacute;&eacute;n centrale plek worden opgeslagen, werken deze applicaties
                        automatisch met elkaar.
                    </p>

                    <p>
                        Naast een uitgebreide <em>back-end</em>, bied de <em>KoornBase</em> ook een paar
                        simpele standaard-applicaties (zie hiernaast). Deze applicaties helpen bijvoorbeeld
                        bij <em>inloggen</em>, <em>beheren van de gegevens</em> en het <em>ontwikkelen van apps</em>.
                    </p>

                    <p class="text-right">
                        <a href="{{ route('about') }}" class="btn btn-outline-secondary">Lees meer...</a>
                    </p>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="media">
                            <div class="probootstrap-icon pr-4">
                                <img src="/images/koornbase/cloud-key.svg" alt="KoornBase Logo" />
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0">KoornBase <strong>Login</strong></h4>
                                <p class="mt-0">
                                    Inloggen met een <strong>KB-account</strong> voor de <em>KoornBase</em> of apps die
                                    de <em>KoornBase</em> gebruiken. Ook om apps <strong>toestemming te geven</strong>
                                    om jouw <em>KB-account</em> te gebruiken.
                                </p>
                            </div>
                        </li>
                        <li class="media">
                            <div class="probootstrap-icon pr-4">
                                <img src="/images/koornbase/server.svg" alt="KoornBase Logo" />
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0">KoornBase <strong>Dashboard</strong></h4>
                                <p class="mt-0">
                                    Een <strong>Web-based GUI</strong> voor de Koornbase. Met deze applicatie kun
                                    je de gegevens van de <em>KoornBase</em> beheren.
                                </p>
                            </div>
                        </li>
                        <li class="media">
                            <div class="probootstrap-icon pr-4">
                                <img src="/images/koornbase/empty.svg" alt="KoornBase Logo" />
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0">KoornBase <strong>Ontwikkelaars</strong></h4>
                                <p class="mt-0">
                                    Een verzameling van <strong>programmeer-tools</strong> die het makkelijker maken
                                    om apps te bouwen die gebruik maken van de <em>KoornBase</em>.
                                </p>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </section>

    <section class="probootstrap-section-feature py-4">
        <div class="container">
            <div class="row">

                <div class="col-md-6">

                </div>

                <div class="col-md">
                    <h1 class="probootstrap-heading">Een centrale plek voor&hellip;</h1>
                    <div class="probootstrap-item">
                        <h3>&hellip;KB-accounts</h3>
                        <p>
                            De KoornBase beheert de Koornbeurs-accounts en regelt alle inlog-logistiek.
                            Met deze accounts kun je inloggen op de het Wi-Fi netwerk van de Koornbeurs of op
                            een (web-)app die gebruik maakt van de KoornBase.
                        </p>
                    </div>
                    <div class="probootstrap-item">
                        <h3>&hellip;Leden</h3>
                        <p>
                            Alle gegevens over de leden die de Koornbeurs heeft worden in de KoornBase opgeslagen.
                            Denk hierbij aan persoons- en contactgegevens, status van het lidmaatschap en behaalde
                            certificaten.
                        </p>
                        <p>
                            Een lid kan toestemming geven om deze gegevens met andere leden te delen. Hierdoor kunnen
                            de leden de KoornBase gebruiken als een adressenboek.
                        </p>
                    </div>

                    <div class="probootstrap-item">
                        <h3>&hellip;Comissies/Groepen</h3>
                        <p>
                            De KoornBase slaat op in welke comissies/groepen welke leden zitten. Denk hierbij aan
                            besturen, disputen, jaarlagen etc.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="probootstrap-section">
        <div class="container">
            <div class="row">
                <div class="col-md">
                    <h1>Apps voor/met de KoornBase.</h1>
                    <div style="font-size: 1.4rem;" class="pb-2">
                        <p>
                            De KoornBase is vooral gemaakt als algemene back-end voor verschillende toepassingen.
                            De KoornBase bied de volgende technieken om apps te maken.
                        </p>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="media">
                                <div class="probootstrap-icon">
                                    <span class="icon-key display-4"></span>
                                </div>
                                <div class="media-body">
                                    <h5>OAuth2 Server</h5>
                                    <p>
                                        De KoornBase bied een OAuth2 Server (gebaseerd op
                                        <a href="https://laravel.com/docs/5.6/passport" target="_blank">Laravel Passport</a>
                                        ). Gebruik KB-accounts om in te loggen op je app en gebruik gegevens uit de
                                        centrale database.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="media">
                                <div class="probootstrap-icon">
                                    <span class="icon-network display-4"></span>
                                </div>
                                <div class="media-body">
                                    <h5>GraphQL</h5>
                                    <p>
                                        Gebruik de <a href="https://graphql.org" target="_blank">GraphQL</a> API om
                                        gegevens uit de database op te vragen of acties op de KoornBase uit te voeren. Met de
                                        online <a href="{{ route('graphiql') }}">GraphiQL UI</a> schrijf en test je
                                        moeiteloos alle queries voor jouw app.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="media">
                                <div class="probootstrap-icon">
                                    <span class="icon-cloud display-4"></span>
                                </div>
                                <div class="media-body">
                                    <h5>REST API</h5>
                                    <p>
                                        Voor als je geen GraphQL kunt/wilt gebruiken, is er een REST API voor de
                                        veel voorkomende taken.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="media">
                                <div class="probootstrap-icon">
                                    <span class="icon-code display-4"></span>
                                </div>
                                <div class="media-body">
                                    <h5>Code Libraries</h5>
                                    <p>
                                        Voor enkele talen is er een library geschreven. Dit maakt het nog makkelijk om
                                        zelf een app te maken.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-4">
                        <p class="text-right">
                            <a href="{{ route('developers') }}" class="btn btn-outline-secondary">Meer voor ontwikkelaars...</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection