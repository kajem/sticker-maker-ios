@extends('layouts.admin-template')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <h1 class="m-0 text-dark">Search Keywords</h1>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="search-keywords" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th scope="col" width="30%">Keyword</th>
                            <th scope="col">Count</th>
                            <th scope="col">Item Found</th>
                            <th scope="col">Created at</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($keywords as $keyword)
                                <tr>
                                    <td>{{$keyword->name}}</td>
                                    <td>{{$keyword->count}}</td>
                                    <td>{{$keyword->is_item_found}}</td>
                                    <td>{{$keyword->created_at}}</td>
                                </tr>
                            @endforeach
                        </tbody> --}}
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
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
<script>
    let table;
    $(function () {
      table = $('#search-keywords').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "order": [[ 0, "asc" ]],
        "info": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            'url':"/admin/report/search-keyword",
            'type': 'POST',
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        },
        "columns": [
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'count',
                name: 'count'
            },
            {
                data: 'is_item_found',
                name: 'is_item_found'
            },
            {
                data: 'created_at',
                name: 'created_at'
            }
        ],
        "drawCallback": function( settings ) {
            $('<li><a onclick="refresh_tab()" class="fa fa-refresh"></a></li>').prependTo('div.dataTables_paginate ul.pagination');
        }
      });
    });

    let refresh_tab = function(){
        table.ajax.reload();
    }

  </script>
@endsection