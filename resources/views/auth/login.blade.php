@extends('layouts.single')

@section('content')

    <b-form class="card" action="{{ route('login') }}" method="post">

        @csrf

        <b-card-body class="p-6">
            <div class="card-title">Inloggen op de KoornBase</div>



            <div class="form-group">
                <label class="form-label">E-Mailadres</label>
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                       name="email" value="{{ old('email') }}" required autofocus placeholder="Vul hier je e-mail in.">

                @if ($errors->has('email'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">
                    Wachtwoord
                    <a href="{{ route('password.request') }}" class="float-right small">Wachtwoord Vergeten?</a>
                </label>
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                       name="password" required placeholder="Vul hier je wachtwoord in.">
            </div>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" {{ old('remember') ? 'checked' : '' }} />
                    <span class="custom-control-label">Onthoud Mij</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">Log-in</button>

                <p class="text-center my-2">-- OF --</p>

                <a href="{{ route('login.social', ['provider' => 'facebook']) }}" class="btn btn-facebook btn-block">
                    <i class="fa fa-facebook mr-2"></i> Inloggen met Facebook
                </a>

                <a href="{{ route('login.social', ['provider' => 'google']) }}" class="btn btn-google btn-block">
                    <i class="fa fa-google mr-2"></i> Inloggen met Google
                </a>

                <a href="{{ route('login.social', ['provider' => 'twitter']) }}" class="btn btn-twitter btn-block">
                    <i class="fa fa-twitter mr-2"></i> Inloggen met Twitter
                </a>

                <a href="{{ route('login.social', ['provider' => 'github']) }}" class="btn btn-github btn-block">
                    <i class="fa fa-github mr-2"></i> Inloggen met Github
                </a>

            </div>
        </b-card-body>

    </b-form>

    <div class="text-center text-muted">
        Klik <a href="{{ route('index') }}">hier</a> om naar de welkomspagina te gaan.
    </div>

@endsection