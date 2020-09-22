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
                <h5 class="pb-3 font-weight-bold">About</h5>
                <ul class="p-0 list-unstyled links">
                    <li><a href="{{url('about-us')}}">About us</a></li>
                    <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                    <li><a href="{{url('terms')}}">Terms of Use</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Other Apps</h5>
                <div class="app-links">
                    <a target="_blank" href="http://braincraftapps.com/our-products/details/48">
                        Meme Maker
                    </a><br/>
                    <a target="_blank" href="http://braincraftapps.com/our-products/details/54">
                        Add Music to Video
                    </a><br/>
                    <a target="_blank" href="http://braincraftapps.com/our-products/details/60">
                        GIF Maker
                    </a><br/>
                    <a target="_blank" href="http://braincraftapps.com/our-products/details/57">
                        SlideShow Maker
                    </a>
                </div>
            </div>
{{--            <div class="col-sm-2">--}}
{{--                <h5 class="pb-3 font-weight-bold">Get the app</h5>--}}
{{--                <div class="get-app-links">--}}
{{--                    @include('common.download-app-links')--}}
{{--                </div>--}}
{{--            </div>--}}
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
