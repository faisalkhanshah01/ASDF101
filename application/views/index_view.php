<?php $this->load->view('includes/header'); ?> 
  <?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-offset-3 col-md-6">

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
			
			<?php echo form_open(current_url(), 'class="form-horizontal"');?>
			
			<!--<form class="form-horizontal" name="signUp" id="signUp" action="" method="post">-->
				<legend>Sign In</legend>
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Username / Email</label>
					<div class="col-md-8">
						<input type="email" class="form-control" id="identity" name="login_identity" placeholder="Username / Email" required>
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-md-4 control-label">Password</label>
					<div class="col-md-8">
						<input type="password" class="form-control" id="password" name="login_password" placeholder="Password" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-4 col-md-8">
					<!-- <button type="submit" class="btn btn-primary" name="signin" id="signin" value="SIGNIN">Sign In</button> -->
						<a href="<?php echo $base_url; ?>auth" class="btn btn-primary">Sign In</a>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-4 col-md-8"><a href="<?php echo $base_url; ?>auth/forgotten_password">Forgot Password</a></div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-4 col-md-8"><a href="<?php echo $base_url;?>auth/resend_activation_token">Resend Account Activation Token</a></div>
					
				</div>
				<div class="form-group">
					<div class="col-md-offset-4 col-md-8"> Not Registered ?? <a href="<?php echo $base_url;?>auth/register_account" class="btn btn-primary">Register Now</a> </div>
				</div>
			<?php echo form_close();?>
			<!--</form>-->
		</div>
	</div>
<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 