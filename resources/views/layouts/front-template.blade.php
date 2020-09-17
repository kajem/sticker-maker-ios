<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sticker Maker</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>
<body>
<div class="position-ref full-height flex-center">
    @include('front.common.header')
    @yield('content')
    @include('front.common.footer')
</div>
</body>
</html>
