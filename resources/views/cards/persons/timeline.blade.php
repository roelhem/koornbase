<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tijdlijn</h3>
    </div>

    <div class="card-body">
        <ul class="timeline">
        @foreach($person->timeline as $item)
            <li class="timeline-item">
                <div class="timeline-badge {{ $item['badge'] }}"></div>
                <div>
                    {{ $item['label'] }}
                </div>
                <div class="timeline-time">{{ $item['date'] }}</div>
            </li>
        @endforeach
        </ul>
    </div>
</div>