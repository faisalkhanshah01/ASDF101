<?php $this->load->view('admin_includes/header'); ?>
	<?php $this->load->view('admin_includes/head'); ?> 
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
	<!-- Main Content -->
			
			<div class="text-center">
				<?php if (! empty($message)) { ?>
					<div id="message">
						<?php echo $message; ?>
					</div>
				<?php } ?>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
						<span><?php if( $lang["update_account_details"]['description'] !='' ){ echo $lang["update_account_details"]['description']; }else{ echo "Update account Details"; }  ?></span>
					</div>
				<div class="panel-body">
					<?php echo form_open_multipart(current_url(), 'class="form-horizontal"');	?>
						<div class="row">
							<div class="col-md-8">
								<div class="panel panel-default">
									<div class="panel-heading">
										<span><?php if( $lang["personal_details"]['description'] !='' ){ echo $lang["personal_details"]['description']; }else{ echo "Personal Details"; }  ?></span>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="first_name" class="col-md-4 control-label"><?php if( $lang["first_name"]['description'] !='' ){ echo $lang["first_name"]['description']; }else{ echo "First Name"; }  ?>:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" id="first_name" name="update_first_name" value="<?php echo set_value('update_first_name',$user['upro_first_name']);?>" required/>
											</div>
										</div>
										<div class="form-group">
											<label for="last_name" class="col-md-4 control-label"><?php if( $lang["last_name"]['description'] !='' ){ echo $lang["last_name"]['description']; }else{ echo "Last Name"; }  ?>:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" id="last_name" name="update_last_name" value="<?php echo set_value('update_last_name',$user['upro_last_name']);?>" required/>
											</div>
										</div>
										
										<legend><h5><?php if( $lang["contact_details"]['description'] !='' ){ echo $lang["contact_details"]['description']; }else{ echo "Contact Details"; }  ?></h5></legend>
											<div class="form-group">
												<label for="phone_number" class="col-md-4 control-label"><?php if( $lang["phone_number"]['description'] !='' ){ echo $lang["phone_number"]['description']; }else{ echo "Phone Number"; }  ?>:</label>
												<div class="col-md-8">
													<input type="text" class="form-control" id="phone_number" name="update_phone_number" value="<?php echo set_value('update_phone_number',$user['upro_phone']);?>" required/>
												</div>	
											</div>

										<legend><h5><?php if( $lang["login_details"]['description'] !='' ){ echo $lang["login_details"]['description']; }else{ echo "Login Details"; }  ?></h5></legend>
										
										<div class="form-group">
											<label class="col-md-4 control-label"><?php if( $lang["email_address"]['description'] !='' ){ echo $lang["email_address"]['description']; }else{ echo "Email Address"; }  ?>:</label>
											<div class="col-md-8">
												<input type="text" id="email" name="update_email" value="<?php echo set_value('update_email',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" class="form-control"
												title="Set an email address that can be used to login with." readonly
												/>
											</div>
										</div>
										<div class="form-group">
											<hr/>
											<label for="username" class="col-md-4 control-label"><?php if( $lang["username"]['description'] !='' ){ echo $lang["username"]['description']; }else{ echo "Username"; }  ?>:</label>
											<div class="col-md-8">
												<input type="text" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="form-control"
												title="Set a username that can be used to login with." required
												/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><?php if( $lang["password"]['description'] !='' ){ echo $lang["password"]['description']; }else{ echo "Password"; }  ?>:</label>
											<div class="col-md-8">
												<a href="<?php echo $base_url;?>auth_public/change_password" class="form-control btn btn-info"><?php if( $lang["click_here_to_change_your_password"]['description'] !='' ){ echo $lang["click_here_to_change_your_password"]['description']; }else{ echo "Click here to change your password"; }  ?>
</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-default">
									<div class="panel-heading">
										<span><?php if( $lang["profile_image"]['description'] !='' ){ echo $lang["profile_image"]['description']; }else{ echo "Profile Image"; }  ?></span>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="email" class="col-md-4 control-label"></label>
											<div class="col-md-8">
												<div id="img_table" class="table-responsive">
													<table class="table" id="client_pic_table">
														<tr><td style="border:none;">
														<?php if($user['upro_image'] != ''){ ?>
															<img src="<?php echo $base_url;?>uploads/images/users/<?php echo $user['upro_image']; ?>" alt="<?php if( $lang["profile_image"]['description'] !='' ){ echo $lang["profile_image"]['description']; }else{ echo "Profile image"; }  ?>
" width="150" height="200" />
															
														<?php }else{ ?>
															<img src="<?php echo $base_url;?>uploads/images/users/default.jpg" alt="<?php if( $lang["profile_image"]['description'] !='' ){ echo $lang["profile_image"]['description']; }else{ echo "Profile image"; }  ?>
" width="150" height="200" />
														<?php } ?>
														
														</td>
														</tr>
													</table>
												</div>
											</div>
											<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_image"]['description'] !='' ){ echo $lang["upload_image"]['description']; }else{ echo "Upload Image"; }  ?></label>
											<div class="col-md-8">
												<input type="hidden" name="profile_image" id="profile_image" value="<?php echo $user['upro_image']; ?>" />
												<input type="file" onchange="readImage(this);" id="user_image" name="user_image" class="form-control tooltip_trigger" />
												<?php echo form_error('user_image'); ?>
												<span class="errorImage"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row col-md-8">
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="update_account" id="submit" value="<?php if( $lang["update_account"]['description'] !='' ){ echo $lang["update_account"]['description']; }else{ echo "Update Account"; }  ?>" class="btn btn-primary"/>
								</div>
							</div>
							
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('admin_includes/footer'); ?>
<?php $this->load->view('admin_includes/scripts'); ?>