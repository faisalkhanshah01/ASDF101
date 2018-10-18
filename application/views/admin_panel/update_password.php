<?php
	//print_r($_SESSION);
?>
<?php $this->load->view('admin_panel/common/header', $header_data);?>
<div class="col-md-8 col-md-offset-2 after-login-forms" id="update-pwd-page">

	<div class="col-md-8 col-md-offset-2 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<h4>Update Password</h4>
		<?php $attributes = array("name" => "adminUpdatePasswordForm", "class" => "form-horizontal admin-password");
		echo form_open_multipart("admin/update_password.html", $attributes);?>

		<div class="form-group">
			<label for="admin_email">Email : </label>
			<input class="form-control" name="admin_email" placeholder="Email" type="text" value="<?php echo $this->session->userdata('admin_email'); ?>" readonly="TRUE" />
			<span class="text-danger"><?php echo form_error('admin_email'); ?></span>
		</div>

		<div class="form-group">
			<label for="admin_pwd">Old Password : </label>
			<input class="form-control" name="admin_pwd" placeholder="Old Password" type="password" value="" />
			<span class="text-danger"><?php echo form_error('admin_pwd'); ?></span>
		</div>

		<div class="form-group">
			<label for="admin_new_pwd">New Password : </label>
			<input class="form-control" name="admin_new_pwd" placeholder="New Password" type="password" value="" />
			<span class="text-danger"><?php echo form_error('admin_new_pwd'); ?></span>
		</div>

		<div class="form-group">
			<label for="admin_confirm_pwd">Confirm Password : </label>
			<input class="form-control" name="admin_confirm_pwd" placeholder="Last Name" type="password" value="" />
			<span class="text-danger"><?php echo form_error('admin_confirm_pwd'); ?></span>
		</div>

		<div class="form-group">
			<button name="submit" type="submit" class="btn btn-default btn-block">Update Password</button>
		</div>

		<?php echo form_close(); ?>
	</div>

</div>
<?php   $this->load->view('admin_panel/common/footer'); ?>