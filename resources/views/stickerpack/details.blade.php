<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sticker Maker</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="/css/pack-details.css" rel="stylesheet">
    </head>
    <body>
        <div class="position-ref full-height">
            <div class="row"> 
                <div class="content">
                    @if(!empty($pack->id))
                       <h2>{{$pack->name}}</h2>
                       <h3>By <i>{{$is_braincraft_pack ? $pack->author->name : $pack->author}}</i></h3>
                       @php 
                       if($is_braincraft_pack)
                            $stickers = unserialize($pack->stickers);
                        else
                            $stickers = json_decode($pack->stickers);
                       @endphp
                       @if(!empty($stickers))
                            @foreach($stickers as $sticker)
                                <div class="image">
                                <img src="/storage/{{$pack_root_folder}}/{{$pack->code}}/{{$sticker}}" alt="" />
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                       @endif
                       <div class="center mt-20">
                            {{-- <a  href="/get-pack/{{$pack->code}}">Get Stickers</a> --}}
                            <a  href="stickermakerui://{{$pack->code}}">Get Stickers</a>
                       </div>
                    @else
                        <h2>No packs found.</h2>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>