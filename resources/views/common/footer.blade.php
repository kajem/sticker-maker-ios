<section id="footer" class="pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Made @ Braincraft</h5>
                <a target="_blank" href="https://braincraftapps.com">
                    <img width="125" src="{{asset('images/braincraft-logo.png')}}" alt="BrainCraft Limited">
                </a>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold about">About</h5>
                <ul class="p-0 list-unstyled links">
                    <li><a href="{{url('about-us')}}">About us</a></li>
                    <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                    <li><a href="{{url('terms')}}">Terms of Use</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Other Apps</h5>
                <div class="app-links">
                    <ul class="p-0 list-unstyled links">
                        <li>
                            <a target="_blank" href="https://apps.apple.com/us/app/gif-maker-make-video-to-gifs/id1215606098">
                                GIF Maker
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://apps.apple.com/us/app/add-music-to-video-voice-over/id1276523677">
                                Add Music to Video
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://apps.apple.com/us/app/slideshow-maker-with-music-fx/id1265026847">
                                Slide Show Maker
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://apps.apple.com/us/app/meme-maker-meme-creator-to-make-photo-memes/id1250756211">
                                Meme Maker
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Stay in touch</h5>
                <div class="social-icons">
                    @include('common.social-icons')
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center copyright">
            &copy; Copyright {{date('Y')}} <a target="_blank" href="https://braincraftapps.com">Braincraft Ltd.</a>
        </div>
    </div>
</section>
@if(!isset($_COOKIE['cookie_accepted']))
    @include('common.cookie-policy')
@endif
