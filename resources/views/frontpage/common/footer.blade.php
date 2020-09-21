<section id="footer" class="pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h3 class="pb-2">Product by</h3>
                <a target="_blank" href="https://braincraftapps.com">
                    <img width="100" src="{{asset('images/braincraft-logo.png')}}" alt="BrainCraft Limited">
                </a>
            </div>
            <div class="col-sm-4">
                <h3 class="pb-3">Get the app</h3>
                <div class="app-icon">
                    <a target="_blank" href="#"><i class="fab fa-apple"></i></a>
                </div>
            </div>
            <div class="col-sm-4">
                <h3 class="pb-3">Stay in touch</h3>
                <div class="social-icons">
                    @include('frontpage.common.social-icons')
                </div>
            </div>
        </div>
{{--        <hr>--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-4 offset-4">--}}
{{--                <div class="app-icon">--}}
{{--                    <span>Get the free app</span>--}}
{{--                    <a target="_blank" href="#"><i class="fab fa-apple"></i></a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-sm-4 social-icons text-right">--}}
{{--                @include('frontpage.common.social-icons')--}}
{{--            </div>--}}
{{--        </div>--}}
        <br/>
        <hr>
        <div class="text-center copyright">
            &copy; Copyright {{date('Y')}} <a target="_blank" href="https://braincraftapps.com">Braincraft Ltd.</a>
        </div>
    </div>
</section>
