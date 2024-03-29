@extends('layouts.admin-template', ['pageTitle' => 'Category: '.$category->name, 'backButtonText' => 'Back to category list', 'backButtonURL' => url('category/list'), 'showBackButton' => true])

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
                                    <td>Packs / Stickers:</td>
                                    <td>{{$category->items}} / {{$category->stickers}}</td>
                                </tr>
                                <tr>
                                    <td>Version:</td>
                                    <td>{{$category->version}}</td>
                                </tr>
                                <tr>
                                    <td>Description:</td>
                                    <td>{{$category->text}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Add Pack</h3>
                        </div>
                        <div class="card-body all-item-card">
                            <div class="row">
                                <div class="col-sm-2 font-weight-bold">Thumb</div>
                                <div class="col-sm-4 font-weight-bold">Name</div>
                                <div class="col-sm-2 font-weight-bold text-center">Code</div>
                                <div class="col-sm-1 font-weight-bold text-center p-0">Total Sticker</div>
                                <div class="col-sm-1 font-weight-bold text-center p-0">Status</div>
                                <div class="col-sm-2 font-weight-bold text-center">Action</div>
                            </div>
                            <div id="allItems">
                                <ul class="list-group">
                                    @if (!empty($all_items))
                                        @foreach ($all_items as $item)
                                            <li class="list-group-item p-2" id="item-{{$item->id}}" data-id="{{$item->id}}">
                                                <div class="drag-item d-block">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <i class="fas fa-sort pt-3 pr-3 d-none arrow-up-down"></i>
                                                            <a href="/pack/braincraft/{{$item->code}}" target="_blank">
                                                                <?php
                                                                    $thumb = !empty($item->thumb) ? explode("/", $item->thumb) : '';
                                                                    $thumb = is_array($thumb) && count($thumb) > 1 ? end($thumb) : '';
                                                                ?>
                                                                <img width="45" src="{{config('app.asset_base_url')}}items/{{$item->code}}/200__{{$thumb}}" alt="{{$item->name}}"/>
                                                            </a>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <a href="/pack/braincraft/{{$item->code}}" target="_blank">{{$item->name}}</a>
                                                            @if(!empty($item->categories))
                                                                <br><small><strong>Cat.:</strong> {!! $item->categories !!}</small>
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-2 pt-3 text-center">
                                                            {{$item->code}}
                                                        </div>
                                                        <div class="col-sm-1 pt-3 text-center">
                                                            {{$item->total_sticker}}
                                                        </div>
                                                        <div class="col-sm-1 pt-3 text-center p-0">
                                                            {{$item->status ? 'Active' : 'Inactive'}}
                                                        </div>
                                                        <div class="col-sm-2 pt-3 text-right">
                                                            <a href="javascript:void(0)" data-item-id="{{$item->id}}"
                                                               title="Add to category" class="pr-3 add-item-to-category"><i class="fas fa-plus-circle"></i></a>
                                                            <a href="javascript:void(0)" data-item-id="{{$item->id}}" data-item-name="{{$item->name}}"
                                                               title="Remove" class="d-none remove-item-from-category  text-red"><i class="fas fa-minus-circle"></i></a>
                                                            &nbsp;&nbsp;&nbsp;<a target="_blank" href="/item/edit/{{$item->id}}"
                                                               title="Edit"><i class="fas fa-edit"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
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
                            <div class="row">
                                <div class="col-sm-2 font-weight-bold"><span class="pl-3">Thumb</span></div>
                                <div class="col-sm-4 font-weight-bold">Name</div>
                                <div class="col-sm-2 font-weight-bold text-center">Code</div>
                                <div class="col-sm-1 font-weight-bold text-center p-0">Total Sticker</div>
                                <div class="col-sm-1 font-weight-bold text-center p-0">Status</div>
                                <div class="col-sm-2 font-weight-bold text-right">Action</div>
                            </div>
                            <ul class="sort_item list-group" id="sortable">
                                <input type="hidden" name="category_id" id="category_id" value="{{$category->id}}"/>
                                @if (!empty($items))
                                    @foreach ($items as $item)
                                        <li class="list-group-item sortable-item-{{$item->id}}" data-id="{{$item->id}}">
                                            <div class="drag-item d-block">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <i class="fas fa-sort pt-3 pr-3"></i>
                                                        <a href="/pack/braincraft/{{$item->code}}" target="_blank">
                                                            <?php
                                                            $thumb = !empty($item->thumb) ? explode("/", $item->thumb) : '';
                                                            $thumb = is_array($thumb) && count($thumb) > 1 ? end($thumb) : '';
                                                        ?>
                                                            <img width="45" src="{{config('app.asset_base_url')}}items/{{$item->code}}/200__{{$thumb}}" alt="{{$item->name}}"/>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <a href="/pack/braincraft/{{$item->code}}" target="_blank">{{$item->name}}</a>
                                                        @if(!empty($item->categories))
                                                            <br><small><strong>Cat.:</strong> {!! $item->categories !!}</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-2 pt-3 text-center">
                                                        {{$item->code}}
                                                    </div>
                                                    <div class="col-sm-1 pt-3 text-center">
                                                        {{$item->total_sticker}}
                                                    </div>
                                                    <div class="col-sm-1 pt-3 text-center p-0">
                                                        {{$item->status ? 'Active' : 'Inactive'}}
                                                    </div>
                                                    <div class="col-sm-2 pt-3 text-right">
                                                        <a href="javascript:void(0)" data-item-id="{{$item->id}}" data-item-name="{{$item->name}}"
                                                            title="Remove" class="remove-item-from-category text-red"><i class="fas fa-minus-circle"></i></a>
                                                        &nbsp;&nbsp;&nbsp;<a target="_blank" href="/item/edit/{{$item->id}}"
                                                           title="Edit"><i class="fas fa-edit"></i></a>
                                                    </div>
                                                </div>
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
    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.css") }}">
    <script src="{{ asset("/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js") }}"></script>
    <script>
        $(function () {
            let category_id = $('#category_id').val();
            $("#sortable").sortable();
            $("#sortable").disableSelection();

            let updateOrder = function (idString) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
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

            //Add Item to Category
            $('.add-item-to-category').on('click', function () {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
                let item_id = $(this).attr('data-item-id');

                $.ajax({
                    url: '{{url('category/add-item-to-category')}}',
                    method: 'POST',
                    data: {category_id: category_id, item_id: item_id},
                    beforeSend: function() {
                        $('.all-item-card .alert').remove();
                        $('.all-item-card').prepend('<div class="alert alert-success alert-dismissible">\n' +
                            '                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                            '                  <span class="message">Request in progress, please wait...</span>\n' +
                            '                </div>');
                    },
                    success: function (data) {
                        if(data.status == 200){
                            $('.all-item-card .alert span.message').text(data.message);

                            $('#item-'+item_id).clone().appendTo('#sortable');
                            $('#item-'+item_id).remove();
                            $('#item-'+item_id+' .arrow-up-down').removeClass('d-none');
                            $('#item-'+item_id+' a.add-item-to-category').remove();
                            $('#item-'+item_id+' a.remove-item-from-category').removeClass('d-none');
                        }else{
                            $('.all-item-card .alert').removeClass('alert-success').addClass('alert-danger');
                            $('.all-item-card .alert span.message').text(data.message);
                        }
                        setTimeout( function(){
                            $('button.close').trigger('click');
                        }, 5000 );
                    }
                })
            });

            //Remove Item from Category
            $(document).on('click', '.remove-item-from-category', function () {
                let item_name = $(this).attr('data-item-name');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You would like to remove pack - '+item_name+'.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.value) {
                        let item_id = $(this).attr('data-item-id');
                        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});
                        $.ajax({
                            url: '{{url('/category/remove-item-from-category')}}',
                            method: 'POST',
                            data: {category_id: category_id, item_id: item_id},
                            success: function (data) {
                                $('#sortable li[data-id="'+item_id+'"]').remove();
                                Swal.fire(
                                    'Removed!',
                                    data.message,
                                    'success'
                                )
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection
