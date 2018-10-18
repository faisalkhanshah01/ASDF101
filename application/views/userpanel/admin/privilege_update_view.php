<?php $this->load->view('includes/header'); ?>
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
			
			
				<legend class="home-heading">Update Privilege</legend>
			<div class="col-md-offset-3 col-md-6">	
				<div class="col-md-offset-9">
				<a href="<?php echo $base_url;?>auth_admin/manage_privileges" class="btn btn-info">Manage Privileges</a>
					<br><br>
				</div>
			
				<?php echo form_open(current_url(), 'class="form-horizontal"');?>
					<fieldset>
						<legend>Privilege Details</legend>
						
							<div class="form-group">	
								<label for="privilege" class="col-md-4 control-label">Privilege Name:</label>
								<div class="col-md-8">
									<input type="text" id="privilege" name="update_privilege_name" value="<?php echo set_value('update_privilege_name', $privilege[$this->flexi_auth->db_column('user_privileges', 'name')]);?>" class="form-control"
									title="The name of the privilege."
								/>
								</div>
							</div>
							<div class="form-group">	
								<label for="description" class="col-md-4 control-label">Privilege Description:</label>
								<div class="col-md-8">
									<textarea id="description" name="update_privilege_description" class="form-control"
									title="A short description of the purpose of the privilege."><?php echo set_value('update_privilege_description', $privilege[$this->flexi_auth->db_column('user_privileges', 'description')]);?></textarea>
								</div>	
							</div>
							
							<div class="form-group">	
								<label for="submit" class="col-md-4 control-label">Update Privilege:</label>
								<div class="col-md-8">
									<input type="submit" name="update_privilege" id="submit" value="Update" class="btn btn-primary"/>
								</div>
							</div>
						
					</fieldset>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>