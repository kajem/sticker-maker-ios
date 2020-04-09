<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sticker Maker</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #000080;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .content{
                background: #ffffff;
                width: 400px;
                margin: 0 auto;
                min-height: 500px;
                border-radius: 9px;
                padding: 10px;
            }
            h2, h3{
                padding: 0;
                margin: 0;
            }
            div.image{
                border: 1px solid #efefef;
                width: 100px;
                height: 100px;
                float: left;
                padding: 5px;
                margin: 5px;
            }
            img{
                max-width: 100px;
                max-height: 100px;
            }
            .clearfix{
                clear: both;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="row"> 
                <div class="content">
                    @if(!empty($pack->id))
                       <h2>{{$pack->name}}</h2>
                       <h3>By <i>{{$pack->author}}</i></h3>
                       @php 
                        $stickers = json_decode($pack->stickers);
                       @endphp
                       @if(!empty($stickers))
                            @foreach($stickers as $sticker)
                                <div class="image">
                                    <img src="/storage/sticker-packs/{{$pack->code}}/256__{{$sticker}}" alt="" />
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                       @endif
                    @else
                        No packs found.
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>