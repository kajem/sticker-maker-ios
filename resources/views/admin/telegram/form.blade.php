@extends('layouts.admin-template', ['pageTitle' => !empty($title) ? $title : 'Add new Item', 'backButtonText' => 'Back to item list', 'backButtonURL' => url('item/list'), 'showBackButton' => true])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General Info</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="telegram-pack" action="{{url('telegram/create')}}" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{$item->id}}">

                                @if(!empty($item->telegram_name))
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">
                                        Telegram Set URL
                                        @if($item->is_telegram_set_completed)
                                            <i class="fas fa-check-circle text-primary"></i>
                                        @endif
                                    </label>
                                    <div class="col-sm-10 pt-2">
                                        <a target="_blank" href="{{config('services.telegram.set_base_url').$item->telegram_name}}">
                                            {{config('services.telegram.set_base_url').$item->telegram_name}}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="telegram_name" name="telegram_name"
                                               value="{{$telegram_name}}"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Stickers ({{$item->total_sticker}})</label>
                                    <div class="col-sm-10">
                                        @php
                                           $stickers = unserialize($item->stickers);
                                           $count = 0;
                                        @endphp
                                        @foreach($stickers as $sticker)
                                            <div class="d-inline-block position-relative" id="sticker-{{$count}}">
                                                <img class="border mb-1" width="100" data-src="{{$pack_root_folder}}{{$item->code}}/{{$sticker}}" src="{{$pack_root_folder}}{{$item->code}}/thumb/{{$sticker}}" alt=""/>
                                            </div>
                                            @php $count++; @endphp
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <div class="progress d-none mb-3">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Please wait... <span>0%</span>completed</div>
                                        </div>
                                        <div class="message mb-3 d-none">
                                            <div class="alert" role="alert">
                                                <span class="text-msg"></span>
                                                <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            @if(!empty($item->telegram_name))
                                                Recreate sticker set on telegram
                                            @else
                                                Create new sticker set on telegram
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script src="{{asset('/js/admin/telegram-sticker-set.js')}}"></script>
@endsection
