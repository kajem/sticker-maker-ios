@extends('layouts.front-template',
[
    'page_title' => 'Page not found!',
    'meta_title' => '404, page not found',
    'meta_keywords' => '404, page not found',
])
@section('content')
    <div id="page-404" class="content mb-5 text-center">
        <div class="container">
            <h1 class="404-text">4<img src="/images/404-emoji.png" alt="0"/>4</h1>
            <h2>PAGE NOT FOUND!</h2>
            <div class="mt-5">
                <a href="/" title="Home page" class="btn btn-primary">Go back home</a>
            </div>
        </div>
    </div>
@endsection
