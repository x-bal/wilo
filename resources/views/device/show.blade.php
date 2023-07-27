@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
    button.gm-ui-hover-effect {
        visibility: hidden;
    }

    .map-label {
        -webkit-text-stroke: 1px rgba(255, 255, 255, .4) !important;
        color: #DB0202 !important;
        top: 35px;
        left: 0;
        position: relative;
        font-weight: bold;
        font-size: 16px !important;
    }

    /* .gm-style .gm-style-iw {} */
</style>
@endpush

@section('content')
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    Validation Error
    <button type="button" class="btn-close" data-bs-dismiss="alert"></span>
</div>
@enderror

<div class="row mb-5">
    <div class="col-md-12">
        <div class="card" style="min-width: 12rem; max-width: 15rem; position: absolute; z-index: 1; margin-top: 55px; margin-left: 10px;">
            <div class="card-body text-center" id="image">

            </div>
        </div>

        <div class="card" style="min-width: 12rem; max-width: 15rem; position: absolute; z-index: 1; margin-top: 200px; margin-left: 10px;">
            <div class="card-body" id="info">

            </div>
        </div>

        <div class="card" style="min-width: 12rem; max-width: 15rem; position: absolute; z-index: 1; margin-top: 5px; margin-left: 750px;">
            <div class="card-body text-center" id="modbus">

            </div>
        </div>

        <div class="card" style="min-width: 12rem; max-width: 15rem; position: absolute; z-index: 1; margin-top: 280px; margin-left: 750px;">
            <div class="card-body text-center" id="digital">

            </div>
        </div>

        <div id="map" style="width: 100%; height: 480px; z-index: 0;"></div>
    </div>
</div>

