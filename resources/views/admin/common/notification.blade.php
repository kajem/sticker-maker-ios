@if ($message = Session::get('success'))
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success alert-block">
                    <button type="button" class="close text-white" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif


@if ($message = Session::get('error'))
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-block">
                    <button type="button" class="close text-white" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif
