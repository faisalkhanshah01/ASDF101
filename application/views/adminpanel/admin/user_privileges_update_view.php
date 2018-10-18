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
		
		<legend class="home-heading">User Privileges</legend>
	
		<!-- Main Content -->
		<div class="content_wrap main_content_bg">
			<div class="content clearfix">
				<div class="col100">
					<p>User Privileges of '<strong><?php echo $user['upro_first_name'].' '.$user['upro_last_name']; ?></strong>', Member of the Group '<strong><?php echo $user['ugrp_name']; ?></strong>'.</p><br />
					<a href="<?php echo $base_url;?>auth_admin/manage_user_accounts">Manage User Accounts</a> | 
					<a href="<?php echo $base_url;?>auth_admin/update_user_account/<?php echo $user['upro_uacc_fk']; ?>">Update Users Account</a>
					
					<?php echo form_open(current_url(), 'class="form-horizontal"');	?>  	
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th title="The name of the privilege."/>
												Privilege Name
											</th>
											<th title="A short description of the purpose of the privilege."/>
												Description
											</th>
											<!--<th title="If checked, the user will be granted the privilege, regardless of whether their user group has the privilege."/>
												User Has Individual Privilege
											</th>-->
											<th title="Indicates whether the privilege has been assigned to the user via the privileges defined for their user group."/>
												Has Privilege From User Group
											</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($privileges as $privilege) { ?>
											<tr>
												<td>
													<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][id]" value="<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>"/>
													<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'name')];?>
												</td>
												<td><?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'description')];?></td>
												<!--<td align="center">
													<?php /*
														// Define form input values.
														$current_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $user_privileges)) ? 1 : 0; 
														$new_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $user_privileges)) ? 'checked="checked"' : NULL;
													?>
													<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][current_status]" value="<?php echo $current_status ?>"/>
													<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="0"/>
													<input type="checkbox" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="1" <?php echo $new_status */ ?>/>
												</td>-->
												<td align="center">
													<?php echo (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges) ? 'Yes' : 'No'); ?>
												</td>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4">
												<input type="submit" name="update_user_privilege" value="Update User Privileges" class="btn btn-primary"/>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>	
	</div>
</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>