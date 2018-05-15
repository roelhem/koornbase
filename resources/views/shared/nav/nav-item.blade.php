<li class="nav-item @if($item->children)dropdown @endif">

    <a href="{{ $item->href }}" class="{{ $item->linkClass('nav-link') }}" @if($item->children)data-toggle="dropdown" @endif>
        {!! $item->icon('fe') !!}
        {{ $item->label }}
    </a>

    @if($item->children)
    <div class="dropdown-menu dropdown-menu-arrow">
        @each('shared.nav.dropdown-item', $item, 'item')
    </div>
    @endif

</li>