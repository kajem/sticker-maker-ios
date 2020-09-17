@extends('layouts.front-template')
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
                            <img src="{{$pack_root_folder}}{{$pack->code}}/thumb/{{$sticker}}" alt=""/>
                        </div>
                    @endforeach
                    <div class="clear-both"></div>
                @endif
                <div class="text-center mt-3 mb-5">
                    {{-- <a  href="/get-pack/{{$pack->code}}">Get Stickers</a> --}}
                    <a href="stickermakerui://{{$pack->code}}">Get Stickers</a>
                </div>
            @else
                <h2>No packs found.</h2>
            @endif
        </div>
    </div>
@endsection
