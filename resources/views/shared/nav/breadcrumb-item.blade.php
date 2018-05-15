<li class="breadcrumb-item">
    @if($item->link && !$item->matchesRequest())<a href="{{ $item->href }}">@endif
        {!! $item->icon('fa') !!}
        {{ $item->name }}
    @if($item->link && !$item->matchesRequest())</a>@endif
</li>