$(".table").on('change', '.modbus-name', function () {
    let id = $(this).attr('data-id');
    let field = 'name';
    let val = $(this).val();
    let url = '/api/modbus';

    update(id, field, val, url);
})

$(".table").on('change', '.modbus-satuan', function () {
    let id = $(this).attr('data-id');
    let field = 'satuan';
    let val = $(this).val();
    let url = '/api/modbus';

    update(id, field, val, url);
})

$(".table").on('click', '.modbus-showed', function () {
    let id = $(this).attr('data-id');
    let field = 'is_showed';
    let val = 0;
    let url = '/api/modbus';

    if ($(this).is(':checked')) {
        val = 1;
    } else {
        val = 0;
    }

    update(id, field, val, url)
})

$(".table-digital").on('change', '.digital-name', function () {
    let id = $(this).attr('data-id');
    let field = 'name';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('change', '.digital-yes', function () {
    let id = $(this).attr('data-id');
    let field = 'yes';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('change', '.digital-no', function () {
    let id = $(this).attr('data-id');
    let field = 'no';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('click', '.digital-used', function () {
    let id = $(this).attr('data-id');
    let field = 'is_used';
    let val = 0;
    let url = '/api/digital';

    if ($(this).is(':checked')) {
        val = 1;
    } else {
        val = 0;
    }

    update(id, field, val, url)
})

function update(id, field, val, url) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            id: id,
            field: field,
            val: val,
        },
        success: function (response) {
            console.log(response)
            if (response.status == 'success') {
                // iziToast.success({
                //     title: 'Success',
                //     position: 'topRight',
                //     message: response.message,
                // });
            } else {
                // iziToast.error({
                //     title: 'Error',
                //     position: 'topRight',
                //     message: response.message,
                // });
            }
        },
        error: function (response) {
            console.log(response)
            let message = response.responseJSON.message;

            // iziToast.error({
            //     title: 'Error',
            //     position: 'topRight',
            //     message: message,
            // });
        }
    })
}

$(".table").on('change', '.modbus-mark', function () {
    let id = $(this).attr('data-id');
    let mark = $(".mark-" + id).find(":selected").val();
    let val = $("#math-" + id).val()

    if (mark == '&') {
        $("#math-" + id).val('')
        $("#math-" + id).attr('type', 'text')
        $("#math-" + id).attr('placeholder', 'PVmax&PVmin&1000')
        $(".mod-" + id).append('PVmax&PVmin&Devide');
    } else {
        $("#math-" + id).attr('type', 'number')
        $("#math-" + id).attr('placeholder', '')
        $(".mod-" + id).empty();
    }
})

$(".table").on('change', '.modbus-math', function () {
    let id = $(this).attr('data-id');
    let val = parseFloat($("#val-" + id).val());
    let math = parseFloat($(this).val());
    let mark = $(".mark-" + id).find(":selected").val();
    let after = 0;

    if (mark == "x") {
        after = val * math;
    }

    if (mark == ":") {
        after = val / math;
    }

    if (mark == "+") {
        after = val + math;
    }

    if (mark == "-") {
        after = val - math;
    }

    let field = mark + ',' + math;

    if (mark == "&") {
        let before = $(this).val()
        let max = before.split('&')[0]
        let min = before.split('&')[1]
        let devide = before.split('&')[2]

        if (before.split('&').length < 3 || before.split('&').length > 3) {
            alert("Format not matches");
            return false;
        }

        if (val < 4000) {
            field = mark + ',' + $(this).val()
            after = 0;
        } else {
            field = mark + ',' + $(this).val()
            after = (((val / devide) - 4) / 16 * (parseFloat(max) - parseFloat(min))) + parseFloat(min);
        }
    }

    $("#after-" + id).empty().val(after)

    $.ajax({
        url: '/api/math',
        type: 'GET',
        data: {
            id: id,
            after: after,
            math: field
        },
        success: function (response) {
            if (response.status == 'success') {
                // iziToast.success({
                //     title: 'Success',
                //     position: 'topRight',
                //     message: response.message,
                // });
            } else {
                // iziToast.error({
                //     title: 'Error',
                //     position: 'topRight',
                //     message: response.message,
                // });
            }
        }
    })
})

$(".table").on('click', '.device-active', function () {
    let id = $(this).attr('data-id');
    let active = 0;
    let status = '';

    if ($(this).is(':checked')) {
        active = 1;
        status = 'Active';
    } else {
        active = 0;
        status = 'Nonactive'
    }

    $.ajax({
        url: '/api/device/active',
        type: 'GET',
        data: {
            id: id,
            active: active
        },
        success: function (response) {
            if (response.status == 'success') {
                $(".label-" + id).empty().append(status)

                // iziToast.success({
                //     title: 'Success',
                //     position: 'topRight',
                //     message: response.message,
                // });
            } else {
                // iziToast.error({
                //     title: 'Error',
                //     position: 'topRight',
                //     message: response.message,
                // });
            }
        },
        error: function (response) {
            let message = response.responseJSON.message;

            // iziToast.error({
            //     title: 'Error',
            //     position: 'topRight',
            //     message: message,
            // });
        }
    });
})

$('.table').on('click', '.modbus-merge', function () {
    var ischecked = $(this).is(':checked');
    let id = $(this).attr('data-id')
    console.log(id)
    if (ischecked == false) {
        $('#merge-' + id).remove();
    } else {
        $('.form-merge').append('<input type="hidden" name="modbus_id[]" id="merge-' + id + '" value="' + id + '"/>');
    }
})

$(".table-merge").on('change', '.merge-type', function () {
    let id = $(this).attr('data-id');
    let type = $(this).val();

    $.ajax({
        url: '/api/merge/change',
        type: 'GET',
        data: {
            id: id,
            type: type
        },
        success: function (response) {
            console.log(response)
            if (response.status == 'success') {
                $("#merge-val-" + id).empty().append(response.val)

                // iziToast.success({
                //     title: 'Success',
                //     position: 'topRight',
                //     message: response.message,
                // });
            } else {
                // iziToast.error({
                //     title: 'Error',
                //     position: 'topRight',
                //     message: response.message,
                // });
            }
        },
        error: function (response) {
            let message = response.responseJSON.message;

            // iziToast.error({
            //     title: 'Error',
            //     position: 'topRight',
            //     message: message,
            // });
        }
    })
})

$(".table-merge").on('change', '.merge-mark', function () {
    let id = $(this).attr('data-id');
    let mark = $(".mark-merge-" + id).find(":selected").val();
    // console.log(mark)

    if (mark == '&') {
        $("#merge-math-" + id).val('')
        $("#merge-math-" + id).attr('type', 'text')
        $("#merge-math-" + id).attr('placeholder', 'PVmax&PVmin&1000')
    } else {
        $("#merge-math-" + id).attr('type', 'number')
    }
})

$(".table-merge").on('change', '.merge-math', function () {
    let id = $(this).attr('data-id');
    let val = parseFloat($("#merge-val-" + id).text());
    let math = parseFloat($(this).val());
    let mark = $(".mark-merge-" + id).find(":selected").val();
    let after = 0;


    if (mark == "x") {
        after = val * math;
    }

    if (mark == ":") {
        after = val / math;
    }

    if (mark == "+") {
        after = val + math;
    }

    if (mark == "-") {
        after = val - math;
    }

    let field = mark + ',' + math;
    // console.log(after)

    if (mark == "&") {
        let before = $(this).val()
        let max = before.split('&')[0]
        let min = before.split('&')[1]
        let devide = before.split('&')[2]

        if (before.split('&').length < 3 || before.split('&').length > 3) {
            alert("Format not matches");
            return false;
        }

        if (val < 4000) {
            field = mark + ',' + $(this).val()
            after = 0;
        } else {
            field = mark + ',' + $(this).val()
            after = (((val / devide) - 4) / 16 * (parseFloat(max) - parseFloat(min))) + parseFloat(min);
        }

        // if (val >= 20) {
        //     val = 20;
        // }
    }

    $("#merge-after-" + id).empty().val(after)

    $.ajax({
        url: '/api/merge/math',
        type: 'GET',
        data: {
            id: id,
            after: after,
            math: field
        },
        success: function (response) {
            console.log(response)

            if (response.status == 'success') {
                // iziToast.success({
                //     title: 'Success',
                //     position: 'topRight',
                //     message: response.message,
                // });
            } else {
                // iziToast.error({
                //     title: 'Error',
                //     position: 'topRight',
                //     message: response.message,
                // });
            }
        }
    })
})

$(".table-merge").on('change', '.merge-satuan', function () {
    let id = $(this).attr('data-id');
    let field = 'unit';
    let val = $(this).val();
    let url = '/api/merge';

    update(id, field, val, url);
})

$(".table-merge").on('click', '.merge-used', function () {
    let id = $(this).attr('data-id');
    let field = 'is_used';
    let val = 0;
    let url = '/api/merge';

    if ($(this).is(':checked')) {
        val = 1;
    } else {
        val = 0;
    }

    update(id, field, val, url)
})

$(".table").on('change', '.merge-name', function () {
    let id = $(this).attr('data-id');
    let field = 'name';
    let val = $(this).val();
    let url = '/api/merge';

    update(id, field, val, url);
})

$('.access-check').on('click', function () {
    var ischecked = $(this).is(':checked');
    let id = $(this).val();

    console.log(id)
    if (ischecked == false) {
        $('#device-' + id).remove();
    } else {
        $('.form-access').append('<input type="hidden" name="device_id[]" id="device-' + id + '" value="' + id + '"/>');
    }
})