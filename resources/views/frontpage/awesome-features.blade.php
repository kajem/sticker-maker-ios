<section id="awesome-features">
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12 pt-5 pb-4">
                <h2 class="title text-center text-white">Amazing Features</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <img class="amazing-feature" src="{{asset('images/Magic.png')}}"
                     alt="Magical Background Remove!"/>
                <h5 class="font-weight-bold text-center">Magical Background Remove!</h5>
                <p>
                    Do a Magic! Use the Magic feature and automatically remove the background. You can also select
                    the portion you want to keep. Feel a smart experience now.
                </p>
            </div>
            <div class="col-sm-3">
                <img class="amazing-feature" src="{{asset('images/Lasso.png')}}"
                     alt="Cropping a men's face with lasso tool"/>
                <h5 class="font-weight-bold text-center">Precise Crop!</h5>
                <p>
                    Need more precise crop!!! Try lasso. It's super handy! Select your area with your fingertip and
                    adjust with dots to cut your image wherever you want and turn your photo into a sticker.
                </p>
            </div>
            <div class="col-sm-3">
                <img class="amazing-feature" src="{{asset('images/add-text.png')}}"
                     alt="Add Text to a sticker"/>
                <h5 class="font-weight-bold text-center">Add Text</h5>
                <p>
                    How about adding text on your stickers from a huge collection of fonts in different backgrounds!
                    Make your stickers funnier. Just input a text expression, choose a font and color, change the background.
                    All are set. Try it now!
                </p>
            </div>
            <div class="col-sm-3">
                <img class="amazing-feature" src="{{asset('images/add-sticker-on-a-sticker.png')}}"
                     alt="Add Sticker on a Sticker"/>
                <h5 class="font-weight-bold text-center">Add Sticker on a Sticker</h5>
                <p>
                    Wanna change a build-in sticker! Choose and edit a sticker from the sticker pack. Add border, text,
                    emoji or even you can add a sticker on the sticker.
                </p>
            </div>

        </div>
        <div class="mt-5 text-center">
            @include('common.download-app')
        </div>
    </div>
</section>
