<?php $this->load->view('includes/header'); ?>
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
					<span >Add Client/Dealer</span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($base_url.'Client_kare/add_clients', 'class="form-horizontal"', 'id="add_clients"', 'method="post"'); ?>
						<div class="form-group">
						  <label for="client_name" class="col-md-4 control-label">Client Name</label>
						  <div class="col-md-6">
							<input type="text" class="form-control" value="" id="client_name" name="client_name" placeholder="Name" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="district" class="col-md-4 control-label">District</label>
						  <div class="col-md-6">
							<input type="text" class="form-control" value="" id="district" name="district" placeholder="Client District" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="circle" class="col-md-4 control-label">Circle</label>
						  <div class="col-md-6">
							<input type="text" class="form-control" value="" id="circle" name="circle" placeholder="Client Circle" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="contactPerson" class="col-md-4 control-label">Contact Person</label>
						  <div class="col-md-6">
							<input type="text" class="form-control" value="" id="contactPerson" name="contactPerson" placeholder="Client Contact Person" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="contactNo" class="col-md-4 control-label">Contact No.</label>
						  <div class="col-md-6">
							<input type="text" class="form-control" value="" id="contactNo" name="contactNo" placeholder="Client Contact No." required>
						  </div>
						</div>
						
						<div class="form-group">
							<label for="contactPerson_email" class="col-md-4 control-label">Contact Person Email</label>
							<div class="col-md-6">
							<input type="text" class="form-control" value="" id="contactPerson_email" name="contactPerson_email" placeholder="Client Contact Person Email" required>
						  </div>
						</div>
						
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Client Type</label>
							<div class="col-md-6">
								<select  id="client_type" name="client_type"  class="form-control tooltip_trigger" required>
									<option value=""> - Select Type - </option>
										<?php
										/*if(!empty($client_types)){
											foreach($client_types as $type){
												echo "<option value='".$type['id']."'>".$type['type_name']."</option>";
											}
										}*/
										?>
                                                                      <option value="client">Client </option>
                                                                      <option value="dealer">Dealer </option>
								</select>
								<?php echo form_error('client_type'); ?>  
							</div>
						</div>
						
						<div class="form-group">
							<label for="mobile" class="col-md-4 control-label">Status</label>
							<div class="col-md-6">
								<select class="form-control" id="status" name="status" required>
									<option value=""> - Select Status - </option>
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="add_clients" class="btn btn-primary" id="submit" value="Add Client" />
								<a href="<?php echo $base_url;?>client_kare/client_view"><button class="btn btn-info" type="button"> Back  </button>
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