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

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Code</label>
                                        <div class="col-sm-10 pt-2">
                                            {{$item->code}}
                                        </div>
                                    </div>
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
                                                          name="tag">{{!empty($item->tag) ? $item->tag : old('tag')}}</textarea>
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="meta_title" class="col-sm-2 col-form-label">Meta Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="60"
                                               value="{{!empty($item->meta_title) ? $item->meta_title : old('meta_title')}}"
                                               >

                                        <small class="form-text text-muted">
                                            Max character length: 60.
                                            <span id="meta_title_char_count" class="text-danger"></span>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="meta_description" class="col-sm-2 col-form-label">Meta Description</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="meta_description" maxlength="160"
                                                          name="meta_description">{{!empty($item->meta_description) ? $item->meta_description : old('meta_description')}}</textarea>
                                        <small class="form-text text-muted">
                                            Max character length: 160.
                                            <span id="meta_description_char_count" class="text-danger"></span>
                                        </small>
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
                                                     src="{{$thumb}}" style="{{ !empty($item->thumb_bg_color) ? 'background-color:'.$item->thumb_bg_color : '' }}"
                                                     alt="{{!empty($item->name) ? $item->name : ''}}">
                                            </div>
                                            <div class="col-sm-11">
                                                <input type="file"
                                                       class="form-control @error('thumb') is-invalid @enderror"
                                                       onchange="readURL(this, '#imgThumb');"
                                                       id="thumb" name="thumb" accept="image/png,image/webp">
                                                <small id="thumbHelpBlock" class="form-text text-muted">
                                                    Only png, webp image accepted.
                                                </small>
                                                @error('thumb')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="thumb_bg_color" class="col-sm-2 col-form-label">Thumb background color</label>
                                    <div class="col-sm-10">
                                        <input type="color" style="width: 200px;" class="form-control" id="thumb_bg_color" name="thumb_bg_color"
                                               value="{{!empty($item->thumb_bg_color) ? $item->thumb_bg_color : old('thumb_bg_color')}}"
                                               >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="stickers" class="col-sm-2 col-form-label">
                                    Stickers
                                    @if(!empty($item->total_sticker))
                                        ({{$item->total_sticker}})
                                    @endif
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="file"
                                               class="form-control @error('stickers') is-invalid @enderror"
                                               id="stickers" name="stickers[]" accept="image/png,image/webp" multiple>
                                            @error('stickers')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            @if(!empty($item->stickers))
                                            <div class="pt-2">
                                                @php $stickers = unserialize($item->stickers) @endphp
                                                @foreach($stickers as $sticker)
                                                    <img width="85" class="rounded img-thumbnail mb-1" src="{{config('app.asset_base_url').'items/'.$item->code.'/thumb/'.$sticker}}" alt=""/>
                                                @endforeach
                                            </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="is_premium" class="col-sm-2 col-form-label">Is Stickers/Thumb animated?</label>
                                    <div class="col-sm-10">
                                        <select name="is_animated" id="is_animated" class="form-control">
                                            <option value="0" {{!empty($item) && $item->is_animated === 0 ? 'selected': ''}}>No</option>
                                            <option value="1" {{!empty($item->is_animated) ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="compress_with_pngquant" id="compress_with_pngquant">
                                            <label class="form-check-label" for="compress_with_pngquant">Compress Thumb/Stickers with pngquant</small></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="author" class="col-sm-2 col-form-label">Author*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="author" name="author"
                                               value="{{!empty($item->author) ? $item->author : old('author')}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="is_premium" class="col-sm-2 col-form-label">Is premium?</label>
                                    <div class="col-sm-10">
                                        <select name="is_premium" id="is_premium" class="form-control">
                                            <option value="1" {{!empty($item->is_premium) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{!empty($item) && $item->is_premium === 0 ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="version" class="col-sm-2 col-form-label">Version</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control @error('version') is-invalid @enderror"
                                               id="version" name="version"
                                               value="{{!empty($item->version) ? $item->version + 1 : 1}}">
                                        @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                        <div class="alert alert-warning d-none mb-2" role="alert" id="submit-alert">
                                            Please wait. This process may takes few minutes.
                                        </div>
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
    <script src="/js/admin/read-image-url.js"></script>
    <script src="/js/admin/item-form.js?01112020"></script>
@endsection
