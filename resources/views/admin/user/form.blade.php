@extends('layouts.admin-template', ['pageTitle' => !empty($title) ? $title : 'Add User', 'backButtonText' => 'Back to user list', 'backButtonURL' => url('user/list'), 'showBackButton' => !empty($profile_update) ? false : true])
@php
    $user_id = !empty($user->id) ? $user->id : '';
    $permissions = !empty($user->permissions) ? unserialize($user->permissions) : [];

    //Permissionable routes list
    $category_routes = config('constants.permissions.category_routes');
    $sticker_package_routes = config('constants.permissions.sticker_package_routes');
    $how_to_use_sm_routes = config('constants.permissions.how_to_use_sm_routes');
@endphp
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
                            <form method="POST" action="{{ '/user/'.$user_id }}">
                                @csrf
                                @if (!empty($user->id))
                                    @method('put')
                                    <input type="hidden" name="id" value="{{$user->id}}">

                                    @if(!empty($profile_update))
                                        <input type="hidden" name="profile_update" value="1">
                                    @endif
                                @endif

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{!empty($user->name) ? $user->name : old('name')}}" required />
                                        @if ($errors->has('name'))
                                             <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email*</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{!empty($user->email) ? $user->email : old('email')}}" required {{ empty($user->id) ? '' : 'readonly' }}  />
                                        @if ($errors->has('email'))
                                             <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if(empty($profile_update))
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Permissions</label>
                                    <div class="col-sm-10">
                                        <div class="font-weight-bold">Category:</div>
                                        @foreach ($category_routes as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input"  name="permissions[]" id="category-{{ $key }}" value="{{ $key }}"
                                                {{!empty($permissions) && in_array($key, $permissions) ? 'checked' : ''}}
                                                >
                                                <label class="form-check-label" for="category-{{ $key }}">{{ $value }}</label>
                                            </div>
                                        @endforeach

                                        <div class="font-weight-bold mt-2">Sticker Package:</div>
                                        @foreach ($sticker_package_routes as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input"  name="permissions[]" id="sticker-package-{{ $key }}" value="{{ $key }}"
                                                {{!empty($permissions) && in_array($key, $permissions) ? 'checked' : ''}}
                                                >
                                                <label class="form-check-label" for="sticker-package-{{ $key }}">{{ $value }}</label>
                                            </div>
                                        @endforeach

                                        <div class="font-weight-bold mt-2">How to use SM:</div>
                                        @foreach ($how_to_use_sm_routes as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input"  name="permissions[]" id="how-to-use-sm-{{ $key }}" value="{{ $key }}"
                                                {{!empty($permissions) && in_array($key, $permissions) ? 'checked' : ''}}
                                                >
                                                <label class="form-check-label" for="how-to-use-sm-{{ $key }}">{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label">Password{{ empty($user->id) ? '*' : '' }}</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" {{ empty($user->id) ? 'required' : '' }} />
                                        @if ($errors->has('password'))
                                             <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password{{ empty($user->id) ? '*' : '' }}</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ empty($user->id) ? 'required' : '' }} />
                                        @if ($errors->has('password_confirmation'))
                                             <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href="/user/list" class="btn btn-info">Cancel</a>
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
