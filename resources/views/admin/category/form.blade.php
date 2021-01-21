@extends('layouts.admin-template', ['pageTitle' => !empty($title) ? $title : 'Add Category', 'backButtonText' => 'Back to category list', 'backButtonURL' => url('category/list'), 'showBackButton' => true])

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
                            <form method="POST" action="{{url('category/save')}}" enctype="multipart/form-data">
                                @csrf
                                @if (!empty($category->id))
                                    <input type="hidden" name="id" value="{{$category->id}}">
                                @endif

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name"
                                               value="{{!empty($category->name) ? $category->name : old('name')}}"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="text" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="text"
                                                  name="text">{{!empty($category->text) ? $category->text : old('text')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="thumb" class="col-sm-2 col-form-label">Thumb</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                @php
                                                    $thumb = url('/images/no-image-icon.png');
                                                    if(!empty($category->thumb))
                                                        $thumb = config('app.asset_base_url').'category-thumbs/'.$category->thumb;
                                                @endphp
                                                <img id="imgThumb" class="thumb-photo img-thumbnail" width="100"
                                                     src="{{$thumb}}" style="{{ !empty($category->thumb_bg_color) ? "background-color:".$category->thumb_bg_color : '' }}"
                                                     alt="{{!empty($category->name) ? $category->name : ''}}">
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
                                <div class="form-group row">
                                    <label for="thumb" class="col-sm-2 col-form-label">Thumb (Landscape)</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                @php
                                                    $thumb_v = url('/images/no-image-icon.png');
                                                    if(!empty($category->thumb_v))
                                                        $thumb_v = config('app.asset_base_url').'category-thumbs/'.$category->thumb_v;
                                                @endphp
                                                <img id="imgThumbV" class="thumb-photo img-thumbnail" width="100"
                                                     src="{{$thumb_v}}" style="{{ !empty($category->thumb_bg_color) ? "background-color:".$category->thumb_bg_color : '' }}"
                                                     alt="{{!empty($category->name) ? $category->name : ''}}">
                                            </div>
                                            <div class="col-sm-11">
                                                <input type="file"
                                                       class="form-control @error('thumb_v') is-invalid @enderror"
                                                       onchange="readURL(this, '#imgThumbV');"
                                                       id="thumb_v" name="thumb_v" accept="image/png">
                                                <small id="thumb_vHelpBlock" class="form-text text-muted">
                                                    Only png image accepted.
                                                </small>
                                                @error('thumb_v')
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
                                               value="{{!empty($category->thumb_bg_color) ? $category->thumb_bg_color : old('thumb_bg_color')}}"
                                               >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="version" class="col-sm-2 col-form-label">Version</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control @error('version') is-invalid @enderror"
                                               id="version" name="version"
                                               value="{{!empty($category->version) ? $category->version : old('version')}}">
                                        @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{!empty($category->status) ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{!empty($category) && $category->status === 0 ? 'selected': ''}}>Inctive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href="{{url('category/list')}}" class="btn btn-info">Cancel</a>
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
