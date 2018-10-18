<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>    
 <?php 
$CI=& get_instance();
$CI->load->model('kare_model');

$inspector_list=$CI->kare_model->get_inspector_name();
$button = "save_assignInspector";
$button_title = "SAVE";
$page_title = "INSERT ASSIGN INSPECTOR";
if(isset($assignInspector)){

	$button = 'edit_assignInspector';
	$button_title = "UPDATE";
	$page_title = "EDIT ASSIGN INSPECTOR";

	if(!empty($inspector_list)){
		$inspector_flip_edit 		= array_flip($inspector_list);
	}
	$jobCard_edit					= $assignInspector['inspector_jobCard'];
	$sms_edit	 					= $assignInspector['inspector_sms'];
	$site_id_edit 					= json_decode($assignInspector['site_id']);
	$inspector_ids_edit 			= json_decode($assignInspector['inspector_ids']);
	$inspector_created_date_edit 	= $assignInspector['inspector_created_date'];
	$inspector_status 				= $assignInspector['status'];
	
	$CI->load->model('SiteId_model');
	$site_list_array=$CI->SiteId_model->ajax_get_siteID_from_sms($sms_edit,$jobCard_edit);
	
	$site_list_edit = array();
	$ins_list_edit = array();
	
	if(is_array($site_list_array)){
		foreach($site_list_array as $siteVal){
			foreach($site_id_edit as $id){
				if($siteVal['siteID_id'] == $id){
					$site_list_edit[] = array($id=>$siteVal['site_id']);
				}
			}
		}
	}
	

	for($j=0; $j<count($inspector_ids_edit); $j++){
		$ins_key = $inspector_ids_edit[$j];
		$ins_value = array_keys($inspector_flip_edit,$inspector_ids_edit[$j]);
		foreach($ins_value as $insValue){
			$ins_list_edit[] = array($ins_key=>$insValue);
		}
	}
}

