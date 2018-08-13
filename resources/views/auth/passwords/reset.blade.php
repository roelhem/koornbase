@extends('layouts.single')

@section('content')



<div class="card">

    <div class="card-body p-6">
        <div class="card-title">Wachtwoord Opnieuw Instellen</div>

        <form action="{{ route('password.request') }}" method="post">

            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <p class="text-muted">
                Geef je e-mailadres en een nieuw wachtwoord om je wachtwoord opnieuw in te stellen.
            </p>

            <div class="form-group">
                <label class="form-label">E-Mailadres</label>
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                       name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="Vul hier je e-mail in.">

                @if ($errors->has('email'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Wachtwoord</label>
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                       name="password" required placeholder="Geef een nieuw wachtwoord.">

                @if ($errors->has('password'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('password') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Wachtwoord Controle</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required placeholder="Herhaal het nieuwe wachtwoord.">
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">Wachtwoord Herstellen</button>
            </div>

        </form>
    </div>

</div>

@endsection

<?php /*

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

 */ ?>