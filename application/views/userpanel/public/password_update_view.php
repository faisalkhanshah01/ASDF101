<?php $this->load->view('includes/header'); ?> 
	<!-- Navigation -->
	<?php //$this->load->view('includes/head'); ?>
	<?php	//$this->load->view('includes/head');	?>
	 	<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
	<div id="global_searchAllView">
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
						<span><?php if( $lang["update_password"]['description'] !='' ){ echo $lang["update_password"]['description']; }else{ echo "Update Password"; }  ?></span>
					</div>
					<div class="panel-body">
						<div class="col-md-offset-9 col-md-3">
							<a href="<?php echo $base_url;?>auth_public/update_account" class="btn btn-info"><?php if( $lang["update_account_details"]['description'] !='' ){ echo $lang["update_account_details"]['description']; }else{ echo "Update account Details"; }  ?></a>
						</div>
						<div class="col-md-12 ">
						<?php echo form_open(current_url(), 'class="form-horizontal"');	?>
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<small>
										<?php if( $lang["password_length_must_be_more_than"]['description'] !='' ){ echo $lang["password_length_must_be_more_than"]['description']; }else{ echo "Password length must be more than"; }  ?> <?php echo $this->flexi_auth->min_password_length(); ?> <?php if( $lang["update_password_hint"]['description'] !='' ){ echo $lang["update_password_hint"]['description']; }else{ echo "characters in length. Only alpha-numeric, @, dashes, underscores, periods and comma characters are allowed."; }  ?>
									</small>
								</div>
							</div>
							<div class="form-group">
								<label for="current_password" class="col-md-4 control-label"><?php if( $lang["current_password"]['description'] !='' ){ echo $lang["current_password"]['description']; }else{ echo "Current Password"; }  ?>:</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="current_password" name="current_password" value="<?php echo set_value('current_password');?>" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="new_password" class="col-md-4 control-label"><?php if( $lang["new_password"]['description'] !='' ){ echo $lang["new_password"]['description']; }else{ echo "New Password"; }  ?>:</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="new_password" name="new_password" value="<?php echo set_value('new_password');?>" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="confirm_new_password" class="col-md-4 control-label"><?php if( $lang["confirm_new_password"]['description'] !='' ){ echo $lang["confirm_new_password"]['description']; }else{ echo "Confirm New Password"; }  ?>:</label>
								<div class="col-md-4">
									<input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password');?>" required/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-4 col-md-4">
									<input type="submit" name="change_password" id="submit" value="<?php if( $lang["submit"]['description'] !='' ){ echo $lang["submit"]['description']; }else{ echo "Submit"; }  ?>" class="btn btn-primary act-but-prm"/>
								</div>
							</div>
						<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>	
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>