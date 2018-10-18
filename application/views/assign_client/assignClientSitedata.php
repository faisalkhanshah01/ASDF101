	
	<label for="email" class="col-md-2 control-label" style="margin-top:10px;"><?php if( $lang["no_data_available"]['description'] !='' ){ echo $lang["no_data_available"]['description']; }else{ echo 'No data'; } ?></label>
	<div class="col-md-10" style="margin-top:10px;">
		<div class="col-md-2" style="margin-left:-39px;">
			 <button type="button" id="selectAll" class="btn btn-info"><?php if( $lang["select_all"]['description'] !='' ){ echo $lang["select_all"]['description']; }else{ echo 'Select All'; } ?></button>
		</div>
		<div class="col-md-8" style="margin-left:-40px;">
			<input type="text" id="search_tool_site_id" name="search_tool_site_id" class="form-control tooltip_trigger"  placeholder="Search SITE ID"/>
		</div>
		<div class="col-md-2" style="float: left;margin-left:-9px;">
			<button type="button" id="selectAllDelete" class="btn btn-info" style="margin-right:0px;"><?php if( $lang["all"]['description'] !='' ){ echo $lang["all"]['description']; }else{ echo 'All'; } ?></button> &nbsp;
			<button type="button" class="btn btn-info" id="delete_selected_siteID" style="margin-right:-101px;margin-top:0px;"><span class="glyphicon glyphicon-trash"></span></button>
		</div>
		<div class="col-md-1" style="float: right;margin-right:-29px;">
			<button type="button" class="btn btn-info btn-md popupSubmit" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-glass"></span></button>
		</div>
		<!-- Modal -->

		  <div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg">
			  <div class="modal-content">
				<div class="modal-header">
				 <h4 class="modal-title"><?php if( $lang["search_site"]['description'] !='' ){ echo $lang["search_site"]['description']; }else{ echo 'Search SITE'; } ?> </h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="state-name" class="form-control-label">State :</label>
						<select class="form-control state_name" id="stateID">
						  <option value="">Select State</option>
						   <?php 	
								if(!empty($site_list) && is_array($site_list)){
									$sitelist = array_unique(array_column($site_list,'site_location'));
									foreach($sitelist as $k => $v){ ?>
										<option  value="<?php print $v;?>"><?php print $v;?></option>
                            <?php 	}
								}?>
						</select>
					</div>
					<div class="form-group">
						<label for="state-name" class="form-control-label">City :</label>
						<select class="form-control search-city" id="city_name">
							<option value="">Select City</option>
						</select>
					</div>
					<div class="form-group">
						<label for="state-name" class="form-control-label">Client Name :</label>
						<select class="form-control client_name_popup" id="client_name">
						  <option value="">Select Client Name</option>
						   <?php 	
								if(!empty($site_list) && is_array($site_list)){
									$sitelist = array_unique(array_column($site_list,'clientName'));
									foreach($sitelist as $k => $v){ ?>
										<option  value="<?php if(!empty($v)){ print $v;}?>"><?php if(!empty($v)){ print $v;}?></option>
                            <?php 	}
								}?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" style="z-index:-9999"; data-dismiss="modal">Close</button>
				  <button type="button" class="btn btn-primary popupsubmit">Search</button>
				</div>
			  </div>
			</div>
		  </div>
	</div>	
	<label for="email" class="col-md-2 control-label" style="margin-top:10px;"><?php if( $lang["select_site_id"]['description'] !='' ){ echo $lang["select_site_id"]['description']; }else{ echo 'Select SITE ID'; } ?> </label>	
	<div id ="popupsearchHide">
		<div class="col-md-10" style="margin-bottom:10px;">
			<div class="row">
				<div class="col-md-5"> 
					<div class="component-container search-siteID form-control" id='sel_siteID' style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
						<?php
							if(!empty($site_list) && is_array($site_list)){
								foreach($site_list as $key=>$value){
								?>
								<p><?php echo $value['clientName']; ?>
								<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="<?php echo "chk_".$value['siteID_id']; ?>" value="<?php echo $value['siteID_id']; ?>" rel="<?php echo $value['clientName']; ?>" /></p>
						<?php } }else { ?> 
								<p></p>
						<?php }  ?> 
					</div> 
				</div>
				<div class="col-md-2" align="center">
					<button id="com_sel_btn_siteID" class="btn site_id_value" type="button" style="margin-top:50px;"> >> </button>
				</div>
				<div class="col-md-5" >
					<div class="component-container form-control" id="selected_siteID" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div>
				</div>
			</div> 
		</div>
		
		<div id="client-address">
			<label  class="col-md-2 control-label" style="margin-top:10px;">Site Address</label>
			<div class="col-md-10" id="address-siteID"></div>
		</div>
	</div>
	<div id ="popupsearchShow">
		<div class="col-md-10" style="margin-bottom:10px;">
			<div class="row">
				<div class="col-md-5"> 
					<div class="component-container search-siteID form-control popupsearchShow-siteID" id='sel_siteID_1' style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div> 
				</div>
				<div class="col-md-2" align="center">
					<button id="com_sel_btn_siteID_1" class="btn site_id_value_1" type="button" style="margin-top:50px;"> >> </button>
				</div>
				<div class="col-md-5" >
					<div class="component-container form-control" id="selected_siteID_1" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div>
				</div>
			</div> 
		</div>
		
		<div id="client-address">
			<label  class="col-md-2 control-label" style="margin-top:10px;">Site Address</label>
			<div class="col-md-10" id="address-siteID1"></div>
		</div>
	</div>
    <script>
		$(document).ready(function () {
		  $('#selectAll').click(function () {
			if ($(this).hasClass('allChecked')) {
				$('.check-element').prop('checked', false);
			} else {
				$('.check-element').prop('checked', true);
			}
			$(this).toggleClass('allChecked');
		  });
		  
		  $('#selectAllDelete').click(function () {
			if ($(this).hasClass('allChecked')) {
				$('.check-delete').prop('checked', false);
			} else {
				$('.check-delete').prop('checked', true);
			}
			$(this).toggleClass('allChecked');
		  });
		});
		
		$(document).ready(function(){
			$('#stateID').change(function(){
				var value = $(this).val();
				$.get("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
					{ 'site_location' : value,'type':3},
				function(data){
					$(".search-city").html(data);
				});
			});	
			
			/*var checkedVals = $('div#sel_siteID input:checked').map(function() {
					return this.value;
				}).get();
				alert(checkedVals.join(","));*/
			
			$("#popupsearchShow").hide();
			$("#client-address").hide();
			$('.site_id_value').click(function(){
				var checkedVals = $('div#sel_siteID input:checked').map(function() {
					return this.value;
				}).get();
				
				var siteID = $("input[name='site_id[]']").map(function(){return $(this).val();}).get();
				if(siteID == ''){
					var siteID_add = checkedVals.join(",");
				}else{
					var siteIDadd = checkedVals.join(",");
					var siteID_add = siteID+','+siteIDadd;
				}
				//var siteID_add = checkedVals.join(",");
				
				$("#client-address").show();
				$.post("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
				{ 'siteID' : siteID_add,'type':2},
				function(data){
					$("#address-siteID").html(data);	 
				});
			});	
			/*$('.site_id_value').click(function(){
			    $("div#sel_siteID input:checked").each(function(index,ele){
					var siteID_key =$(this).val();
					var siteID = $("input[name='site_id[]']").map(function(){return $(this).val();}).get();
					
					if(siteID == ''){
						var siteID_add = siteID_key;
					}else{
						var siteID_add = siteID+','+siteID_key;
					}	
					$("#client-address").show();
					$.get("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
					{ 'siteID' : siteID_add,'type':2},
					function(data){
						$("#address-siteID").html(data);	 
					});
					
				});
			});*/ 
			
			$('.site_id_value_1').click(function(){
				var checkedVals = $('div#sel_siteID_1 input:checked').map(function() {
					return this.value;
				}).get();
				
				var siteID = $("input[name='site_id1[]']").map(function(){return $(this).val();}).get();
				if(siteID == ''){
					var siteID_add = checkedVals.join(",");
				}else{
					var siteIDadd = checkedVals.join(",");
					var siteID_add = siteID+','+siteIDadd;
				}
				
				
				$("#client-address").show();
				$.post("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
				{ 'siteID' : siteID_add,'type':2},
				function(data){
					$("#address-siteID").html(data);	 
				});
			});
			
			/*$('.site_id_value_1').click(function(){
			    $("div#sel_siteID_1 input:checked").each(function(index,ele){
					var siteID_key =$(this).val();
					var siteID = $("input[name='site_id1[]']").map(function(){return $(this).val();}).get();
					
					if(siteID == ''){
						var siteID_add = siteID_key;
					}else{
						var siteID_add = siteID+','+siteID_key;
					}	
					$("#client-address").show();
					
					$.get("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
					{ 'siteID' : siteID_add,'type':2},
					function(data){
						$("#address-siteID").html(data);	 
					});
					
				});
			});*/

			$('.popupsubmit').click(function(){
				var state  = $('.state_name').find(":selected").val();
				if(state ==''){
					var stateValue = 'blank';
				}else{
					var stateValue = state;
				}
				var city  = $('#city_name').find(":selected").val();
				if(city ==''){
					var cityValue = 'blank';
				}else{
					var cityValue = city;
				}
				
				var client_name  = $('#client_name').find(":selected").val();
				if(client_name ==''){
					var client_nameValue = 'blank';
				}else{
					var client_nameValue = client_name;
				}
				$.get("<?php print base_url()."assign_client_controller/searchClientAddress";?>",
				{ 'site_location' : stateValue,'clientName' : client_nameValue,'site_city' : cityValue},
				function(data){
					var json = $.parseJSON(data);
					//alert(json.type);
					if(json.type == 1){
						$("#client-address").show();
					    $("#address-siteID").html(json.respose);
					}else if(json.type == 2){
						$("#client-address").show();
					    $("#address-siteID").html(json.respose);
					}else if(json.type == 3){
						$("#popupsearchHide").hide();
						$("#popupsearchShow").show();
						$("#client-address").show();
						$("#address-siteID1").html(json.respose);
						$(".popupsearchShow-siteID").html(json.clientName_view);
					}else if(json.type == 4){
						$("#popupsearchHide").hide();
						$("#popupsearchShow").show();
						$("#client-address").show();
						$("#address-siteID1").html(json.respose);
						$(".popupsearchShow-siteID").html(json.clientName_view);
					}else if(json.type == 5){
						$("#popupsearchHide").hide();
						$("#popupsearchShow").show();
						$("#client-address").show();
						$("#address-siteID1").html(json.respose);
						$(".popupsearchShow-siteID").html(json.clientName_view);
					}		
				});
				$('#myModal').modal("hide");
				return false;
			});
			
		});
	
		$(document).ready(function() {
			$("#search_tool_site_id").keyup(function(){
				var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get("<?php print base_url()."assign_client_controller/searchClientSiteData";?>",
					{ 'search' : value,'type':1},
					function(data){
					$(".search-siteID").html(data);	 
				});
			});
			

			//$(".search-subAssets").on("click",":checkbox",function(){
			$(".search-siteID").click("input:checked",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			
			//<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="<?php echo "chk_".$value['siteID_id']; ?>" value="<?php echo $value['siteID_id']; ?>" rel="<?php echo $value['clientName']; ?>" />
			var siteId_list = [];
            $('#com_sel_btn_siteID').click(function(){
				// get all the selected values
				$("input.subAssets:hidden").each(function(){
						siteId_list.push($(this).val());				       	
				});

				$("div#sel_siteID input:checked").each(function(index,ele){
					siteID_key=$(this).val();
					var siteID_Value =$(this).attr('rel');
					if(siteId_list.indexOf(siteID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Site ID already in list");	
					}else{
					$("<p class='bg-success  check-element-delete'>"+siteID_Value+'<span rel="'+siteID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="checkbox" class="pull-left check-delete"  id="chk_'+siteID_key+'" value="'+siteID_key+'" style="margin-right:9px;"/>'
							+'<input type="hidden" class="sel-component subAssets" name="site_id[]" value="'+siteID_key+'"/>'
							+"</p>").appendTo("#selected_siteID");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
			   });
			});
		   
		    $("#selected_siteID").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				siteId_list = jQuery.grep(siteId_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
			
			$("#delete_selected_siteID").click(function() {
				$("p.check-element-delete input:checked").parent("p").remove();
				
			});
			
			$('#com_sel_btn_siteID_1').click(function(){
				// get all the selected values
				$("input.subAssets1:hidden").each(function(){
						siteId_list.push($(this).val());				       	
				});

				$("div#sel_siteID_1 input:checked").each(function(index,ele){
					siteID_key=$(this).val();
					var siteID_Value =$(this).attr('rel');
					if(siteId_list.indexOf(siteID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Site ID already in list");	
					}else{
					$("<p class='bg-success check-element-delete'>"+siteID_Value+'<span rel="'+siteID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="checkbox" class="pull-left check-delete"  id="chk_'+siteID_key+'" value="'+siteID_key+'" style="margin-right:9px;"/>'
							+'<input type="hidden" class="sel-component subAssets1" name="site_id1[]" value="'+siteID_key+'"/>'
							+"</p>").appendTo("#selected_siteID_1");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
			   });
			});
		   
		    $("#selected_siteID_1").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				siteId_list = jQuery.grep(siteId_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
		});	
	</script>