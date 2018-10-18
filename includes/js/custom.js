$(document).ready(function() {
	
	/* Start of Model Image Pop UP */
    $(document).on('click','.asset_image', function () {
        var image = $(this).attr('src');
        var altt = $(this).attr('alt');
		
		$("#myModal").css("display","block");
            $("#img01").attr("src", image);
            $("#img01").attr("alt", altt);
			$('#caption').html(altt);
    });
	

	$(document).on('click','.close', function () {
		$("#myModal").css("display","none");
	})

	/* End of Model Image Pop UP */ 
	
	/* Loding Image Effect While ajax Loading */
	/*
	$('#loading').bind('ajaxStart', function(){
		$(this).show();
	}).bind('ajaxStop', function(){
			$(this).hide();
	});
	*/
    $('.shorting').DataTable( {
        "order": [[ 5, "desc" ]]
    } );
	$('.simple').DataTable( {
        "order": [[ 1, "desc" ]]
    } );

	$(document).on('click','#download_sms_excel',function(){
		$('#loading').show();
		$.post(base_url+'sms_controller/export_sms_component_excel')
        .done(function(data) {
            $('#loading').hide();
			window.location.href=data;
        });

	});
	
	$(document).on('click','#download_client_excel',function(){
		$('#loading').show();
		$.post(base_url+'client_kare/download_clientExcel')
        .done(function(data) {
            $('#loading').hide();
			window.location.href=data;
        });
	});
	
	/*
	$(document).on('click','#export_masterdata_xls',function(){
		$('#loading').show();
		 $.post(base_url+'manage_kare/export_masterdata_xls')
        .done(function(data) {
			alert(data);
            $('#loading').hide();
			if(data =='done'){
				alert(base_url+"manage_kare/mdata_inspection");
				location.href = base_url+"manage_kare/mdata_inspection";
			}
			
        });
	});  */
	
	$(document).on('click','.delete', function(e){
			if (!confirm("Do you want to delete it ? ")){
			  return false;
			}
	});
		
	$('#message').delay(10000).fadeOut('slow');
	$('.alert').delay(10000).fadeOut('slow');
	
	
	
	var count = 1;
	$("#btnAddRow").on("click",function(){
		$("#Table1").append('<table class="table siteID_table" id="site_id_table'+count+'" data-id="'+count+'">'+
								'<tr>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
											'<label for="group" class="control-label">Site ID</label>'+
												'<input type="text" class="form-control" name="siteID_info['+count+'][site_id]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
											'<label for="group" class="control-label">Site Location</label>'+
												'<input type="text" class="form-control" name="siteID_info['+count+'][site_location]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">City/District</label>'+
												'<input type="text" class="form-control" name="siteID_info['+count+'][site_city]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Site Address</label>'+
												'<input type="text" id="group" class="form-control" name="siteID_info['+count+'][site_address]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Lattitude</label>'+
												'<input type="text" id="group" class="form-control" name="siteID_info['+count+'][site_lattitude]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<div><label for="group" class="control-label"> </label> </div>'+
												'<a class="btn btn-danger removeData" href="javascript:void(0)" data-id="'+count+'"><i class="glyphicon glyphicon-trash"></i></a>'+
										'</div>'+
									'</td>'+
								'</tr>'+
								'<tr>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Longitude</label>'+
												'<input type="text" class="form-control" name="siteID_info['+count+'][site_longitude]" required/>'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Client Contact Person Name</label>'+
												'<input type="text" class="form-control" data-start="'+count+'" data-id="'+count+'" name="siteID_info['+count+'][site_contact_name]" required />'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Client Contact Person Number</label>'+
												'<input type="text" class="form-control" data-id="'+count+'" name="siteID_info['+count+'][site_contact_number]" required />'+
										'</div>'+
									'</td>'+
									'<td>'+
										'<div class="form-group col-md-12">'+
												'<label for="group" class="control-label">Client Contact Person Email</label>'+
												'<input type="text" class="form-control" data-id="'+count+'" name="siteID_info['+count+'][site_contact_email]" required />'+
										'</div>'+
									'</td>'+
									'<td>'+
									'</td>'+
								'</tr>'+
							'</table>');
		count++;
	});

// Date picker
	
	$(document).on('focus','.date1',function(){
		$(this).datepicker({
			changeMonth:true,
			changeYear:true,
			dateFormat:"dd-mm-yy"
		});
	});
	
	$(document).on('focus','.work_date',function(){
		$(this).datepicker({
			changeMonth:true,
			changeYear:true,
			dateFormat:"dd-M-yy"
		});
	});
	
	
	$(document).on('change','.observation_type',function(){
		var value = $(this).val();
		var rel = $(this).attr('rel');
		if(value !=''){
			$.ajax({
				type: "POST",
				url: base_url + "form_controller/get_actionProposed",
				data: {id: value, type : 'view'},
				success: function(res){
					try{
						$('#action_proposed'+rel).html(res);
					}catch(e) {
						alert('Exception while request..');
					}
				},
				error: function(){
					alert('Error while requesting Ajax');
				}
			});
			
		}else{
			$('#action_proposed'+rel).html("<option value=''> - Select Action Proposed - <?option>");
		}
		
	});
	
	
	$(document).on('click','.remove_thumb_image',function(){
		if (!confirm("Do you want to delete this Image ? Please remember it will delete the image permanently. ")){
			  return false;
		}else{
			var id = $(this).data('id');
			var rel = $(this).attr('rel');
			var path = $(this).data('name');
			if(path !=''){
				$.ajax({
					type: "POST",
					url: base_url + "category_controller/delete_categoryImage",
					data: {id: id, path : path},
					success: function(res){
						try{
							$('#imageDefault-'+rel).html('');
							$('#imageDefault-'+rel).html('<div id="imageDefault-'+rel+'"><img alt="Thumbnail Image" src="'+base_url+'includes/images/no_image.jpg" width="120" height="120">');
							$('#remove_'+rel).remove();
						}catch(e) {
							alert('Exception while request..');
						}
					},
					error: function(){
						alert('Error while requesting Ajax');
					}
				});
				
			}
		}
	});
	
	/* Start of Image thumbnail preview code */
	$(".imagePreviewType").on("change", function(){
		var imageType = $(this).attr('rel');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
			
            reader.onloadend = function(){ // set image data as background of div
				$('#imageDefault-'+imageType).html('');
				$('#imageDefault-'+imageType).append('<div id="imagePreview-'+imageType+'" class="imagePreviewClass"></div>');
	           $("#imagePreview-"+imageType).css("background-image", "url("+this.result+")");
            }
        }
    });
	/* End of Image thumbnail preview code */
	
	
	$(document).on('click','.single',function(){
			title = $(this).attr('title');
			$.ajax({
				url : "submit_report",
				type : "POST",
				data : "value="+title,
				success: function(data){
					alert(data);
					var urls = base_url + "inspector_inspection";
					setTimeout("window.location.href ='"+urls+"'",100);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				},
				beforeSend: function(){
					  $('.loader').show();
				},
				complete: function(){
					$('.loader').hide();
				},
			});
	});
	
});

