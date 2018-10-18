<?php $this->load->view('includes/header_new'); ?> 

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
<?php 
//error_reporting(E_ALL & ~E_WARNING);

$CI=& get_instance();
$CI->load->model('kare_model');
$component_list=array_column($CI->kare_model->get_components_list(),'component_code');
$product_list=array_column($CI->kare_model->get_products_list('product_code'),'product_code');
$product_components=array_column($CI->kare_model->get_products_list('product_components'),'product_components');


foreach($product_components as $key=>$val){
	if($val ==''){
		$product_components[$key] = 0;
	}
}

$item_list=array($product_list,$product_components);
//print_r($component_list);die;
//$dealer_list=$CI->kare_model->get_dealer_list('client_id,client_name');
$duplicate_jobSMS_list=$CI->kare_model->duplicate_jobSMS_list();
?>
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
					<span>ADD CERTIFICATE</span>
				</div>
				<div class="panel-body">
                                <form class="form" id ="client_data"> 
								<div class="form-group">
                                    <label for="status" class="col-md-2 control-label" style="margin-top:10px">Type</label>
                                    <div class="col-md-10">
                                        <select  id="clientType" name="clientType" class="form-control tooltip_trigger"  style="margin-top:10px">
                                                <option selected value="1"> - Select Option - </option>	
												<option  value="8">Assets</option>
												<option  value="7">Asset Series</option>                                               
                                        </select>
                                    </div>
                                </div>
								<div class="form-group col-md-12"><!--- Start to Asset 11--->
									<label for="email" class="control-label">Asset </label>	
									<input style="width:40.5%;" type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
									<div class="row">
										<div class="col-md-5">
											<div class="component-container search-expectedResult form-control" id='sel_expectedResult' style="height: 150px;border: 1px solid #CCC;">
												<?php 
												if(!empty($component_list)){
													foreach($component_list as $resultKey=>$resultValue){ 
												?>
												<p><?php echo $resultValue; ?>
												<input  class="pull-right" type="checkbox" name="assetSerise[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultValue; ?>" rel="<?php echo $resultValue; ?>" /></p>
												<?php } } ?> 
											</div> 
										</div>
										<div class="col-md-2">
											<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
										</div>
										<div class="col-md-5" >
											<div class="component-container form-control" id="selected_expectedResult" style="height: 150px;margin-left:20px;">
											</div>
										</div>
									</div> 
								 </div><!--- End to Asset 11--->
								
								<div class="form-group col-md-12"><!--- Start to Asset Series 11--->
								  <label for="email" class=" control-label">Asset Series </label>
								  <input style="width:40.5%;" type="text" id="search_assetSeries" name="search_tool" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Asset Series"/>
								  <div class="row">
									<div class="col-md-5">
										<div class="search-container" id='sel_component'>
											<?php 
												$mitem = $item_list[0];
												$product_component = $item_list[1];
											for($i=0; $i<count($item_list[0]);$i++){
											// foreach($item_list as $mitem){
												?>
												<p id="<?php echo $i; ?>"><?php echo $mitem[$i]; ?>
													<input class="pull-right" type="checkbox" name="components[]" rel='<?php echo $product_component[$i]; ?>' value="<?php echo $mitem[$i]; ?>" />
												</p>
											<?php }?>
										</div>
									</div>
									<div class="col-md-2">
									  <button id="com_sel_btn_master" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
									  <div class="component-container" id="product_component_master" data-name='mdata_insert'>
									   <?php if($item['mdata_item_series']!='')
											 {
											$dbitem=json_decode($item['mdata_item_series']);
											foreach($dbitem as $x){ 
											echo $respose="<p class='bg-success'>".$x.'<span rel="'.$x.'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'."<input type='hidden' class='pull-right' name='product_components[]' value='$x' />"."</p>";
											}
										}
											?>
									   </div>
									</div>
								  </div>
								</div><!--- End to Asset Series 11--->

                            </form>    
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


	$(document).ready(function(e) {
		$("#search_tool_expected_result").keyup(function(e){
			 var query=$(this).val();
			 if(1){
				 var searchStr="search="+query;
				 $.get("<?php echo base_url('manage_kare/ajax_get_components') ?>",searchStr,function(data){
					 alert(data);
					 $(".sel_expectedResult").html(data);	 
				 });
			 }
		})
	});
    </script>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts_data'); ?> 
