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

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="telegram_name" name="telegram_name"
                                               value="{{$telegram_name}}"
                                               required {{!empty($item->telegram_name) ? 'readonly' : ''}}>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Stickers</label>
                                    <div class="col-sm-10">
                                        @php
                                             $stickers = unserialize($item->stickers);
                                        @endphp
                                        @foreach($stickers as $sticker)
                                            <img class="border mb-1" width="100" data-src="{{$pack_root_folder}}{{$item->code}}/{{$sticker}}" src="{{$pack_root_folder}}{{$item->code}}/thumb/{{$sticker}}" alt=""/>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <button type="submit" class="btn btn-primary">Create new sticker set on telegram</button>
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
    <script src="{{asset('/js/admin/telegram-bot.js')}}"></script>
@endsection
