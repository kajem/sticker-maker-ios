<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sticker Maker</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="css/app.css">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <script src="{{ asset("/bower_components/admin-lte/plugins/jquery/jquery.min.js") }}"></script>
</head>
<body>
<div class="position-ref full-height flex-center">
    <section id="top-bar" class="pt-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <a href="{{url('/')}}" class="logo">
                        <h2 class="text-white">
                            <img width="64" src="{{asset('images/logo.png')}}" alt="Sticker Maker"/>
                            Sticker Maker
                        </h2>
                    </a>
                </div>
                <div class="col-sm-7">
                    {{--                <nav class="navbar navbar-expand-lg navbar-light bg-light">--}}
                    {{--                    <a class="navbar-brand" href="#">Navbar</a>--}}
                    {{--                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
                    {{--                        <span class="navbar-toggler-icon"></span>--}}
                    {{--                    </button>--}}

                    {{--                    <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
                    {{--                        <ul class="navbar-nav mr-auto">--}}
                    {{--                            <li class="nav-item active">--}}
                    {{--                                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a class="nav-link" href="#">Link</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item dropdown">--}}
                    {{--                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--                                    Dropdown--}}
                    {{--                                </a>--}}
                    {{--                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
                    {{--                                    <a class="dropdown-item" href="#">Action</a>--}}
                    {{--                                    <a class="dropdown-item" href="#">Another action</a>--}}
                    {{--                                    <div class="dropdown-divider"></div>--}}
                    {{--                                    <a class="dropdown-item" href="#">Something else here</a>--}}
                    {{--                                </div>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a class="nav-link disabled" href="#">Disabled</a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                        <form class="form-inline my-2 my-lg-0">--}}
                    {{--                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
                    {{--                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
                    {{--                        </form>--}}
                    {{--                    </div>--}}
                    {{--                </nav>--}}
                </div>
            </div>

        </div>

    </section>
    <section id="hero-area" class="pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h1 class="text-white">Sticker maker for iMessage, WhatsApp</h1>
                    <h2 class="text-white">Create your personal sticker packs in just <span class="text-bold">&nbsp;3 easy steps!</span>
                    </h2>
                    <p class="mt-3 lead text-white">Send cool stickers in iMessage, WhatsApp and spice up the boring
                        group chats! Share single stickers or entire sticker packs!</p>

                    <div class="mt-5">
                        <div class="card download-now d-inline-block pb-3">
                            <div class="card-body text-center">
                                <p class="download-now-text mx-3 mb-4">Download now</p>
{{--                                <a href="https://play.google.com/store/apps/details?id=com.marsvard.stickermakerforwhatsapp"--}}
{{--                                   class="d-inline-block ml-0 ml-sm-3">--}}
{{--                                    <img width="170" src="{{asset('images/google-play.png')}}" alt="Google Play Button">--}}
{{--                                </a>--}}
                                <a target="_blank" href="https://itunes.apple.com/app/"
                                   class="d-inline-block mr-0 mr-sm-3">
                                    <img width="170" src="{{asset('images/apple_store.png')}}" class="store-img"
                                         alt="Apple Store Button">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <img src="{{asset('images/hero-1.png')}}" alt="App Image"/>
                </div>
            </div>
        </div>
    </section>

    @if(!$items->isEmpty())
    <section id="sticker-packs" class="pb-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center pb-3">Excellent Sticker Packs</h2>

                    @foreach($items as $item)
                        @php
                            $thumb_arr = explode('/', $item->thumb);
                            $thumb = $asset_base_url.'items/'.$item->code.'/200__'.end($thumb_arr);
                        @endphp
                    <div class="card sticker-bg-{{rand(1, 21)}} text-center">
                        <div class="image">
                            <img class="card-img-top" src="{{$thumb}}" alt="Card image cap">
                        </div>
                        <div class="card-body p-0 pt-2 text-center">
                            <span class="font-weight-bold">{{$item->name}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center">
                <a href="#" class="all-sticker-packs">Get all sticker packs</a>
            </div>
        </div>
    </section>
    @endif

    <section id="awesome-features">
        <div class="container pb-5">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center text-white">Awesome Features</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 text-center pt-3">
                    <img height="500" src="{{asset('images/crop-face.png')}}" alt="Cropping a men's face with lesso tool"/>
                </div>
                <div class="col-sm-5">
                    <h3 class="mt-150 font-weight-bold">Precise and Manual trace</h3>
                    <p>
                        Creating a sticker has never been so easy. You can trace with your finger the part of the image you want to turn into a sticker. Then as easily as it seems you can adjust this path by moving the dots that will appear. You can even zoom in to the image to be more precise.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5 offset-1">
                    <h3 class="mt-150 font-weight-bold">Automatic Background Removal</h3>
                    <p>
                        There's a mode in the app that we call 'Magic', and there is a reason for this. As fancy as it sounds, with this feature you can automatically remove the background of the image you have selected. You just need to trace with your finger the part you want to keep and the app will extract it from the background.
                    </p>
                </div>
                <div class="col-sm-6 text-center">
                    <img height="500" src="{{asset('images/remove-background.png')}}" alt="Removing background from an women's photo"/>
                </div>
            </div>
            <div class="text-center">
                <div class="mt-5">
                    <div class="card download-now d-inline-block pb-3">
                        <div class="card-body text-center">
                            <p class="download-now-text mx-3 mb-4">Download now</p>
                            {{--                                <a href="https://play.google.com/store/apps/details?id=com.marsvard.stickermakerforwhatsapp"--}}
                            {{--                                   class="d-inline-block ml-0 ml-sm-3">--}}
                            {{--                                    <img width="170" src="{{asset('images/google-play.png')}}" alt="Google Play Button">--}}
                            {{--                                </a>--}}
                            <a target="_blank" href="https://itunes.apple.com/app/"
                               class="d-inline-block mr-0 mr-sm-3">
                                <img width="170" src="{{asset('images/apple_store.png')}}" class="store-img"
                                     alt="Apple Store Button">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-to-make">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center">Make your own stickers!</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="number-circle float-left">1</div>
                    <div class="float-left text-block">
                        <h3>Select name for your pack</h3>
                        <p>Pick a catchy name for your sticker pack to get started.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-sm-6">
                    <div class="number-circle float-left">2</div>
                    <div class="float-left text-block">
                        <h3>Add the stickers to the pack, cut them with your finger</h3>
                        <p>Add up to 30 stickers from your photos .You can cut the stickers or use transparent PNG files
                            if you have created the stickers in a photo editing app.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="number-circle float-left">3</div>
                    <div class="float-left text-block">
                        <h3>Add to WhatsApp</h3>
                        <p>Add the sticker pack to your WhatsApp, the sticker pack is created just for you.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-sm-6">
                    <div class="number-circle float-left">4</div>
                    <div class="float-left text-block">
                        <h3>ENJOY!</h3>
                        <p>Send the stickers to your friends in WhatsApp and share them in groups. Your stickers will
                            start to spread on WhatsApp and live a life of their own.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
{{--            <div class="mt-3 yellow-bg-download mb-5 text-center">--}}
{{--                <div class="card download-now d-inline-block pb-3">--}}
{{--                    <div class="card-body text-center">--}}
{{--                        <p class="download-now-text mx-3 mb-4">Download now</p>--}}
{{--                        --}}{{--                                <a href="https://play.google.com/store/apps/details?id=com.marsvard.stickermakerforwhatsapp"--}}
{{--                        --}}{{--                                   class="d-inline-block ml-0 ml-sm-3">--}}
{{--                        --}}{{--                                    <img width="170" src="{{asset('images/google-play.png')}}" alt="Google Play Button">--}}
{{--                        --}}{{--                                </a>--}}
{{--                        <a target="_blank" href="https://itunes.apple.com/app/"--}}
{{--                           class="d-inline-block mr-0 mr-sm-3">--}}
{{--                            <img width="170" src="{{asset('images/apple_store.png')}}" class="store-img"--}}
{{--                                 alt="Apple Store Button">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>

    <section id="graphics-designer">
        <div class="container pb-5">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center text-white">Are you Graphics Designer?</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2 offset-3">
                    <img width="150" src="{{asset('images/graphics-designer-funny-face.png')}}" alt="Funny Face Emoji" />
                </div>
                <div class="col-sm-2 offset-1">
                    <img width="150" src="{{asset('images/graphics-designer-profession.png')}}" alt="An emoji related to profession" />
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-2 offset-3">
                    <img width="150" src="{{asset('images/graphics-designer-puppy.png')}}" alt="Puppy Emoji" />
                </div>
                <div class="col-sm-2 offset-1">
                    <img width="150" src="{{asset('images/graphics-designer-women.png')}}" alt="Women Emoji" />
                </div>
            </div>
            <div class="text-center mt-5">
                <h2 class="text-white">Let your sticker packs go viral on WhatsApp and iMessage.</h2>
            </div>

            <div class="mt-5 text-center">
                <div class="card download-now d-inline-block pb-3">
                    <div class="card-body text-center">
                        <p class="download-now-text mx-3 mb-4">Download now</p>
                        {{--                                <a href="https://play.google.com/store/apps/details?id=com.marsvard.stickermakerforwhatsapp"--}}
                        {{--                                   class="d-inline-block ml-0 ml-sm-3">--}}
                        {{--                                    <img width="170" src="{{asset('images/google-play.png')}}" alt="Google Play Button">--}}
                        {{--                                </a>--}}
                        <a target="_blank" href="https://itunes.apple.com/app/"
                           class="d-inline-block mr-0 mr-sm-3">
                            <img width="170" src="{{asset('images/apple_store.png')}}" class="store-img"
                                 alt="Apple Store Button">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="contact-us">
        <div class="container pb-5">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center">Contact Us</h2>
                </div>
            </div>
            <form action="#">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="name" placeholder="Name">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="email" name="email" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="subject" placeholder="Subject">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea rows="5" class="form-control" name="message" placeholder="Message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 text-right">
                            <input type="submit" value="Submit" class="btn btn-primary pl-5 pr-5 pt-2 pb-2">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section id="footer" class="pb-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="{{url('/')}}" class="logo">
                        <h2 class="text-white">
                            <img width="64" src="{{asset('images/logo.png')}}" alt="Sticker Maker"/>
                            Sticker Maker
                        </h2>
                    </a>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-4">
                            <h5 class="font-weight-bold">Products</h5>
                            <ul class="p-0 list-unstyled">
                                <li><a href="#">Link 1</a></li>
                                <li><a href="#">Link 2</a></li>
                                <li><a href="#">Link 3</a></li>
                                <li><a href="#">Link 4</a></li>
                                <li><a href="#">Link 5</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="font-weight-bold">About</h5>
                            <ul class="p-0 list-unstyled">
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Career</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="font-weight-bold">Learn</h5>
                            <ul class="p-0 list-unstyled">
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Tutorial</a></li>
                                <li><a href="#">Challenges</a></li>
                                <li><a href="#">Link 4</a></li>
                                <li><a href="#">Link 5</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4 offset-4">
                    <div class="app-icon">
                        <span>Get the free app</span>
                        <a target="_blank" href="#"><i class="fab fa-apple"></i></a>
                    </div>
                </div>
                <div class="col-sm-4 social-icons text-right">
                    <a target="_blank" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a target="_blank" href="#"><i class="fab fa-twitter"></i></a>
                    <a target="_blank" href="#"><i class="fab fa-instagram"></i></a>
                    <a target="_blank" href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <hr>
            <div class="text-center copyright">
                &copy; Copyright {{date('Y')}} <a target="_blank" href="https://braincraftapps.com">Braincraft Ltd.</a>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset("/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
</body>
</html>
