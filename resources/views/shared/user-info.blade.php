@inject('repository', '\App\Services\Navigation\NavigationItemRepository')
@auth
<div class="dropdown">


        <?php $user = Auth::user(); ?>

    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
        <base-avatar color="blue" image="{{ $user->avatar }}" letters="{{ $user->avatar_letters }}"></base-avatar>
        <span class="ml-2 d-none d-lg-block">
            <span class="text-default">{{ $user->name_display }}</span>
            <span class="text-muted d-block mt-1 small">{{ $user->email }}</span>
        </span>
    </a>


    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

        @include('shared.nav.dropdown-item', ['item' => $repository->require('me')])

        <a class="dropdown-item" href="#">
            <i class="dropdown-icon fe fe-settings"></i> Instellingen
        </a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="dropdown-icon fe fe-log-out"></i> Uitloggen
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>

</div>
@endauth