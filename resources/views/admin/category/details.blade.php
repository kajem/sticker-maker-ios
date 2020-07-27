@extends('layouts.admin-template', ['pageTitle' => 'Category: '.$category->name, 'showBackButton' => false])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General Info</h3>
                            <a href="{{url('category/'.$category->id)}}/edit" class="float-right ml-3" title="Edit"><i
                                    class="fas fa-edit"></i></a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{$category->name}}</td>
                                </tr>
                                <tr>
                                    <td>Packs:</td>
                                    <td>{{$category->items}}</td>
                                </tr>
                                <tr>
                                    <td>Stickers:</td>
                                    <td>{{$category->stickers}}</td>
                                </tr>
                                <tr>
                                    <td>Version:</td>
                                    <td>{{$category->version}}</td>
                                </tr>
                                <tr>
                                    <td>Type:</td>
                                    <td>{{ucfirst($category->type)}}</td>
                                </tr>
                                <tr>
                                    <td>Description:</td>
                                    <td>{{$category->text}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Packs <small>(You may sort the packs by dragging up and down)</small>
                            </h3>
                        </div>
                        <div class="card-body">
                            <ul class="sort_item list-group" id="sortable">
                                @if (!empty($items))
                                    <input type="hidden" name="category_id" id="category_id" value="{{$category->id}}"/>
                                    @foreach ($items as $item)
                                        <li class="list-group-item" data-id="{{$item->id}}">
                                            <div class="drag-item d-block">
                                                <i class="fas fa-sort"></i> &nbsp;&nbsp;
                                                {{$item->name}}
                                                (<small>
                                                    <strong>Code: </strong>{{$item->code}},
                                                    <strong> Stickers: </strong>{{$item->total_sticker}}
                                                </small>)
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
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
                let category_id = $('#category_id').val();
                $.ajax({
                    url: '{{url('/category/update-item-order')}}',
                    method: 'POST',
                    data: {ids: idString, category_id: category_id},
                    success: function () {
                    }
                })
            }

            var target = $('.sort_item');
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
