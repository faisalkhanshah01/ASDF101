<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>    
	
	<?php
		// echo "<pre>";
		// print_r($client_data);
		// die();
	?>
	
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
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
					<span>EDIT ASSIGN CLIENT</span>
				</div>
				<div class="row">
					<div class="col-md-12" style="margin-top:15px; margin-bottom:15px;">
						<a href='<?php echo base_url(); ?>assign_client_controller/assign_client' class="btn btn-primary pull-right">Back</a>
					</div>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="productForm"'); ?>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label">Search Client User</label>
							<div class="col-md-10">
								<input type="text" id="search_tool_siteID_client" name="search_tool_client_user" class="form-control tooltip_trigger"  placeholder="Search Client User"/>
							</div>
							<label for="email" class="col-md-2 control-label">Select Client User</label>
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-5">
										<?php if(!isset($assign_client)){ ?>
										<div class="component-container search-clientUser" id='sel_clientUser'></div>
										<?php }else{ ?>
											<div class="component-container search-clientUser" id='sel_clientUser' style="height: 300px !important;">
											<?php
											if($assign_client != ''){
											$count = 1;
											foreach($assign_client as $clientUser){
												if($count %2 == 0){
													$class= "";
												}else{
													$class= "alert-info";
												}
											?>
												<p class="<?php echo $class; ?>">
	<?php echo $count ." | ". $clientUser['client_name'] ." (".$clientUser['client_district'] .", ". $clientUser['client_circle'] . ")" ; ?>
														<input class="pull-right" type="checkbox" name="clientUser[]" id="chk_<?php echo $clientUser['client_id']; ?>" value="<?php echo $clientUser['client_id']; ?>" rel="<?php echo $clientUser['client_name'] ." (".$clientUser['client_district'] .", ". $clientUser['client_circle'] . ")" ; ?>" />
												</p>
										<?php $count++;	} }  ?>
											</div>
										<?php } ?>
									</div>
									<div class="col-md-2" align="center">
										<button id="com_sel_btn_clientUser" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
										<div class="component-container component-clientUser" id="selected_clientUser" style="height: 300px !important;">
											<?php
												if(!empty($client_user_name)){
													foreach($client_user_name as $val){
											 ?>
												<p class='bg-success'><?php echo $val['client_name'] ." (".$val['client_district'] .", ". $val['client_circle'] . ")" ; ?>
													<span rel="<?php echo $val['client_name'] ." (".$val['client_district'] .", ". $val['client_circle'] . ")" ; ?>" class="pull-right text-danger  glyphicon glyphicon-trash">
													</span>
													<input type="hidden" class="sel-component job" name="clientUser[]" value="<?php echo $val['client_id']; ?>"/>
															</p>
											<?php } } ?>
										</div>
									</div>
								</div>
								<?php echo form_error('selected_jobCardNo'); ?>  
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Client List</label>
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-5"> 
										<div class="component-container search-client" id='sel_client' style="height: 150px !important;">
											<?php
											if($client_list != ''){
												foreach($client_list as $key=>$value){
												?>
												<p>
													<?php echo $value; ?>
													<input class="pull-right" type="checkbox" name="client_ids[]" id="<?php echo "chk_".$key; ?>" value="<?php echo $key; ?>" rel="<?php echo $value; ?>" />
												</p>
											<?php } }else { ?> 
												<p></p>
											<?php }  ?> 
											
										</div> 
									</div>
									<div class="col-md-2" align="center">
										<button id="com_sel_btn_client" class="btn" type="button" style="margin-top:50px;"> >> </button>
									</div>
									<div class="col-md-5" >
										<div class="component-container component-client" id="selected_client" style="height: 150px !important;">
											<?php 
											if(!empty($client_list_Name)){
												foreach($client_list_Name as $value){     ?>
													<p class='bg-success'><?php echo $value['upro_first_name'] ." ". $value['upro_last_name']; ?><span rel="<?php echo $value['upro_first_name'] ." ". $value['upro_last_name']; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
													<input type="hidden" class="sel-component ins" name="client_ids[]" value="<?php echo $value['upro_uacc_fk']; ?>"/>
													</p>
											<?php		
											} }
											
											?>
										</div>
									</div>
								</div>
								 <?php echo form_error('selected_client'); ?>
							</div>
						</div>
						<div class="form-group">
								<label for="status" class="col-md-2 control-label">Status</label>
								<div class="col-md-10">
									<select  id="status" name="status" class="form-control tooltip_trigger" required>
									<option selected value=""> - Status - </option>
										
										<option <?php echo ($client_data['status'] == 'Active')? 'Selected' : '' ; ?> value="Active">Active</option>
										<option <?php echo ($client_data['status'] == 'Inactive')? 'Selected' : '' ; ?> value="Inactive">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-6 col-md-6">
								<input type="submit" value="Update" name="update_assignInspector" class="btn btn-primary" id="update_assignInspector"  />
							</div>
						</div>
						<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
