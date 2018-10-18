<?php $this->load->view('admin_includes/header'); ?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6 form-cont">
			<div class="text-center">
				<?php 
                                echo $this->session->flashdata('message'); 
                                if($message = $this->session->flashdata('message')){
                                    $this->session->set_flashdata('msg',$message); }
                                    
                                if (!empty($this->session->flashdata('msg')) || isset($msg)) { ?>
					<div class="row" class="msg-display">
						<div class="col-md-12">
							<?php
								echo (isset($msg))? $msg : $this->session->flashdata('msg');
								echo validation_errors(); ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Admin Login Panel</span>
				</div>
				<div class="panel-body">
					<?php echo form_open("admin/auth/login", 'class="form-horizontal"');?>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Username / Email</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="identity" name="login_identity" placeholder="Username / Email" required>
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
								<!--<a href="<?php echo $base_url; ?>auth" class="btn btn-primary">Sign In</a>-->
								<input type="submit" name="login_user" id="submit" value="Submit" class="btn btn-primary act-but-prm" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8"><a href="<?php echo $base_url; ?>auth/forgotten_password" class="act-link-prm">Forgot Password</a></div>
						</div>
						<!--<div class="form-group">
							<div class="col-md-offset-4 col-md-8"><a href="<?php echo $base_url;?>auth/resend_activation_token" class="act-link-prm">Resend Account Activation Token</a></div>
							
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8"> Not Registered ? <a href="<?php echo $base_url;?>auth/register_account" class="act-link-prm">Register Now</a> </div>
						</div>-->
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
<!-- Footer -->  
<?php $this->load->view('admin_includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 