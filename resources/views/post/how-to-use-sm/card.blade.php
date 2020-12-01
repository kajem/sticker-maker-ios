<div class="card text-center">
    <a href="/how-to-use-sticker-maker/{{$card_post->slug}}">
        <img class="card-img-top" src="{{config('app.asset_base_url')}}website_resource/post_images/{{$card_post->banner}}" alt="{{$card_post->banner_alt}}">
    </a>
    <div class="card-body">
        <h2 class="card-title">
            <a class="links" href="/how-to-use-sticker-maker/{{$card_post->slug}}">{{$card_post->title}}</a>
        </h2>
        @if(!empty($card_post->subtitle))
            <h3 class="card-subtitle mb-2 text-muted">{{$card_post->subtitle}}</h3>
        @endif
        <div class="small">
            {{$card_post->published_date->diffForHumans()}}
            @if(!empty($card_post->author))
                &nbsp;&nbsp;&nbsp;By <i>  <b>{{$card_post->author}}</b></i>
            @endif
        </div>
        <p class="card-text pt-2">{{$card_post->short_description}}</p>
        <a href="/how-to-use-sticker-maker/{{$card_post->slug}}" class="btn btn-primary">Read more</a>
    </div>
</div>
