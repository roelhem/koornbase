<div class="header py-4">
    <div class="container">
        <div class="d-flex">

            <a class="header-brand" href="/">
                <span class="font-weight-normal">KoornBase </span> Admin
            </a>

            <div class="d-flex order-lg-2 ml-auto">

                <div class="nav-item d-none d-md-flex">
                    @guest

                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary"><i class="fe fe-log-in"></i> Inloggen</a>

                    @else

                        @include('shared.user-info')

                    @endguest
                </div>

            </div>

            <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
            </a>

        </div>
    </div>
</div>

<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    @inject('navbar', 'App\Services\Navigation\NavbarService')
                    @each('shared.nav.nav-item', $navbar, 'item')
                </ul>
            </div>
        </div>
    </div>
</div>