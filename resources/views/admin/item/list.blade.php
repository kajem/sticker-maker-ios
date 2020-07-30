@extends('layouts.admin-template', ['pageTitle' => 'All Packs', 'showBackButton' => false])
@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="item-list" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Code</th>
                                    <th scope="col" class="text-center">Total Sticker</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Author</th>
                                    <th scope="col" class="text-center">Premium</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col">Updated at</th>
                                    <th scope="col" class="text-center">Action</th>
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
        let table;
        $(function () {
            table = $('#item-list').DataTable({
                "language": {
                    "emptyTable": "No packs found."
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
                    'url': "/item/list",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {
                        data: null,
                        orderable: false,
                        render: function (data) {
                            return '<img width="45" src="{{config("app.asset_base_url")}}items/' + data.code + '/200__' + data.code + '.png" alt="' + data.name + '"/>';
                        }
                    },
                    {
                        data: 'name',
                        name: 'items.name'
                    },
                    {
                        data: 'code',
                        className: 'text-center',
                        name: 'items.code'
                    },
                    {
                        data: 'total_sticker',
                        className: 'text-center',
                        name: 'items.total_sticker'
                    },
                    {
                        data: null,
                        name: 'categories.name',
                        render: function (data) {
                            return '<a href="/category/' + data.category_id + '">' + data.category_name + '</a>';
                        }
                    },
                    {
                        data: 'author',
                        name: 'items.author'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        name: 'items.is_premium',
                        render: function (data) {
                            return data.is_premium == 1 ? 'Yes' : 'No';
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        name: 'items.status',
                        render: function (data) {
                            return data.status == 1 ? 'Active' : 'Inactive';
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'items.updated_at'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function (data) {
                            var actionHTML = '';
                            //actionHTML += '<a href="#" title="View Details"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;';
                            actionHTML += '<a href="/item/edit/' + data.id + '" title="Edit"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;';
                            actionHTML += '<a href="/pack/braincraft/' + data.code + '" target="_blank" title="View Stickers"><i class="far fa-grin"></i></a>';
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
