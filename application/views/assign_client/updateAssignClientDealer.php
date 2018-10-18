<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
 <script>
        $(document).ready(function() {
            $('.submit').click(function(){
                var data = {
                    status:$('#status').find(":selected").val(),
                    site_id:$("input[name='site_id[]']").map(function(){return $(this).val();}).get(),
                    client_id:$("input[name='client_ids[]']").map(function(){return $(this).val();}).get(),
                    id:<?php print $this->uri->segment(3);?>,
                    submit:1
                };
                $.ajax({
                    url: '<?php print base_url()."assign_client_controller/update_assignClient";?>',
                    type: 'post',
                    dataType: "json",
                    data: data,
                    success: function(output) {
                        if(output.responseType == 'fail'){
                            alert(output.message);
                        }else{
                            alert(output.message);
                        }  
                    }
                });  
                return false;
            });
        });
  </script>  
	<?php $this->load->view('includes/head'); ?>  
	<div class="row">
		<div class="col-md-12">
			 <div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>ASSIGN CLIENT / DEALER / INSPECTOR</span>
				</div>
				<div class="row">
					<div class="col-md-12" style="margin-top:15px; margin-bottom:15px;">
						<a href='<?php echo base_url(); ?>assign_client_controller/assign_client_dealer' class="btn btn-primary pull-right">Back</a>
					</div>
				</div>
				<div class="panel-body">
                                <form class="form" id ="client_data">    
                                <div class="form-group">
                                        <label for="email" class="col-md-2 control-label">Search SITE ID</label>
										<div class="col-md-10">
											<input type="text" id="search_tool_site_id" name="search_tool_site_id" class="form-control tooltip_trigger"  placeholder="Search SITE ID"/>
										</div>
                                        <label for="email" class="col-md-2 control-label">Select SITE ID </label>	
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-5"> 
                                                    <div class="component-container search-siteID form-control" id='sel_siteID'>
                                                        <?php
                                                        if(!empty($siteID) && is_array($siteID)){
                                                                foreach($siteID as $key=>$value){
                                                                ?>
                                                                <p><?php echo $value['site_name']; ?>
                                                                <input class="pull-right" type="checkbox" name="site_ids[]" id="<?php echo "chk_".$value['siteID_id']; ?>" value="<?php echo $value['siteID_id']; ?>" rel="<?php echo $value['site_name']; ?>" /></p>
                                                        <?php } }else { ?> 
                                                                <p></p>
                                                        <?php }  ?>

														
                                                    </div> 
                                                </div>
                                                <div class="col-md-2" style="margin-top:50px;">
                                                    <button id="com_sel_btn_siteID" class="btn" type="button" style="margin-top:50px;"> >> </button>
                                                </div>
                                                <div class="col-md-5" >
                                                    <div class="component-container form-control" id="selected_siteID">
                                                        <?php 
                                                            if(!empty($siteID)){
                                                                foreach($siteID as $key => $value){?>
                                                                <p class='bg-success'><?php echo $value['site_name']; ?><span rel="<?php echo $value['siteID']; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
                                                                    <input type="hidden" class="sel-component subAssets" name="site_id[]" value="<?php echo $value['siteID']; ?>"/>
                                                                </p>
                                                        <?php } }?>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                  
                                <div class="form-group">
                                    <label for="email" class="col-md-2 control-label" style="margin-top:10px;">Search Client List</label>
										<div class="col-md-10">
											<input type="text" id="search_client_list" name="search_client_list" class="form-control tooltip_trigger"  placeholder="Search Client List"  style="margin-top:10px;"/>
										</div>
                                    <label class="col-md-2 control-label">Client List</label>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5"> 
                                                <div class="component-container search-clientID" id='sel_clientID' style="height: 150px !important;">
                                                        <?php
                                                        if($client_list != ''){
                                                                foreach($client_list as $key=>$value){
                                                                ?>
                                                                <p><?php echo $value; ?>
                                                                <input class="pull-right" type="checkbox" name="client_id[]" id="<?php echo "chk_".$key; ?>" value="<?php echo $key; ?>" rel="<?php echo $value; ?>" /></p>
                                                        <?php } }else { ?> 
                                                                <p></p>
                                                        <?php }  ?> 

                                                </div> 
                                            </div>
                                            <div class="col-md-2" align="center">
                                                        <button id="com_sel_btn_clientID" class="btn" type="button" style="margin-top:50px;"> >> </button>
                                            </div>
                                            <div class="col-md-5" >
                                                    <div class="component-container component-client" id="selected_clientID" style="height: 150px !important;">
                                                        <?php
                                                            if(!empty($userName)){
                                                                foreach($userName as $value){     ?>
                                                                    <p class='bg-success'><?php echo $value['upro_first_name'] ." ". $value['upro_last_name']; ?><span rel="<?php echo $value['upro_first_name'] ." ". $value['upro_last_name']; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
                                                                    <input type="hidden" class="sel-component ins" name="client_ids[]" value="<?php echo $value['upro_uacc_fk']; ?>"/>
                                                                    </p>
                                                         <?php   } }?>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="col-md-2 control-label" style="margin-top:10px">Status</label>
                                    <div class="col-md-10">
                                        <select  id="status" name="status" class="form-control tooltip_trigger" required style="margin-top:10px">
                                                <option selected value=""> - Status - </option>										
                                                <option <?php echo (!empty($client_data) && ($client_data['status'] == 'Active'))? 'Selected' : '' ; ?> value="1">Active</option>
                                                <option <?php echo (!empty($client_data) && ($client_data['status'] == 'Inactive'))? 'Selected' : '' ; ?> value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-md-offset-6 col-md-6" style="margin-top:10px">
                                                <input type="submit" name="save_assignInspector" class="btn btn-primary submit" id="save_assignInspector" style="margin-top:10px" />
                                        </div>
                                </div>
                                </form>    
				</div>
			</div>
		</div>
	</div>
	
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
<script>

		$(document).ready(function() {
			$("#search_client_list").keyup(function(){
				var query=$(this).val();
				
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				var id = <?php echo $this->uri->segment('3');?>;
				$.get("<?php print base_url()."assign_client_controller/search_site_id";?>",
					{ 'search' : value,'id':id,'type':1},
				function(data){
					$(".search-clientID").html(data);	 
				});
			});
			
			$(".search-clientID").click("input:checked",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			/************************************client list end************************************************/
			$("#search_tool_site_id").keyup(function(){
				var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				var id = <?php echo $this->uri->segment('3');?>;
				$.get("<?php print base_url()."assign_client_controller/search_site_id";?>",
					{ 'search' : value,'id':id,'type':2},
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
		});
        //var subAssets_list=[];
         var siteId_list = [];
		$(document).ready(function() {
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
							$("<p class='bg-success'>"+siteID_Value+'<span rel="'+siteID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
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
			
			/*********************************Client List***************************************/
			var clientID_list = [];
			$('#com_sel_btn_clientID').click(function(){
				// get all the selected values
				$("input.subclient:hidden").each(function(){
						clientID_list.push($(this).val());				       	
				});

				$("div#sel_clientID input:checked").each(function(index,ele){
					 clientID_key = $(this).val();
					var client_Value = $(this).attr('rel');
					if(clientID_list.indexOf(clientID_key)!=-1){
							//alert( siteID_key + " already in list");
						alert("Client ID already in list");	
					}else{
					$("<p class='bg-success'>"+client_Value+'<span rel="'+clientID_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
							+'<input type="hidden" class="sel-component subclient" name="client_ids[]" value="'+clientID_key+'"/>'
							+"</p>").appendTo("#selected_clientID");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");

			   });
			});
		   
		    $("#selected_clientID").on("click",".glyphicon-trash",function(){
				var ids = $(this).attr("rel");
				
				clientID_list = jQuery.grep(clientID_list, function( n ) {
				  return ( n !== ids );
				});
				$(this).parent("p").remove();
			});
			
			/*********************************Client List***************************************/
        });
    </script>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
