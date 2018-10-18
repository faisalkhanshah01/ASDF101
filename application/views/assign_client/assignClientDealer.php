<?php $this->load->view('includes/header'); ?> 

<style>
	.close{
		color:#000000;
	}
	.modal-backdrop{
		 z-index: -1040;
	}
	.modal-backdrop.in {
		opacity: 0;
	}
</style>
<!-- Navigation -->
 <script>
        $(document).ready(function() {
            $('.submit').click(function(){
				var text = "";
				jQuery("textarea#client_address").each(function(){
				   text += jQuery(this).val()+" /.../"+ "\n";
				})
				
				
				
				if($('#clientType').find(":selected").val() == 1){
					alert('Please, Fill Client Type Value.');
					return false;
				}
				
				if($('#status').find(":selected").val() == 2){
					alert('Please, Fill Status Value.');
					return false;
				}

                var data = {
                    status:$('#status').find(":selected").val(),
					client_type:$('#clientType').find(":selected").val(),
					dealer_client_id:$("input[name='client_ids_7[]']").map(function(){return $(this).val();}).get(),
					client_client_id:$("input[name='client_ids_8[]']").map(function(){return $(this).val();}).get(),
					inspector_client_id:$("input[name='client_ids_9[]']").map(function(){return $(this).val();}).get(),
                    site_id:$("input[name='site_id[]']").map(function(){return $(this).val();}).get(),
					site_id_1:$("input[name='site_id1[]']").map(function(){return $(this).val();}).get(),
					site_address:text,
					state_name_popup:$('.state_name').find(":selected").val(),
					city_name_popup:$('#city_name').find(":selected").val(),
					client_name_popup:$('.client_name_popup').find(":selected").val(),
                    submit:1
                };
                $.ajax({
                    url: '<?php print base_url()."assign_client_controller/insert_assign_client_dealer";?>',
                    type: 'post',
                    dataType: "json",
                    data: data,
                    success: function(output) {
                        if(output.responseType == 'fail'){
                            alert(output.message);
                        }else{
                            alert(output.message);
                           location.href=location.href;
                        }     
                       
                    }
                });  
                return false;
            });
			/***************************Client Type********************************************/
			$("#clientType_8").hide();
            $("#clientType_7").hide();
			$("#clientType_9").hide();
			$("#clientType_all").show();
			$('#clientType').change(function(){
				var value = $(this).val();
				
				$("#clientType_all").hide();
				if(value == 8){
					$("#clientType_7").hide();
					$("#clientType_9").hide();
				}else if(value == 7){
					$("#clientType_8").hide();
					$("#clientType_9").hide();
				}else if(value == 9){
					$("#clientType_7").hide();
					$("#clientType_8").hide();
				}
			    $("#clientType_"+value).show();
				
				$.get("<?php print base_url()."assign_client_controller/client_name";?>",
					{ 'clientType' : value,'type':1},
					function(data){
						if(value == 8){
							$(".search-clientID-8").html(data);
					    }else if(value == 9){
							$(".search-clientID-9").html(data);
						}else if(value == 7){
							$(".search-clientID-7").html(data);
						}
				});
			});
			/***************************Client Type End****************************************/
        });
  </script>    
	<?php $this->load->view('includes/head'); ?>    
	<div class="row">
		<div class="col-md-12">
			 <div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span><?php if( $lang["assign_client_dealer_inspector"]['description'] !='' ){ echo $lang["assign_client_dealer_inspector"]['description']; }else{ echo 'ASSIGN CLIENT / DEALER / INSPECTOR'; } ?></span>
				</div>
				<div class="panel-body">
                                <form class="form" id ="client_data"> 
								<div class="form-group">
                                    <label for="status" class="col-md-2 control-label" style="margin-top:10px"><?php if( $lang["client_type"]['description'] !='' ){ echo $lang["client_type"]['description']; }else{ echo 'Client Type'; } ?></label>
                                    <div class="col-md-10">
                                        <select  id="clientType" name="clientType" class="form-control tooltip_trigger"  style="margin-top:10px">
                                                <option selected value="1"> - Select Option - </option>	
												<option  value="8">Client</option>
												<option  value="7">Dealer</option>
                                                <option  value="9">Inspector</option>
                                        </select>
                                    </div>
                                </div>
								
								<div id="clientType_8">
									<div class="form-group">
										<label for="email" class="col-md-2 control-label" style="margin-top:10px;"><?php if( $lang["search_client_list"]['description'] !='' ){ echo $lang["search_client_list"]['description']; }else{ echo 'Search Client List'; } ?></label>
											<div class="col-md-10">
												<input type="text" id="search_tool_client_list_8" name="search_tool_client_list" class="form-control tooltip_trigger"  placeholder="Search Client List"  style="margin-top:10px;"/>
											</div>
										<label class="col-md-2 control-label"><?php if( $lang["client_list"]['description'] !='' ){ echo $lang["client_list"]['description']; }else{ echo 'Client List'; } ?></label>
										<div class="col-md-10">
											<div class="row">
												<div class="col-md-5"> 
													<div class="component-container search-clientID-8" id='sel_clientID_8' style="height: 150px !important;"></div> 
												</div>
												<div class="col-md-2" align="center">
															<button id="com_sel_btn_clientID_8" class="btn" type="button" style="margin-top:50px;"> >> </button>
												</div>
												<div class="col-md-5" >
														<div class="component-container component-client" id="selected_clientID_8" style="height: 150px !important;">
														</div>
												</div>
											</div>
										</div>
									</div>
							    </div>

								<div id="clientType_7">
									<div class="form-group">
										<label for="email" class="col-md-2 control-label" style="margin-top:10px;"><?php if( $lang["search_client_list"]['description'] !='' ){ echo $lang["search_client_list"]['description']; }else{ echo 'Search Client List'; } ?></label>
											<div class="col-md-10">
												<input type="text" id="search_tool_client_list_7" name="search_tool_client_list" class="form-control tooltip_trigger"  placeholder="Search Client List"  style="margin-top:10px;"/>
											</div>
										<label class="col-md-2 control-label"><?php if( $lang["client_list"]['description'] !='' ){ echo $lang["client_list"]['description']; }else{ echo 'Client List'; } ?></label>
										<div class="col-md-10">
											<div class="row">
												<div class="col-md-5"> 
													<div class="component-container search-clientID-7" id='sel_clientID_7' style="height: 150px !important;"></div> 
												</div>
												<div class="col-md-2" align="center">
															<button id="com_sel_btn_clientID_7" class="btn" type="button" style="margin-top:50px;"> >> </button>
												</div>
												<div class="col-md-5" >
														<div class="component-container component-client" id="selected_clientID_7" style="height: 150px !important;">
														</div>
												</div>
											</div>
										</div>
									</div>
							    </div>
								
								<div id="clientType_9">
									<div class="form-group">
										<label for="email" class="col-md-2 control-label" style="margin-top:10px;"><?php if( $lang["search_client_list"]['description'] !='' ){ echo $lang["search_client_list"]['description']; }else{ echo 'Search Client List'; } ?></label>
											<div class="col-md-10">
												<input type="text" id="search_tool_client_list_9" name="search_tool_client_list" class="form-control tooltip_trigger"  placeholder="Search Client List"  style="margin-top:10px;"/>
											</div>
										<label class="col-md-2 control-label"><?php if( $lang["client_list"]['description'] !='' ){ echo $lang["client_list"]['description']; }else{ echo 'Client List'; } ?></label>
										<div class="col-md-10">
											<div class="row">
												<div class="col-md-5"> 
													<div class="component-container search-clientID-9" id='sel_clientID_9' style="height: 150px !important;"></div> 
												</div>
												<div class="col-md-2" align="center">
															<button id="com_sel_btn_clientID_9" class="btn" type="button" style="margin-top:50px;"> >> </button>
												</div>
												<div class="col-md-5" >
														<div class="component-container component-client" id="selected_clientID_9" style="height: 150px !important;">
														</div>
												</div>
											</div>
										</div>
									</div>
							    </div>
								
                                <div class="form-group">
									<?php  $this->load->view('assign_client/assignClientSitedata');?>
								</div>
                                <div class="form-group">
                                    <label for="status" class="col-md-2 control-label" style="margin-top:10px"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; } ?></label>
                                    <div class="col-md-10">
                                        <select  id="status" name="status" class="form-control tooltip_trigger"  style="margin-top:10px">
                                                <option selected value="2"> - Status - </option>										
                                                <option  value="1">Active</option>
                                                <option  value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-md-offset-6 col-md-6">
                                                <input type="submit" name="save_assignInspector" class="btn btn-primary submit" id="save_assignInspector"  style="margin-top:10px"/>
                                        </div>
                                </div>
                            </form>    
				</div>
			</div>
		</div>
	</div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading home-heading">
                         <span><?php if( $lang["assigned_client_list"]['description'] !='' ){ echo $lang["assigned_client_list"]['description']; }else{ echo 'Assigned Client List'; } ?></span>
                    </div>
                    <div class="panel-body">
                        <table id="client_table" class="table table-bordered table-hover">
                            <thead>
                                <th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S.No'; } ?></th>
                                <th><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo 'Site ID'; } ?></th>
                                <th><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo 'Client Name'; } ?></th>
                                <th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; } ?></th>
                                <th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo 'Action'; } ?></th>
                            </thead> 
                            <?php if(!empty($client_datas) && is_array($client_datas)){
                                     foreach ($client_datas as $key => $value) {?>
                                        <tbody>
                                            <td><?php print $key+1;?></td>
                                            <td>
												
													<?php
														$site_id = implode('<br/>', $value['site_data']);
														print $site_id;
													?>
													
                                            </td>
                                            <td>
                                                <?php 
                                                    $user_name = implode('<br/>', $value['user_name']);
                                                    print $user_name;
                                                ?>
                                            </td>
                                            <td><?php print $value['status'];?></td>
                                            <td>
                                                <center>
                                                    <a href="<?php echo $base_url."assign_client_controller/update_assign_client/".$value['id']; ?>">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </a>
                                                    <a class="delete" style="margin:0px 0px 0px 30px;" href="<?php echo $base_url."/assign_client_controller/delete_client/".$value['id']; ?>">
                                                        <span class="glyphicon glyphicon-trash error"></span>
                                                    </a>
                                                </center>    
                                            </td>
                                        </tbody> 
                                     <?php }?>
                            <?php }else{  ?>  
										<tr><td colspan="100%" ><?php if( $lang["no_data_available"]['description'] !='' ){ echo $lang["no_data_available"]['description']; }else{ echo 'No data'; } ?> </td></tr>	
							<?php } ?>    
                        </table>
                    </div>
                </div>
            </div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
    <script>
		
             // form validation 
		/*$(document).ready(function(){
            $("#client_table").DataTable({
                 "order":[[ 0,"asc" ]]
             });
         }); */    
        
        //var subAssets_list=[];
		$(document).ready(function() {
			$("#search_tool_client_list_8").keyup(function(){
				var clientType = $('#clientType	').val();
				var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				
				$.get("<?php print base_url()."assign_client_controller/search_client_ajax_get";?>",
					{ 'search' : value,'clientType':clientType},
				function(data){
					$(".search-clientID-8").html(data);
				});
			});
			
			
			$(".search-clientID-8").click("input:checked",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			
			$("#search_tool_client_list_7").keyup(function(){
				var clientType = $('#clientType	').val();
				var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				
				$.get("<?php print base_url()."assign_client_controller/search_client_ajax_get";?>",
					{ 'search' : value,'clientType':clientType},
				function(data){
						$(".search-clientID-7").html(data);
				});
			});
			$(".search-clientID-7").click("input:checked",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			
			$("#search_tool_client_list_9").keyup(function(){
				var clientType = $('#clientType	').val();
				var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				
				$.get("<?php print base_url()."assign_client_controller/search_client_ajax_get";?>",
					{ 'search' : value,'clientType':clientType},
				function(data){
					$(".search-clientID-9").html(data);
				});
			});
			
			$(".search-clientID-9").click("input:checked",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			/******************************client list end*********************************************/
			
		});
		
        
		$(document).ready(function() {
			var clientID_list = [];
			$('#com_sel_btn_clientID_7').click(function(){
				// get all the selected values
				$("input.subclient_7:hidden").each(function(){
						clientID_list.push($(this).val());				       	
				});

				$("div#sel_clientID_7 input:checked").each(function(index,ele){
					 clientID_key = $(this).val();
					var client_Value = $(this).attr('rel');
					if(clientID_list.indexOf(clientID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Client ID already in list");	
					}else{
					$("<p class='bg-success'>"+client_Value+'<span rel="'+clientID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="hidden" class="sel-component subclient_7" name="client_ids_7[]" value="'+clientID_key+'"/>'
							+"</p>").appendTo("#selected_clientID_7");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");

			   });
			});
		   
		    $("#selected_clientID_7").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				clientID_list = jQuery.grep(clientID_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
			
			$('#com_sel_btn_clientID_8').click(function(){
				// get all the selected values
				$("input.subclient_8:hidden").each(function(){
						clientID_list.push($(this).val());				       	
				});

				$("div#sel_clientID_8 input:checked").each(function(index,ele){
					 clientID_key = $(this).val();
					var client_Value = $(this).attr('rel');
					if(clientID_list.indexOf(clientID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Client ID already in list");	
					}else{
					$("<p class='bg-success'>"+client_Value+'<span rel="'+clientID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="hidden" class="sel-component subclient_8" name="client_ids_8[]" value="'+clientID_key+'"/>'
							+"</p>").appendTo("#selected_clientID_8");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");

			   });
			});
		   
		    $("#selected_clientID_8").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				clientID_list = jQuery.grep(clientID_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
			
			$('#com_sel_btn_clientID_9').click(function(){
				// get all the selected values
				$("input.subclient_8:hidden").each(function(){
						clientID_list.push($(this).val());				       	
				});

				$("div#sel_clientID_9 input:checked").each(function(index,ele){
					 clientID_key = $(this).val();
					var client_Value = $(this).attr('rel');
					if(clientID_list.indexOf(clientID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Client ID already in list");	
					}else{
					$("<p class='bg-success'>"+client_Value+'<span rel="'+clientID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="hidden" class="sel-component subclient_9" name="client_ids_9[]" value="'+clientID_key+'"/>'
							+"</p>").appendTo("#selected_clientID_9");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");

			   });
			});
		   
		    $("#selected_clientID_9").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				clientID_list = jQuery.grep(clientID_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
			
			/********************************Client List************************************/
        });
    </script>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts_data'); ?> 
