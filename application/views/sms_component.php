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
		
			
			<?php echo form_open_multipart($base_url.'Sms_controller/sms_component', 'class="form-horizontal"', 'id="sms_component"', 'method="post"'); ?>
				<legend  class="home-heading">Sms Component </legend>
					<div class="form-group">
					  <label for="jc_number" class="col-md-4 control-label">Job Card Number</label>
					  <div class="col-md-6" id="jobcard-dropdown">
						<select class="form-control" id="jc_number" name="jc_number" required>
							<option value=""> - Select Job Card - </option> 
							<?php
								if(!empty($jobcards)){
									foreach($jobcards as $key=>$value){ ?>
										<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
							<?php		}
								}
							?>	
						</select>
					  </div>
					</div>
					
					<div class="form-group">
					  <label for="sms_number" class="col-md-4 control-label">Sms Number</label>
					  <div class="col-md-6" id="sms-dropdown">
						<select class="form-control sms_number" rel="sms_component"  id="sms_number" name="sms_number" required>
							<option value=""> - Select SMS Number - </option> 
						</select>
					
					  </div>
					</div>
					
					<div class="form-group">
					  <label for="component_name" class="col-md-4 control-label">Component Name</label>
					  <div class="col-md-6">
						<select class="form-control" id="component_name" name="component_name" required>
							<option value=""> - Select Component - </option>
						</select>
						
					  </div>
					</div>
					
					<div class="form-group">
						<label for="component_no" class="col-md-4 control-label">Total No. of component</label>
						<div class="col-md-6">
							<input type="text" class="form-control" value="" id="component_no" name="component_no" placeholder="Component Number" onkeypress="return isNumber(event);" onchange="updateDue()" required> 
					
						</div>
					</div>
					
					<div class="form-group">
						<label for="component_lines" class="col-md-4 control-label">Total No. of Lines</label>
						<div class="col-md-6">
							<input type="text" class="form-control" value="" id="component_lines" name="component_lines" placeholder="Component Lines" onkeypress="return isNumber(event);" onchange="updateDue()" required>
						</div>
					</div>
					
					<div class="form-group">
						<label for="component_result" class="col-md-4 control-label">Total Components in Lines</label>
						<div class="col-md-6">
							<input type="text" class="form-control" value="" id="component_result" name="component_result" placeholder="Component Result" readonly> 
						</div>
					</div>
					
					<div class="form-group">
						<label for="mobile" class="col-md-4 control-label">Status</label>
						<div class="col-md-6">
							<select class="form-control" id="status" name="status" required>
								<option value=""> - Select Status - </option>
								<option value="active">Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-5 col-md-8">
							<input type="submit" name="add_component" class="btn btn-primary" id="add_component" value="Add Component" />
							<a href="<?php echo $base_url;?>sms_controller/sms_component_view"><button class="btn btn-info" type="button"> Back  </button></a>
						</div>
					</div>
					
			 <?php echo form_close();?>
		<!--	</form> -->
		
		</div>
	</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>   
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 