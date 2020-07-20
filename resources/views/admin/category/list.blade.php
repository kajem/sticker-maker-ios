@extends('layouts.admin-template')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <h1 class="m-0 text-dark">{{$title}}</h1>
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
                <div class="card-header">
                    <h3 class="card-title">Category List <small>(You can sort the categories by dragging up and down)</small></h3><br>
                </div>
                <div class="card-body">
                    <ul class="sort_category list-group"  id="sortable">
                        @foreach ($categories as $category)
                        <li class="list-group-item" data-id="{{$category->id}}">
                            <div class="drag-item d-block">
                                <i class="fas fa-sort"></i> &nbsp;&nbsp;
                                <a href="{{url('category/'.$category->id)}}">{{$category->name}}</a> 
                                (<small>
                                    <strong>Packs: </strong>{{$category->items}},
                                    <strong> Stickers: </strong>{{$category->stickers}}
                                </small>)

                                <a href="{{url('category/'.$category->id)}}/edit" class="float-right ml-3" title="Eid"><i class="fas fa-edit"></i></a>
                                <a href="{{url('category/'.$category->id)}}" class="float-right" title="View Details"><i class="fas fa-eye"></i></a>
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

<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/jquery-ui/jquery-ui.min.css") }}">
<script src="{{ asset("/bower_components/admin-lte/plugins/jquery-ui/jquery-ui.min.js") }}"></script>
<script>
    $( function() {
      $( "#sortable" ).sortable();
      $( "#sortable" ).disableSelection();

      let updateOrder = function(idString){
    	   $.ajaxSetup({ headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
    		
    	   $.ajax({
              url:'{{url("category/update-order")}}',
              method:'POST',
              data:{ids: idString, sort_field: '$sort_field'},
              success:function(){
              }
           })
    	}

        var target = $('.sort_category');
        target.sortable({
            handle: '.drag-item',
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