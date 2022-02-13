@extends('layouts.admin-template', ['pageTitle' => 'All Users', 'backButtonText' => '<i class="fas fa-plus"></i> Add new user', 'backButtonURL' => '/user/add', 'showBackButton' => true])
@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="user-list" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Permissions</th>
                                    <th scope="col">Updated at</th>
                                    <th scope="col">Created at</th>
                                    <th scope="col" class="text-center" width="80">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Data Table -->
    <link rel="stylesheet"
          href="{{ asset("/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script
        src="{{ asset("/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
    <!-- Data Table Responsive-->
    <link rel="stylesheet"
          href="{{ asset("/bower_components/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css") }}">
    <script
        src="{{ asset("/bower_components/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"></script>
    <script>
        var table;
        var permissions = {!! json_encode(config('constants.permissions')) !!};
        console.log("permissions");
        console.log(permissions);
        $(function () {
            table = $('#user-list').DataTable({
                "language": {
                    "emptyTable": "No users found."
                },
                "paging": true,
                "responsive": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "order": [[1, "asc"]],
                "info": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "/user/list",
                    'type': 'POST'
                },
                "columns": [
                    {
                        data: 'name',
                        name: 'users.name'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (data){
                            var html = '';
                            var category_html = '';
                            var sticker_package_html = '';
                            var how_to_use_sm_html = '';
                            for (const [key, value] of Object.entries(permissions.category_routes)) {
                                if(data.permissions !== null && data.permissions.indexOf(key) !== -1){
                                    category_html += category_html.length > 0 ? ", " + value : value;
                                }
                            }

                            for (const [key, value] of Object.entries(permissions.sticker_package_routes)) {
                                if(data.permissions !== null && data.permissions.indexOf(key) !== -1){
                                    sticker_package_html += sticker_package_html.length > 0 ? ", " + value : value;
                                }
                            }

                            for (const [key, value] of Object.entries(permissions.how_to_use_sm_routes)) {
                                if(data.permissions !== null && data.permissions.indexOf(key) !== -1){
                                    how_to_use_sm_html += how_to_use_sm_html.length > 0 ? ", " + value : value;
                                }
                            }
                            if(category_html.length > 0){
                                html += '<div class=""><strong>Category</strong>: '+category_html+'</div>';
                            }
                            if(sticker_package_html.length > 0){
                                html += '<div class=""><strong>Sticker Package</strong>: '+sticker_package_html+'</div>';
                            }
                            if(how_to_use_sm_html.length > 0){
                                html += '<div class=""><strong>How to use SM</strong>: '+how_to_use_sm_html+'</div>';
                            }
                            //console.log(data.permissions);
                            return html;
                            //console.log(unserialize(data.permissions);
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'users.updated_at'
                    },
                    {
                        data: 'created_at',
                        name: 'users.created_at'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function (data) {
                            var actionHTML = '';
                            actionHTML += '<a href="/user/' + data.id  + '/edit" title="Edit"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;';
                            return actionHTML;
                        }
                    }
                ],
                "drawCallback": function (settings) {
                    $('<li><a onclick="refresh_tab()" class="fa fa-refresh"></a></li>').prependTo('div.dataTables_paginate ul.pagination');
                }
            });
        });

        let refresh_tab = function () {
            table.ajax.reload();
        }
    </script>
    <!-- Data Table -->
@endsection
