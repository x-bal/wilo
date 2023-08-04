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
        <!-- <div id="map" style="width: 100%; height: 480px; z-index: 0;"></div> -->
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
        <div class="row">
            <div class="col-md-5">
                <table>
                    <tr>
                        <th>User</th>
                        <td> : </td>
                        <td>{{ $device->company->name }}</td>
                    </tr>
                    <tr>
                        <th>Site Location</th>
                        <td> : </td>
                        <td>{{ $device->company->address }}</td>
                    </tr>
                    <tr>
                        <th>Pump Service</th>
                        <td> : </td>
                        <td>{{ $device->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5">
                <table>
                    <tr>
                        <th>Pump Type</th>
                        <td> : </td>
                        <td>{{ $device->type }}</td>
                    </tr>
                    <tr>
                        <th>Power</th>
                        <td> : </td>
                        <td>{{ $device->power }}</td>
                    </tr>
                    <tr>
                        <th>Head</th>
                        <td> : </td>
                        <td>{{ $device->head }}</td>
                    </tr>
                    <tr>
                        <th>Flow</th>
                        <td> : </td>
                        <td>{{ $device->flow }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-2">
                <a href="" class="btn btn-sm btn-success">Export Data</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <img src="{{ asset('/storage/' . $device->image) }}" alt="" class="img-fluid" width="150">

                <table>
                    <tr>
                        <th>Pump Status</th>
                        <td> : </td>
                        <td>{{ $device->is_active == 1 ? "On" : "Off" }}</td>
                    </tr>
                    <tr>
                        <th>Fault Status</th>
                        <td> : </td>
                        <td>Normal</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-9">
                <div class="row my-3">
                    <h4 class="text-center my-3">Trend Grafik</h4>

                    <div class="col-md-6 mb-3 chart-1">
                        <canvas id="chart-1" width="100%"></canvas>
                    </div>
                    <div class="col-md-6 mb-3 chart-2">
                        <canvas id="chart-2" width="100%"></canvas>
                    </div>
                    <div class="col-md-6 mb-3 chart-3">
                        <canvas id="chart-3" width="100%"></canvas>
                    </div>
                    <div class="col-md-6 mb-3 chart-4">
                        <canvas id="chart-4" width="100%"></canvas>
                    </div>
                    <div class="col-md-6 mb-3 chart-5">
                        <canvas id="chart-5" width="100%"></canvas>
                    </div>
                    <div class="col-md-6 mb-3 chart-6">
                        <canvas id="chart-6" width="100%"></canvas>
                    </div>
                </div>
            </div>
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
<script src="{{ asset('/') }}plugins/chart.js/dist/chart.min.js"></script>

<script src="{{ asset('/') }}js/device.js"></script>
<!-- <script>
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
</script> -->

<script>
    let id = "{{ $device->id }}"
    let labelsOne = [];
    let labelsTwo = [];
    let labelsThree = [];
    let labelsFour = [];
    let labelsFive = [];
    let labelsSix = [];
    let datasetOne = [];
    let datasetTwo = [];
    let datasetThree = [];
    let datasetFour = [];
    let datasetFive = [];
    let datasetSix = [];

    function parseTime(dateTime) {
        let timestamp = new Date(dateTime);
        let date = timestamp.getDate();
        let month = timestamp.getMonth();
        let year = timestamp.getFullYear();
        let hours = timestamp.getHours();
        let minute = timestamp.getMinutes();
        let second = timestamp.getSeconds();

        return date + '/' + (month + 1) + '/' + year + ' ' + hours + ':' + minute + ':' + second
    }

    function getChart() {
        $.ajax({
            url: '/api/get-history-modbus/' + id,
            type: 'GET',
            success: function(response) {
                console.log(response)
                let active = response.active;
                let history = response.history;
                let digital = response.digital;

                labelsOne = [];
                labelsTwo = [];
                labelsThree = [];
                labelsFour = [];
                labelsFive = [];
                labelsSix = [];
                datasetOne = [];
                datasetTwo = [];
                datasetThree = [];
                datasetFour = [];
                datasetFive = [];
                datasetSix = [];


                if (active[0]) {
                    $.each(active[0].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[0].name + ' (' + active[0].satuan + ')';

                        labelsOne.push(time)
                        datasetOne.push(data.val)

                        createChart('chart-1', 'chart-1', labelsOne, datasetOne, labelName)
                    });
                }

                if (active[1]) {
                    $.each(active[1].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[1].name + ' (' + active[1].satuan + ')';

                        labelsTwo.push(time)
                        datasetTwo.push(data.val)

                        createChart('chart-2', 'chart-2', labelsTwo, datasetTwo, labelName)
                    })

                }

                if (active[2]) {
                    $.each(active[2].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[2].name + ' (' + active[2].satuan + ')';

                        labelsThree.push(time)
                        datasetThree.push(data.val)

                        createChart('chart-3', 'chart-3', labelsThree, datasetThree, labelName)
                    })

                }

                if (active[3]) {
                    $.each(active[3].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[3].name + ' (' + active[3].satuan + ')';

                        labelsFour.push(time)
                        datasetFour.push(data.val)

                        createChart('chart-4', 'chart-4', labelsFour, datasetFour, labelName)
                    })
                }

                if (active[4]) {
                    $.each(active[4].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[4].name + ' (' + active[4].satuan + ')';

                        labelsFive.push(time)
                        datasetFive.push(data.val)

                        createChart('chart-5', 'chart-5', labelsFive, datasetFive, labelName)
                    })
                }

                if (active[5]) {
                    $.each(active[5].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[5].name + ' (' + active[5].satuan + ')';

                        labelsSix.push(time)
                        datasetSix.push(data.val)

                        createChart('chart-6', 'chart-6', labelsSix, datasetSix, labelName)
                    })
                }
            }
        })
    }

    getChart()

    setInterval(function() {
        getChart()
    }, 30000)

    function createChart(ctxid, ctxclass, labels, dataset, label) {
        $("#" + ctxid).remove();
        const canvas = document.createElement("canvas");
        canvas.setAttribute("id", ctxid);
        canvas.setAttribute('width', '1007');
        canvas.setAttribute('height', '503');
        canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
        $("." + ctxclass).append(canvas)

        let myChart = new Chart($("#" + ctxid), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: dataset,
                    backgroundColor: 'rgb(0, 156, 130)',
                    borderColor: 'rgb(0, 156, 130)',
                }]
            }
        });
    }
</script>
@endpush