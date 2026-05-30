function isDomElem(obj) {
    if(obj instanceof HTMLCollection && obj.length) {
        for(var a = 0, len = obj.length; a < len; a++) {
            if(!checkInstance(obj[a])) {
                console.log(a);
                return false;
            }
        }
        return true;
    } else {
        return checkInstance(obj);
    }

    function checkInstance(elem) {
        if((elem instanceof jQuery && elem.length) || elem instanceof HTMLElement) {
            return true;
        }
        return false;
    }
}

function getEmployeeData(ev) {
	var data = {
		id: $(ev.target).val()
	};

	$.ajax({
		url: 'employee-info',
		type: 'POST',
		dataType: 'json',
		cache: false,
		data: data,
		error: function() {
			alert('System error: Employee information could not be retrieved');
		},
		success: function(response){
			var data = response.body;
			if(data === null) {
				$('#payroll-emp_id').val('');
				$('#payroll-emp_id_number').val('').attr('readonly', false);
				$('#payroll-emp_email').val('').attr('readonly', false);
				$('#payroll-emp_mobile').val('').attr('readonly', false);
			} else {
				$('#payroll-emp_id').val(data.emp_id);
				$('#payroll-emp_id_number').val(data.id_number).attr('readonly', true);
				$('#payroll-emp_email').val(data.email).attr('readonly', true);
				$('#payroll-emp_mobile').val(data.mobile).attr('readonly', true);
			}
		}
	});
}

function getProductData(param) {
    var product_name;
    var elementPrice;
    var elementDescription;

    if(!isDomElem(param)) {
        elementDescription = $(param.target);
    } else {
        elementDescription = $(param);
    }

    product_name = elementDescription.val();
    elementPrice = elementDescription.parent().parent().parent().siblings().find("div input[id$='line_unit_price']");

	$.ajax({
        url: '/business/invoice/product-data',
        type: 'post',
        dataType: 'json',
        cache: false,
        data: {name: product_name},
        success: function (response) {
            var data = response.data;
            if(data === null) {
                console.log('Server error encountered! Could not retrieve product information');
            } else {
                elementPrice.val(data.selling_price);
                elementPrice.trigger('change');
            }
        },
        error: function() {
            console.log('System error: product information could not be retreived');
        }
    });
}

function getPayslipData(param) {
    var payslip_name;
    var elementPrice;
    var elementDescription;

    if(!isDomElem(param)) {
        elementDescription = $(param.target);
    } else {
        elementDescription = $(param);
    }

    payslip_name = elementDescription.val();
    elementPrice = elementDescription.parent().parent().parent().siblings().find("div input[id$='line_unit_price']");

    $.ajax({
        url: '/business/payslip/product-data',
        type: 'post',
        dataType: 'json',
        cache: false,
        data: {name: payslip_name},
        success: function (response) {
            var data = response.data;
            if(data === null) {
                console.log('Server error encountered! Could not retrieve payslip information');
            } else {
                elementPrice.val(data.selling_price);
                elementPrice.trigger('change');
            }
        },
        error: function() {
            console.log('System error: payslip information could not be retreived');
        }
    });
}

function deleteItem(button) {
    button.closest('tr').detach();
    calculateTotals();
}

function isEmpty( el ){
    return !$.trim(el.html())
}

function deleteQuoteItem(button) {
    button.closest('tr').detach();
    calculateQuoteTotals();
}

function deletePayslipItem(button) {
    button.closest('tr').detach();
    calculatePayslipTotals();
}

function calculateTotals()
{
    var business_vat_el = $('td#business_vat');
    var line_total = vat = 0;
    var $line_amount_el = $('input[id*="line_amount"]');
    var discount_el = $('input[id*="invoice-discount"]');
    var amount_el = $('input[id*="invoice-amount"]');
    var subtotal_el = $('input[id*="invoice-subtotal"]');
    var client_vat_el = $('input[id*="invoice-client_vat"]');
    var invoice_vat_el =$('input[id*="invoice-vat"]');
    var total_el = $('input[id*="invoice-total"]');
    var discount = Math.round(Number(discount_el.val()) * 100) / 100;
    $.each($line_amount_el, function(index, element) {
        line_total += Math.round(Number($(element).val() * 100)) / 100;
    });
    amount_el.val(Number(line_total));
    subtotal_el.val(Number(line_total - discount));

    if(business_vat_el.text() !== '') {
        vat = Math.round((Number((subtotal_el.val()) * Number(0.14)) * 100)) / 100;
        invoice_vat_el.val(vat);
        total_el.val(Math.round((Number(subtotal_el.val()) + Number(invoice_vat_el.val())) * 100) / 100);
    } else {
        invoice_vat_el.val(Math.round(Number(0.00 * 100)) / 100);
        total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
    }
}

