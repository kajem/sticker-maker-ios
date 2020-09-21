<section id="footer" class="pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Product by</h5>
                <a target="_blank" href="https://braincraftapps.com">
                    <img width="100" src="{{asset('images/braincraft-logo.png')}}" alt="BrainCraft Limited">
                </a>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">About</h5>
                <ul class="p-0 list-unstyled">
                    <li><a href="#">Link 1</a></li>
                    <li><a href="#">Link 2</a></li>
                    <li><a href="#">Link 3</a></li>
                    <li><a href="#">Link 4</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Get the app</h5>
                <div class="app-icon">
                    <a target="_blank" href="#"><i class="fab fa-apple"></i></a>
                </div>
            </div>
            <div class="col-sm-3">
                <h5 class="pb-3 font-weight-bold">Stay in touch</h5>
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
        <hr>
        <div class="text-center copyright">
            &copy; Copyright {{date('Y')}} <a target="_blank" href="https://braincraftapps.com">Braincraft Ltd.</a>
        </div>
    </div>
</section>