/* Profile Image Upload */
	function readImage(input)
	{
		//alert(input);
		if(input.files.length>1)
		{
			alert("More than 1 images is not allowed");
			input.value="";
			return false;
		}
			jQuery('#show').hide();
			jQuery(".errorImage").html('');
			var file_type = input.files[0].type;
			var file_size = input.files[0].size;
			
			if(file_type == 'image/jpeg' || file_type == 'image/png'){
				var counter=1;
				jQuery(".errorImage").html('');
				jQuery("#img_table").find("tr").empty();
				for (var i = 0; i < input.files.length; i++) {
					jQuery("#img_table").find("tr").append('<td style="border:none;"><img style="width:150px; height:200px;" id="image'+i+'" alt="profile image" src="" ></td>');
					showImage(input.files[i],i);
				}
				jQuery("#img_table").show();
		
			}else{
				jQuery("#img_table").find("tr").html('<td><img src="'+base_url+'uploads/images/users/default.jpg" alt="profile Image" width="150" height="200" /></td>');
				var $el = jQuery('#user_image');
				$el.wrap('<form>').closest('form').get(0).reset();
				$el.unwrap();
				jQuery("#user_image").focus();
				jQuery(".errorImage").html('<p style="color:red"> Only .jpeg / .png type of images are allowed</p>');
			}
	}
	
	function showImage(file,counter)
	{
		var name=file.name;
		var reader = new FileReader();
		reader.onload = function(e) {
			jQuery("#image"+counter).attr("src",e.target.result);
		}
		reader.readAsDataURL(file);
	}
	/* End of Display Image */
