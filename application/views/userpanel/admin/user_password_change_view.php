<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			
			<div class="panel panel-default">
                                    <div class="panel-heading home-heading clearfix">
						<span class="panel-title pull-left" style="padding-top: 7.5px;">Update Account Details</span>
                                                <div class="btn-group pull-right">
                                                    <a href="<?php echo $base_url;?>auth_admin/password_change" class="btn btn-default btn-sm">Back</a>
                                                </div>
					</div>
				<div class="panel-body">
					<?php echo form_open_multipart(current_url(), 'class="form-horizontal"');	?>
						<div class="row">
							<div class="col-md-8">
								<div class="panel panel-default">
									<div class="panel-heading">
										<span>Personal Details</span>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="first_name" class="col-md-4 control-label">First Name:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" id="first_name" name="update_first_name" value="<?php echo $user['upro_first_name'];?>" required/>
											</div>
										</div>
										<div class="form-group">
											<label for="last_name" class="col-md-4 control-label">Last Name:</label>
											<div class="col-md-8">
												<input type="text" class="form-control" id="last_name" name="update_last_name" value="<?php echo $user['upro_last_name'];?>" required/>
											</div>
										</div>
										
										<legend><h5>Contact Details</h5></legend>
											<div class="form-group">
												<label for="phone_number" class="col-md-4 control-label">Phone Number:</label>
												<div class="col-md-8">
													<input type="text" class="form-control" id="phone_number" name="update_phone_number" value="<?php echo $user['upro_phone'];?>" required/>
												</div>	
											</div>

										<legend><h5>Login Details</h5></legend>
										
										<div class="form-group">
											<label class="col-md-4 control-label">Email Address:</label>
											<div class="col-md-8">
												<input type="text" id="email" name="update_email" value="<?php echo $user['uacc_email'];?>" class="form-control"
												title="Set an email address that can be used to login with." readonly
												/>
											</div>
										</div>
										<div class="form-group">
											<hr/>
											<label for="username" class="col-md-4 control-label">Username:</label>
											<div class="col-md-8">
												<input type="text" id="username" name="update_username" value="<?php echo $user['uacc_username'];?>" class="form-control"
												title="Set a username that can be used to login with." required
												/>
											</div>
										</div>
                                                                                <div class="form-group">
											<hr/>
											<label for="username" class="col-md-4 control-label">Group User:</label>
											<div class="col-md-8">
												<input type="text" id="username" name="update_username" value="<?php echo $user['ugrp_name'];?>" class="form-control"
												title="Set a username that can be used to login with." required
												/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-default">
									<div class="panel-heading">
										<span>Profile Image</span>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="email" class="col-md-4 control-label"></label>
											<div class="col-md-8">
												<div id="img_table" class="table-responsive">
													<table class="table" id="client_pic_table">
														<tr><td style="border:none;">
														<?php if($user['upro_image'] != ''){ ?>
															<img src="<?php echo $base_url;?>uploads/images/users/<?php echo $user['upro_image']; ?>" alt="profile Image" width="150" height="200" />
															
														<?php }else{ ?>
															<img src="<?php echo $base_url;?>uploads/images/users/defult_image.jpg" alt="profile Image" width="150" height="200" />
														<?php } ?>
														
														</td>
														</tr>
													</table>
												</div>
											</div>
											<!--<label for="email" class="col-md-4 control-label">Upload Image</label>
											<div class="col-md-8">
												<input type="hidden" name="profile_image" id="profile_image" value="<?php echo $user['upro_image']; ?>" />
												<input type="file" onchange="readImage(this);" id="user_image" name="user_image" class="form-control tooltip_trigger" />
												<?php echo form_error('user_image'); ?>
												<span class="errorImage"></span>
											</div>-->
										</div>
									</div>
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