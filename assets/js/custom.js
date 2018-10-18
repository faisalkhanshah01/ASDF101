/*Custom Js Starts*/
$(document).ready(function() {
	$(".email_status_check").keyup(function() {
		var email = $(this).val();
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		if(email == "") {
			$(".email_status_check ~ .text-success").html("");
			$(".email_status_check ~ .text-danger").html("<p><span class='glyphicon glyphicon-remove'></span> Email cannot be blank</p>");
			return false;
		}
		if(!pattern.test(email)) {
			$(".email_status_check ~ .text-success").html("");
			$(".email_status_check ~ .text-danger").html("<p><span class='glyphicon glyphicon-remove'></span> Invalid email</p>");
			return false;
		}
		if(email == 'user_email') {
			url = '/Register_Controller/email_check_ajax';
		} else {
			url = '/admin/Register_Controller/email_check_ajax';
		}
		check_email_exists(email, url);
	}); //email status check ends


	$(".customer-search .datepicker-class").datetimepicker({
		format: 'YYYY-MM-DD'
    });

    $('#client-search-table').DataTable({
	    searching: true,
	    dom: 'Bfrtip',
	    aaSorting: [],
	    buttons: [
	       {
	         extend: 'excel',
	         text: 'Download Report',
	         footer: true
	       },
    	]
  	});

}); //jquery ready ends



/**Functions Starts**/
function check_email_exists(email, url) {
	console.log(url);
	$.ajax({
		type: "post",
		url:  url,
		data: {email:email},
		success: function(response) {
			//console.log("sshshhs");
			if(response == 0) {
				$(".email_status_check ~ .text-success").html("");
				$(".email_status_check ~ .text-danger").html("<p><span class='glyphicon glyphicon-remove'></span> An admin account with this email already exists</p>");
			} else {
				$(".email_status_check ~ .text-danger").html("");
				$(".email_status_check ~ .text-success").html("<p><span class='glyphicon glyphicon-ok'></span> Email available</p>");
			}
		}
	});
}

