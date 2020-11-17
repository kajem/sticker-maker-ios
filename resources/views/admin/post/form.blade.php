@extends('layouts.admin-template', ['pageTitle' => !empty($title) ? $title : 'Add new post', 'backButtonText' => 'Back to post list', 'backButtonURL' => url('post/how-to-use-sm/list'), 'showBackButton' => true])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General Info</h3>
                            @if (!empty($post->id))
                            <a target="_blank" class="float-right" href="/how-to-use-sticker-maker/{{$post->slug}}" title="View Details"><i class="fas fa-eye"></i></a>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('post/'.$type.'/save')}}" enctype="multipart/form-data">
                                @csrf
                                @if (!empty($post->id))
                                    <input type="hidden" name="id" value="{{$post->id}}">
                                @else
                                    <input type="hidden" name="type" value="{{$type}}">
                                @endif
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{!empty($post->title) ? $post->title : old('title')}}"
                                               required>
                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-sm-2 col-form-label{{ $errors->has('slug') ? ' text-danger' : '' }}">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug" name="slug"
                                               value="{{!empty($post->slug) ? $post->slug : old('slug')}}" required>
                                        @if ($errors->has('slug'))
                                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="subtitle" class="col-sm-2 col-form-label">Subtitle</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                                               value="{{!empty($post->subtitle) ? $post->subtitle : old('subtitle')}}"
                                               >
                                        @if ($errors->has('subtitle'))
                                            <span class="text-danger">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label for="tag" class="col-sm-2 col-form-label">Tag</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" id="tag"
                                                          name="tag">{{!empty($post->tag) ? $post->tag : old('tag')}}</textarea>
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="meta_title" class="col-sm-2 col-form-label">Meta Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="60"
                                               value="{{!empty($post->meta_title) ? $post->meta_title : old('meta_title')}}"
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
                                                          name="meta_description">{{!empty($post->meta_description) ? $post->meta_description : old('meta_description')}}</textarea>
                                        <small class="form-text text-muted">
                                            Max character length: 160.
                                            <span id="meta_description_char_count" class="text-danger"></span>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="banner" class="col-sm-2 col-form-label">Banner</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                @php
                                                    $banner = url('/images/no-image-icon.png');
                                                    if(!empty($post->banner)){
                                                        $banner = config('app.asset_base_url').'website_resource/post_images/'.$post->banner;
                                                    }
                                                @endphp
                                                <img id="imgThumb" class="thumb-photo img-thumbnail" width="100"
                                                     src="{{$banner}}"
                                                     alt="{{!empty($post->banner_alt) ? $post->banner_alt : ''}}">
                                            </div>
                                            <div class="col-sm-11">
                                                <input type="file"
                                                       class="form-control @error('banner') is-invalid @enderror"
                                                       onchange="readURL(this, '#imgThumb');"
                                                       id="banner" name="banner" accept="image/*">
                                                <small class="form-text text-muted">
                                                    Recommended banner size is 730x400 px.
                                                </small>
                                                @error('banner')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="banner_alt" class="col-sm-2 col-form-label mw-100">Banner Alt Text</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="banner_alt" name="banner_alt"
                                               value="{{!empty($post->banner_alt) ? $post->banner_alt : old('banner_alt')}}"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="short_description" class="col-sm-2 col-form-label">Short Description</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="short_description"
                                                  name="short_description">{{!empty($post->short_description) ? $post->short_description : old('short_description')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="description"
                                                  name="description">{{!empty($post->description) ? $post->description : old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row" id="related-posts">
                                    <label for="related_posts" class="col-sm-2 col-form-label">Related posts</label>
                                    <div class="col-sm-10">
                                        <div class="posts">
                                            @php
                                                $related_posts = !empty($post->related_posts) ? unserialize($post->related_posts) : old('related_posts');
                                            @endphp
                                            @foreach($posts as $p)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="related_posts[]" id="postCheck{{$p->title}}" value="{{$p->id}}"
                                                    {{!empty($p->id) && !empty($related_posts) && in_array($p->id, $related_posts) ? 'checked' : ''}} >
                                                <label class="form-check-label" for="postCheck{{$p->title}}">{{$p->title}}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="author" class="col-sm-2 col-form-label">Author</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="author" name="author"
                                               value="{{!empty($post->author) ? $post->author : old('author')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="published_date" class="col-sm-2 col-form-label">Published Date*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="published_date" name="published_date" autocomplete="off"
                                               value="{{!empty($post->published_date) ? date('Y-m-d', strtotime($post->published_date)) : old('published_date')}}"
                                               required>
                                        @if ($errors->has('published_date'))
                                            <span class="text-danger">{{ $errors->first('published_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{!empty($post->status) ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{!empty($post) && $post->status === 0 ? 'selected': ''}}>Inctive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href="{{url('post/list')}}" class="btn btn-info">Cancel</a>
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
    @include('ckfinder::setup')
    <script src="/js/admin/read-image-url.js"></script>
    <script type="module" src="/ckeditor5/build/ckeditor.js"></script>
    <!--jQuery UI-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="module" src="/js/admin/post-form.js?01112sf20"></script>
@endsection
