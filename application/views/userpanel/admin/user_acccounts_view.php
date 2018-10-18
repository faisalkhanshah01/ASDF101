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
			<div class="content_wrap main_content_bg">
				<legend class="home-heading"><?php if( $lang["user_accounts"]['description'] !='' ){ echo $lang["user_accounts"]['description']; }else{ echo 'User Accounts'; } ?></legend>
				<?php echo form_open(current_url());	?>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered shorting table-hover" >
							<thead>
								<tr>
									<th><?php if( $lang["email"]['description'] !='' ){ echo $lang["email"]['description']; }else{ echo 'Email'; } ?></th>
									<th><?php if( $lang["first_name"]['description'] !='' ){ echo $lang["first_name"]['description']; }else{ echo 'First Name'; } ?></th>
									<th><?php if( $lang["last_name"]['description'] !='' ){ echo $lang["last_name"]['description']; }else{ echo 'Last Name'; } ?></th>
									<th>User Role</th>
                                                                        <th>User Group</th>
									<th><?php if( $lang["user_privileges"]['description'] !='' ){ echo $lang["user_privileges"]['description']; }else{ echo 'User Privileges'; } ?></th>
									<th><?php if( $lang["account_suspended"]['description'] !='' ){ echo $lang["account_suspended"]['description']; }else{ echo 'Account Suspended'; } ?></th>
									<th><?php if( $lang["delete"]['description'] !='' ){ echo $lang["delete"]['description']; }else{ echo 'Delete'; } ?></th>
								</tr>
							</thead>
							<?php if (!empty($users)) { ?>
							<tbody>
								<?php foreach ($users as $user) { 
                                                                    #echo "<pre>";  print_r($user); 
                                                                 ?>
								<tr>
									<td>
										<a href="<?php echo $base_url.'auth_admin/update_user_account/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>">
											<?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')];?>
										</a>
									</td>
									<td><?php echo $user['upro_first_name'];?></td>
									<td><?php echo $user['upro_last_name'];?></td>
									<td><?php echo $user[$this->flexi_auth->db_column('user_group', 'name')];?></td>
                                              
                                                                        <td><?php echo $user['cgrp_name']; ?></td>
           
									<td><a href="<?php echo $base_url.'auth_admin/update_user_privileges/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>"> <?php if( $lang["manage"]['description'] !='' ){ echo $lang["manage"]['description']; }else{ echo 'Manage'; } ?> </a></td>
									<td align="center">
										<input type="hidden" name="current_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="<?php echo $user[$this->flexi_auth->db_column('user_acc', 'suspend')];?>"/>
										<!-- A hidden 'suspend_status[]' input is included to detect unchecked checkboxes on submit -->
										<input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
									
										<?php if ($this->flexi_auth->is_privileged('Update Users')) { ?>
											<input type="checkbox" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1" <?php echo ($user[$this->flexi_auth->db_column('user_acc', 'suspend')] == 1) ? 'checked="checked"' : "";?>/>
										<?php } else { ?>
											<input type="checkbox" disabled="disabled"/>
										<small><?php if( $lang["not_privileged"]['description'] !='' ){ echo $lang["not_privileged"]['description']; }else{ echo 'Not Privileged'; } ?></small>
										<input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
										<?php } ?>
									</td>
									<td align="center">
										<?php if ($this->flexi_auth->is_privileged('Delete Users')) { ?>
											<input type="checkbox" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1"/>
										<?php } else { ?>
											<input type="checkbox" disabled="disabled"/>
											<small><?php if( $lang["not_privileged"]['description'] !='' ){ echo $lang["not_privileged"]['description']; }else{ echo 'Not Privileged'; } ?></small>
											<input type="hidden" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
							<tr>
								<td colspan="7">
									<?php $disable = (! $this->flexi_auth->is_privileged('Update Users') && ! $this->flexi_auth->is_privileged('Delete Users')) ? 'disabled="disabled"' : NULL;?>
									<input type="submit" name="update_users" value="Update / Delete Users" class="btn btn-primary" <?php echo $disable; ?>/>
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="7" class="highlight_red">
									<?php if( $lang["no_users_are_available"]['description'] !='' ){ echo $lang["no_users_are_available"]['description']; }else{ echo 'No users are available.'; } ?>
								</td>
							</tr>
						</tbody>
					<?php } ?>
						</table>
					</div>
				</div>
				
				<?php echo form_close();?>
			</div>
		</div>
	</div> <!-- end of row -->
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>