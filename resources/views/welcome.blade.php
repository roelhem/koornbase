@extends('layouts.app')

@section('title', 'Welkom')

@section('content')

    @inject('items', 'App\Services\Navigation\NavigationItemRepository')

    <div class="container">

        <div class="row">
            <div class="col-lg-8">
                <p>Welkom op de KoornBase website</p>

                <h2>De door Aron gemaakte logo's</h2>

                <div class="row">
                    <div class="col-lg-2">
                        <img src="/images/koornbase/empty.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/cloud-eye.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/cloud-eye-slash.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/cloud-key.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/feed.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/server.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/shield-admin.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/shield-cog.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/shield-delft.svg">
                    </div>
                    <div class="col-lg-2">
                        <img src="/images/koornbase/user.svg">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-paint-brush text-muted mr-2"></i> Styling Referenties</h3>
                    </div>

                    <div class="card-body">
                        <h4>Tablar</h4>
                        <p>Dit is de Bootstrap 4 template waarop deze website gebaseerd is.</p>

                        <div class="text-right mt-2 mb-4">
                            <a href="https://tabler.github.io/tabler/" target="_blank" class="btn btn-outline-primary btn-block">
                                <i class="fa fa-eye mr-2"></i> Online Demo
                            </a>
                            <a href="https://tabler.github.io/tabler/docs/index.html" target="_blank" class="btn btn-outline-secondary btn-block">
                                <i class="fa fa-book mr-2"></i> Documentatie
                            </a>
                            <a href="https://github.com/tabler/tabler" target="_blank" class="btn btn-outline-secondary btn-block">
                                <i class="fa fa-code mr-2"></i> Source-code
                            </a>
                        </div>

                        <h4>FontAwesome Icons</h4>

                        <p>De icons die vooral in het middelste gedeelte van de website worden gebruikt.</p>

                        <div class="text-right mt-2 mb-4">
                            <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank" class="btn btn-outline-info btn-block">
                                <i class="fa fa-search mr-2"></i> Lijst van alle icons (v4.7.0)
                            </a>
                        </div>

                        <h4>Feather Icons</h4>

                        <p>De icons die vooral in de header worden gebruikt.</p>

                        <div class="text-right my-2 mb-4">
                            <a href=https://feathericons.com/" target="_blank" class="btn btn-outline-info btn-block">
                                <i class="fa fa-search mr-2"></i> Lijst van alle icons
                            </a>
                        </div>


                        <p class="text-muted-dark font-weight-bold">Dit zou alleen zichtbaar moeten zijn als je bezig bent met het onwikkelen van de KoornBase website.</p>

                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-plug text-muted mr-2"></i> Externe Admin-consoles</h3>
                    </div>
                    <div class="card-body">

                        <h4>Google APIs</h4>

                        <p>Wordt hoofdzakelijk gebruikt voor inloggen met een Google account. Verder zijn er nog wat instellingen voor het gebruik van GoogleMaps.</p>

                        <p>
                            <a href="https://console.developers.google.com/apis/dashboard?project=koornbase" target="_blank" class="btn btn-google btn-block">
                                <i class="fa fa-tachometer mr-2"></i> KoornBase Dashboard
                            </a>
                        </p>

                        <h4>Facebook Graph API</h4>

                        <p>Wordt hoofdzakelijk gebruikt voor inloggen met een Facebook account en het ophalen van de afbeeldingen van personen.</p>

                        <p>
                            <a href="https://developers.facebook.com/apps/1279156192221650/dashboard/" target="_blank" class="btn btn-facebook btn-block">
                                <i class="fa fa-tachometer mr-2"></i> KoornBase Dashboard
                            </a>
                        </p>

                        <h4>GitHub Developer</h4>

                        <p>Voor snel inloggen via een GitHub account vanuit virtual servers.</p>

                        <p>
                            <a href="https://github.com/settings/applications/738192" target="_blank" class="btn btn-github btn-block">
                                <i class="fa fa-tachometer mr-2"></i> KoornBase Developer Settings
                            </a>
                        </p>


                        <p class="text-muted-dark font-weight-bold">Dit zou alleen zichtbaar moeten zijn als je bezig bent met het onwikkelen van de KoornBase website.</p>


                    </div>
                </div>

            </div>
        </div>


    </div>

@endsection()