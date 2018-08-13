<!doctype html>

<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RBAC-Graph Dashboard</title>

    <link href="{{ asset('vendor/rbac-graph/css/graph-dashboard.css') }}" rel="stylesheet">

</head>

<body>

<div class="page" id="app">
    @yield('page')
</div>



<script src="{{ asset('vendor/rbac-graph/js/graph-dashboard.js') }}"></script>

</body>

</html>