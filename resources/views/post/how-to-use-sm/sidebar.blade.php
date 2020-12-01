<div class="right-sidebar">
    <div class="about mt-3 mb-5 border text-center p-3">
        <h4 class="widget-title"><span>About</span></h4>
        Greetings from Sticker.Me! We are happy to welcome you in our Blog section, to inspire and guide you in using Sticker.Me Sticker Maker to create amazing and funny Stickers for Messenger Apps.
        Sticker.Me is a product of Brain Craft Ltd. one of the top App developers in the App Store.
        <div class="mt-1">
            <a href="/about-us">Read more</a>
        </div>

        <div class="social-icons text-center mt-1">
            @include('common.social-icons')
        </div>
    </div>

    <div class="search-box mb-5 border text-center p-3">
        <h4 class="widget-title"><span>Search</span></h4>
        <form method="get" action="/how-to-use-sticker-maker">
            <input type="text" class="form-control w-auto d-inline-block" autocomplete="off" name="keyword" id="keyword" placeholder="Type here..." value="{{ app('request')->input('keyword') }}"/>
            <input type="submit" class="btn btn-dark btn-sm mt--2" value="Search" />
        </form>
    </div>

    @if(!$recent_posts->isEmpty())
    <div class="recent-posts mb-5 border p-3">
        <h4 class="widget-title text-center"><span>Recent Posts</span></h4>
        <ul class="list-unstyled m-0">
            @foreach($recent_posts as $post)
                <li class="mt-3">
                    <div class="row">
                        <div class="col-3 col-sm-3 pr-0">
                            <a href="/how-to-use-sticker-maker/{{$post->slug}}">
                                <img class="img-thumbnail" width="70" src="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}" alt="{{$post->banner_alt}}">
                            </a>
                        </div>
                        <div class="col-9 col-sm-9 word-wrap-break">
                            <a href="/how-to-use-sticker-maker/{{$post->slug}}">
                                {{ $post->title  }}
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($archives))
    <div class="archives mb-5 text-center border p-3">
        <h4 class="widget-title"><span>Archives</span></h4>
        <ul class="list-unstyled m-0">
            @foreach($archives as $archive)
                <li>
                    <a href="/how-to-use-sticker-maker/?year={{$archive->year}}&month={{$archive->month}}">
                        {{$months[$archive->month - 1]}}, {{$archive->year}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    @include('post.how-to-use-sm.instagram-feed')
</div>
