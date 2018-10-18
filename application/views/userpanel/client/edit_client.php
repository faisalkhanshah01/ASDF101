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
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span ><?php if( $lang["edit_client_dealer"]['description'] !='' ){ echo $lang["edit_client_dealer"]['description']; }else{ echo "Edit Client/Dealer"; } ?></span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($base_url.'Client_kare/update_client', 'class="form-horizontal"', 'id="update_clients"', 'method="post"'); ?>
					
					<?php foreach ($edit as $postt){ ?>
						<input type="hidden" value="<?php echo $postt->client_id; ?>" name="client_id" />
						
							<div class="form-group">
							  <label for="client_name" class="col-md-4 control-label"><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo "Client Name"; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_name; ?>" id="client_name" name="client_name" placeholder="Name" required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="district" class="col-md-4 control-label"><?php if( $lang["district"]['description'] !='' ){ echo $lang["district"]['description']; }else{ echo "District"; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_district; ?>" id="district" name="district" placeholder="Client District" required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="circle" class="col-md-4 control-label"><?php if( $lang["circle"]['description'] !='' ){ echo $lang["circle"]['description']; }else{ echo "Circle"; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_circle; ?>" id="circle" name="circle" placeholder="Client Circle" required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="contactPerson" class="col-md-4 control-label"><?php if( $lang["contact_person"]['description'] !='' ){ echo $lang["contact_person"]['description']; }else{ echo "Contact Person"; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_contactPerson; ?>" id="contactPerson" name="contactPerson" placeholder="Client Contact Person" required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="contactNo" class="col-md-4 control-label"><?php if( $lang["contact"]['description'] !='' ){ echo $lang["contact"]['description']; }else{ echo "Contact No."; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_contactNo; ?>" id="contactNo" name="contactNo" placeholder="Client Contact No." required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="contactPerson_email" class="col-md-4 control-label"><?php if( $lang["contact_person_email"]['description'] !='' ){ echo $lang["contact_person_email"]['description']; }else{ echo "Contact Person Email"; } ?></label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->client_contactPerson_email; ?>" id="contactPerson_email" name="contactPerson_email" placeholder="Client Contact Person Email" required>
							  </div>
							</div>
						
							<div class="form-group">
								<label for="client_type" class="col-md-4 control-label"><?php if( $lang["client_type"]['description'] !='' ){ echo $lang["client_type"]['description']; }else{ echo "Client Type"; } ?></label>
								<div class="col-md-6">
									<select class="form-control" id="client_type" name="client_type" required>
										<option value=""> - Select Status - </option>
										<?php
										if(!empty($client_types)){
											foreach($client_types as $type){
												$selected = ($postt->client_type == $type['id'])? 'Selected' : '' ;
												echo "<option ".$selected." value='".$type['id']."'>".$type['type_name']."</option>";
											}
										}else{
											echo '<option value="0">New Type</option>';
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="mobile" class="col-md-4 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
								<div class="col-md-6">
									<select class="form-control" id="status" name="status" required>
										<option value=""> - Select Status - </option>
										<option value="Active"<?php if(ucfirst(strtolower($postt->client_status)) == "Active") {?> selected <?php }?>>Active</option>
										<option value="Inactive"<?php if(ucfirst(strtolower($postt->client_status)) == "Inactive") {?> selected <?php }?>>Inactive</option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="update_clients" class="btn btn-primary" id="update_clients" value="<?php if( $lang["update"]['description'] !='' ){ echo $lang["update"]['description']; }else{ echo "Update"; } ?>" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $base_url;?>client_kare/client_view"><button class="btn btn-info" type="button"> Back  </button>
								</div>
							</div>
					<?php } ?>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>   
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
