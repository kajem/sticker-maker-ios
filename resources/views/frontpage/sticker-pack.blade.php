@if(!$items->isEmpty())
    <section id="sticker-packs" class="pb-5 text-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 pt-5 pb-4">
                    <h2 class="title text-center pb-4">Cool and Fancy Sticker Gallery</h2>
                    @php $count = 1; @endphp
                    @foreach($items as $item)
                        @php
                            $thumb_arr = explode('/', $item->thumb);
                            $thumb = $asset_base_url.'items/'.$item->code.'/200__'.end($thumb_arr);

                        @endphp
                        <a href="/pack/{{$item->slug}}">
                            <div class="card sticker-bg-{{$count++}} text-center">
                                <span class="badge badge-pill badge-warning">{{$item->total_sticker}} Stickers</span>
                                <div class="image text-center">
                                    <img class="card-img-top" src="{{$thumb}}" alt="Card image cap">
                                </div>
                                <div class="card-body p-0 text-center">
                                    <span>{{$item->name}}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
