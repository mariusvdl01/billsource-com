$(document).ready(function () {
    $('body').on('keypress', 'form', function(e) {
        var key;
        if(window.event)
            key = window.event.keyCode; //IE
        else
            key = e.which; //firefox
        console.log('Enter pressed');
        return (key != 13);
    });

    var body_hook = $('body');
    var items_hook = $('tbody#items');

    items_hook.on('keypress change', 'input[id*="unit_price"]', function () {
        var price = Number($(this).val());
        var qty = Number($(this).closest('td').siblings().children('div').find('input[id*="line_qty"]').val());
        var amount = Number(qty * price);
        var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
        amnt_el.val(Number(amount));

        calculatePayslipTotals();
    });

    items_hook.on('keypress change', 'input[id*="line_qty"]', function () {
        var qty = Number($(this).val());
        var price = Number($(this).closest('td').siblings().children('div').find('input[id*="unit_price"]').val());
        var amount = Number(qty * price);
        var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
        amnt_el.val(Number(amount));

        calculatePayslipTotals();
    });

    body_hook.on('keypress change', 'input[id*="payslip-discount"]', function () {
        calculatePayslipTotals();
    });

    body_hook.on("change", 'select#employee-select', function (event) {

        $.ajax({
            url: '/business/payslip/employee-data',
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {id: $(this).val()},
            success: function (response) {
                var data = response.body;
                if (data === null) {
                    $('#payslip-alt_business_name').val('').attr('readonly', false);
                    $('#payslip-client_id').val('').attr('readonly', false);
                    $('#payslip-client_mobile').val('').attr('readonly', false);
                    $('#payslip-client_email').val('').attr('readonly', false);
                    $('#payslip-client_vat').val('').attr('readonly', false);
                } else {
                    var cust_name = '';
                    cust_name = (data['first_name'] + ' ' + data['last_name']);
                    $('#payslip-alt_business_name').val(cust_name).attr('readonly', true);

                    if (data['id_number'] !== '') {
                        $('#payslip-client_id').val(data['id_number']).attr('readonly', true);
                    } else {
                        $('#payslip-client_id').val('').attr('readonly', false);
                    }

                    if (data['mobile'] !== '') {
                        $('#payslip-client_mobile').val(data['mobile']).attr('readonly', true);
                    } else {
                        $('#payslip-client_mobile').val('').attr('readonly', false);
                    }
                    $('#payslip-client_email').val(data['email']).attr('readonly', true);
                }
            },
            error: function () {
                console.log('System error: customer information could not be retrieved');
            }
        });

        calculatePayslipTotals();
    });

    body_hook.on("change", 'select#task-employee-select', function (event) {

        $.ajax({
            url: '/business/payslip/employee-data',
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {id: $(this).val()},
            success: function (response) {
                var data = response.body;
                if (data === null) {
                    $('#task-alt_business_name').val('').attr('readonly', false);
                    $('#task-client_id').val('').attr('readonly', false);
                    $('#task-client_mobile').val('').attr('readonly', false);
                    $('#task-client_email').val('').attr('readonly', false);
                    $('#task-client_vat').val('').attr('readonly', false);
                } else {
                    var cust_name = '';
                    cust_name = (data['first_name'] + ' ' + data['last_name']);
                    $('#task-alt_business_name').val(cust_name).attr('readonly', true);

                    if (data['id_number'] !== '') {
                        $('#task-client_id').val(data['id_number']).attr('readonly', true);
                    } else {
                        $('#task-client_id').val('').attr('readonly', false);
                    }

                    if (data['mobile'] !== '') {
                        $('#task-client_mobile').val(data['mobile']).attr('readonly', true);
                    } else {
                        $('#task-client_mobile').val('').attr('readonly', false);
                    }
                    $('#task-client_email').val(data['email']).attr('readonly', true);
                }
            },
            error: function () {
                console.log('System error: customer information could not be retrieved');
            }
        });

        calculatePayslipTotals();
    });
});