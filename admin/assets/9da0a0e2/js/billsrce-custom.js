$(function() {
	var form = $('#register_form');
	var url = '';
	var newhtml = '';
	
	form.submit(function(event) {
		event.preventDefault();
		var formData = form.serialize();
		
		if($('#individual').is(':checked')) {
			url = 'pub-ind-register.php';
		} 
		else {
			url = 'pub-business-register.php';
		}
		
		$.ajax({
			type: 'post',
			url: url,
			data: formData,
			success: function(response) {
				console.log(response);
				var parsed = JSON.parse(response);
				
				if(parsed.length > 0) {
					 $.each(parsed, function(i, item) {
				          newhtml += '<p>' + item + '</p>';
					 }); 
					 $('#alert').append(newhtml).addClass('uk-alert-danger').show();
				} 
			},
			complete: function() {
				window.location.replace("pub-default.php")
			},
			error: function(xhr, desc, err) {
		        console.log(xhr);
		        console.log("Details: " + desc + "\nError:" + err);
		   }
		});
	});
});