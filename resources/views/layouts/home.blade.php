<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>
    <title>KoornBase - De database backend van O.J.V. de Koornbeurs</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="De database en backend van O.J.V. de Koornbeurs." />
    <meta name="author" content="koornbeurs.nl" />

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ mix('/css/home.css') }}">
</head>

<body>

    @include('shared.home-navbar')

    @yield('content')

    @include('shared.home-footer')

    <script src="{{ mix('/js/home.js') }}"></script>

</body>

</html>