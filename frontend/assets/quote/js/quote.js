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

        calculateQuoteTotals();
    });

    items_hook.on('keypress change', 'input[id*="line_qty"]', function () {
        var qty = Number($(this).val());
        var price = Number($(this).closest('td').siblings().children('div').find('input[id*="unit_price"]').val());
        var amount = Number(qty * price);
        var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
        amnt_el.val(Number(amount));

        calculateQuoteTotals();
    });

    body_hook.on('keypress change', 'input[id*="quote-discount"]', function () {
        calculateQuoteTotals();
    });

    body_hook.on("change", 'select#customer-select', function (event) {

        $.ajax({
            url: '/business/quote/customer-data',
            type: 'post',
            dataType: 'json',
            data: {id: $(this).val()},
            success: function (response) {
                var data = response.body;
                if (data === null) {
                    $('#quote-alt_business_name').val('').attr('readonly', false);
                    $('#quote-client_id').val('').attr('readonly', false);
                    $('#quote-client_mobile').val('').attr('readonly', false);
                    $('#quote-client_email').val('').attr('readonly', false);
                    $('#quote-client_vat').val('').attr('readonly', false);
                } else {
                    var cust_name = '';
                    if (data['is_business'] === '1') {
                        cust_name = data['trading_name'];
                    } else {
                        cust_name = (data['first_name'] + ' ' + data['last_name']);
                    }
                    $('#quote-alt_business_name').val(cust_name).attr('readonly', true);

                    if (data['is_business'] === '1') {
                        $('#quote-client_id').val(data['registration_number']).attr('readonly', true);
                    } else if (data['is_business'] === '0') {
                        if (data['id_number'] !== '') {
                            $('#quote-client_id').val(data['id_number']).attr('readonly', true);
                        } else {
                            $('#quote-client_id').val('').attr('readonly', false);
                        }
                    } else {
                        $('#quote-client_id').val('').attr('readonly', false);
                    }

                    if (data['mobile'] !== '') {
                        $('#quote-client_mobile').val(data['mobile']).attr('readonly', true);
                    } else if (data['phone_number'] !== '') {
                        $('#quote-client_mobile').val(data['phone_number']).attr('readonly', true);
                    } else {
                        $('#quote-client_mobile').val('').attr('readonly', false);
                    }
                    $('#quote-client_email').val(data['email']).attr('readonly', true);

                    if (data['vat_reg_number'] !== '') {
                        $('#quote-client_vat').val(data['vat_reg_number']).attr('readonly', true);
                    } else {
                        $('#quote-client_vat').val('').attr('readonly', false);
                    }
                }
            },
            error: function () {
                console.log('System error: customer details could not be retreived');
            }
        });

        calculateQuoteTotals();
    });
});