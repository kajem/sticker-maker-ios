@extends('layouts.admin-template', ['pageTitle' => 'Search Keywords', 'showBackButton' => false])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row d-none" id="filterDiv">
                                <label class="pl-2">Date From: <input type='text' id='date_from' class="datepicker form-control form-control-sm" autocomplete="off"></label>
                                <label class="pl-2">Date To: <input type='text' id='date_to' class="datepicker form-control form-control-sm" autocomplete="off"></label>
                                <button class="btn btn-primary btn-sm ml-2" id="reset_filter">Reset</button>
                            </div>

                            <table id="search-keywords" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" width="30%">Keyword</th>
                                    <th scope="col">Count</th>
                                    <th scope="col">Item Found</th>
                                    <th scope="col">Updated at</th>
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

    <!--jQuery UI-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        let table;
        $(function () {
            table = $('#search-keywords').DataTable({
                "language": {
                    "emptyTable": "No keywords found."
                },
                "paging": true,
                "responsive": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "order": [[0, "asc"]],
                "info": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "/report/search-keyword",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data': function(data){
                        // Read values
                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();

                        // Append to data
                        data.date_from = date_from;
                        data.date_to = date_to;
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
                        data: 'updated_at',
                        name: 'updated_at'
                    }
                ],
                "drawCallback": function (settings) {
                    $('<li><a onclick="refresh_tab()" class="fa fa-refresh"></a></li>').prependTo('div.dataTables_paginate ul.pagination');
                }
            });

            let refresh_tab = function () {
                table.ajax.reload();
            }

            //Structuring the Date from and Date to field
            var date_fields = $('#filterDiv').html();
            $('#filterDiv').remove();
            $('#search-keywords_filter').append(date_fields);
            $('#search-keywords_wrapper div.col-sm-12').removeClass('col-md-6');

            // Datapicker
            $( "#date_from, #date_to" ).datepicker({
                "dateFormat": "yy-mm-dd"
            });

            $('#date_from').on('change', function() {refresh_tab(); } );
            $('#date_to').on('change', function() { refresh_tab(); } );

            $('#reset_filter').on('click', function (){
                $('#search-keywords_filter input').val('');
                table.search( this.value ).draw();
            });
        });
    </script>
    <!-- Data Table -->
@endsection
