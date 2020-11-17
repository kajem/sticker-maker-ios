@extends('layouts.admin-template', ['pageTitle' => 'All Posts - How to use Sticker Maker', 'backButtonText' => '<i class="fas fa-plus"></i> Add new post', 'backButtonURL' => url('post/how-to-use-sm/add'), 'showBackButton' => true])
@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="post-list" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Banner</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Published at</th>
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
        let type = '{{$type}}';
        $(function () {
            table = $('#post-list').DataTable({
                "language": {
                    "emptyTable": "No posts found."
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
                    'url': "/post/how-to-use-sm/list",
                    'type': 'POST'
                },
                "columns": [
                    {
                        data: null,
                        orderable: false,
                        render: function (data) {
                            return '<img width="45" src="{{config("app.asset_base_url")}}website_resource/post_images/' + data.banner + '" alt="' + data.banner_alt + '"/>';
                        }
                    },
                    {
                        data: 'title',
                        name: 'posts.title'
                    },
                    {
                        data: 'slug',
                        name: 'posts.slug'
                    },
                    {
                        data: 'tag',
                        name: 'posts.tag'
                    },
                    {
                        data: 'author',
                        name: 'posts.author'
                    },
                    {
                        data: 'published_date',
                        name: 'posts.published_date'
                    },
                    {
                        data: 'updated_at',
                        name: 'posts.updated_at'
                    },
                    {
                        data: null,
                        name: 'posts.status',
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
                            actionHTML += '<a target="_blank" href="/how-to-use-sticker-maker/'+data.slug+'" title="View Details"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;';
                            actionHTML += '<a href="/post/' + type + '/' + data.id  + '/edit" title="Edit"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;';
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
