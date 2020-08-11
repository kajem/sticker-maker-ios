@extends('layouts.admin-template', ['pageTitle' => $title, 'backButtonText' => '<i class="fas fa-plus"></i> Add new category', 'backButtonURL' => url('category/add'), 'showBackButton' => true])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Category List <small>(You may sort the categories by dragging up and
                                    down)</small></h3><br>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 font-weight-bold"><span class="pl-5">Thumb</span></div>
                                <div class="col-sm-4 font-weight-bold">Name</div>
                                <div class="col-sm-2 font-weight-bold text-center">Pack / Sticker</div>
                                <div class="col-sm-2 font-weight-bold text-center">Status</div>
                                <div class="col-sm-2 font-weight-bold text-center">Action</div>
                            </div>
                            <ul class="sort_category list-group" id="sortable">
                                @foreach ($categories as $category)
                                    <li class="list-group-item" data-id="{{$category->id}}">
                                        <div class="drag-item d-block">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <i class="fas fa-sort pt-3 pr-3"></i>
                                                    <a href="{{url('category/'.$category->id)}}">
                                                        <img width="45" src="{{config('app.asset_base_url')}}category-thumbs/cat_{{$category->id}}_tmb.png" alt="{{$category->name}}"/>
                                                    </a>
                                                </div>
                                                <div class="col-sm-4 pt-3">
                                                    <a href="{{url('category/'.$category->id)}}">{{$category->name}}</a>
                                                </div>
                                                <div class="col-sm-2 pt-3 text-center">
                                                    {{$category->items}} / {{$category->stickers}}
                                                </div>
                                                <div class="col-sm-2 pt-3 text-center">
                                                    {{$category->status == 1 ? 'Active' : 'Inactive'}}
                                                </div>
                                                <div class="col-sm-2 pt-3 text-center">
                                                    <a href="{{url('category/'.$category->id)}}/edit"
                                                       title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a href="{{url('category/'.$category->id)}}" class="ml-3"
                                                       title="View Details"><i class="fas fa-eye"></i></a>
                                                </div>
                                            </div>
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
        $(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();

            let updateOrder = function (idString) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});

                $.ajax({
                    url: '{{url("category/update-order")}}',
                    method: 'POST',
                    data: {ids: idString, sort_field: '{{$sort_field}}'},
                    success: function () {
                    }
                })
            }

            var target = $('.sort_category');
            target.sortable({
                handle: '.drag-item',
                placeholder: 'highlight',
                axis: "y",
                update: function (e, ui) {
                    var sortData = target.sortable('toArray', {attribute: 'data-id'})
                    updateOrder(sortData.join(','))
                }
            })
        });
    </script>
@endsection
