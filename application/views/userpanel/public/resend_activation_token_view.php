<?php
//echo current_url(); die; 
?>
<?php $this->load->view('includes/header'); ?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6 form-cont">
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
					<span>Resend Activation Token</span>
				</div>
				<div class="panel-body">
					<?php echo form_open(current_url() , 'class="form-horizontal"'); ?>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Username / Email</label>
							<div class="col-md-8">
								<input type="text" id="identity" name="activation_token_identity" class="form-control tooltip_trigger" placeholder="Username / Email" required />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<?php if(isset($_SESSION['user_from']) && $_SESSION['user_from']=='infonet'){ ?>
									<input type="submit" name="send_activation_token" id="submit" value="Submit" class="btn btn-primary act-but-sec"/> <a href="<?php echo $base_url; ?>Infonet" class="btn btn-default">Back</a>
								<?php }else{ ?>
									<input type="submit" name="send_activation_token" id="submit" value="Submit" class="btn btn-primary act-but-sec"/> <a href="<?php echo $base_url; ?>" class="btn btn-default">Back</a>
								<?php } ?>
							</div>
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/scripts'); ?> 
