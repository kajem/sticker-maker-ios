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
                            <form method="POST" action="{{url('category/save')}}">
                                @csrf
                                @if (!empty($category->id))
                                    <input type="hidden" name="id" value="{{$category->id}}">
                                @endif

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                               value="{{!empty($category->name) ? $category->name : old('name')}}"
                                               required>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
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
                                    <label for="version" class="col-sm-2 col-form-label">Version</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control @error('version') is-invalid @enderror" id="version" name="version"
                                               value="{{!empty($category->version) ? $category->version : old('version')}}">
                                        @error('version')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{!empty($category->status) ? 'selected': ''}}>Active
                                            </option>
                                            <option value="0" {{!empty($categor) && $category->status === 0 ? 'selected': ''}}>Inctive                                            </option>
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
