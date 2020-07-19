@extends('layouts.admin-template')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <h1 class="m-0 text-dark">Category List</h1>
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
                    <ul class="sort_category list-group"  id="sortable">
                        @foreach ($categories as $category)
                        <li class="list-group-item" data-id="{{$category->id}}">
                            <div class="cat-item d-block">
                                <i class="fas fa-sort"></i> &nbsp;&nbsp;
                                <a href="/">{{$category->name}}</a> 
                                (<small>
                                    <strong>Items: </strong>{{$category->items}},
                                    <strong> Stickers: </strong>{{$category->stickers}}
                                </small>)
                            </div>
                        </li>
                        @endforeach
                    </ul> 
                </div>
            </div>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<style>
    .list-group-item a:hover{
        text-decoration: underline;
    }

    .cat-item{
        cursor: pointer;
    }

    .highlight {
        background: #f7e7d3;
        min-height: 30px;
        list-style-type: none;
    }</style>
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/jquery-ui/jquery-ui.min.css") }}">
<script src="{{ asset("/bower_components/admin-lte/plugins/jquery-ui/jquery-ui.min.js") }}"></script>
<script>
    $( function() {
      $( "#sortable" ).sortable();
      $( "#sortable" ).disableSelection();

      let updateOrder = function(idString){
    	   $.ajaxSetup({ headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
    		
    	   $.ajax({
              url:'{{url('/category/update-order')}}',
              method:'POST',
              data:{ids: idString},
              success:function(){
              }
           })
    	}

        var target = $('.sort_category');
        target.sortable({
            handle: '.cat-item',
            placeholder: 'highlight',
            axis: "y",
            update: function (e, ui){
               var sortData = target.sortable('toArray',{ attribute: 'data-id'})
               updateOrder(sortData.join(','))
            }
        })
    });
    </script>
@endsection