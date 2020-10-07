@extends('layouts.front-template',
[
    'page_title' => 'Sticker Maker for Messenger Apps',
    'meta_title' => 'Sticker Maker for Messenger Apps',
    'meta_description' => "Use Sticker Maker to create memes or stickers for Whatsapp, iMessege, other social apps. Get animated stickers and cool stickers from chat sticker packs!"
])
@section('content')
    @include('frontpage.hero-area')
    @include('frontpage.sticker-pack')
    @include('frontpage.awesome-features')
    @include('frontpage.how-to-make')
{{--    @include('frontpage.graphics-designer')--}}
    @include('frontpage.contact-us')
@endsection
