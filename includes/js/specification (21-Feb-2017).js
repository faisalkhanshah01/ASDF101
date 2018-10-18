$(document).ready(function() {
	
	$(document).on('change','.select_type',function(){
		var type_name = $(this).val();
		if(type_name !=""){
			$.ajax({
				type: "POST",
				url: base_url + "specification/get_selected_data",
				data: {type_name: type_name},
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
	
	$(document).on('change','.select_option',function(){
		var sel_option = $(this).val();
		var id = $(this).attr('id');
		if(sel_option !=""){
			if(id=='select_tds'){
				if(sel_option =='url'){
					$('#display_tds').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='tds_url' id='tds_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_tds').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_tds' name='upload_tds' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}else if(id=='select_certificate'){
				if(sel_option =='url'){
					$('#display_certificate').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='certificate_url' id='certificate_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_certificate').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_certificate' name='upload_certificate' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}else if(id=='select_image'){
				if(sel_option =='url'){
					$('#display_image').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='image_url' id='image_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_image').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_image' name='upload_image' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}else if(id=='select_user_manual'){
				if(sel_option =='url'){
					$('#display_user_manual').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='user_manual_url' id='user_manual_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_user_manual').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_user_manual' name='upload_user_manual' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}else if(id=='select_test_report'){
				if(sel_option =='url'){
					$('#display_test_report').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='test_report_url' id='test_report_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_test_report').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_test_report' name='upload_test_report' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}else if(id=='select_other'){
				if(sel_option =='url'){
					$('#display_other').html("<div class='col-md-12'>"+
					"<input type='url' class='form-control' name='other_url' id='test_report_url' placeholder='URL' required/>"+
					"</div>");
				}else if(sel_option =='upload'){
					$('#display_other').html("<div class='col-md-12'>"+
								"<input type='file' class='form-control' id='upload_test_report' name='upload_other' placeholder='Thumbnail' required >"+
							"</div>");
				}
			}
		} // end of if statememnt
		else{
			if(id=='select_tds'){
				$('#display_tds').html('');
			}else if(id=='select_certificate'){
				$('#display_certificate').html('');
			}else if(id=='select_image'){
				$('#display_image').html('');
			}else if(id=='select_user_manual'){
				$('#display_user_manual').html('');
			}else if(id=='select_test_report'){
				$('#display_test_report').html('');
			}else if(id=='select_other'){
				$('#display_other').html('');
			}
		}
	});
	

}); // end of document.ready