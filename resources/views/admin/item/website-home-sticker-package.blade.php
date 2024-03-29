@extends('layouts.admin-template', ['pageTitle' => 'Website home sticker packages', 'showBackButton' => false])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All sticker packages</h3>
                        </div>
                        <div class="card-body all-item-card">
                            <div class="row">
                                <div class="col-sm-2 font-weight-bold">Thumb</div>
                                <div class="col-sm-4 font-weight-bold">Name</div>
                                <div class="col-sm-2 font-weight-bold text-center">Code</div>
                                <div class="col-sm-2 font-weight-bold text-center">Total Sticker</div>
                                <div class="col-sm-2 font-weight-bold text-center">Action</div>
                            </div>
                            <div id="allItems" class="website-home">
                                <ul class="list-group">
                                    @if (!empty($all_items))
                                        @foreach ($all_items as $item)
                                            <li class="list-group-item p-2" id="item-{{$item->id}}" data-id="{{$item->id}}">
                                                <div class="drag-item d-block">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <i class="fas fa-sort pt-3 pr-3 d-none arrow-up-down"></i>
                                                            <a href="/pack/braincraft/{{$item->code}}" target="_blank">
                                                                <img width="45" src="{{config('app.asset_base_url')}}items/{{$item->code}}/200__{{$item->code}}.png" alt="{{$item->name}}"/>
                                                            </a>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <a href="/pack/braincraft/{{$item->code}}" target="_blank">{{$item->name}}</a>
                                                        </div>
                                                        <div class="col-sm-2 pt-3 text-center">
                                                            {{$item->code}}
                                                        </div>
                                                        <div class="col-sm-2 pt-3 text-center">
                                                            {{$item->total_sticker}}
                                                        </div>
                                                        <div class="col-sm-2 pt-3 text-right">
                                                            <a href="javascript:void(0)" data-item-id="{{$item->id}}"
                                                               title="Add to website home" class="pr-3 add-item-to-website-home"><i class="fas fa-plus-circle"></i></a>
                                                            <a href="javascript:void(0)" data-item-id="{{$item->id}}" data-item-name="{{$item->name}}"
                                                               title="Remove" class="d-none remove-item-from-website-home  text-red"><i class="fas fa-minus-circle"></i></a>
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
                            <h3 class="card-title">Website home sticker packages <small>(You may sort the packs by dragging up and down)</small>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 font-weight-bold"><span class="pl-3">Thumb</span></div>
                                <div class="col-sm-4 font-weight-bold">Name</div>
                                <div class="col-sm-2 font-weight-bold text-center">Code</div>
                                <div class="col-sm-2 font-weight-bold text-center">Total Sticker</div>
                                <div class="col-sm-2 font-weight-bold text-right">Action</div>
                            </div>
                            <ul class="sort_item list-group" id="sortable">
                                @if (!empty($items))
                                    @foreach ($items as $item)
                                        <li class="list-group-item sortable-item-{{$item->id}}" data-id="{{$item->id}}">
                                            <div class="drag-item d-block">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <i class="fas fa-sort pt-3 pr-3"></i>
                                                        <a href="/pack/braincraft/{{$item->code}}" target="_blank">
                                                            <img width="45" src="{{config('app.asset_base_url')}}items/{{$item->code}}/200__{{$item->code}}.png" alt="{{$item->name}}"/>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <a href="/pack/braincraft/{{$item->code}}" target="_blank">{{$item->name}}</a>
                                                    </div>
                                                    <div class="col-sm-2 pt-3 text-center">
                                                        {{$item->code}}
                                                    </div>
                                                    <div class="col-sm-2 pt-3 text-center">
                                                        {{$item->total_sticker}}
                                                    </div>
                                                    <div class="col-sm-2 pt-3 text-right">
                                                        <a href="javascript:void(0)" data-item-id="{{$item->id}}" data-item-name="{{$item->name}}"
                                                            title="Remove" class="remove-item-from-website-home text-red"><i class="fas fa-minus-circle"></i></a>
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
            $("#sortable").sortable();
            $("#sortable").disableSelection();

            let updateOrder = function (idString) {
                $.ajax({
                    url: '{{url('/item/update-website-home-package-order')}}',
                    method: 'POST',
                    data: {ids: idString},
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

            //Add Item to website home
            $('.add-item-to-website-home').on('click', function () {
                let item_id = $(this).attr('data-item-id');

                $.ajax({
                    url: '{{url('item/add-stickage-package-to-website-home')}}',
                    method: 'POST',
                    data: {item_id: item_id},
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
                            $('#item-'+item_id+' a.add-item-to-website-home').remove();
                            $('#item-'+item_id+' a.remove-item-from-website-home').removeClass('d-none');
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

            //Remove Item from website home
            $(document).on('click', '.remove-item-from-website-home', function () {
                let item_name = $(this).attr('data-item-name');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You would like to remove sticker package - '+item_name+'.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.value) {
                        let item_id = $(this).attr('data-item-id');
                        $.ajax({
                            url: '{{url('/item/remove-stickage-package-from-website-home')}}',
                            method: 'POST',
                            data: {item_id: item_id},
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
