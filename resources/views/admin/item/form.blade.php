@extends('layouts.admin-template', ['pageTitle' => $title, 'backButtonText' => 'Back to item list', 'backButtonURL' => url('item/list'), 'showBackButton' => true])

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
                            <form method="POST" action="{{url('item/save')}}">
                                @csrf
                                @if ($item->id)
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                @endif

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{!empty($item->name) ? $item->name : old('name')}}"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{!empty($category->status) ? 'selected': ''}}>Active
                                            </option>
                                            <option value="0" {{empty($category->status) ? 'selected': ''}}>Inctive
                                            </option>
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-10 offset-2">--}}
{{--                                        <button type="submit" class="btn btn-primary">Save</button>--}}
{{--                                        <a href="{{url('item/list')}}" class="btn btn-info">Cancel</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
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
