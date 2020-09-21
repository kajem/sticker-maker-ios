<section id="awesome-features">
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12 pt-5 pb-4">
                <h2 class="title text-center text-white">Amazing Features</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 text-center pt-3">
                <img height="500" src="{{asset('images/Lasso.png')}}"
                     alt="Cropping a men's face with lasso tool"/>
            </div>
            <div class="col-sm-5">
                <h3 class="mt-150 font-weight-bold">Precise Crop!</h3>
                <p>
                    Need more precise crop!!! Try lasso. It's super handy! Select your area with your fingertip and
                    adjust with dots to cut your image wherever you want and turn your photo into a sticker.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5 offset-1">
                <h3 class="mt-150 font-weight-bold">Magical Background Remove!</h3>
                <p>
                    Do a Magic! Use the Magic feature and automatically remove the background. You can also select
                    the portion you want to keep. Feel a smart experience now.
                </p>
            </div>
            <div class="col-sm-6 text-center">
                <img height="500" src="{{asset('images/Magic.png')}}"
                     alt="Removing background from photo"/>
            </div>
        </div>
        <div class="mt-5 text-center">
            @include('frontpage.common.download-app')
        </div>
    </div>
</section>
