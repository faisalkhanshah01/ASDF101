$(document).ready(function() {
	
	$(document).on('change','.select_product_type',function(){
		var type_name 	= $(this).val();
		var rel 		= $(this).attr('id');
		if(type_name !=""){
			$.ajax({
				type: "POST",
				url: base_url + "Productedit_controller/get_selected_data",
				data: 	{
							type_name	: type_name,
							rel 		: rel,
						},
				success: function(res){
					$('#display_value').html(res);
				},
				error: function(){
					alert('Error while request ajax...');
				}
			});	// end of ajax
		} // end of if statememnt
		else{
			$('#display_value').html('');
		}
	});
}); // end of document.ready