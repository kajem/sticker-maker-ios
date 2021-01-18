@extends('layouts.admin-template', ['pageTitle' => 'Restricted Area', 'showBackButton' => false])

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="display-1 text-red">403</h1>
                            <h1 class="display-5 text-red">Restricted Area!</h1>
                            <h4 class="mt-4">You don't have permission to this page. <br/>Please contact your administrator.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
