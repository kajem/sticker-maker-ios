<div class="right-sidebar">
    <div class="about mt-3 mb-5 border text-center p-3">
        <h4 class="widget-title"><span>About</span></h4>
        Greetings from Sticker.Me! We are happy to welcome you in our Blog section, to inspire and guide you in using Sticker.Me Sticker Maker to create amazing and funny Stickers for Messenger Apps.
        Sticker.Me is a product of Brain Craft Ltd. one of the top App developers in the App Store.
        <div class="mt-1 mb-3">
            <a href="/about-us">Read more</a>
        </div>

        <div class="social-icons text-center">
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
                <li>
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="/how-to-use-sticker-maker/{{$post->slug}}">
                                <img class="float-left mr-2 mb-3 img-thumbnail" width="70" src="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}" alt="{{$post->banner_alt}}">
                                {{ $post->title  }}
                                <div class="clear-both"></div>
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

    @if(!empty($instagram))
    <div class="follow-on-instagram mb-5 text-center border p-3">
        <h4 class="widget-title"><span>Follow us on instagram</span></h4>
        <div class="header mt-3">
            <a target="_blank" href="https://www.instagram.com/_stickermaker/">
                <img width="40" class="rounded-circle" src="{{$instagram['profile_pic']}}" alt="_stickermaker" />
                <strong class="pl-2 font-size-18">_stickermaker</strong>
            </a>
        </div>
        <div class="row mt-3 text-center">
            <div class="col-sm-4">
                Posts
                <h3>{{$instagram['post_count']}}</h3>
            </div>
            <div class="col-sm-4">
                Followers
                <h3>{{$instagram['followers']}}</h3>
            </div>
            <div class="col-sm-4">
                Following
                <h3>{{$instagram['following']}}</h3>
            </div>
        </div>

        @if(!empty($instagram['posts']))
            <div class="posts">
                @php
                    $i = 1;
                @endphp
                @foreach($instagram['posts'] as $instagram_post)
                    @if($i == 1 || $i % 2 == 1)
                        <div class="row">
                            @endif
                            <div class="col-sm-6 mt-4">
                                <a target="_blank" href="https://www.instagram.com/p/{{$instagram_post->node->shortcode}}/">
                                    <div class="post-body">
                                        <img class="embed-responsive border instagram-post-image" src="{{$instagram_post->node->display_url}}" alt=""/>
                                        @if($instagram_post->node->__typename == 'GraphVideo')
                                            <i class="fas fa-video"></i>
                                        @endif
                                        <div class="hover-info">
                                            <div class="content">
                                                @if($instagram_post->node->is_video)
                                                    <i class="fas fa-play"></i>
                                                    {{$instagram_post->node->video_view_count}}
                                                @else
                                                    <i class="fas fa-heart"></i>
                                                    {{$instagram_post->node->edge_media_preview_like->count}}
                                                @endif
                                                &nbsp;&nbsp;&nbsp;
                                                <i class="fas fa-comment"></i>
                                                {{$instagram_post->node->edge_media_to_comment->count}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @if($i % 2 == 0 || $i >= count($instagram['posts']) )
                                </div>
                            @endif
                    @php
                        $i++;
                        if($i > 6)
                            break;
                    @endphp
                @endforeach
            </div>
        @endif
        <div class="text-center mt-3">
            <a target="_blank" href="https://www.instagram.com/_stickermaker/">View more posts on instagram</a>
        </div>
    </div>
    @endif
</div>
