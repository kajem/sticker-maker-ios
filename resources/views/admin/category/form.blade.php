@extends('layouts.admin-template')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6 text-right">
            <a href="{{url('category/list')}}" class="btn btn-primary">Back to category list</a>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@include('admin.common.notification')
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
                    <form method="POST" action="{{url('category/save')}}">
                        @csrf
                        @if ($category->id)
                            <input type="hidden" name="id" value="{{$category->id}}">
                        @endif
                        
                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                          <input type="text" class="form-control" id="name" name="name" value="{{!empty($category->name) ? $category->name : old('name')}}" required>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="text" class="col-sm-2 col-form-label">Description</label>
                          <div class="col-sm-10">
                            <textarea type="text" class="form-control" id="text" name="text">{{!empty($category->text) ? $category->text : old('text')}}</textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="items" class="col-sm-2 col-form-label">Items</label>
                            <div class="col-sm-10">
                            <input type="number" class="form-control" id="items" name="items" value="{{!empty($category->items) ? $category->items : old('items')}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="stickers" class="col-sm-2 col-form-label">Stickers</label>
                            <div class="col-sm-10">
                            <input type="number" class="form-control" id="stickers" name="stickers" value="{{!empty($category->stickers) ? $category->stickers : old('stickers')}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="version" class="col-sm-2 col-form-label">Version</label>
                            <div class="col-sm-10">
                            <input type="number" class="form-control" id="version" name="version" value="{{!empty($category->version) ? $category->version : old('version')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{!empty($category->status) ? 'selected': ''}}>Active</option>
                                <option value="0" {{empty($category->status) ? 'selected': ''}}>Inctive</option>
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
@endsection