<div class="post-social-share">
    <span class="title font-weight-bold mb-2 d-inline-block">SHARE</span>
    <div data-network="facebook"
         class="st-custom-button button share-button facebook-share-button text-primary"
         data-username="sticker.me.stickermaker"
         data-url="{{url()->current()}}"
         data-title="{{$post->title}}"
        @if(!empty($post->banner))
            data-image="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}"
        @endif
         data-description="{{$post->short_description}}"
    >
        <i class="fab fa-facebook-f"></i>
    </div>
    <div data-network="twitter"
         data-username="StickerMaker5"
         class="st-custom-button button share-button twitter-share-button  text-primary"
         data-title="{{$post->title}}"
         @if(!empty($post->banner))
         data-image="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}"
         @endif
         data-description="{{$post->short_description}}"
    >
        <i class="fab fa-twitter"></i>
    </div>
    <div data-network="pinterest"
         class="st-custom-button button share-button pinterest-share-button  text-primary"
         data-username="socialmedia1972"
         data-title="{{$post->title}}"
         @if(!empty($post->banner))
         data-image="{{config('app.asset_base_url')}}website_resource/post_images/{{$post->banner}}"
         @endif
         data-description="{{$post->short_description}}"
    >
        <i class="fab fa-pinterest"></i>
    </div>
</div>

