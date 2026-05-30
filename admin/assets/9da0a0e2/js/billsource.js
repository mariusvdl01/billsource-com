$(window).ready(function() {

	function initProfile() {
		var profile_id = $("#businessclient-profile_id").val();
		if(profile_id == '3') {
			$("#do-details").hide();
		}
	}
	
	function profileChanged(e) {
		var id = $(e.target).val();
		if(id != '3') {
			$("#do-details").hide();
			$("#do-details").show();
		} else {
			initProfile();
		}
	}
	
	$("body").on("change", "#businessclient-profile_id", profileChanged);
	
	var crm_el = $('#businessclientcrm-is_business');
	if(crm_el.val() == '1') {
		$('a[href*="bio-data"]').parent().hide();
		$('a[href*="business-details"]').parent().hide();
		$('a[href*="bio-data"]').parent().show();
		$('a[href*="business-details"]').parent().show();
	} else {
		$('a[href*="bio-data"]').parent().hide();
		$('a[href*="business-details"]').parent().hide();
		$('a[href*="bio-data"]').parent().show();
	}
	
	$('body').on('change', '#businessclientcrm-is_business', function(e) {
		if($(this).val() == '1') {
			$('a[href*="bio-data"]').parent().hide();
			$('a[href*="business-details"]').parent().hide();
			$('a[href*="bio-data"]').parent().show();
			$('a[href*="business-details"]').parent().show();
		} else {
			$('a[href*="bio-data"]').parent().hide();
			$('a[href*="business-details"]').parent().hide();
			$('a[href*="bio-data"]').parent().show();
		}
	});
	
	initProfile();
});