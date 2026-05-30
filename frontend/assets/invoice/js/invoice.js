$(document).ready(function() {
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

    function calculateTotals() {
		var business_vat_el = $('td#business_vat');
		var line_total = vat = 0;
		var $line_amount_el = $('input[id*="line_amount"]');
		var discount_el = $('input[id*="invoice-discount"]');
		var amount_el = $('input[id*="invoice-amount"]');
		var subtotal_el = $('input[id*="invoice-subtotal"]');
        //var client_vat_el = $('input[id*="invoice-client_vat"]');
		var invoice_vat_el =$('input[id*="invoice-vat"]');
		var total_el = $('input[id*="invoice-total"]');
		var discount = Math.round(Number(discount_el.val()) * 100) / 100;
		$.each($line_amount_el, function(index, element) {
            line_total += Math.round(Number($(element).val()) * 100) / 100;
		});
		amount_el.val(Number(line_total));
		subtotal_el.val(Number(line_total - discount));

        if (business_vat_el.text() !== '') {
			vat = Math.round((Number((subtotal_el.val()) * Number(0.14)) * 100)) / 100;
			invoice_vat_el.val(vat);
			total_el.val(Math.round((Number(subtotal_el.val()) + Number(invoice_vat_el.val())) * 100) / 100);
		} else {
			invoice_vat_el.val(Math.round(Number(0.00 * 100)) / 100);
			total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
		}	
	}

    items_hook.on('keypress change', 'input[id*="unit_price"]', function () {
		var price = Number($(this).val());
		var qty = Number($(this).closest('td').siblings().children('div').find('input[id*="line_qty"]').val());
		var amount = Number(qty * price);
		var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
		amnt_el.val(Number(amount));
		
		calculateTotals();
	});

    items_hook.on('keypress change', 'input[id*="line_qty"]', function () {
		var qty = Number($(this).val());
		var price = Number($(this).closest('td').siblings().children('div').find('input[id*="unit_price"]').val());
		var amount = Number(qty * price);
		var amnt_el = $(this).closest('td').siblings().children('div').find('input[id*="line_amount"]');
		amnt_el.val(Number(amount));
		
		calculateTotals();
	});

    body_hook.on('keypress change', 'input[id*="invoice-discount"]', function() {
		calculateTotals();
	});

    body_hook.on("change", 'select#customer-select', function(event) {
		
		$.ajax({
			url: '/business/invoice/customer-data',
		    type: 'post',
		    dataType: 'json',
		    cache: false,
		    data: {id: $(this).val()},
		    success: function (response) {
		    	var data = response.body;
		    	if(data === null) {
		    		$('#invoice-alt_business_name').val('').attr('readonly', false);
		    		$('#invoice-client_id').val('').attr('readonly', false);
						$('#invoice-client_mobile').val('').attr('readonly', false);
						$('#invoice-client_email').val('').attr('readonly', false);
						$('#invoice-client_vat').val('').attr('readonly', false);
		    	} else {
		    		var cust_name = '';
                    if (data['is_business'] === '1') {
		    			cust_name = data['trading_name'];
		    		} else { 
		    			cust_name = (data['first_name'] + ' ' + data['last_name']);
		    		}
		    		$('#invoice-alt_business_name').val(cust_name).attr('readonly', true);

                    if (data['is_business'] === '1') {
		    			$('#invoice-client_id').val(data['registration_number']).attr('readonly', true);
                    } else if (data['is_business'] === '0') {
                        if (data['id_number'] !== '') {
		    				$('#invoice-client_id').val(data['id_number']).attr('readonly', true);
		    			} else {
		    				$('#invoice-client_id').val('').attr('readonly', false);
		    			}
		    		} else {
		    			$('#invoice-client_id').val('').attr('readonly', false);
		    		}

                    if (data['mobile'] !== '') {
		    			$('#invoice-client_mobile').val(data['mobile']).attr('readonly', true);
                    } else if (data['phone_number'] !== '') {
		    			$('#invoice-client_mobile').val(data['phone_number']).attr('readonly', true);
		    		} else {
		    			$('#invoice-client_mobile').val('').attr('readonly', false);
		    		}
		    		$('#invoice-client_email').val(data['email']).attr('readonly', true);

                    if (data['vat_reg_number'] !== '') {
		    			$('#invoice-client_vat').val(data['vat_reg_number']).attr('readonly', true);
		    		} else {
		    			$('#invoice-client_vat').val('').attr('readonly', false);
		    		}
		    	}
		    },
		    error: function() {
		    	alert('System error: customer information could not be retreived');
		    }
		});

		calculateTotals();
	});
});