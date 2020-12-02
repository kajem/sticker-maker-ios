<section id="top-bar" class="pt-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-md-5 col-lg-5">
                <h2 class="text-white font-weight-bold">
                    <a href="/" class="logo text-white">
                        <img width="64" src="/images/logo.png" alt="Sticker Maker"/>
                        Sticker Maker
                    </a>
                </h2>
                <!--START: mobile menu-->
                <div class="hide-on-desktop hide-on-pad text-right">
                    <nav class="navbar mobile-nav-icon">
                        <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                    </nav>
                    <div class="collapse" id="navbarToggleExternalContent">
                        <div class="bg-white rounded mb-3 mobile-menu-items">
                            @include('common.top-menu')
                        </div>
                    </div>
                </div>
                <!--END: mobile menu-->
            </div>
            <div class="col-sm-2  col-md-7 col-lg-7">
                <nav class="navbar navbar-expand-lg text-right show-on-desktop show-on-pad">
                    <div class="collapse navbar-collapse" id="navbarToggleExternalContent">
                        @include('common.top-menu')
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
