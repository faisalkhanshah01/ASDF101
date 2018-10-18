<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>    

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
					<span>ASSIGN CLIENT</span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($base_url.'assign_client_controller/insert_assignClient/', 'class="form-horizontal" id="productForm"'); ?>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label">Search Client User</label>
							<div class="col-md-10">
								<input type="text" id="search_tool_client_user" name="search_tool_client_user" class="form-control tooltip_trigger"  placeholder="Search Client User"/>
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
														
													<!--	<input class="pull-right" type="checkbox" name="clientUser[]" id="chk_<?php // echo $clientUser['client_id']; ?>" value="<?php // echo $clientUser['client_name'] ." (".$clientUser['client_district'] .", ". $clientUser['client_circle'] . ")" ; ?>" rel="<?php // echo $clientUser['client_name'] ." (".$clientUser['client_district'] .", ". $clientUser['client_circle'] . ")" ; ?>" /> -->
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
												<p><?php echo $value; ?>
												<input class="pull-right" type="checkbox" name="client_ids[]" id="<?php echo "chk_".$key; ?>" value="<?php echo $key; ?>" rel="<?php echo $value; ?>" /></p>
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
										<option <?php echo (isset($assignclient) && $client_status == 'Active')? 'Selected' : '' ; ?> value="Active">Active</option>
										<option <?php echo (isset($assignclient) && $client_status == 'Inactive')? 'Selected' : '' ; ?> value="Inactive">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-6 col-md-6">
								<input type="submit" name="save_assignInspector" class="btn btn-primary" id="save_assignInspector"  />
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
					<span>Assigned Client List</span>
				</div>
			<div class="panel-body">
				<div>
				<!--	<a href="<?php // echo $base_url;?>assign_client_controller/approved_rejected_list" class="btn btn-warning pull-left" style="margin-bottom:15px;">Approved / Rejected Site ID's</a> -->
				</div>
				
			<table id="inspector_table" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th><center>Action</center></th>
						<th><center>S.No</th>
						<th><center>Client Users</center></th>
						<th><center>Client Names</center></th>
						<th><center>Status</center></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($client_datas)){
						$sno = 1;
						foreach($client_datas as $client_val){
					?>
						<tr>
							<td><center><a href="<?php echo $base_url."assign_client_controller/assign_client_edit/".$client_val['id']; ?>">
									<span class="glyphicon glyphicon-pencil"></span></a>
								<a class="delete" style="margin:0px 0px 0px 30px;" href="<?php echo $base_url."assign_client_controller/delete_client/".$client_val['id']; ?>">
									<span class="glyphicon glyphicon-trash error"></span>
								</a>
							</td>
							<td><center><?php echo $sno; ?></td>
							<td>
								<?php foreach($client_val['client_names'] as $val){ ?>
									<p><?php echo $val; ?></p>
								<?php } ?>
							</td>
							<td>
								<?php foreach($client_val['user_name'] as $uval){ ?>
									<p><?php echo $uval; ?></p>
								<?php } ?>
							</td>
							<td class="text-center">
								<?php echo $client_val['status']; ?>
							</td>
						</tr>
					<?php $sno++; } } ?>
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
