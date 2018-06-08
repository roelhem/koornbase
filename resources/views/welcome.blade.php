@extends('layouts.app')

@section('title', 'Welkom')

@section('content')

    @inject('items', 'App\Services\Navigation\NavigationItemRepository')

    <b-container>

        <b-row>
            <b-col>

                <p>Welkom op de KoornBase website</p>

                <h2>De door Aron gemaakte logo's</h2>

                <b-row class="row">
                    <b-col>
                        <img src="/images/koornbase/empty.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/cloud-eye.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/cloud-eye-slash.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/cloud-key.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/feed.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/server.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/shield-admin.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/shield-cog.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/shield-delft.svg">
                    </b-col>
                    <b-col>
                        <img src="/images/koornbase/user.svg">
                    </b-col>
                </b-row>

                <h2 class="my-8">Permissions!</h2>

                <p>{{ var_dump(\App\Facades\Rbac::userRoles()) }}</p>
            </b-col>

            <b-col lg="4">

                <b-card>
                    <h3 class="card-title" slot="header"><i class="fa fa-paint-brush text-muted mr-2"></i> Styling Referenties</h3>

                    <h4>Tablar</h4>
                    <p>Dit is de Bootstrap 4 template waarop deze website gebaseerd is.</p>

                    <div class="mt-2 mb-4">
                        <b-button href="https://tabler.github.io/tabler/" block variant="outline-primary" target="_blank">
                            <i class="fa fa-eye mr-2"></i> Online Demo
                        </b-button>
                        <b-button href="https://tabler.github.io/tabler/docs/index.html" block variant="outline-secondary" target="_blank">
                            <i class="fa fa-book mr-2"></i> Documentatie
                        </b-button>
                        <b-button href="https://github.com/tabler/tabler" block variant="outline-secondary" target="_blank">
                            <i class="fa fa-code mr-2"></i> Source-code
                        </b-button>
                    </div>

                    <h4>FontAwesome Icons</h4>

                    <p>De icons die vooral in het middelste gedeelte van de website worden gebruikt.</p>

                    <div class="mt-2 mb-4">
                        <b-button href="https://fontawesome.com/v4.7.0/icons/" target="_blank" block variant="outline-info">
                            <i class="fa fa-search mr-2"></i> Lijst van alle icons (v4.7.0)
                        </b-button>
                    </div>

                    <h4>Feather Icons</h4>

                    <p>De icons die vooral in de header worden gebruikt.</p>

                    <div class="my-2 mb-4">
                        <b-button href=https://feathericons.com/" target="_blank" block variant="outline-info">
                            <i class="fa fa-search mr-2"></i> Lijst van alle icons
                        </b-button>
                    </div>


                    <p class="text-muted-dark font-weight-bold">Dit zou alleen zichtbaar moeten zijn als je bezig bent met het onwikkelen van de KoornBase website.</p>
                </b-card>


                <b-card class="card">
                    <h3 class="card-title" slot="header"><i class="fa fa-plug text-muted mr-2"></i> Externe Admin-consoles</h3>

                    <h4>Google APIs</h4>

                    <p>Wordt hoofdzakelijk gebruikt voor inloggen met een Google account. Verder zijn er nog wat instellingen voor het gebruik van GoogleMaps.</p>

                    <p>
                        <b-button href="https://console.developers.google.com/apis/dashboard?project=koornbase" target="_blank" variant="google" block>
                            <i class="fa fa-tachometer mr-2"></i> KoornBase Dashboard
                        </b-button>
                    </p>

                    <h4>Facebook Graph API</h4>

                    <p>Wordt hoofdzakelijk gebruikt voor inloggen met een Facebook account en het ophalen van de afbeeldingen van personen.</p>

                    <p>
                        <b-button href="https://developers.facebook.com/apps/1279156192221650/dashboard/" target="_blank" variant="facebook" block>
                            <i class="fa fa-tachometer mr-2"></i> KoornBase Dashboard
                        </b-button>
                    </p>

                    <h4>GitHub Developer</h4>

                    <p>Voor snel inloggen via een GitHub account vanuit virtual servers.</p>

                    <p>
                        <b-button href="https://github.com/settings/applications/738192" target="_blank" variant="github" block>
                            <i class="fa fa-tachometer mr-2"></i> KoornBase Developer Settings
                        </b-button>
                    </p>


                    <p class="text-muted-dark font-weight-bold">Dit zou alleen zichtbaar moeten zijn als je bezig bent met het onwikkelen van de KoornBase website.</p>

                </b-card>

            </b-col>
        </b-row>

    </b-container>

@endsection()