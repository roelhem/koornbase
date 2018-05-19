<div class="header py-4">
    <b-container>
        <div class="d-flex">

            <a class="header-brand" href="/">
                <img src="/images/koornbase/server.svg" class="header-brand-img" alt="Koornbase Admin logo">
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
    </b-container>
</div>

<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <b-container>
        <b-row class="align-items-center">
            <b-col lg order-lg="first">
                <b-nav tabs class="border-0 flex-column flex-lg-row">
                    @inject('navbar', 'App\Services\Navigation\NavbarService')
                    @foreach($navbar as $item)
                        @if($item->children)
                            <b-nav-item-dropdown no-caret href="{{ $item->href }}" extra-menu-classes="dropdown-menu-arrow" :no-flip="true">
                                <template slot="text">{!! $item->icon('fe') !!} {{ $item->label }}</template>
                                @foreach($item as $child)
                                    <b-dropdown-item href="{{ $child->href }}">
                                        {!! $child->icon(['dropdown-icon'],'fe','fa') !!} {{ $child->label }}
                                    </b-dropdown-item>
                                @endforeach
                            </b-nav-item-dropdown>
                        @else
                            <b-nav-item href="{{ $item->href }}">{!! $item->icon('fe') !!} {{ $item->label }}</b-nav-item>
                        @endif
                    @endforeach
                </b-nav>
            </b-col>
        </b-row>
    </b-container>
</div>