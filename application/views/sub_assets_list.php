<?php $this->load->view('includes/header'); ?> 

	<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
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
					$.get(base_url+"manage_kare/ajax_get_expected_result",
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
					$.get( base_url+"manage_kare/ajax_get_observations",
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

	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
				<li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["sub_asset_data"]['description'] !='' ){ echo $lang["sub_asset_data"]['description']; }else{ echo "SUB ASSET DATA"; } ?></a></li>
				<li role="presentation"><a href="<?php echo $base_url;?>subassets_kare/subassets_search"><?php if( $lang["view_sub_asset_list"]['description'] !='' ){ echo $lang["view_sub_asset_list"]['description']; }else{ echo "VIEW SUB ASSET LIST"; } ?></a></li>
                                <li role="presentation" style="float:right"><a href="<?php echo $base_url;?>manage_kare/download_subAsset_sample"><?php if( $lang["download_sample_sub_asset_data"]['description'] !='' ){ echo $lang["download_sample_sub_asset_data"]['description']; }else{ echo "DOWNLOAD SAMPLE SUB ASSET DATA"; } ?></a></li>
			</ul>
	<!-- Tab panes -->
		<div class="tab-content"><div role="tabpanel" class="tab-pane active" id="mdata_form">
		<div class="row">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-md-8">
					<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="subassetsForm"'); ?>
						<legend  class="home-heading"><?php if( $lang["add_sub_assets"]['description'] !='' ){ echo $lang["add_sub_assets"]['description']; }else{ echo "ADD SUB ASSETS"; } ?></legend>
						<div class="form-group">
							<div class="col-md-10">
                                 <input type="hidden" class="form-control" id="speci_file_id" name="speci_file_id" 
								value="<?php print key($_SESSION['flexi_auth']['group']);?>" required />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["sub_assets_code"]['description'] !='' ){ echo $lang["sub_assets_code"]['description']; }else{ echo "Sub Assets Code"; } ?></label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="sub_assets_code" name="sub_assets_code" 
								value="<?php echo set_value('sub_assets_code');?>" required />
								<?php echo form_error('sub_assets_code'); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["description"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo "Description"; } ?></label>
							<div class="col-md-10">
								<textarea id="sub_assets_description" name="sub_assets_description"  class="form-control tooltip_trigger"  required><?php echo set_value('sub_assets_description');?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["upload_image"]['description'] !='' ){ echo $lang["upload_image"]['description']; }else{ echo "Upload Image"; } ?></label>
							<div class="col-md-10">
								<input type="file" class="form-control" id="assets_image" name="assets_image" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["uom"]['description'] !='' ){ echo $lang["uom"]['description']; }else{ echo "UOM"; } ?></label>
							<div class="col-md-10">
								<select id='subasset_uom' class='form-control' name="subasset_uom">
									<option value=''> - Select UOM Type - </option>
									<?php 
										$data = array('mtr' => 'MTR', 'nos' =>'NOS');
										foreach($data as $id=>$val){
											echo "<option value='".$id."'>".$val."</option>";
										}
									?>
								</select>
								<?php  echo form_error('subasset_uom'); ?>  
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["type_of_inspection"]['description'] !='' ){ echo $lang["type_of_inspection"]['description']; }else{ echo "Type of Inspection"; } ?></label>
							<div class="col-md-10">
								<select  id="subasset_inspectiontype" name="subasset_inspectiontype"  class="form-control tooltip_trigger" required>
									<option value=""> - Select Inspection Type - </option>
										<?php
										if(!empty($inspection)){
											foreach($inspection as $insKey=>$insValue){
												echo "<option value='".$insKey."'>".$insValue."</option>";
											}
										}
										?>
								</select>
								<?php echo form_error('subasset_inspectiontype'); ?>  
							</div>
						</div>
						
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["expected_results"]['description'] !='' ){ echo $lang["expected_results"]['description']; }else{ echo "Expected Result"; } ?> </label>	
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-6"> 
									<input type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="component-container search-expectedResult" id='sel_expectedResult'>
											<?php 
											if(is_array($result)){
											foreach($result as $resultKey=>$resultValue){ 
											?>
											<p><?php echo $resultValue; ?>
											<input class="pull-right" type="checkbox" name="expectedresult[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultKey; ?>" rel="<?php echo $resultValue; ?>" /></p>
											<?php } } ?> 
										</div> 
									</div>
									<div class="col-md-1">
										<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
										<div class="component-container" id="selected_expectedResult" style="margin-left:20px;"></div>
									</div>
								</div> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-md-2 control-label"><?php if( $lang["observations"]['description'] !='' ){ echo $lang["observations"]['description']; }else{ echo "Observations"; } ?></label>	
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-6"> 
									<input type="text" id="search_tool_observations" name="search_tool_observations" class="form-control tooltip_trigger"  placeholder="Search observations"/> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="component-container search-observation" id='sel_observation'>
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
							<label for="email" class="col-md-2 control-label"><?php if( $lang["repair"]['description'] !='' ){ echo $lang["repair"]['description']; }else{ echo "Repair"; } ?></label>
							<div class="col-md-10">
								<span class="col-md-3">
									<input  type="radio" id="subasset_repair" name="subasset_repair" value="yes" <?php echo set_checkbox('subasset_repair','yes',true);?> /> Yes
								</span>
								<input type="radio" id="subasset_repair" name="subasset_repair" value="no"  <?php echo set_checkbox('subasset_repair','no');?>/> No
								<?php echo form_error('subasset_repair'); ?>  
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
							<div class="col-md-10">
								<select  id="sub_assets_status" name="sub_assets_status" class="form-control tooltip_trigger" required>
								<option selected value=""> - Status - </option>
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							 <?php echo form_error('sub_assets_status'); ?> 
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-6 col-md-8">
								<input type="submit" name="save_sub_assets" class="btn btn-primary" id="submit" value="SAVE" />
							</div>
						</div>

					<!--</form>-->
						<?php echo form_close();?>
				</div>
        
				<div class="col-md-4">
					<?php echo form_open_multipart($base_url.'subassets_kare/import_sub_assets_list', 'class="form-horizontal"'); ?>
					<legend class="home-heading"><?php if( $lang["import_data_from_xls_csv_file"]['description'] !='' ){ echo $lang["import_data_from_xls_csv_file"]['description']; }else{ echo "Import Data From XLS/CSV File"; } ?> </legend>
						<div class="form-group">
						<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_xls_file"]['description'] !='' ){ echo $lang["upload_xls_file"]['description']; }else{ echo "Upload Xls FIle"; } ?></label>
						<div class="col-md-8">
							<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
							<?php echo form_error('file_upload'); ?>    
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<input type="submit" name="import_sub_assets_list" class="btn btn-primary" id="import_sub_assets_list" value="Uplaod XLS" />
						</div>
					</div>
				<?php echo form_close();?>
				</div>
			</div>
		</div>
		</div></div></div></div>
	</div>

	<!--<div class="row" id="bookmark">
		<div class="panel panel-default">
			<div class="panel-heading home-heading">
				<span >SUB ASSETS LIST</span>
			</div>
			<div class="panel-body">
				<?php //if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
				<div class="col-md-offset-10">
					<a href="<?php //echo $base_url;?>manage_kare/reset_table_data/subAssets" class="btn btn-danger delete">Reset Sub Asset Table</a>
					</br></br>
				</div>
				<?php //} ?>
				<table class="table table-bordered" id="sub_assets_table">
					<thead>
					<th>Action</th><th>Sub Assets Code</th><th>Description</th><th>Image</th>
					<th>UOM</th><th>Inspection Type</th><th>Expected Result</th><th>Observation</th><th>Repair</th><th>Status</th><th>Featured Image</th>
					</thead>
				</table>
			</div>
		</div>
	</div>-->
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/scripts'); ?>