?>   
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
					<span><?php if( $lang["assign_inspector"]['description'] !='' ){ echo $lang["assign_inspector"]['description']; }else{ echo 'Action'; } ?></span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="productForm"'); ?>
					
					<?php if(!isset($assignInspector)){ ?> 
						<div class="form-group">
							<label for="email" class="col-md-3 control-label"><?php if( $lang["search_job_card"]['description'] !='' ){ echo $lang["search_job_card"]['description']; }else{ echo 'Search Job Card'; } ?></label>
							<div class="col-md-8">
								<input type="text" id="search_tool_jobcard_inspector" name="search_tool_jobcard_inspector" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Site ID."/>
							</div>
							<label for="email" class="col-md-3 control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></label>
							<div class="col-md-8">
								<select id="jc_number" name="jc_number" rel="inspector_jc" class="form-control jc_number">
									<option value="">Select Job Card</option>
									<?php
									foreach($jobcards as $jobValue){ ?>
											<option value='<?php echo $jobValue; ?>'><?php echo $jobValue; ?></option>
									<?php }
									?>
								</select>
								<?php echo form_error('jc_number'); ?>
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="col-md-3 control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></label>
							<div class="col-md-8">
								<select name="sms_number" id="sms_number" rel="inspector" class="form-control sms_number">
									<option value=""> - Select SMS Number - </option>
								</select>
								<?php echo form_error('sms_number'); ?>
							</div>
						</div>
					<?php } else{ ?>
						<div class="form-group">
							<label for="group" class="col-md-3 control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?>:</label>
							<div class="col-md-8">
								<div class="form-control" ><?php echo $jobCard_edit; ?></div>
								<input type="hidden" id="jc_number" class="form-control jc_number" value="<?php echo $jobCard_edit; ?>" name="jc_number" readonly required/>
							</div>
						</div>
							
						<div class="form-group">
							<label for="group" class="col-md-3 control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?>:</label>
							<div class="col-md-8">
							<div class="form-control" ><?php echo $sms_edit; ?></div>
								<input type="hidden" id="sms_number" class="form-control sms_number" value="<?php echo $sms_edit; ?>" name="sms_number" readonly required/>
							</div>
						</div>
					<?php } ?>
						<div class="form-group">
							<label for="email" class="col-md-3 control-label"><?php if( $lang["search_site_id"]['description'] !='' ){ echo $lang["search_site_id"]['description']; }else{ echo 'Search SITE ID'; } ?></label>
							<div class="col-md-8">
								<input type="text" id="search_tool_siteID_inspector" name="search_tool_siteID_inspector" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Site ID."/>
							</div>
							<label for="email" class="col-md-3 control-label"><?php if( $lang["select_site_id"]['description'] !='' ){ echo $lang["select_site_id"]['description']; }else{ echo 'Select SITE ID'; } ?></label>
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5">
										<?php if(!isset($assignInspector)){ ?>
										<div class="component-container search-jobCardNo" id='sel_jobCardNo'></div>
										<?php }else{ ?>
											<div class="component-container search-jobCardNo" id='sel_jobCardNo'>
											<?php
											if(is_array($site_list_array)){
											foreach($site_list_array as $siteVal){ ?>
														<p><?php echo $siteVal['site_id']; ?>
														<input class="pull-right" type="checkbox" name="jobCardNo[]" id="chk_<?php echo $siteVal['site_id']; ?>" value="<?php echo $siteVal['siteID_id']; ?>" rel="<?php echo $siteVal['site_id']; ?>" /></p>
											<?php	} } ?>
											</div>
										<?php } ?>
									</div>
									<div class="col-md-2 text-center">
										<button id="com_sel_btn_jobCard" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
										<div class="component-container component-jobCardNo" id="selected_jobCardNo">
											<?php
											
												if(isset($site_list_edit)){
													foreach($site_list_edit as $val){
													foreach($val as $k=>$v){ ?>
															<p class='bg-success'><?php echo $v; ?><span rel="<?php echo $v; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
															<input type="hidden" class="sel-component job" name="site_id[]" value="<?php echo $k; ?>"/>
															</p>
													<?php		
											} } } ?>
										</div>
									</div>
								</div>
								<?php echo form_error('selected_jobCardNo'); ?>  
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-3 control-label"><?php if( $lang["search_inspector"]['description'] !='' ){ echo $lang["search_inspector"]['description']; }else{ echo 'Search Inspector'; } ?></label>
							<div class="col-md-8">
								<input type="text" id="search_tool_inspector" name="search_tool_inspector" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Inspector."/>
							</div>
							<label for="email" class="col-md-3 control-label"><?php if( $lang["inspector_list"]['description'] !='' ){ echo $lang["inspector_list"]['description']; }else{ echo 'Inspector List'; } ?></label>
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5"> 
										<div class="component-container search-inspector" id='sel_inspector'>
											<?php foreach($inspector_list as $key=>$value){
											?>
											<p><?php echo ucwords($value); ?>
											<input class="pull-right" type="checkbox" name="inspector_ids[]" id="<?php echo "chk_".$key; ?>" value="<?php echo $key; ?>" rel="<?php echo $value; ?>" /></p>
											<?php }?> 
										</div> 
									</div>
									<div class="col-md-2 text-center">
										<button id="com_sel_btn_inspector" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
										<div class="component-container component-inspector" id="selected_inspector">
											<?php 
											if(isset($ins_list_edit)){
											foreach($ins_list_edit as $ins){ 
												foreach($ins as $insk=>$insv){     ?>
													<p class='bg-success'><?php echo $insv; ?><span rel="<?php echo $insk; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
													<input type="hidden" class="sel-component ins" name="inspector_ids[]" value="<?php echo $insk; ?>"/>
													</p>
											<?php		
											} }
											}
											?>
										</div>
									</div>
								</div>
								 <?php echo form_error('selected_inspector'); ?>
							</div>
						</div>
						
						<div class="form-group">
								<label for="email" class="col-md-3 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; } ?></label>
								<div class="col-md-8">
									<select  id="status" name="status" class="form-control tooltip_trigger" required>
									<option selected value=""> - Status - </option>
										
										<option <?php echo (isset($assignInspector) && $inspector_status == 1)? 'Selected' : '' ; ?> value="1">Active</option>
										<option <?php echo (isset($assignInspector) && $inspector_status == 0)? 'Selected' : '' ; ?> value="0">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-3 col-md-8">
								<input type="submit" name="<?php echo $button; ?>" class="btn btn-primary" id="submit" value="<?php echo $button_title; ?>" />
								<?php if(isset($assignInspector)){ ?>
								<a href="<?php echo base_url('manage_kare/assignInspector_list'); ?>" class="btn btn-default pull-right">BACK</a>
								<?php } ?>
							</div>
						</div>
						<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12" >
			 <div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span><?php if( $lang["assigned_inspectors_list"]['description'] !='' ){ echo $lang["assigned_inspectors_list"]['description']; }else{ echo 'Assigned Inspectors List'; } ?></span>
				</div>
				<div class="panel-body">
				<?php if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
					<div>
						<a href="<?php echo $base_url;?>inspector_inspection/approved_rejected_list" class="btn btn-warning pull-left"><?php if( $lang["approved__rejected_site_ids"]['description'] !='' ){ echo $lang["approved__rejected_site_ids"]['description']; }else{ echo "Approved / Rejected Site ID's"; } ?></a>
						<a href="<?php echo $base_url;?>manage_kare/reset_table_data/inspector" class="btn btn-danger pull-right delete"><?php if( $lang["reset_inspector_table"]['description'] !='' ){ echo $lang["reset_inspector_table"]['description']; }else{ echo 'Reset Inspector Table'; } ?></a>
						</br></br>
					</div>
				<?php } ?>
				<table id="inspector_table" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th><center><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo 'Action'; } ?></center></th>
						<th><center><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S. No.'; } ?></th>
						<th><center><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></center></th>
						<th><center><?php if( $lang["sms_no"]['description'] !='' ){ echo $lang["sms_no"]['description']; }else{ echo 'SMS No'; } ?></center></th>
						<th><center><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo 'Site ID'; } ?></center></th>
						<th><center><?php if( $lang["inspector_names"]['description'] !='' ){ echo $lang["inspector_names"]['description']; }else{ echo 'Inspector Names'; } ?></center></th>
						<th><center><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; } ?></center></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sno=0;
					if(!empty($assignInspector_list)){

						if(!empty($inspector_list)){
							$inspector_flip = array_flip($inspector_list);
						}
						foreach($assignInspector_list as $list){
							$id 					= $list['id'];
							$inspector_jobCard 		= $list['inspector_jobCard'];
							$inspector_sms 			= $list['inspector_sms'];
							$site_id 				= json_decode($list['site_id']);
							$inspector_ids 			= json_decode($list['inspector_ids']);
							$inspector_created_date = $list['inspector_created_date'];
							$inspector_status 		= $list['status'];
							
							$site_list = array();
							$ins_list = array();
							
							$CI->load->model('SiteId_model');
							$site_lists=$CI->SiteId_model->ajax_get_siteID_from_sms($inspector_sms,$inspector_jobCard);
							
							if(is_array($site_lists)){
								foreach($site_lists as $siteV){
									foreach($site_id as $id){
										if($siteV['siteID_id'] == $id){
											$site_list[] = $siteV['site_id'];
										}
									}
								}
							}
							if(is_array($ins_list)){
								foreach($inspector_ids as $insID){
									$ins_lists = array_keys($inspector_flip,$insID);
									if(!empty($ins_lists)){
										$ins_list[] = $ins_lists[0];
									}
								}
							}
						$sno++;
						?>
						<tr>
							<td><center><a href="<?php echo $base_url."manage_kare/assignInspector_list/".$list['id']; ?>">
									<span class="glyphicon glyphicon-pencil"></span></a>
								<a class="delete" style="margin:0px 0px 0px 30px;" href="<?php echo $base_url."manage_kare/delete_inspector/".$list['id']; ?>">
									<span class="glyphicon glyphicon-trash error"></span>
								</a>
							</td>
							<td><center><?php echo $sno; ?></td>
							<td><?php echo $inspector_jobCard; ?></td>
							<td><?php echo $inspector_sms; ?></td>
							<td>
								<table class="table table-hover">
									<?php
								if(is_array($site_list)){
									foreach($site_list as $site){ 
									?>
										<tr>
											<td><center><?php echo $site; ?></td>
										</tr>
									<?php }
								}
								?>
								</table>
							</td>
							<td>
								<table class="table table-hover">
									<?php
								if(is_array($ins_list)){
									foreach($ins_list as $insV){ ?>
										<tr>
											<td><center><?php echo $insV; ?></td>
										</tr>
									<?php }
								}
								?>
								</table>
							</td>
							<td><center><?php echo ($inspector_status ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>'; ?></center></td>
						</tr> 
					<?php }
					} // end of if
					?>
				</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
