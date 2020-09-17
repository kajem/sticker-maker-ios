<section id="awesome-features">
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12 pt-5 pb-4">
                <h2 class="title text-center text-white">Awesome Features</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 text-center pt-3">
                <img height="500" src="{{asset('images/crop-face.png')}}"
                     alt="Cropping a men's face with lesso tool"/>
            </div>
            <div class="col-sm-5">
                <h3 class="mt-150 font-weight-bold">Precise and Manual trace</h3>
                <p>
                    Creating a sticker has never been so easy. You can trace with your finger the part of the image
                    you want to turn into a sticker. Then as easily as it seems you can adjust this path by moving
                    the dots that will appear. You can even zoom in to the image to be more precise.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5 offset-1">
                <h3 class="mt-150 font-weight-bold">Automatic Background Removal</h3>
                <p>
                    There's a mode in the app that we call 'Magic', and there is a reason for this. As fancy as it
                    sounds, with this feature you can automatically remove the background of the image you have
                    selected. You just need to trace with your finger the part you want to keep and the app will
                    extract it from the background.
                </p>
            </div>
            <div class="col-sm-6 text-center">
                <img height="500" src="{{asset('images/remove-background.png')}}"
                     alt="Removing background from an women's photo"/>
            </div>
        </div>
        <div class="mt-5 text-center">
            @include('front.common.download-app')
        </div>
    </div>
</section>
