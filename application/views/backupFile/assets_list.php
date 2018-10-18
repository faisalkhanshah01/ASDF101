<?php $this->load->view('includes/header'); ?> 
	<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 

	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
<style>
.hover_class:hover {
	background-color: <?php $_SESSION['color_code']; ?> !important;
}
</style>
<script>
		$(document).ready(function(){	
			/* *********** search_tool_expected_result *********************** */
			$("#search_tool_expected_result").keyup(function(e){
				var query=$(this).val();
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get( "http://arresto.in/kare/manage_kare/ajax_get_expected_result",
						{ 'search' : value },
						function(data){
						$(".search-expectedResult").html(data);	 
					});
			});
		});	
		$(document).on("click", ".search-expectedResult",":checkbox",function(){
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}
		});
			
			//$("#com_sel_btn_subAssets").click(function(){
		$(document).on("click", "#com_sel_btn_expectedResult", function(){
				// get all the selected values
				$("input.subresult:hidden").each(function(){
					sub_result_list.push($(this).val());				       	
				});

				$("div#sel_expectedResult input:checked").each(function(index,ele){
					
					 subResult_key = $(this).val();
					var subResult_Value = $(this).attr('rel');
					
					/*if(sub_result_list.indexOf(subResult_key)!=-1){
						alert( subResult_key + " already in list");	
					}else{
						alert(subResult_Value);*/
						$("<p class='bg-success'>"+subResult_Value+'<span rel="'+subResult_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component sub_result" name="expectedResult[]" value="'+subResult_key+'"/>'
						+"</p>").appendTo("#selected_expectedResult");
					//}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
		});
		
	</script>	
	<script>
		$(document).ready(function(){	
			$("#search_tool_observations").keyup(function(e){
				var query=$(this).val();
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get( "http://arresto.in/kare/manage_kare/ajax_get_observations",
						{ 'search' : value },
						function(data){
						$(".search-observation").html(data);	 
					});
			});
			
			$(document).on("click", ".search-observation",":checkbox",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			
			$(document).on("click", "#com_sel_btn_observation", function(){
				$("input.subobservation:hidden").each(function(){
					sub_observation_list.push($(this).val());				       	
				});

				$("div#sel_observation input:checked").each(function(index,ele){
					 subObservation_key = $(this).val();
					var subObservation_Value = $(this).attr('rel');
					
					$("<p class='bg-success'>"+subObservation_Value+'<span rel="'+subObservation_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component subobservation" name="observation[]" value="'+subObservation_key+'"/>'
					+"</p>").appendTo("#selected_observation");
					
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
			});
		});	
	</script>
	<div class="row">
	<div class="col-md-12">
	<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
	  <li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab" class="hover_class">ADD ASSET DATA</a></li>
	  <li role="presentation"><a href="<?php echo $base_url;?>Manage_kare/download_asset_sample" class="hover_class">DOWNLOAD SAMPLE ASSET EXCEL</a></li>
	  <li role="presentation"><a href="<?php echo $base_url;?>manage_kare/assets_search" class="hover_class">VIEW  ASSET LIST</a></li>
	</ul>

	<!-- Tab panels -->
	<div class="tab-content">
	  <div role="tabpanel" class="tab-pane active" id="mdata_form">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-md-8">
						<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="componentForm"'); ?>
							<legend class="home-heading">ADD ASSETS</legend>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Assets Code</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="product_code" name="product_code" 
									value="<?php echo set_value('product_code');?>" required />
									<?php echo form_error('product_code'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Description</label>
								<div class="col-md-10">
									<textarea id="product_description" name="product_description"  class="form-control tooltip_trigger"  required><?php echo set_value('product_description');?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Upload Image</label>
								<div class="col-md-10">
									<input type="file" class="form-control" id="product_image" name="product_image" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Add Sub Assets </label>	
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-6"> 
										<input type="text" id="search_tool_sub_assets" name="search_tool_sub_assets" class="form-control tooltip_trigger"  placeholder="Search sub assets"/> 
										</div>
									</div>
									<div class="row">
										<div class="col-md-6"> 
											<div class="component-container search-subAssets form-control" id='sel_subAssets' style="height: 150px;border: 1px solid #CCC;">
												<?php if($sub_assets_list !=''){
													foreach($sub_assets_list as $sub_assets){ 
												?>
												<p><?php echo $sub_assets; ?>
												<input class="pull-right" type="checkbox" name="sub_assets[]" id="<?php echo "chk_".$sub_assets; ?>" value="<?php echo $sub_assets; ?>" /></p>
												<?php } } ?> 
											</div> 
										</div>
										<div class="col-md-1">
											<button id="com_sel_btn_subAssets" class="btn" type="button" style="margin-top:50px;"> >> </button>
										</div>
										<div class="col-md-5" >
											<div class="component-container form-control" id="selected_subAssets" style="height: 150px;margin-left:20px;"></div>
										</div>
									</div> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">UOM</label>
								<div class="col-md-10">
									<select id='product_uom' class='form-control' name="product_uom">
										<option value=''> - Select UOM Type- </option>
										<?php 
											$data = array('mtr' => 'MTR', 'nos' =>'NOS');
											foreach($data as $id=>$val){
												echo "<option value='".$id."'>".$val."</option>";
											}
										?>
									</select>
									<?php echo form_error('product_uom'); ?>  
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Type of Inspection</label>
								<div class="col-md-10">
									<select  id="product_inspectiontype" name="product_inspectiontype"  class="form-control tooltip_trigger" required>
										<option value=""> - Select Inspection Type - </option>
											<?php
											if(!empty($inspection)){
												foreach($inspection as $insKey=>$insValue){
													echo "<option value='".$insKey."'>".$insValue."</option>";
												}
											}
											?>
									</select>
									<?php echo form_error('product_inspectiontype'); ?>  
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Expected Result </label>	
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-6"> 
										<input type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="component-container search-expectedResult form-control" id='sel_expectedResult' style="height: 150px;border: 1px solid #CCC;">
												<?php 
												if(!empty($result)){
												foreach($result as $resultKey=>$resultValue){ 
												?>
												<p><?php echo $resultValue; ?>
												<input class="pull-right" type="checkbox" name="product_expectedresult[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultKey; ?>" rel="<?php echo $resultValue; ?>" /></p>
												<?php } } ?> 
											</div> 
										</div>
										<div class="col-md-1">
											<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
										</div>
										<div class="col-md-5" >
											<div class="component-container form-control" id="selected_expectedResult" style="height: 150px;margin-left:20px;">
											</div>
										</div>
									</div> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Observations</label>	
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-6"> 
										<input type="text" id="search_tool_observations" name="search_tool_observations" class="form-control tooltip_trigger"  placeholder="Search observations"/> 
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="component-container search-observation" id='sel_observation' style="border: 1px solid #CCC;">
												<?php if(is_array($observation)){
													foreach($observation as $obsKey=>$obsValue){
												?>
												<p><?php echo $obsValue; ?>
												<input class="pull-right" type="checkbox" name="observation[]" id="<?php echo "chk_".$obsKey; ?>" value="<?php echo $obsKey; ?>" rel="<?php echo $obsValue; ?>" /></p>
												<?php } } ?> 
											</div> 
										</div>
										<div class="col-md-1">
											<button id="com_sel_btn_observation" class="btn" type="button" style="margin-top:50px;"> >> </button>
										</div>
										<div class="col-md-5" >
											<div class="component-container" id="selected_observation" style="margin-left:20px;"></div>
										</div>
									</div> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Repair</label>
								<div class="col-md-10">
									<span class="col-md-3">
										<input  type="radio" id="product_repair" name="product_repair" value="yes" <?php echo set_checkbox('product_repair','yes',true);?> /> Yes
									</span>
									<input type="radio" id="product_repair" name="product_repair" value="no"  <?php echo set_checkbox('product_repair','no');?>/> No
									<?php echo form_error('product_repair'); ?>  
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Geo Fancing</label>
								<div class="col-md-10">
									<span class="col-md-3">
										<input  type="radio" id="geo_fancing" name="geo_fancing" value="yes" <?php echo set_checkbox('geo_fancing','yes',true);?> /> Yes
									</span>
									<input type="radio" id="geo_fancing" name="geo_fancing" value="no"  <?php echo set_checkbox('geo_fancing','no');?>/> No
									<?php echo form_error('geo_fancing'); ?>  
								</div>
							</div>
                                                        
                                                        <div class="form-group">
								<label for="email" class="col-md-2 control-label">Work Permit</label>
								<div class="col-md-10">
									<span class="col-md-3">
										<input  type="radio" id="work_permit" name="work_permit" value="yes" <?php echo set_checkbox('work_permit','yes',true);?> /> Yes
									</span>
									<input type="radio" id="work_permit" name="work_permit" value="no"  <?php echo set_checkbox('work_permit','no');?>/> No
									<?php echo form_error('work_permit'); ?>  
								</div>
							</div>
                                                        
							<div class="form-group">
									<label for="email" class="col-md-2 control-label">Frequency of Asset Series</label>
									<div class="col-md-10">
											<input type="number" class="form-control" id="frequency_asset" name="frequency_asset" 
											value="<?php echo set_value('component_frequency_asset');?>" min="1" max="24" />
									</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="product_category_status">Knowledgetree Status</label>
								<div class="col-md-10">
									<select class="form-control" id="infonet_status_status" name="infonet_status_status" required="required">
										<option value=""> - Select Status - </option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Status</label>
								<div class="col-md-10">
									<select  id="status" name="status" class="form-control tooltip_trigger" required>
										<option selected value=""> - Status - </option>
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-offset-6 col-md-8">
									<input type="submit" name="save_assets" class="btn btn-primary" id="submit" value="SAVE" />
								</div>
							</div>

						<!--</form>-->
							<?php echo form_close();?>
					</div>

					<div class="col-md-4">
							<?php echo form_open_multipart($base_url.'manage_kare/import_assets_list', 'class="form-horizontal"'); ?>
							<legend class="home-heading">IMPORT DATA FROM XLS/CSV</legend>
								<div class="form-group">
								<label for="email" class="col-md-4 control-label">Upload Xls FIle</label>
								<div class="col-md-8">
									<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
									<?php echo form_error('file_upload'); ?>    
								</div>
							</div>
							
							
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="import_assets_list" class="btn btn-primary" id="import_assets_list" value="Uplaod XLS" />
								</div>
							</div>
							<?php echo form_close();?>
						
					</div>
				</div>
			</div>
		</div></div>
		</div>
	</div>
	<!--<div class="row" id="bookmark">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>ASSETS LIST</span>
				</div>
				<div class="panel-body">
					<table class="table table-bordered" id="asset_table">
						<thead>
							<th>Action</th>
							<th>Assets Code</th><th>Description</th><th>Image</th><th>UOM</th>
							<th>Inspection Type</th><th>Expected Result</th><th>Observations</th><th>Repair</th><th>Infonet Status</th><th>Status</th>
							<th> Add Featured Image</th>
						</thead>   
						<tbody>
						</tbody> 
					</table>
				</div>
			</div>
		</div>
	</div>-->
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/scripts'); ?>