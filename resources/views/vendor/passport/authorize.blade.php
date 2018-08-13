@extends('layouts.single')


<!--

Authorize request:

https://127.0.0.1:44300/oauth/authorize?client_id=2&redirect_uri=https://homestead.test/test/callback&response_type=code&scope=*

-->

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="media">
                <span class="avatar avatar-xl avatar-blue mr-5">AB</span>
                <div class="media-body">
                    <h4 class="m-0">Roel Hemerik</h4>
                    <p class="text-muted mb-0">
                        ik@roelweb.com
                        <span class="font-italic">(roelhem)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="card">

        <div class="card-body p-6">

            <h1 class="card-title card-text">Een app wilt je account gebruiken!</h1>

            <div class="small text-muted">(Client: <em>{{ $client->name }}</em>)</div>

            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-muted-dark">Naam van App</h4>
                    <p class="card-text text-center text-muted small">Door: <span class="font-italic">Roel Hemerik</span></p>
                    <p class="card-text text-center text-muted-dark">Hier een korte omschrijving van wat de app doet. Dit verduidelijkt de aanvraag voor de gebruiker.</p>
                </div>
            </div>


            <p class="card-text">
                De bovenstaande applicatie wilt toegang tot je <em>Koornbeurs-account</em>.
                @if(count($scopes) > 0)
                    Daarnaast wilt deze app:
                @endif
            </p>

            <!-- SCOPES -->
            @if (count($scopes) > 0)
            <ul>
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
            @endif

            <p class="text-muted small">
                Controleer vooral of de <strong>naam</strong> en <strong>client</strong> van de applicatie kloppen.
            </p>

            <div class="mb-2">
                <!-- Authorize Button -->
                <form method="post" action="/oauth/authorize">
                    {{ csrf_field() }}

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <button type="submit" class="btn btn-success btn-block btn-approve">
                        <i class="fe fe-check mr-1"></i> Accepteren
                    </button>
                </form>
            </div>


            <div>
                <!-- Cancel Button -->
                <form method="post" action="/oauth/authorize">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <button class="btn btn-danger btn-block">
                        <i class="fe fe-x mr-1"></i> Weigeren
                    </button>
                </form>
            </div>

        </div>

    </div>

@endsection



<?php /*

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Authorization</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <style>
        .passport-authorize .container {
            margin-top: 30px;
        }

        .passport-authorize .scopes {
            margin-top: 20px;
        }

        .passport-authorize .buttons {
            margin-top: 25px;
            text-align: center;
        }

        .passport-authorize .btn {
            width: 125px;
        }

        .passport-authorize .btn-approve {
            margin-right: 15px;
        }

        .passport-authorize form {
            display: inline;
        }
    </style>
</head>
<body class="passport-authorize">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Authorization Request
                    </div>
                    <div class="card-body">
                        <!-- Introduction -->
                        <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

                        <!-- Scope List -->
                        @if (count($scopes) > 0)
                            <div class="scopes">
                                    <p><strong>This application will be able to:</strong></p>

                                    <ul>
                                        @foreach ($scopes as $scope)
                                            <li>{{ $scope->description }}</li>
                                        @endforeach
                                    </ul>
                            </div>
                        @endif

                        <div class="buttons">
                            <!-- Authorize Button -->
                            <form method="post" action="/oauth/authorize">
                                {{ csrf_field() }}

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <button type="submit" class="btn btn-success btn-approve">Authorize</button>
                            </form>

                            <!-- Cancel Button -->
                            <form method="post" action="/oauth/authorize">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <button class="btn btn-danger">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> */ ?>
