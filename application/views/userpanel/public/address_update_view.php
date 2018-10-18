<?php $this->load->view('includes/header'); ?> 
	<!-- Demo Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
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
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Update Address</span>
				</div>
				<div class="panel-body">
					<div class="col-md-offset-8 col-md-4 ">	
						<a href="<?php echo $base_url;?>auth_public/manage_address_book" class="btn btn-info">Manage Address Book</a>
						</br></br>
					</div>

					<?php echo form_open(current_url() , 'class="form-horizontal"');	?>
					
							<legend>Address Alias</legend>
								<div class="form-group">
									<label for="alias" class="col-md-4 control-label">Alias:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="alias" name="update_alias" value="<?php echo set_value('update_alias',$address['uadd_alias']);?>" class="tooltip_trigger"
										title="An alias to reference the address by."
										/>
									</div>
								</div>
								
							<legend>Recipient Details</legend>
								<div class="form-group">
									<label for="recipient" class="col-md-4 control-label">Recipient Name:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="recipient" name="update_recipient" value="<?php echo set_value('update_recipient',$address['uadd_recipient']);?>"/>
									</div>
								</div>
								<div class="form-group">
									<label for="phone_number" class="col-md-4 control-label">Phone Number:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="phone_number" name="update_phone_number" value="<?php echo set_value('update_phone_number',$address['uadd_phone']);?>"/>
									</div>
								</div>
								
							<legend>Address Details</legend>
								<div class="form-group">
									<label for="company" class="col-md-4 control-label">Company:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="company" name="update_company" value="<?php echo set_value('update_company',$address['uadd_company']);?>"/>
									</div>
								</div>
								<div class="form-group">
									<label for="address_01" class="col-md-4 control-label">Address 01:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="address_01" name="update_address_01" value="<?php echo set_value('update_address_01',$address['uadd_address_01']);?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label for="address_02" class="col-md-4 control-label">Address 02:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="address_02" name="update_address_02" value="<?php echo set_value('update_address_02',$address['uadd_address_02']);?>"/>
									</div>
								</div>
								<div class="form-group">
									<label for="city" class="col-md-4 control-label">City / Town:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="city" name="update_city" value="<?php echo set_value('update_city',$address['uadd_city']);?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label for="county" class="col-md-4 control-label">State / County:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="county" name="update_county" value="<?php echo set_value('update_county',$address['uadd_county']);?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label for="post_code" class="col-md-4 control-label">Post Code:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="post_code" name="update_post_code" value="<?php echo set_value('update_post_code',$address['uadd_post_code']);?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label for="country" class="col-md-4 control-label">Country:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="country" name="update_country" value="<?php echo set_value('update_country',$address['uadd_country']);?>" required/>
									</div>
								</div>
								
							<legend>Update Address Book</legend>
								<div class="form-group">
									<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="update_address" id="submit" value="Update Address Book" class="btn btn-primary"/>
									<input type="hidden" name="update_address_id" value="<?php echo $address['uadd_id'];?>"/>
									</div>
								</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div> 
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>