function calculateQuoteTotals() {
    var business_vat_el = $('td#business_vat');
    var line_total = vat = 0;
    var $line_amount_el = $('input[id*="line_amount"]');
    var discount_el = $('input[id*="quote-discount"]');
    var amount_el = $('input[id*="quote-amount"]');
    var subtotal_el = $('input[id*="quote-subtotal"]');
    //var client_vat_el = $('input[id*="quote-client_vat"]');
    var quote_vat_el = $('input[id*="quote-vat"]');
    var total_el = $('input[id*="quote-total"]');
    var discount = Math.round(Number(discount_el.val()) * 100) / 100;
    $.each($line_amount_el, function (index, element) {
        line_total += Math.round(Number($(element).val()) * 100) / 100;
    });
    amount_el.val(Number(line_total));
    subtotal_el.val(Number(line_total - discount));

    if (business_vat_el.text() !== '') {
        vat = Math.round((Number((subtotal_el.val()) * Number(0.14)) * 100)) / 100;
        quote_vat_el.val(vat);
        total_el.val(Math.round((Number(subtotal_el.val()) + Number(quote_vat_el.val())) * 100) / 100);
    } else {
        quote_vat_el.val(Math.round(Number(0.00 * 100)) / 100);
        total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
    }
}

function calculatePayslipTotals() {
    var business_vat_el = $('td#business_vat');
    var line_total = vat = 0;
    var $line_amount_el = $('input[id*="line_amount"]');
    var discount_el = $('input[id*="payslip-discount"]');
    var amount_el = $('input[id*="payslip-amount"]');
    var subtotal_el = $('input[id*="payslip-subtotal"]');
    //var client_vat_el = $('input[id*="payslip-client_vat"]');
    var payslip_vat_el = $('input[id*="payslip-vat"]');
    var total_el = $('input[id*="payslip-total"]');
    var discount = Math.round(Number(discount_el.val()) * 100) / 100;
    $.each($line_amount_el, function (index, element) {
        line_total += Math.round(Number($(element).val()) * 100) / 100;
    });
    amount_el.val(Number(line_total));
    subtotal_el.val(Number(line_total) - Number(discount));

    if (business_vat_el.text() !== '') {
        vat = Math.round((Number((subtotal_el.val()) * Number(0.14)) * 100)) / 100;
        payslip_vat_el.val(vat);
        total_el.val(Math.round((Number(subtotal_el.val()) + Number(payslip_vat_el.val())) * 100) / 100);
    } else {
        payslip_vat_el.val(Math.round(Number(0.00 * 100)) / 100);
        total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
    }
}

$(document).ready(function() {
    var body = $('body');
    $('#billers-select').on('change', function() {
        $.ajax({
            type: 'post',
            url: '/business/biller/switch-user',
            cache: false,
            data: {user_id: $(this).val()},
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });

    // Select the submit buttons of forms with data-confirm attribute
    var accept_buttons = $("table button[data-confirm]");

    // On click of one of these submit buttons
    accept_buttons.on('click', function (e) {
        var button = $(this); // Get the button
        var msg = button.data('confirm'); // Get the confirm message
        //console.log(button, msg);
        if(confirm(msg)) {
            $.ajax({
                type: 'post',
                url: '/business/quote/accept-quote',
                cache: false,
                data: {id: button.val()},
                success: function (response) {
                    if(!response.success) {
                        console.log('Server error encountered!');
                    }

                    location.reload();
                },
                error: function(xhr, desc, err) {
                    console.error(xhr);
                    console.error("Details: " + desc + "\nError:" + err);
                }
            });
        }
        return false;
    });

	function initProfile() {
		var profile_id = $("#businessclient-profile_id").val();
		if(profile_id === '3') {
			$("#do-details").hide();
		}
	}
	
	function profileChanged(e) {
		var id = $(e.target).val();
		var doDotails = $("#do-details");
		if(id !== '3') {
			doDotails.hide();
			doDotails.show();
		} else {
			initProfile();
		}
	}
	
	body.on("change", "#businessclient-profile_id", profileChanged);
	
	var crm_el = $('#businessclientcrm-is_business');
	if(crm_el.val() === '1') {
		$('a[href*="bio-data"]').parent().hide();
		$('a[href*="business-details"]').parent().hide();
		$('a[href*="bio-data"]').parent().show();
		$('a[href*="business-details"]').parent().show();
	} else {
		$('a[href*="bio-data"]').parent().hide();
		$('a[href*="business-details"]').parent().hide();
		$('a[href*="bio-data"]').parent().show();
	}
	var detachedHeader;
	var detachedContent;

	body.on('change', '#businessclientcrm-is_business', function(e) {
		if($(this).val() === '1') {
			$('a[href*="bio-data"]').parent().hide();
			$('a[href*="bio-data"]').parent().show();
			if(detachedHeader && detachedContent) {
				$('#nav-tabs').append(detachedHeader);
				$('div.tab-content').append(detachedContent);
			}
		} else {
			$('a[href*="bio-data"]').parent().hide();
			detachedHeader = $('a[href*="business-details"]').parent().detach();
			detachedContent = $('#business-details').detach();
			$('a[href*="bio-data"]').parent().show();
		}
	});
	
	initProfile();

	body.on('beforeSubmit', 'form#desktop', function () {
		var form = $(this);
		// return false if form still have some validation errors
		if (form.find('.has-error').length) {
		  return false;
		}
		// submit form
		$.ajax({
		  url: form.attr('action'),
		  type: 'post',
		  data: form.serialize(),
		  success: function (response) {
		  	console.log('Success: ' + response);
		  },
		  error: function(response) {
		  	console.log('Error: ' + response);
		  }
		});
		return false;
	});
});