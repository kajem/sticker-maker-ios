<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>StickerMaker | Admin</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/adminlte.min.css") }}">
    <!-- Google Font: Source Sans Pro -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset("/css/admin.css") }}">

    <!-- jQuery -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/jquery/jquery.min.js") }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('admin.common.header')
@include('admin.common.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-4 text-right">
                        @if($showBackButton)
                            <a href="{{$backButtonURL}}" class="btn btn-primary">{!! $backButtonText  !!}</a>
                        @endif
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @include('admin.common.notification')
        @yield('content')
        @include('admin.common.footer')

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Bootstrap 4 -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset("/bower_components/admin-lte/dist/js/adminlte.min.js") }}"></script>
    <script src="{{ asset("/js/admin/common.js") }}"></script>
</body>
</html>
