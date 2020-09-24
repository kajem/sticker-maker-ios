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
    <link href="{{asset('css/style.css?ys4')}}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/jquery/jquery.min.js") }}"></script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TK3ZJCV');</script>
    <!-- End Google Tag Manager -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TK3ZJCV"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="position-ref full-height flex-center">
    @include('common.header')
    @yield('content')
    @include('common.footer')
</div>
<!-- Bootstrap 4 -->
<script src="{{ asset("/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("resources/js/bootstrap.js") }}"></script>
{{--<script src="{{ asset("js/common.js?sdf") }}"></script>--}}
</body>
</html>
