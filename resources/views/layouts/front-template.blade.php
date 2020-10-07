<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="website" />
    <meta property="og:url" ontent="{{url()->current()}}" />
    <meta property="og:site_name" content="Sticker Maker for Messenger Apps" />
    <meta property="og:title" content="{{ !empty($meta_title) ? $meta_title : 'Sticker Maker | Sticker.Me' }}" />
    <meta property="og:description" content="{{!empty($meta_description) ? $meta_description : ''}}" />
    <meta name="description" content="{{!empty($meta_description) ? $meta_description : ''}}" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="theme-color" content="#00b129" />

    <title>Sticker Maker | {{!empty($page_title) ? $page_title : 'Personalize your own stickers easily!'}}</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/favicon-96x96.png" />

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css"">
    <link rel="stylesheet" href="/css/app.css?asdf">
    <link href="/css/style.css?ti20s4" rel="stylesheet">
    <!-- jQuery -->
    <script src="/bower_components/admin-lte/plugins/jquery/jquery.min.js"></script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TK3ZJCV');</script>
    <!-- End Google Tag Manager -->
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
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
<script src="/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
{{--<script src="{{ asset("resources/js/bootstrap.js") }}"></script>--}}
{{--<script src="{{ asset("js/common.js?sdf") }}"></script>--}}
</body>
</html>
