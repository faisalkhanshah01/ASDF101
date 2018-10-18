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
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>EDIT SUB ASSETS </span>
				</div>
				<div class="panel-body">
				<?php echo form_open_multipart(current_url() ,'class="form-horizontal"'); ?>
					<div class="form-group">
						<div class="col-md-10">
							 <input type="hidden" class="form-control" id="speci_file_id" name="speci_file_id" 
							value="<?php print key($_SESSION['flexi_auth']['group']);?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Sub Assets Code</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="sub_assets_code" name="sub_assets_code" 
							value="<?php echo set_value('sub_assets_code',$sub_assets['sub_assets_code']);?>" required />
							<?php echo form_error('sub_assets_code'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Description</label>
						<div class="col-md-8">
							<textarea id="sub_assets_description" name="sub_assets_description"  class="form-control tooltip_trigger" ><?php echo set_value('sub_assets_description',$sub_assets['sub_assets_description']);?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Upload Image</label>
						<div class="col-md-8">
							<input type="file" class="form-control" id="assets_image" name="assets_image" />
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">UOM</label>
						<div class="col-md-8">
							<select id='subasset_uom' class='form-control' name="subasset_uom">
								<option value=''> - Select UOM Type - </option>
								<?php 
									$data = array('mtr' => 'Meters', 'nos' =>'Numbers');
									foreach($data as $id=>$val){
										$selected = ($sub_assets['sub_assets_uom'] == $id)? 'Selected' : '';
										echo "<option ".$selected." value='".$id."'>".$val."</option>";
									}
								?>
							</select>
							<?php  echo form_error('subasset_uom'); ?>  
						</div>
					</div>

					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Type of Inspection</label>
						<div class="col-md-8">
							<select  id="subasset_inspectiontype" name="subasset_inspectiontype"  class="form-control tooltip_trigger" required>
								<option value=""> - Select Inspection Type - </option>
									<?php
									if(!empty($inspection)){
										foreach($inspection as $insKey=>$insValue){
											$selected = ($sub_assets['sub_assets_inspection'] == $insKey)? 'Selected' : '';
											echo "<option ".$selected." value='".$insKey."'>".$insValue."</option>";
										}
									}
									?>
							</select>
							<?php echo form_error('subasset_inspectiontype'); ?>  
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Expected Result </label>	
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-5"> 
								<input type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<div class="component-container search-expectedResult" id='sel_expectedResult'>
										<?php if(!empty($result)){
											foreach($result as $resultKey=>$resultValue){ 
										?>
										<p><?php echo $resultValue; ?>
										<input class="pull-right" type="checkbox" name="expectedResults[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultKey; ?>" rel="<?php echo $resultValue; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5" >
									<div class="component-container" id="selected_expectedResult">
									<?php if(!empty($sub_assets['sub_assets_result'])){
												$excpected_result = json_decode($sub_assets['sub_assets_result'],true);
												foreach($excpected_result as $comp){
													if(is_array($result) && array_key_exists($comp,$result)){ ?>
													
														<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $result[$comp]; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
															<input type="hidden" class="sel-component" name="expectedResult[]" value="<?php echo $comp; ?>"/>
														</p>
											<?php	
													}else{ ?>
														<p id='<?php echo $comp; ?>' class='bg-danger'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
															<input type="hidden" class="sel-component" name="expectedResult[]" value="<?php echo $comp; ?>"/>
														</p>
											<?php	}
												}
											}
									?>
									</div>
								</div>
							</div> 
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Observations</label>	
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-5"> 
								<input type="text" id="search_tool_observations" name="search_tool_observations" class="form-control tooltip_trigger"  placeholder="Search observations"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<div class="component-container search-observation" id='sel_observation'>
										<?php if(is_array($observation)){
												foreach($observation as $obsKey=>$obsValue){
										?>
										<p><?php echo $obsValue; ?>
										<input class="pull-right" type="checkbox" name="observation[]" id="<?php echo "chk_".$obsKey; ?>" value="<?php echo $obsKey; ?>" rel="<?php echo $obsValue; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_observation" class="btn" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5" >
									<div class="component-container" id="selected_observation">
									<?php if(!empty($sub_assets['sub_assets_observation'])){
												$obs_result = json_decode($sub_assets['sub_assets_observation'],true);
												foreach($obs_result as $comp){
													if(is_array($observation) && array_key_exists($comp,$observation)){ ?>
													
														<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $observation[$comp]; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
															<input type="hidden" class="sel-component" name="observation[]" value="<?php echo $comp; ?>"/>
														</p>
											<?php	
													}else{ ?>
														<p id='<?php echo $comp; ?>' class='bg-danger'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
															<input type="hidden" class="sel-component" name="observation[]" value="<?php echo $comp; ?>"/>
														</p>
											<?php	}
												}
											}
									?>
									</div>
								</div>
							</div> 
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Repair</label>
						<div class="col-md-8">
							<span class="col-md-3">
								<input  type="radio" id="subasset_repair" name="subasset_repair" value="yes" <?php echo set_radio_state($sub_assets['sub_assets_repair'],'yes');?> /> Yes
							</span>
							<input type="radio" id="subasset_repair" name="subasset_repair" value="no"  <?php echo set_radio_state($sub_assets['sub_assets_repair'],'no');?>/> No
							<?php echo form_error('subasset_repair'); ?>  
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Status</label>
						<div class="col-md-8">
								<select  id="sub_assets_status" name="sub_assets_status"  class="form-control tooltip_trigger" required>
									<option value="" > - Status -</option>
									<option <?php set_option_state($sub_assets['status'],'Active'); ?> value="Active">Active</option>
									<option <?php set_option_state($sub_assets['status'],'Inactive'); ?> value="Inactive">Inactive</option>
								</select>
							  <?php echo form_error('sub_assets_status'); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-8">
							<input type="submit" name="edit_sub_assets" class="btn btn-primary" id="edit_sub_assets" value="Update" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('subassets_kare/sub_assets_list'); ?>" class="btn btn-default">BACK</a>
						</div>
					</div>

				<!--</form>-->
					<?php echo form_close();?>
				</div>
			</div>
        </div>
	</div>
    

	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
