@extends('layouts.home')

@section('content')

    <section class="probootstrap-cover">
        <div class="container">
            <div class="row probootstrap-vh-75 align-items-center text-left">
                <div class="col">
                    <div class="probootstrap-text">
                        <h1 class="probootstrap-heading text-white mb-4">
                            KoornBase voor Ontwikkelaars
                        </h1>
                        <div class="probootstrap-subheading mb-5">
                            <p class="h4 font-weight-normal">
                                Informatie voor ontwikkelaars die apps willen bouwen die gebruik maken van de KoornBase.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="probootstrap-section">
        <div class="container">
            <div class="pb-4">
                <h1 class="probootstrap-heading">Documentatie</h1>
                <p style="font-size: 1.4rem">Hieronder vind je de links naar de documentatie van de onderdelen van de <em>KoornBase</em>.</p>
            </div>

            <div class="row">
                <div class="col-md-4 my-2">
                    <h2>Algemeen</h2>
                    <p>
                        De documentatie van de algemene structuur van de KoornBase.
                    </p>

                    <p>
                        <a href="#" target="_blank" class="btn btn-outline-info btn-block">Open documentatie</a>
                    </p>
                </div>

                <div class="col-md-4 my-2">
                    <h2>OAuth2 Server</h2>
                    <p>
                        Deze documentatie beschrijft hoe je kunt inloggen op de KoornBase vanaf een externe applicatie.
                    </p>

                    <p>
                        <a href="#" target="_blank" class="btn btn-outline-info btn-block">Open documentatie</a>
                    </p>
                </div>

                <div class="col-md-4 my-2">
                    <h2>GraphQL</h2>
                    <p>
                        Deze documentatie beschrijft hoe je gegevens kunt opvragen/veranderen met de
                        <em>GraphQL</em> API.
                    </p>

                    <p>
                        <a href="#" target="_blank" class="btn btn-outline-info btn-block">Open documentatie</a>
                    </p>
                </div>

                <div class="col-md-4 my-2">
                    <h2>REST-API</h2>
                    <p>
                        Deze documentatie beschrijft hoe je de REST-API kunt gebruiken. Hier vind je ook een
                        beschrijving van alle <em>endpoints</em> en <em>methods</em> die je kunt gebruiken met de
                        REST-API.
                    </p>

                    <p>
                        <a href="#" target="_blank" class="btn btn-outline-info btn-block">Open documentatie</a>
                    </p>
                </div>
            </div>


        </div>
    </section>





    <section class="probootstrap-section-feature py-4">
        <div class="container">
            <div class="row">

                <div class="col-md-8">
                    <h1 class="probootstrap-heading">Voorbeelden</h1>
                    <p style="font-size: 1.4rem">Hieronder staan enkele simpele oefenprojecten die je stap voor stap kunt volgen.</p>
                    <div class="probootstrap-item">
                        <h3>Simpele javascript app</h3>
                        <p>
                            Een voorbeeld hoe je een simpele javascript web-app kunt maken die communiceert met de KoornBase.
                        </p>
                    </div>
                    <div class="probootstrap-item">
                        <h3>App in Swift met Apollo iOS</h3>
                        <p>
                            Een voorbeeld van hoe je zeer snel een iOS kunt maken met behulp van <em>GraphQL</em> en
                            de library <em>Apollo iOS</em>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>




    <section class="probootstrap-section">
        <div class="container">
            <div class="pb-4">
                <h1 class="probootstrap-heading">Ontwikkelaars-tools</h1>
                <p style="font-size: 1.4rem">De onderstaande programma's maken het makkelijker om apps te ontwikkelen voor de KoornBase.</p>
            </div>

            <div class="row">

                <div class="col-md-4 my-2">
                    <h2>GraphiQL</h2>
                    <p>
                        Een web-app waarmee <em>GraphQL-queries</em> geschreven en getest kunnen worden.
                    </p>
                    <ul>
                        <li>Een <strong>documentatie</strong> die altijd de huidige api beschrijft.</li>
                        <li><strong>Syntax highlighting</strong> van zowel de <em>GraphQL</em>-query als variabelen en <em>JSON-response</em>.</li>
                        <li><strong>Code Hinting</strong> van <em>fields</em>, <em>types</em> en <em>arguments</em>.</li>
                    </ul>

                    <p>
                        <a href="{{ route('graphiql') }}" target="_blank" class="btn btn-outline-primary btn-block">Open GraphiQL</a>
                    </p>
                </div>

            </div>
        </div>
    </section>


@endsection