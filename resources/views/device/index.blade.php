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
        <a href="#modal-dialog" id="btn-add" class="btn btn-primary mb-3" data-route="{{ route('devices.store') }}" data-bs-toggle="modal"><i class="ion-ios-add"></i> Add Device</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Foto</th>
                    <th class="text-nowrap">ID Device</th>
                    <th class="text-nowrap">Company</th>
                    <th class="text-nowrap">Name</th>
                    <th class="text-nowrap">Type</th>
                    <th class="text-nowrap">Lat</th>
                    <th class="text-nowrap">Long</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Device</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-device" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="company_id" value="">

                    <div class="form-group row mb-3">
                        <input type="hidden" name="company_id" value="{{ request('company') }}">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $device->name ?? old('name') }}">

                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="iddev">ID Device</label>
                            <input type="text" name="iddev" id="iddev" class="form-control" value="{{ $device->iddev ?? old('iddev') }}">

                            @error('iddev')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <label for="end_user">End User</label>
                            <input type="text" name="end_user" id="end_user" class="form-control" value="{{ $device->end_user ?? old('end_user') }}">

                            @error('end_user')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="lat">Latitude</label>
                            <input type="text" name="lat" id="lat" class="form-control" value="{{ $device->lat ?? old('lat') }}">

                            @error('lat')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group mb-3 row">
                        <div class="col-md-6">
                            <label for="type">Type</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{ $device->type ?? old('type') }}">

                            @error('type')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="long">Longitude</label>
                            <input type="text" name="long" id="long" class="form-control" value="{{ $device->long ?? old('long') }}">

                            @error('long')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <label for="power">Power</label>
                            <input type="text" name="power" id="power" class="form-control" value="{{ $device->power ?? old('power') }}">

                            @error('type')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modbus">Title Modbus</label>
                            <input type="text" name="modbus" id="modbus" class="form-control" value="{{ $device->modbus ?? old('modbus') }}" placeholder="Pump Status">

                            @error('modbus')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <label for="head">Head</label>
                            <input type="text" name="head" id="head" class="form-control" value="{{ $device->head ?? old('head') }}">

                            @error('head')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="digital">Title Digital Input</label>
                            <input type="text" name="digital" id="digital" class="form-control" value="{{ $device->digital ?? old('digital') }}" placeholder="Pump Status">

                            @error('digital')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <label for="flow">Flow</label>
                            <input type="text" name="flow" id="flow" class="form-control" value="{{ $device->flow ?? old('flow') }}">

                            @error('flow')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="company">Company</label>
                            <select name="company_id" id="company" class="form-control">
                                <option disabled selected>-- Select Company --</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>

                            @error('company')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="topic">Topic</label>
                            <input type="text" name="topic" id="topic" class="form-control" value="{{ $device->topic ?? old('topic') }}">

                            @error('topic')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control">

                            @error('image')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}js/device.js"></script>
<script>
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('devices.get') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'iddev',
                name: 'iddev'
            },
            {
                data: 'company',
                name: 'company'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'lat',
                name: 'lat'
            },
            {
                data: 'long',
                name: 'long'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-device").append(`<input type="hidden" name="_method" value="POST">`);
        $("#form-device").attr('action', route)
        $("#name").val("")
        $("#end_user").val("")
        $("#type").val("")
        $("#power").val("")
        $("#head").val("")
        $("#flow").val("")
        $("#company").val("")
        $("#iddev").val("")
        $("#lat").val("")
        $("#long").val("")
        $("#modbus").val("")
        $("#digital").val("")
        $("#topic").val("")
    })

    $("#btn-close").on('click', function() {
        $("#form-device").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-device").attr('action', route)
        $("#form-device").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/devices/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let device = response.device;

                $("#name").val(device.name)
                $("#end_user").val(device.end_user)
                $("#type").val(device.type)
                $("#power").val(device.power)
                $("#head").val(device.head)
                $("#flow").val(device.flow)
                $("#iddev").val(device.iddev)
                $("#company").val(device.company_id)
                $("#lat").val(device.lat)
                $("#long").val(device.long)
                $("#modbus").val(device.modbus)
                $("#digital").val(device.digital)
                $("#topic").val(device.topic)
            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data company?',
            text: 'Menghapus company bersifat permanen.',
            icon: 'error',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-danger',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-delete").submit()
            } else {
                $("#form-delete").attr('action', '')
            }
        });
    })
</script>
@endpush