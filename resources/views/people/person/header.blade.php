<div class="map-header" style="background-image: url(https://upload.wikimedia.org/wikipedia/commons/7/76/Vleeshal_Delft.jpg); background-size: cover; background-position: center; z-index: -1;">
</div>

<div class="container">
    <div class="d-flex">
        <div class="person-avatar py-1 px-2">
            <user-avatar letters="{{ $person->avatar_letters }}" image="{{ $person->avatar }}" class="avatar-xl"></user-avatar>
        </div>
        <div class="person-name p-1">
            <h1 class="m-1">
                {{ $person->name_first }}
                @if($person->name_nickname)<small class="font-italic">[ {{ $person->name_nickname }} ]</small>@endif
                {{ $person->name_prefix }}
                {{ $person->name_last }}
            </h1>
            <div class="tags">
                @foreach($person->currentGroupMemberships as $groupMembership)
                    @if($groupMembership->group->category->options['showOnPersonsPage'])
                        <group-tag id="{{ $groupMembership->group_id }}" label-type="member_name"></group-tag>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="container my-4">
    <nav class="navbar">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="{{ route('people.person', ['person' => $person]) }}">Tijdlijn</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Info</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('people.person.contact', ['person' => $person]) }}">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Evenementen</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Groepen</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Statestieken</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Social Media</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Accounts</a></li>
        </ul>
    </nav>
</div>