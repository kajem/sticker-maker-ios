@extends('layouts.admin-template', ['pageTitle' => 'All Sticker Packages', 'backButtonText' => '<i class="fas fa-plus"></i> Add new package', 'backButtonURL' => url('item/add'), 'showBackButton' => true])
@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="download-item-report">
                                <a href="/item/download-report" class="btn btn-primary btn-sm ml-2 text-white"><i class="fas fa-file-csv"></i> Download CSV</a>
                            </div>
                            <table id="item-list" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col" class="text-center">Code</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col" class="text-center">Total Sticker</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Telegram Name</th>
                                    <th scope="col">Updated at</th>
                                    <th scope="col">Status</th>
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
                            let bg_color = data.thumb_bg_color.length > 1 ? 'background-color:'+ data.thumb_bg_color : '';
                            return '<img width="45" style="'+ bg_color + '" src="{{config("app.asset_base_url")}}items/' + data.code + '/' + data.thumb + '" alt="' + data.name + '"/>';
                        }
                    },
                    {
                        data: 'name',
                        name: 'items.name'
                    },
                    {
                        data: 'slug',
                        name: 'items.slug'
                    },
                    {
                        data: 'code',
                        className: 'text-center',
                        name: 'items.code'
                    },
                    {
                        data: 'tag',
                        name: 'items.tag'
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
                            return data.categories;
                        }
                    },
                    {
                        data: 'author',
                        name: 'items.author'
                    },
                    {
                        data: null,
                        name: 'items.telegram_name',
                        className: 'text-center',
                        render: function (data){
                            var telegram_name = '';
                            if(data.telegram_name != null){
                                telegram_name = '<a target="_blank" href="{{config('services.telegram.set_base_url')}}'+data.telegram_name+'">';
                                telegram_name += data.telegram_name.replace('_by_brain_2015_bot', '');
                                if(data.is_telegram_set_completed){
                                    telegram_name += '&nbsp;&nbsp; <i class="fas fa-check-circle"></i>';
                                }
                                telegram_name += '</a>';
                            }

                            return telegram_name;
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'items.updated_at'
                    },
                    {
                        data: null,
                        name: 'items.status',
                        className: 'text-center',
                        render: function (data){
                            return data.status === 1 ? 'Active' : 'Inactive';
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function (data) {
                            var actionHTML = '';
                            //actionHTML += '<a href="#" title="View Details"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;';
                            actionHTML += '<a href="/item/edit/' + data.id + '" title="Edit"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;';
                            actionHTML += '<a href="/pack/braincraft/' + data.code + '" target="_blank" title="View Stickers"><i class="far fa-grin"></i></a> &nbsp;&nbsp;';
                            if(data.is_animated === 0){
                                actionHTML += '<a href="/telegram/pack/' + data.id + '" title="Telegram Sticker Set"><i class="fab fa-telegram-plane"></i></a>';
                            }
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
