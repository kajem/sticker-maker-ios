<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sticker Maker</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicons/favicon-16x16.png')}}" />
    <link rel="icon" type="image/png" sizes="32x32" href="asset('images/favicons/favicon-32x32.png')}}" />
    <link rel="icon" type="image/png" sizes="96x96" href="asset('images/favicons/favicon-96x96.png')}}" />

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="{{asset('css/app.css?asdf')}}">
    <link href="{{asset('css/style.css?s14s4')}}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/jquery/jquery.min.js") }}"></script>
</head>
<body>
<div class="position-ref full-height flex-center">
    @include('common.header')
    @yield('content')
    @include('common.footer')
</div>
<!-- Bootstrap 4 -->
<script src="{{ asset("/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("js/common.js") }}"></script>
</body>
</html>
