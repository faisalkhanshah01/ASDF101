
<?php $this->load->view('admin_panel/common/header',$header_data); ?>

<?php
  $_POST['admin_email']        = isset($_POST['admin_email'])?$_POST['admin_email']:'';
  $_POST['admin_pwd']          = isset($_POST['admin_pwd'])?$_POST['admin_pwd']:'';
?>
<div class="col-md-4 col-md-offset-4 before-login-forms" id="login">
      <h4>Admin Login</h4>
      <?php echo $this->session->flashdata('msg'); ?>
      <?php $attributes = array("name" => "adminLoginForm");
      echo form_open_multipart("admin/auth/login", $attributes);?>
      <div class="form-group">
        <!-- <label for="admin_email">Email</label> -->
        <input class="form-control" name="login_identity" placeholder="Email Address" type="text" value="<?php echo set_value('login_identity'); ?>" />
        <span class="text-danger"><?php echo form_error('login_identity'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_pwd">Password</label> -->
        <input class="form-control" name="login_password" placeholder="Password" type="password" value="<?php echo htmlspecialchars($_POST['admin_pwd']); ?>"/>
        <span class="text-danger"><?php echo form_error('login_password'); ?></span>
      </div>

      <div class="form-group">
        <button name="login_user" type="submit" value="submit" class="btn btn-default btn-block">Login</button>
      </div>
      <?php echo form_close(); ?>
<!--      <p class="txt-cntr">Not registered yet! <a href='<?php //echo base_url("admin/auth/register");?>'>Click here</a> to register now.</p>-->
      <p class="txt-cntr"><a href='<?php echo base_url("admin/auth/forgotten_password");?>'>Lost your password?</a></p>
  </div>
</div>
<?php $this->load->view('admin_panel/common/footer'); ?>


<?php 
/*$this->load->view('includes/header'); ?>
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
			<legend class="home-heading"><?php if( $lang["manage_user_groups"]['description'] !='' ){ echo $lang["manage_user_groups"]['description']; }else{ echo 'Manage User Groups'; }  ?></legend>
			<div class="col-md-offset-10">
				<?php $disable = (! $this->flexi_auth->is_privileged('Insert User Groups')) ? 'disabled="disabled"' : NULL;?>
				<a href="<?php echo $base_url;?>auth_admin/insert_user_group" class="btn btn-info" <?php echo $disable; ?> > <?php if( $lang["insert_new_user_group"]['description'] !='' ){ echo $lang["insert_new_user_group"]['description']; }else{ echo 'Insert New User Group'; }  ?> </a>
				</br></br>
			</div>
				
				<?php echo form_open(current_url());	?>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered simple">
								<thead>
									<tr>
										<th class="spacer_150 tooltip_trigger" 
											title="The user group name.">
											<?php if( $lang["group_name"]['description'] !='' ){ echo $lang["group_name"]['description']; }else{ echo 'Group Name'; }  ?>
										</th>
										<th class="tooltip_trigger" 
											title="A short description of the purpose of the user group.">
											<?php if( $lang["description"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo 'Description'; }  ?>
										</th>
										<th class="spacer_100 align_ctr tooltip_trigger" 
											title="Indicates whether the group is considered an 'Admin' group.">
											<?php if( $lang["is_admin_group"]['description'] !='' ){ echo $lang["is_admin_group"]['description']; }else{ echo 'Is Admin Group'; }  ?>
										</th>
										<th class="spacer_100 align_ctr tooltip_trigger"
											title="Manage the access privileges of user groups.">
											<?php if( $lang["user_group_privileges"]['description'] !='' ){ echo $lang["user_group_privileges"]['description']; }else{ echo 'User Group Privileges'; } ?>
										</th>
										<th class="spacer_100 align_ctr tooltip_trigger" 
											title="If checked, the row will be deleted upon the form being updated.">
											<?php if( $lang["delete"]['description'] !='' ){ echo $lang["delete"]['description']; }else{ echo 'Delete'; } ?>
										</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($user_groups as $group) { ?>
									<tr>
										<td>
											<a href="<?php echo $base_url;?>auth_admin/update_user_group/<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>">
												<?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
											</a>
										</td>
										<td><?php echo $group[$this->flexi_auth->db_column('user_group', 'description')];?></td>
										<td class="align_ctr"><?php echo ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ? "Yes" : "No";?></td>
										<td class="align_ctr">
											<a href="<?php echo $base_url.'auth_admin/update_group_privileges/'.$group[$this->flexi_auth->db_column('user_group', 'id')];?>"><?php if( $lang["manage"]['description'] !='' ){ echo $lang["manage"]['description']; }else{ echo 'Manage'; } ?></a>
										</td>
										<td class="align_ctr">
										<?php if ($this->flexi_auth->is_privileged('Delete User Groups')) { ?>
											<input type="checkbox" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="1"/>
										<?php } else { ?>
											<input type="checkbox" disabled="disabled"/>
											<small><?php if( $lang["not_privileged"]['description'] !='' ){ echo $lang["not_privileged"]['description']; }else{ echo 'Not Privileged'; } ?></small>
											<input type="hidden" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="0"/>
										<?php } ?>
										</td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot>
									<td colspan="5">
										<?php $disable = (! $this->flexi_auth->is_privileged('Update User Groups') && ! $this->flexi_auth->is_privileged('Delete User Groups')) ? 'disabled="disabled"' : NULL; ?>
										<input type="submit" name="submit" value="<?php if( $lang["delete_checked_user_groups"]['description'] !='' ){ echo $lang["delete_checked_user_groups"]['description']; }else{ echo 'Delete Checked User Groups'; } ?>" class="btn btn-primary" <?php echo $disable; ?>/>
									</td>
								</tfoot>
							</table>
						</div>
					</div>
				<?php echo form_close();?>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
*/
?>