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
                            <form method="POST" action="{{url('item/save')}}" enctype="multipart/form-data">
                                @csrf
                                @if (!empty($item->id))
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                @endif

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{!empty($item->name) ? $item->name : old('name')}}"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-sm-2 col-form-label{{ $errors->has('slug') ? ' text-danger' : '' }}">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug" name="slug"
                                               value="{{!empty($item->slug) ? $item->slug : old('slug')}}">
                                        @if ($errors->has('slug'))
                                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label for="tag" class="col-sm-2 col-form-label">Tag</label>
                                        <div class="col-sm-10">
                                                <textarea type="text" class="form-control" id="tag"
                                                          name="tag">{{!empty($item->tag) ? $item->tag : old('$item')}}</textarea>
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="thumb" class="col-sm-2 col-form-label">Thumb</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                @php
                                                    $thumb = url('/images/no-image-icon.png');
                                                    if(!empty($item->thumb)){
                                                        $thumb_arr = explode("/", $item->thumb);
                                                        $thumb_name = end($thumb_arr);
                                                        $thumb = config('app.asset_base_url').'items/'.$item->code.'/200__'.$thumb_name;
                                                    }
                                                @endphp
                                                <img id="imgThumb" class="thumb-photo img-thumbnail" width="100"
                                                     src="{{$thumb}}"
                                                     alt="{{!empty($item->name) ? $item->name : ''}}">
                                            </div>
                                            <div class="col-sm-11">
                                                <input type="file"
                                                       class="form-control @error('thumb') is-invalid @enderror"
                                                       onchange="readURL(this, '#imgThumb');"
                                                       id="thumb" name="thumb" accept="image/png">
                                                <small id="thumbHelpBlock" class="form-text text-muted">
                                                    Only png image accepted.
                                                </small>
                                                @error('thumb')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <label for="stickers" class="col-sm-2 col-form-label">Sticker's Zip</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="file"--}}
{{--                                               class="form-control @error('stickers') is-invalid @enderror"--}}
{{--                                               id="stickers" name="stickers" accept="application/zip">--}}
{{--                                        @error('stickers')--}}
{{--                                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <label for="author" class="col-sm-2 col-form-label">Author</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="author" name="author"
                                               value="{{!empty($item->author) ? $item->author : old('author')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="is_premium" class="col-sm-2 col-form-label">Premium</label>
                                    <div class="col-sm-10">
                                        <select name="is_premium" id="is_premium" class="form-control">
                                            <option value="1" {{!empty($item->is_premium) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{!empty($item) && $item->is_premium === 0 ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{!empty($item->status) ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{!empty($item) && $item->status === 0 ? 'selected': ''}}>Inctive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href="{{url('item/list')}}" class="btn btn-info">Cancel</a>
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
    <script src="{{asset('/js/admin/read-image-url.js')}}"></script>
@endsection