<div class="panel panel-inverse mb-5">
    <div class="panel-heading">
        <h4 class="panel-title">Modbus Device {{ $device->name }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Id Modbus</th>
                        <th>Val</th>
                        <th colspan="2">Math</th>
                        <th>Val(After)</th>
                        <th>Unit</th>
                        <th>Used</th>
                        <th>Showed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($device->modbuses as $modbus)
                    <tr>
                        <td class="check text-center">
                            <input type="checkbox" name="check" data-id="{{ $modbus->id }}" class="modbus-merge">
                        </td>
                        <td class="no text-center">{{ $loop->iteration }}</td>
                        <td class="name">
                            <input type="text" name="name" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-name" value="{{ $modbus->name }}">
                        </td>
                        <td class="address">
                            <input type="text" name="address" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-address" value="{{ $modbus->address }}" disabled>
                        </td>
                        <td class="id">
                            <input type="number" name="id" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-id" value="{{ $modbus->id_modbus }}" disabled>
                        </td>
                        <td class="val">
                            <input type="text" name="val" id="val-{{ $modbus->id }}" class="form-control form-control-sm" value="{{ $modbus->val }}" disabled>
                        </td>
                        <td class="math" colspan="2">
                            @php
                            $math = explode(',', $modbus->math)
                            @endphp
                            <select name="mark" class="form-control form-control-sm modbus-mark mark-{{ $modbus->id }}" data-id="{{ $modbus->id }}">
                                <option {{ $math[0] == 'x' ? 'selected' : '' }} value="x">x</option>
                                <option {{ $math[0] == ':' ? 'selected' : '' }} value=":">:</option>
                                <option {{ $math[0] == '+' ? 'selected' : '' }} value="+">+</option>
                                <option {{ $math[0] == '-' ? 'selected' : '' }} value="-">-</option>
                                <option {{ $math[0] == '&' ? 'selected' : '' }} value="&">4-20mA</option>
                            </select>
                            <br>
                            <input type="{{ $math[0] == '&' ? 'text' : 'number' }}" name="math" id="math-{{ $modbus->id }}" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-math" value="{{ $math[1] ?? 1 }}">
                            <small class="mod-{{ $modbus->id }}"></small>
                        </td>
                        <td class="after">
                            <input type="text" name="after" id="after-{{ $modbus->id }}" class="form-control form-control-sm" value="{{ $modbus->after }}" disabled>
                        </td>
                        <td class="satuan">
                            <input type="text" name="satuan" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-satuan" value="{{ $modbus->satuan }}">
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input modbus-used" data-id="{{ $modbus->id }}" type="checkbox" name="used" disabled {{ $modbus->is_used == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="used">Used</label>
                            </div>
                        </td>
                        <td class="showed">
                            <div class="form-check form-switch">
                                <input class="form-check-input modbus-showed" data-id="{{ $modbus->id }}" type="checkbox" name="showed" {{ $modbus->is_showed == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="showed">Showed</label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Merge Modbus Device {{ $device->name }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered align-middle table-merge">
                <thead>
                    <tr>
                        <th class="sort text-center" data-sort="no">No</th>
                        <th class="sort" data-sort="name">Name</th>
                        <th class="sort" data-sort="address">Modbus</th>
                        <th class="sort" data-sort="type">Type</th>
                        <th class="sort" data-sort="val">Val</th>
                        <th class="sort" data-sort="math" colspan="2">Math</th>
                        <th class="sort" data-sort="after">Val(After)</th>
                        <th class="sort" data-sort="satuan">Unit</th>
                        <th class="sort" data-sort="used">Used</th>
                        <th class="sort" data-sort="check">Act</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($device->merges as $merge)
                    <tr>
                        <td class="no text-center">{{ $loop->iteration }}</td>
                        <td class="name">
                            <input type="text" name="name" data-id="{{ $merge->id }}" class="form-control form-control-sm merge-name" value="{{ $merge->name }}">
                        </td>
                        <td class="address text-center">
                            @foreach($merge->modbuses as $mod)
                            ({{ $mod->id }}) {{ $mod->address }} <br>
                            @endforeach
                        </td>
                        <td>
                            <select name="type" data-id="{{ $merge->id }}" class="form-control merge-type">
                                <option {{ $merge->type == 'be' ? 'selected' : '' }} value="be">Big Endian</option>
                                <option {{ $merge->type == 'le' ? 'selected' : '' }} value="le">Little Endian</option>
                                <option {{ $merge->type == 'mbe' ? 'selected' : '' }} value="mbe">Mid Big Endian</option>
                                <option {{ $merge->type == 'mle' ? 'selected' : '' }} value="mle">Mid Little Endian</option>
                            </select>
                        </td>
                        <td class="val">
                            <b id="merge-val-{{ $merge->id }}">{{ $merge->val }}</b>
                        </td>
                        <td class="math" colspan="2">
                            @php
                            $mergeMath = explode(',', $merge->math)
                            @endphp
                            <select name="mark" class="form-control form-control-sm merge-mark mark-merge-{{ $merge->id }}" data-id="{{ $merge->id }}">
                                <option {{ $mergeMath[0] == 'x' ? 'selected' : '' }} value="x">x</option>
                                <option {{ $mergeMath[0] == ':' ? 'selected' : '' }} value=":">:</option>
                                <option {{ $mergeMath[0] == '+' ? 'selected' : '' }} value="+">+</option>
                                <option {{ $mergeMath[0] == '-' ? 'selected' : '' }} value="-">-</option>
                                <option {{ $mergeMath[0] == '&' ? 'selected' : '' }} value="&">4-20mA</option>
                            </select>
                            <br>
                            <input type="number" name="math" id="merge-math-{{ $merge->id }}" data-id="{{ $merge->id }}" class="form-control form-control-sm merge-math" value="{{ $mergeMath[1] ?? 1 }}">
                        </td>
                        <td class="after">
                            <input type="text" name="after" id="merge-after-{{ $merge->id }}" class="form-control form-control-sm" value="{{ $merge->after }}" disabled>
                        </td>
                        <td class="satuan">
                            <input type="text" name="satuan" data-id="{{ $merge->id }}" class="form-control form-control-sm merge-satuan" value="{{ $merge->unit }}">
                        </td>
                        <td class="used">
                            <div class="form-check form-switch">
                                <input class="form-check-input merge-used" data-id="{{ $merge->id }}" type="checkbox" name="used" {{ $merge->is_used == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="used">Used</label>
                            </div>
                        </td>
                        <td class="check">
                            <form action="{{ route('merge.delete', $merge->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this merge ?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Merge Modbus Device {{ $device->name }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered align-middle table-digital">
                <thead>
                    <tr>
                        <th class="sort" data-sort="digital">Digital Input</th>
                        <th class="sort" data-sort="name">Name</th>
                        <th class="sort" data-sort="yes">Alias (Yes)</th>
                        <th class="sort" data-sort="no">Alias (No)</th>
                        <th class="sort" data-sort="val">Value</th>
                        <th class="sort" data-sort="used">Used</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($device->digitalInputs as $digital)
                    <tr>
                        <td class="digital text-center">
                            <b>{{ $digital->digital_input }}</b>
                        </td>
                        <td class="name">
                            <input type="text" name="name" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-name" value="{{ $digital->name }}">
                        </td>
                        <td class="yes">
                            <input type="text" name="yes" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-yes" value="{{ $digital->yes }}">
                        </td>
                        <td class="no">
                            <input type="text" name="no" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-no" value="{{ $digital->no }}">
                        </td>
                        <td class="val">
                            <input type="text" name="val" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-val" value="{{ $digital->val }} ({{ $digital->val == 1 ? $digital->yes : $digital->no }})" disabled>
                        </td>
                        <td class="used">
                            <div class="form-check form-switch">
                                <input class="form-check-input digital-used" data-id="{{ $digital->id }}" type="checkbox" name="used" {{ $digital->is_used == 1 ? 'checked' : '' }}>
                                <label class=" form-check-label" for="used">Used</label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFHlyaqNuZ7GXj568mmTyNXVXKm5VFCgc&callback=initMap" defer></script>

<script src="{{ asset('/') }}js/device.js"></script>
<script>
    function initMap() {
        let lat = parseFloat("{{ $device->lat }}");
        let long = parseFloat("{{ $device->long }}");

        const map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
        });

        const marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: long
            },
            map,
        });

        let id = "{{ $device->id }}";

        function getRequest() {
            $.ajax({
                url: '/api/devices/' + id,
                type: 'GET',
                success: function(result) {
                    let dataMarker = [marker];
                    getData(result.device, result.image, result.modbus, result.digital, result.history, map, dataMarker, result.merge)
                }
            })
        }

        getRequest()

        setInterval(function() {
            getRequest()
        }, 30000)

        function getData(device, image, modbus, digital, history, dataMap, dataMarker, merge) {
            let infoFirst = `<h6>` + device.name + `</h6>
                                <table>
                                    <tr>
                                        <td>End User</td>
                                        <td> : </td>
                                        <td>` + device.end_user + `</td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td> : </td>
                                        <td>` + device.type + `</td>
                                    </tr>
                                    <tr>
                                        <td>Power</td>
                                        <td> : </td>
                                        <td>` + device.power + `</td>
                                    </tr>
                                    <tr>
                                        <td>Head</td>
                                        <td> : </td>
                                        <td>` + device.head + `</td>
                                    </tr>
                                    <tr>
                                        <td>Flow</td>
                                        <td> : </td>
                                        <td>` + device.flow + `</td>
                                    </tr>
                                    <tr>
                                        <td>Last Data Send</td>
                                        <td> : </td>
                                        <td>` + history + `</td>
                                    </tr>
                                </table>`;
            $("#info").empty().append(infoFirst)


            let imgFirst = `<img src="` + image + `" alt="" width="100px">`;

            $("#image").empty().append(imgFirst)

            let upFirst = ``;

            if (modbus.length > 0) {
                upFirst = `<h6>` + device.modbus + `</h6>
                                        <table>`
                $.each(modbus, function(i, data) {
                    if (data.merge_id == 0) {
                        upFirst += `<tr>
                                            <td>` + data.name + `</td>
                                            <td> : </td>`;
                        if (data.after == null) {
                            upFirst += `<td>` + Number(Number(data.val).toFixed(3)) + ' ' + data.satuan + `</td>`;
                        } else {
                            upFirst += `<td>` + Number(Number(data.after).toFixed(3)) + ' ' + data.satuan + `</td>`;
                        }
                    }
                    upFirst += `</tr>`;
                })

                if (merge.length > 0) {
                    $.each(merge, function(i, data) {
                        upFirst += `<tr>
                                    <td>` + data.name + `</td>
                                    <td> : </td>`;
                        if (data.after == null) {
                            upFirst += `<td>` + Number(Number(data.val).toFixed(3)) + data.unit + `</td>`;
                        } else {
                            upFirst += `<td>` + Number(Number(data.after).toFixed(3)) + data.unit + `</td>`;
                        }
                    })
                }

                upFirst += `        </tr>
                                </table>`;

                $("#modbus").empty().append(upFirst)
            } else {
                upFirst = ``;
            }

            let downFirst = ``;

            if (digital.length > 0) {
                downFirst = `<h6>` + device.digital + `</h6>
                                    <table>`
                $.each(digital, function(i, data) {
                    downFirst += `<tr>
                                    <td>` + data.name + `</td>
                                    <td> : </td>`;
                    if (data.val == 1) {
                        downFirst += `<td>` + data.yes + `</td>`
                    } else {
                        downFirst += `<td>` + data.no + `</td>`
                    }
                    downFirst += `</tr>`
                })
                downFirst += `</table>`;

                $("#digital").empty().append(downFirst)

            } else {
                downFirst = ``;
            }
        }
    }

    window.initMap = initMap;
</script>
<script>

</script>
@endpush