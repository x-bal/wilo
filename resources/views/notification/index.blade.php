@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
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
        <a href="#modal-dialog" id="btn-add" class="btn btn-primary mb-3" data-route="{{ route('notifications.store') }}" data-bs-toggle="modal"><i class="ion-ios-add"></i> Add Notification</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Device</th>
                    <th class="text-nowrap">Phone</th>
                    <th class="text-nowrap">Person Name</th>
                    <th class="text-nowrap">Interval</th>
                    <th class="text-nowrap">Trigger</th>
                    <th class="text-nowrap">Schedule</th>
                    <th class="text-nowrap">Custom Message</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Notification</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-notification" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="device">Device</label>
                        <select name="device" id="device" class="form-control">
                            <option disabled selected>-- Select Device --</option>
                            @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="number" name="phone" id="phone" class="form-control" value="">

                        @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="person_name">Person Name</label>
                        <input type="text" name="person_name" id="person_name" class="form-control" value="">

                        @error('person_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="interval" name="interval" />
                            <label class="form-check-label" for="interval">Interval</label>

                            @error('interval')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="trigger" name="trigger" />
                            <label class="form-check-label" for="trigger">Trigger</label>

                            @error('trigger')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="schedule_time">Schedule Time</label>
                        <input type="time" name="schedule_time" id="schedule_time" class="form-control" value="">

                        @error('schedule_time')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="custom_message">Custom Message</label>
                        <textarea name="custom_message" id="custom_message" rows="4" class="form-control"></textarea>

                        @error('custom_message')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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

<form action="" class=" d-none" id="form-delete" method="post">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}plugins/select2/dist/js/select2.min.js"></script>

<script>
    $("#permissions").select2({
        dropdownParent: $('#modal-dialog'),
        placeholder: "Select Permissions"
    });

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('notifications.get') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'device_id',
                name: 'device_id'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'person_name',
                name: 'person_name'
            },
            {
                data: 'interval',
                name: 'interval'
            },
            {
                data: 'trigger',
                name: 'trigger'
            },
            {
                data: 'schedule_time',
                name: 'schedule_time'
            },
            {
                data: 'custom_message',
                name: 'custom_message'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-notification").append(`<input type="hidden" name="_method" value="POST">`);
        $("#form-notification").attr('action', route)
        $("#name").val("")
    })

    $("#btn-close").on('click', function() {
        $("#form-notification").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-notification").attr('action', route)
        $("#form-notification").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/roles/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let role = response.role;

                $("#name").val(role.name)
                $("#permissions").val(response.permissions).trigger('change')
            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data role?',
            text: 'Menghapus role bersifat permanen.',
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