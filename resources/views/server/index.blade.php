@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    Validation Error
    <button type="button" class="btn-close" data-bs-dismiss="alert"></span>
</div>
@enderror

<div class="panel panel-inverse">
    <!-- BEGIN panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <!-- END panel-heading -->
    <!-- BEGIN panel-body -->
    <div class="panel-body">
        <form action="{{ route('server.update', $server->id) }}" method="post" id="form-server" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="form-group mb-3">
                <label for="host">Host</label>
                <input type="host" name="host" id="host" class="form-control" value="{{ $server->host }}">

                @error('host')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="port">Port</label>
                <input type="number" name="port" id="port" class="form-control" value="{{ $server->port }}"">

                @error('port')
                <small class=" text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $server->username }}"">

                @error('username')
                <small class=" text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" class="form-control" value="{{ $server->password }}"">

                @error('password')
                <small class=" text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="client_id">Client ID</label>
                <input type="text" name="client_id" id="client_id" class="form-control" value="{{ $server->client_id }}"">

                @error('client_id')
                <small class=" text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
@endpush