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
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Update Account of (<?php echo $user['upro_first_name'].' '.$user['upro_last_name']; ?>)</span>
				</div>
				<div class="panel-body">
					<div class="col-md-offset-7 col-md-5 ">
						<a href="<?php echo $base_url;?>auth_admin/manage_user_accounts" class="btn btn-info">Manage User Accounts</a>
						<br><br>
					</div>
				
					<div class="col-md-offset-3 col-md-6">
				
					<?php echo form_open(current_url(), 'class="form-horizontal"');?>
						<fieldset>
							<legend>Personal Details</legend>
							
								<div class="form-group">		
									<label for="first_name" class="col-md-4 control-label">First Name:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="first_name" name="update_first_name" value="<?php echo set_value('update_first_name',$user['upro_first_name']);?>"/>
									</div>
								</div>
								<div class="form-group">
									<label for="last_name" class="col-md-4 control-label">Last Name:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="last_name" name="update_last_name" value="<?php echo set_value('update_last_name',$user['upro_last_name']);?>"/>
									</div>
								</div>
						</fieldset>
						
						<fieldset>
							<legend>Contact Details</legend>
							
							<div class="form-group">
								<label for="phone_number" class="col-md-4 control-label">Phone Number:</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="phone_number" name="update_phone_number" value="<?php echo set_value('update_phone_number',$user['upro_phone']);?>"/>
								</div>
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Login Details</legend>
							
								<div class="form-group">
									<label for="email_address" class="col-md-4 control-label">Email Address:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="email_address" name="update_email_address" value="<?php echo set_value('update_email_address',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" class="tooltip_trigger"
											title="Set the users email address that they can use to login with."
										/>
									</div>
								</div>
								<div class="form-group">
									<label for="username" class="col-md-4 control-label">Username:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="tooltip_trigger"
											title="Set the users username that they can use to login with."
										/>
									</div>
								</div>
								
								<!--<div class="form-group">
									<label class="col-md-4 control-label">Privileges:</label>
									<div class="col-md-8">
									<a href="<?php //echo $base_url.'auth_admin/update_user_privileges/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>" class="tooltip_trigger"
										title="Manage a users access privileges.">Manage User Privileges</a>
									</div>
								</div> -->
						</fieldset>
						
						<fieldset>
							<legend>User Group</legend>
								<div class="form-group">
									<label for="group" class="col-md-4 control-label">Group:</label>
									<div class="col-md-8">
										<select id="group" name="update_cgroup" class="form-control"
											title="Set the users group, that can define them as an admin, public, moderator etc."
										>
                                                                                   <?php
                                                                                    $client_id=$_SESSION['client']['client_id'];
                                                                                    $client_group_id=$_SESSION['client']['group_id'];
                                                                                    $selected_id=$group['ugrp_pid'];
                                                                                   echo  $this->flexi_auth->groupTree($client_group_id,$group['ugrp_id'],$selected_id);
                                                                                   ?>    
										</select>
									</div>
								</div>
                                                        
                                                            <div class="form-group">
									<label for="group" class="col-md-4 control-label">User Roles:</label>
									<div class="col-md-8">
										<select id="group" name="update_group" class="form-control"
											title="Set the users group, that can define them as an admin, public, moderator etc."
										>
                                                                             <?php
                                                                         
                                                                                foreach($groups as $group) { ?>
                                                                                        <?php $user_group = ($group['ugrp_id'] == $user[$this->flexi_auth->db_column('user_acc', 'group_id')]) ? TRUE : FALSE;?>
                                                                                <option value="<?php echo $group['ugrp_id'];?>" <?php echo set_select('update_group', $group['ugrp_id'], $user_group);?> >
                                                                                            <?php echo $group['ugrp_name']; ?>
                                                                                </option>
										<?php 
                                                                                    } 
										?>
									</div>
								</div>   
						</fieldset>
                                            
						<fieldset>
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="update_users_account" id="submit" value="Update User Details" class="btn btn-primary"/>
								</div>
							</div>
						</fieldset>
					<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>