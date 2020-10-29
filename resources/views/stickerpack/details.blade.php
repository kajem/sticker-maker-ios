@extends('layouts.front-template',
[
    'page_title' => $pack->name,
    'meta_title' => $pack->meta_title,
    'meta_description' => $pack->meta_description,
    'meta_image' => config('app.asset_base_url'). 'items/'.$pack->code.'/'.$pack->code.'.png'
])
@section('content')
    <div class="content" id="pack-details">
        <div class="container">
            @if(!empty($pack->id))
                <div class="row">
                    <div class="col-sm-12 pt-5 pb-4 text-center">
                        <h2 class="title">{{$pack->name}}</h2>
                        <span>By <i>{{$is_braincraft_pack ? $pack->author : $pack->author}}</i></span>
                    </div>
                </div>
                @php
                    if($is_braincraft_pack)
                         $stickers = unserialize($pack->stickers);
                     else
                         $stickers = json_decode($pack->stickers);
                @endphp
                @if(!empty($stickers))
                    @foreach($stickers as $sticker)
                        <div class="image">
                            @if($is_braincraft_pack)
                                <img class="protect-copy" src="{{$pack_root_folder}}{{$pack->code}}/thumb/{{$sticker}}" alt=""/>
{{--                            <div class="water-mark">stickermakerpro.com</div>--}}
                            @else
                                <img class="protect-copy" src="{{$pack_root_folder}}{{$pack->code}}/{{$sticker}}" alt=""/>
                            @endif
                        </div>
                    @endforeach
                    <div class="clear-both"></div>
                @endif
                <div class="text-center mt-3 mb-5">
                    {{-- <a  href="/get-pack/{{$pack->code}}">Get Stickers</a> --}}
                    <a target="_blank" href="https://apps.apple.com/us/app/id1505991796">Get Stickers</a>
                </div>
            @else
                <h2>No packs found.</h2>
            @endif
        </div>
    </div>
@endsection
<script type="text/javascript" src="/js/protect-right-click-and-drag.js"></script>
