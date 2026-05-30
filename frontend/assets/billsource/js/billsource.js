function isDomElem(obj) {
  if (obj instanceof HTMLCollection && obj.length) {
    for (let a = 0, len = obj.length; a < len; a++) {
      if (!checkInstance(obj[a])) {
        console.log(a);
        return false;
      }
    }
    return true;
  }
  return checkInstance(obj);


  function checkInstance(elem) {
    return (elem instanceof jQuery && elem.length) || (elem instanceof HTMLElement);
  }
}

function getEmployeeData(ev) {
  const data = {
    id: $(ev.target).val(),
  };

  $.ajax({
    url: 'employee-info',
    type: 'POST',
    dataType: 'json',
    cache: false,
    data,
    error() {
      alert('System error: Employee information could not be retrieved');
    },
    success(response) {
      const data = response.body;
      if (data === null) {
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
    },
  });
}

function getProductData(param) {
  let product_id;
  let elementPrice;
  let elementDescription;

  if (!isDomElem(param)) {
    elementDescription = $(param.target);
  } else {
    elementDescription = $(param);
  }

  product_id = elementDescription.val();
  elementPrice = elementDescription.parent().parent().parent().siblings()
    .find('div input[id$="line_unit_price"]');

  $.ajax({
    url: '/business/invoice/product-data',
    type: 'post',
    dataType: 'json',
    cache: false,
    data: { id: product_id },
    success(response) {
      const { data } = response;
      if (data === null) {
        console.log('Server error encountered! Could not retrieve product information');
      } else {
        elementPrice.val(data.selling_price);
        elementPrice.trigger('change');
      }
    },
    error() {
      console.log('System error: product information could not be retreived');
    },
  });
}

function getPayslipData(param) {
  let payslip_name;
  let elementPrice;
  let elementDescription;

  if (!isDomElem(param)) {
    elementDescription = $(param.target);
  } else {
    elementDescription = $(param);
  }

  payslip_name = elementDescription.val();
  elementPrice = elementDescription.parent().parent().parent().siblings()
    .find('div input[id$="line_unit_price"]');

  $.ajax({
    url: '/business/payslip/product-data',
    type: 'post',
    dataType: 'json',
    cache: false,
    data: { name: payslip_name },
    success(response) {
      const { data } = response;
      if (data === null) {
        console.log('Server error encountered! Could not retrieve payslip information');
      } else {
        elementPrice.val(data.selling_price);
        elementPrice.trigger('change');
      }
    },
    error() {
      console.log('System error: payslip information could not be retreived');
    },
  });
}

function deleteItem(button) {
  button.closest('tr').detach();
  calculateTotals();
}

function isEmpty(el) {
  return !$.trim(el.html());
}

function deleteQuoteItem(button) {
  button.closest('tr').detach();
  calculateQuoteTotals();
}

function deletePayslipItem(button) {
  button.closest('tr').detach();
  calculatePayslipTotals();
}

function calculateTotals() {
  const business_vat_el = $('td#business_vat');
  let line_total = 0; let
    vat = 0;
  const $line_amount_el = $('input[id*="line_amount"]');
  const discount_el = $('input[id*="invoice-discount"]');
  const amount_el = $('input[id*="invoice-amount"]');
  const subtotal_el = $('input[id*="invoice-subtotal"]');
  //var client_vat_el = $('input[id*="invoice-client_vat"]');
  const invoice_vat_el = $('input[id*="invoice-vat"]');
  const total_el = $('input[id*="invoice-total"]');
  const discount = Math.round(Number(discount_el.val()) * 100) / 100;
  $.each($line_amount_el, (index, element) => {
    line_total += Math.round(Number($(element).val() * 100)) / 100;
  });
  amount_el.val(Number(line_total));
  subtotal_el.val(Number(line_total - discount));

  if (business_vat_el.text() !== '') {
    vat = Math.round((Number((subtotal_el.val()) * Number(tax_rate)) * 100)) / 100;
    invoice_vat_el.val(vat);
    total_el.val(Math.round((Number(subtotal_el.val()) + Number(invoice_vat_el.val())) * 100) / 100);
  } else {
    invoice_vat_el.val(Math.round(Number(0.00)) / 100);
    total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
  }
}

function calculateQuoteTotals() {
  const business_vat_el = $('td#business_vat');
  let line_total = 0;
  let vat = 0;
  const $line_amount_el = $('input[id*="line_amount"]');
  const discount_el = $('input[id*="quote-discount"]');
  const amount_el = $('input[id*="quote-amount"]');
  const subtotal_el = $('input[id*="quote-subtotal"]');
  // var client_vat_el = $('input[id*="quote-client_vat"]');
  const quote_vat_el = $('input[id*="quote-vat"]');
  const total_el = $('input[id*="quote-total"]');
  const discount = Math.round(Number(discount_el.val()) * 100) / 100;
  $.each($line_amount_el, (index, element) => {
    line_total += Math.round(Number($(element).val()) * 100) / 100;
  });
  amount_el.val(Number(line_total));
  subtotal_el.val(Number(line_total - discount));

  if (business_vat_el.text() !== '') {
    vat = Math.round((Number((subtotal_el.val()) * Number(tax_rate)) * 100)) / 100;
    quote_vat_el.val(vat);
    total_el.val(Math.round((Number(subtotal_el.val()) + Number(quote_vat_el.val())) * 100) / 100);
  } else {
    quote_vat_el.val(Math.round(Number(0.00)) / 100);
    total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
  }
}

function calculatePayslipTotals() {
  const business_vat_el = $('td#business_vat');
  let line_total = 0; let
    vat = 0;
  const $line_amount_el = $('input[id*="line_amount"]');
  const discount_el = $('input[id*="payslip-discount"]');
  const amount_el = $('input[id*="payslip-amount"]');
  const subtotal_el = $('input[id*="payslip-subtotal"]');
  // var client_vat_el = $('input[id*="payslip-client_vat"]');
  const payslip_vat_el = $('input[id*="payslip-vat"]');
  const total_el = $('input[id*="payslip-total"]');
  const discount = Math.round(Number(discount_el.val()) * 100) / 100;
  $.each($line_amount_el, (index, element) => {
    line_total += Math.round(Number($(element).val()) * 100) / 100;
  });
  amount_el.val(Number(line_total));
  subtotal_el.val(Number(line_total) - Number(discount));

  if (business_vat_el.text() !== '') {
    vat = Math.round((Number((subtotal_el.val()) * Number(tax_rate)) * 100)) / 100;
    payslip_vat_el.val(vat);
    total_el.val(Math.round((Number(subtotal_el.val()) + Number(payslip_vat_el.val())) * 100) / 100);
  } else {
    payslip_vat_el.val(Math.round(Number(0.00)) / 100);
    total_el.val(Math.round(Number(subtotal_el.val()) * 100) / 100);
  }
}

$(document).ready(() => {
  const body = $('body');
  $('#billers-select').on('change', function () {
    $.ajax({
      type: 'post',
      url: '/business/biller/switch-user',
      cache: false,
      data: { user_id: $(this).val() },
      error(xhr, desc, err) {
        console.log(xhr);
        console.log(`Details: ${desc}\nError:${err}`);
      },
    });
  });

  // Select the submit buttons of forms with data-confirm attribute
  const accept_buttons = $('table button[data-confirm]');

  accept_buttons.on('click', function (e) {
    const button = $(this);
    const msg = button.data('confirm');

    // eslint-disable-next-line no-restricted-globals,no-alert
    if (confirm(msg)) {
      $.ajax({
        type: 'post',
        url: '/business/quote/accept-quote',
        cache: false,
        data: { id: button.val() },
        success(response) {
          if (response.success !== true) {
            console.log('Server error encountered while accepting quote!');
          }

          // eslint-disable-next-line no-restricted-globals
          location.reload();
        },
        error(xhr, desc, err) {
          console.error(xhr);
          console.error(`Details: ${desc}\nError:${err}`);
        },
      });
    }
    return false;
  });

  function initProfile() {
    const profile_id = $('#businessclient-profile_id').val();
    if (profile_id === '3') {
      $('#do-details').hide();
    }
  }

  function profileChanged(e) {
    const id = $(e.target).val();
    const doDotails = $('#do-details');
    if (id !== '3') {
      doDotails.hide();
      doDotails.show();
    } else {
      initProfile();
    }

     $.ajax({
      type: 'get',
      url: '/business/profile/profile-data',
      cache: false,
      data: { id: id },
      success(response) {
        $("#planprice").html(response.fee);
        $("#planname").html(response.description);
        $("#profileid").val(response.id);
      },
      error(xhr, desc, err) {
        console.error(xhr);
        console.error(`Details: ${desc}\nError:${err}`);
      },
    });

  }

  body.on('change', '#businessclient-country_id', function (e) {
    $.ajax({
      type: 'get',
      url: '/business/marketplace/business-structure',
      cache: false,
      data: { id: $(this).val() },
      success(response) {
        const select = $('#businessclient-structure_id');
        let option = '<option value="">Select business structure</option>';

        if (response.body !== '') {
          const structure = response.body;
          for (let key = structure.length - 1; key >= 0; key--) {
            const value = structure[key];
            option += `<option value="${value.id}">${value.name}</option>`;
          }
        }
        select.find('option')
          .remove()
          .end()
          .append(option);
      },
      error(xhr, desc, err) {
        console.error(xhr);
        console.error(`Details: ${desc}\nError:${err}`);
      },
    });
  });

  body.on('change', '#businessclient-sector_id', function (e) {
    $.ajax({
      type: 'get',
      url: '/business/marketplace/business-category',
      cache: false,
      data: { id: $(this).val() },
      success(response) {
        const select = $('#productservice-category_id');
        let option = '<option value="">Select product / service</option>';

        if (response.body !== '') {
          const structure = response.body;
          for (let key = structure.length - 1; key >= 0; key--) {
            const value = structure[key];
            option += `<option value="${value.id}">${value.name}</option>`;
          }
        }
        select.find('option')
          .remove()
          .end()
          .append(option);
      },
      error(xhr, desc, err) {
        console.error(xhr);
        console.error(`Details: ${desc}\nError:${err}`);
      },
    });
  });

  body.on('change', '#individualclient-sector_id', function (e) {
    $.ajax({
      type: 'get',
      url: '/business/marketplace/business-category',
      cache: false,
      data: { id: $(this).val() },
      success(response) {
        const select = $('#productservice-category_id');
        let option = '<option value="">Select product / service</option>';

        if (response.body !== '') {
          const structure = response.body;
          for (let key = structure.length - 1; key >= 0; key--) {
            const value = structure[key];
            option += `<option value="${value.id}">${value.name}</option>`;
          }
        }
        select.find('option')
          .remove()
          .end()
          .append(option);
      },
      error(xhr, desc, err) {
        console.error(xhr);
        console.error(`Details: ${desc}\nError:${err}`);
      },
    });
  });

  body.on('change', '#productservice-category_id', function (e) {
    console.log($(this).val());
    $.ajax({
      type: 'get',
      url: '/business/marketplace/business-category/sub-category',
      cache: false,
      data: { id: $(this).val() },
      success(response) {
        const select = $('#subproductservice-sub_category_id');
        let option = '<option value="">Select sub product / service</option>';

        if (response.body !== '') {
          const structure = response.body;
          for (let key = structure.length - 1; key >= 0; key--) {
            const value = structure[key];
            option += `<option value="${value.id}">${value.name}</option>`;
          }
        }
        select.find('option')
          .remove()
          .end()
          .append(option);
      },
      error(xhr, desc, err) {
        console.error(xhr);
        console.error(`Details: ${desc}\nError:${err}`);
      },
    });
  });

  body.on('change', '#businessclient-profile_id', profileChanged);

  const crm_el = $('#businessclientcrm-is_business');
  if (crm_el.val() === '1') {
    $('a[href*="bio-data"]').parent().hide();
    $('a[href*="business-details"]').parent().hide();
    $('a[href*="bio-data"]').parent().show();
    $('a[href*="business-details"]').parent().show();
  } else {
    $('a[href*="bio-data"]').parent().hide();
    $('a[href*="business-details"]').parent().hide();
    $('a[href*="bio-data"]').parent().show();
  }
  let detachedHeader;
  let detachedContent;

  body.on('change', '#businessclientcrm-is_business', function (e) {
    if ($(this).val() === '1') {
      $('a[href*="bio-data"]').parent().hide();
      $('a[href*="bio-data"]').parent().show();
      if (detachedHeader && detachedContent) {
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
    const form = $(this);
    // return false if form still have some validation errors
    if (form.find('.has-error').length) {
		  return false;
    }
    // submit form
    $.ajax({
		  url: form.attr('action'),
		  type: 'post',
		  data: form.serialize(),
		  success(response) {
		  	console.log(`Success: ${response}`);
		  },
		  error(response) {
		  	console.log(`Error: ${response}`);
		  },
    });
    return false;
  });

	function handleStatusChange(val){
		console.log(val);
	 }

});
