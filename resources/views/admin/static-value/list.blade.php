@extends('layouts.admin-template', ['pageTitle' => 'Static Values', 'showBackButton' => false])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col" width="20%">Label</th>
                                        <th scope="col" width="15%">Name</th>
                                        <th scope="col" width="60%">Value</th>
                                        <th scope="col" width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($static_values as $static_value)
                                        <tr id="static-value-{{$static_value->id}}">
                                            <td class="label">
                                                <span class="d-block">{{$static_value->label}}</span>
                                                @csrf
                                                <input type="text" class="form-control d-none" name="label"
                                                       value="{{$static_value->label}}"/>
                                            </td>
                                            <td>{{$static_value->name}}</td>
                                            <td class="value">
                                                <span class="d-block">{{$static_value->value}}</span>
                                                <input type="text" class="form-control d-none" name="value"
                                                       value="{{$static_value->value}}"/>
                                            </td>
                                            <td>
                                                <a onclick="javascript:edit('{{$static_value->id}}')"
                                                   href="javascript:void(0)" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                                <a onclick="javascript:save('{{$static_value->id}}')"
                                                   href="javascript:void(0)" title="Save"><i
                                                        class="fas fa-save d-none"></i></a>&nbsp;&nbsp;&nbsp;
                                                <a onclick="javascript:cancel('{{$static_value->id}}')"
                                                   href="javascript:void(0)" title="Cancel"><i
                                                        class="far fa-window-close d-none"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css") }}">
    <script src="{{ asset("/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js") }}"></script>
    <script src="{{asset('/js/admin/static-value.js')}}"></script>
@endsection
