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

	$('body').on("change", 'select#customer-select', function(event) {
		
		$.ajax({
			url: '/business/invoice/customer-data',
		    type: 'post',
		    dataType: 'json',
		    cache: false,
		    data: {id: $(this).val()},
		    success: function (response) {
		    	var data = response.body;
		    	if(data === null) {
		    		$('#ticket-alt_business_name').val('').attr('readonly', false);
		    		$('#ticket-client_id').val('').attr('readonly', false);
						$('#ticket-client_mobile').val('').attr('readonly', false);
						$('#ticket-client_email').val('').attr('readonly', false);
						$('#ticket-client_vat').val('').attr('readonly', false);
		    	} else {
		    		var cust_name = '';
					cust_name = (data['first_name'] + ' ' + data['last_name']);
		    		$('#ticket-alt_business_name').val(cust_name).attr('readonly', true);

					if(data['id_number'] != '') {
						$('#ticket-client_id').val(data['id_number']).attr('readonly', true);
					} else {
						$('#ticket-client_id').val('').attr('readonly', false);
					}
		    		
		    		if(data['mobile'] != '') {
		    			$('#ticket-client_mobile').val(data['mobile']).attr('readonly', true);
		    		} else {
		    			$('#ticket-client_mobile').val('').attr('readonly', false);
		    		}
		    		$('#ticket-client_email').val(data['email']).attr('readonly', true);
		    	}
		    },
		    error: function() {
		    	console.log('System error: customer information could not be retrieved');
		    }
		});
	});
});