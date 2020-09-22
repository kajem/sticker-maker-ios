<section id="hero-area" class="pb-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h1 class="text-white mt-5">
                    Sticker Maker for Social Apps
                </h1>
                <h2 class="text-white">
                    Choose build in stickers from stickers' gallery or Personalize your own stickers easily
                </h2>
                <p class="mt-3 lead text-white">
                    Go viral and spice up your chats with unique stickers on Facebook, WhatsApp, Messenger, iMessage, etc.
                </p>

                <div class="mt-5">
                    @include('common.download-app')
                    <div class="social-icons mt-4">
                        @include('common.social-icons')
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="video-frame float-right">
                    <video id="hero-video" controls autoplay muted loop>
                        <source src="{{asset('videos/4x5_1.mp4')}}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
</section>
