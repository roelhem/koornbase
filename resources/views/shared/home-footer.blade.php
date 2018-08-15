<footer class="probootstrap-footer">

    <div class="container">

        <div class="row">

            <div class="col-lg-4">
                <div class="probootstrap-footer-widget">
                    <h1 class="probootstrap-heading">
                        <img src="/images/koornbase/server.svg" class="koornbase-inline-logo" style="width: 70px;" alt="KoornBase Logo" />
                        KoornBase
                    </h1>
                </div>

                <div class="footer-spacer"></div>

                <div class="probootstrap-footer-widget mt-4">
                    <ul class="probootstrap-footer-social list-unstyled float-md-left float-lft">
                        <li><a href="#"><span class="icon-facebook"></span></a></li>
                        <li><a href="#"><span class="icon-twitter"></span></a></li>
                        <li><a href="#"><span class="icon-dropbox"></span></a></li>
                        <li><a href="#"><span class="icon-creative-cloud"></span></a></li>
                        <li><a href="#"><span class="icon-github"></span></a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md">
                <div class="row">

                    <div class="col-lg">
                        <div class="probootstrap-footer-widget mb-4">
                            <h2 class="probootstrap-heading-2">
                                Welkom
                            </h2>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('index') }}" class="py-2 d-block">Homepage</a></li>
                                <li><a href="{{ route('about') }}" class="py-2 d-block">Over de KoornBase</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="probootstrap-footer-widget mb-4">
                            <h2 class="probootstrap-heading-2">
                                Dashboard
                            </h2>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="py-2 d-block">Dashboard</a>
                                    <ul class="sub-list">
                                        <li><a href="#" class="d-block">Personen</a></li>
                                        <li><a href="#" class="d-block">Groepen</a></li>
                                        <li><a href="#" class="d-block">Gebruikers</a></li>
                                        <li><a href="#" class="d-block">Apps</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('dashboard') }}" class="py-2 d-block">Mijn Gegevens</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="probootstrap-footer-widget mb-4">
                            <h2 class="probootstrap-heading-2">
                                Applicaties
                            </h2>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('apps') }}" class="py-2 d-block">Populair</a></li>
                                <li><a href="{{ route('apps') }}" class="py-2 d-block">Zoeken</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="probootstrap-footer-widget mb-4">
                            <h2 class="probootstrap-heading-2">
                                Ontwikkelaars
                            </h2>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('developers') }}" class="py-2 d-block">Documentatie</a>
                                    <ul class="sub-list">
                                        <li><a href="http://koorndocs.wikidot.com/" target="_blank" class="d-block">Koorndocs Wiki</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('developers') }}" class="py-2 d-block">FAQ</a></li>
                                <li>
                                    <a href="{{ route('developers') }}" class="py-2 d-block">Tools</a>
                                    <ul class="sub-list">
                                        <li><a href="{{ route('graphiql') }}" class="d-block" target="_blank">GraphiQL</a></li>
                                        <li><a href="#" class="d-block">Handige Links</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-md text-left">
                <ul class="list-unstyled footer-small-nav">
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">Terms</a></li>
                    <li><a href="{{ config('about.organisation.homepage') }}" target="_blank">{{ config('about.organisation.name') }}</a></li>
                </ul>
            </div>

            <div class="col-md text-md-right text-left">
                <p>
                    <small>&copy;
                        <a href="{{ config('about.copyright.owner.homepage') }}" target="_blank">{{ config('about.copyright.owner.name') }}</a>
                        {{ config('about.copyright.year') }}. All Rights Reserved. <br />
                        <em>Created by <a href="http://www.roelweb.com">Roel Hemerik</a>.</em>
                    </small>
                </p>
            </div>
        </div>


    </div>

</footer>