<a href="{{ $item->href }}" class="{{ $item->linkClass('dropdown-item') }}">
    {!! $item->icon(['dropdown-icon'],'fe','fa') !!}
    {{ $item->label }}
</a>