<?php $this->load->view('includes/header'); ?>
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
					<span>Change Forgotten Password</span>
				</div>
				<div class="panel-body">

					<?php echo form_open(current_url() , 'class="form-horizontal"');	?>  	
						<div class="w100 frame">
								<div class="form-group">
									<small>
										Password length must be more than <?php echo $this->flexi_auth->min_password_length(); ?> characters in length.<br/>Only alpha-numeric, dashes, underscores, periods and comma characters are allowed.
									</small>
								</div>
								<div class="form-group">
									<label for="new_password" class="col-md-4 control-label">New Password:</label>
									<div class="col-md-8">
									<input type="password" class="form-control" id="new_password" name="new_password" value="" required/>
									</div>
								</div>
								<div class="form-group">
									<label for="confirm_new_password" class="col-md-4 control-label">Confirm New Password:</label>
									<div class="col-md-8">
									<input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" value="" required/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="change_forgotten_password" id="submit" value="Change Password" class="btn btn-primary"/>
									</div>
								</div>
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>