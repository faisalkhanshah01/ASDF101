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
		
			<legend class="home-heading">Insert New Privilege</legend>
			<div class="col-md-offset-10 col-md-2 ">
				<a href="<?php echo $base_url;?>auth_admin/manage_privileges"  class="btn btn-info">Manage Privileges</a>
				<br>
			</div>
			
			<?php echo form_open(current_url() , 'class="form-horizontal"');	?>

				<legend>Privilege Details</legend>
				
				<div class="form-group">							
					<label for="privilege" class="col-md-2 control-label">Privilege Name:</label>
					<div class="col-md-10">
						<input type="text" id="privilege" name="insert_privilege_name" value="<?php echo set_value('insert_privilege_name');?>" class="form-control"
								title="The name of the privilege." required/>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-md-2 control-label">Privilege Description:</label>
					<div class="col-md-10">
						<textarea id="description" name="insert_privilege_description" class="form-control"
								title="A short description of the purpose of the privilege."><?php echo set_value('insert_privilege_description');?></textarea>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<input type="submit" name="insert_privilege" id="submit" value="Insert" class="btn btn-primary"/>
					</div>
				</div>
			<?php echo form_close();?>

	</div>
</div>
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/scripts'); ?>