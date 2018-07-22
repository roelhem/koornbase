

<nav class="navbar navbar-expand-lg navbar-dark bg-dark probootstrap-navabr-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="/images/koornbase/server.svg" class="navbar-brand-image" alt="Koornbeurs Admin Logo" />
            KoornBase
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="@probootstrap-nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="probootstrap-nav">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Welkom</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">Over</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('apps') }}">Apps</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('developers') }}">Ontwikkelaars</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>


                @guest
                <li class="nav-item probootstrap-cta probootstrap-seperator">
                    <a class="nav-link" href="{{ route('login') }}">Inloggen</a>
                </li>
                @endguest

                @auth
                    <li class="nav-item probootstrap-cta probootstrap-seperator">
                        <div class="dropdown">
                            <a class="nav-link p-3" data-toggle="dropdown" href="{{ route('dashboard') }}">
                                @if(Auth::user()->avatar)
                                    @if(Auth::user()->avatar->image)
                                        <span class="simple-avatar mr-2" style="background-image: url({!! Auth::user()->avatar->image !!});">
                                        </span>
                                    @else
                                        <span class="simple-avatar mr-2">
                                            <span class="simple-avatar-letters">{{ Auth::user()->avatar->letters }}</span>
                                        </span>
                                    @endif
                                @endif
                                <span>
                                {{ Auth::user()->name_display }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <span class="icon-user mr-1"></span> Mijn Account
                                </a>
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <span class="icon-cog mr-1"></span> Instellingen
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:document.getElementById('user-logout-form').submit();" class="dropdown-item">
                                    <span class="icon-log-out mr-1"></span> Uitloggen
                                </a>
                                <form method="post" action="{{ route('logout') }}" style="display: none" id="user-logout-form"></form>
                            </div>

                        </div>
                    </li>
                @endauth
            </ul>
        </div>

    </div>
</nav>