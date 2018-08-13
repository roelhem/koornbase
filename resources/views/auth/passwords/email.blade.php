@extends('layouts.single')

@section('content')

    <div class="card">

        @csrf

        <div class="card-body p-6">

            <div class="card-title">Wachtwoord Herstellen</div>
            @if(session('status') !== null)
                <p class="text-muted-dark card-text">{{ session('status') }}</p>

                <a href="{{ route('index') }}" class="btn btn-block btn-outline-info">KoornBase Homepage</a>
            @else
            <form action="{{ route('password.email') }}" method="post">
                <p class="text-muted card-text">
                    Geef het e-mailadres waarmee je normaal gesproken inlogd bij de Koornbeurs.
                </p>

                <div class="form-group">
                    <label class="form-label">E-Mailadres</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                           name="email" value="{{ old('email') }}" required placeholder="Vul hier je e-mail in.">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">Wachtwoord Herstellen</button>
                </div>
            </form>
            @endif
        </div>

    </div>

    <div class="text-center text-muted">
        Nogmaals proberen? <a href="{{ route('login') }}">Ga terug</a> naar het inlogscherm.
    </div>

@endsection
