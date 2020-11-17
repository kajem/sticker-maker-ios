<div class="card text-center">
    <a href="/how-to-use-sticker-maker/{{$post->slug}}">
        <img class="card-img-top" src="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}" alt="{{$post->banner_alt}}">
    </a>
    <div class="card-body">
        <h2 class="card-title">
            <a class="links" href="/how-to-use-sticker-maker/{{$post->slug}}">{{$post->title}}</a>
        </h2>
        @if(!empty($post->subtitle))
            <h3 class="card-subtitle mb-2 text-muted">{{$post->subtitle}}</h3>
        @endif
        <div class="small">
            @if(!empty($post->author))
                <i> By <b>{{$post->author}}</b></i>  on
            @endif
            {{date('d F, Y', strtotime($post->published_date))}}
        </div>
        <p class="card-text pt-2">{{$post->short_description}}</p>
        <a href="/how-to-use-sticker-maker/{{$post->slug}}" class="btn btn-primary">Read more</a>
    </div>
</div>
