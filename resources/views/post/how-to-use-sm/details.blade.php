@php
$meta_title = $post->meta_title;
$meta_description = $post->meta_description;
$meta_image = !empty($post->banner) ? config('app.asset_base_url').'website_resource/post_images/'.$post->banner : '';
@endphp
@extends('layouts.front-template',
[
    'page_title' => $post->title,
    'meta_title' => $meta_title,
    'meta_description' => $meta_description,
    'meta_image' => $meta_image
])
@section('content')
    <div class="content mb-5" id="how-to-use-sm-details">
        <div class="container">
            @include('common.social-share-buttons')
            <div class="row">
                <div class="col-sm-8">
                    <h1 class="title pt-5">{!! $post->title !!}</h1>
                    @if(!empty($post->subtitle))
                        <h2 class="card-subtitle mb-2 text-muted">{{$post->subtitle}}</h2>
                    @endif
                    <div class="small mb-3">
                        {{$post->published_date->diffForHumans()}}
                        @if(!empty($post->author))
                            &nbsp;&nbsp;&nbsp;By <i>  <b>{{$post->author}}</b></i>
                        @endif
                    </div>
                    <hr>

                    @if(!empty($post->banner))
                    <img class="banner mb-3" src="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}" alt="{{$post->banner_alt}}">
                    @endif

                    <div class="main-content ck-content">
                        {!! $post->description !!}
                    </div>

                    @if(!empty($related_posts))
                    <div id="related-posts" class="mt-5">
                        <h2>Related posts</h2>
                        <hr>
                        <div class="jcarousel-wrapper">
                            <div class="jcarousel">
                                <ul>
                                @foreach($related_posts as $index => $post)
                                    <li> @include('post.how-to-use-sm.card') </li>
                                @endforeach
                                </ul>
                            </div>
                            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                            <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                        </div>
                    </div>
                    @endif
                </div>
                <!--Right Sidebar-->
                <div class="col-sm-4 pt-5">
                    @include('post.how-to-use-sm.sidebar')
                </div>
            </div>
        </div>
    </div>
    <!-- jCarousel -->
    <link href="/jcarousel/jcarousel.responsive.css" rel="stylesheet">
    <link href="/ckeditor5/src/ckeditor.css" rel="stylesheet">
    <script src="/jcarousel/jquery.jcarousel.min.js"></script>
    <script type="text/javascript">
        (function($) {
            $(function() {
                var jcarousel = $('.jcarousel');

                jcarousel
                    .on('jcarousel:reload jcarousel:create', function () {
                        var carousel = $(this),
                            width = carousel.innerWidth();

                        if (width >= 600) {
                            width = width / 2;
                        } else if (width >= 350) {
                            width = width / 1;
                        }

                        carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
                    })
                    .jcarousel({
                        wrap: 'circular'
                    });

                $('.jcarousel-control-prev')
                    .jcarouselControl({
                        target: '-=1'
                    });

                $('.jcarousel-control-next')
                    .jcarouselControl({
                        target: '+=1'
                    });

                $('.jcarousel-pagination')
                    .on('jcarouselpagination:active', 'a', function() {
                        $(this).addClass('active');
                    })
                    .on('jcarouselpagination:inactive', 'a', function() {
                        $(this).removeClass('active');
                    })
                    .on('click', function(e) {
                        e.preventDefault();
                    })
                    .jcarouselPagination({
                        perPage: 1,
                        item: function(page) {
                            return '<a href="#' + page + '">' + page + '</a>';
                        }
                    });
            });
        })(jQuery);
    </script>
@endsection
