@extends('layouts.base')

@section('page')
    <div class="page-single">

        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center mb-2">
                        <img src="/images/koornbase/cloud-key.svg" class="h-7 align-bottom mr-1"> <span style="font-size: 24px;">KoornBase <strong>Admin</strong></span>
                    </div>

                    @yield('content')
                </div>
            </div>
        </div>

    </div>
@endsection