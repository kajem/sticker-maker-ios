@extends('layouts.front-template')
@section('content')
    @include('frontpage.hero-area')
    @include('frontpage.sticker-pack')
    @include('frontpage.awesome-features')
    @include('frontpage.how-to-make')
{{--    @include('frontpage.graphics-designer')--}}
    @include('frontpage.contact-us')
@endsection
