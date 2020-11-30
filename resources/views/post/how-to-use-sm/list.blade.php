@extends('layouts.front-template',
[
    'page_title' => 'How to use Sticker Maker',
    'meta_title' => 'meta title pending...',
    'meta_description' => 'meta description pending...',
])
@section('content')
    <div class="content" id="how-to-use-sm">
        <div class="container">
            <h2 class="title pt-5 text-center">{{$page_title}}</h2>
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-6  pt-3">
                        @if(!empty($subtitle))
                            <h2 class="card-subtitle mb-2 text-muted">{!! $subtitle !!}</h2>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <div class="text-right pt-3">
                            <form method="get" action="">
                                <input type="text" class="form-control w-auto d-inline-block" autocomplete="off" name="keyword" id="keyword" placeholder="Type here..." value="{{ app('request')->input('keyword') }}"/>
                                <input type="submit" class="btn btn-dark btn-sm mt--2" value="Search" />
                            </form>
                        </div>
                    </div>
                </div>
                @if(!$posts->isEmpty())
                    @php
                        $i = 1;
                    @endphp
                    @foreach($posts as $post)
                        @if($i == 1 || $i % 2 == 1)
                            <div class="row">
                        @endif
                                <div class="col-sm-6 mt-4">
                                    @include('post.how-to-use-sm.card')
                                </div>
                        @if($i % 2 == 0 || $i >= $posts->perpage() )
                            </div>
                        @endif
                        @php
                            $i++;
                        @endphp
                    @endforeach
                <div class="row">
                    <div class="col-sm-12 text-center mt-3">
                        {{$posts->appends($query_string_arr)->links()}}
                    </div>
                </div>
                @else
                    <div class="text-center mt-5"><h3>No posts found.</h3></div>
                @endif
            </div>
        </div>
    </div>
@endsection
