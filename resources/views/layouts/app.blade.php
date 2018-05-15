@extends('layouts.base')

@section('page')
    <div class="page-main">

        @include('shared.header')

        @inject('breadcrumb', 'App\Services\Navigation\BreadcrumbService')

        <div class="my-3 my-md-5">

            @hasSection('title')
            <div class="container">
                <div class="page-header">
                    <h1 class="page-title">
                        @yield('title')

                    </h1>


                    <div class="page-options d-flex">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb my-0 bg-transparent">
                                @each('shared.nav.breadcrumb-item', $breadcrumb->pathToCurrent(), 'item')
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            @endif

            @yield('content')

        </div>

    </div>

    @include('shared.footer')
@endsection

<?php /*
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>


                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

</body>
</html>

  */ ?>
