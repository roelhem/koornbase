@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            @include('shared.logo')
            {{ config('app.name') }} <span class="organisation-name">[van {{ config('about.organisation.name') }}]</span>
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} <a href="{{ config('about.copyright.owner.homepage') }}">{{ config('about.copyright.owner.name') }}</a>. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
