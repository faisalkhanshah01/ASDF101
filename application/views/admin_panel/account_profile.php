<?php
	//print_r($_SESSION);
 #print_r($user); die;
?>
<?php $this->load->view('admin_panel/common/header', $header_data);?>
<div class="col-md-8 col-md-offset-2 after-login-forms" id="profile-page">

	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<h4>Admin Profile</h4>
		<?php $attributes = array("name" => "adminProfileUpdateForm", "class" => "form-horizontal admin-profile");
		echo form_open_multipart("admin/auth_admin/profile", $attributes);?>
		<div class="row">

			<div class="col-md-4">
				<?php if(!empty($admin_image)) { ?>
					<img src="<?php echo base_url($admin_image);?>" class="img-thumbnail profile-img" alt="User Image" width="150">
				<?php } else { ?>
					<img src="<?php echo base_url('assets/images/system/default-user-img.png');?>" class="img-thumbnail profile-img" alt="User Image" width="150">
				<?php } ?>
				<br><br>
				<div class="form-group">
					<label for="admin_image">Upload / Change Image : </label>
					<input type="file" name="admin_image" size="20" id="admin_image" />
				</div>
			</div>

			<div class="col-md-8 pull-right flt-rght">
				<div class="form-group">
					<label for="first_name">First Name : </label>
					<input class="form-control" name="first_name" placeholder="First Name" type="text" value="<?php echo $user['upro_first_name']; ?>" />
					<span class="text-danger"><?php echo form_error('upro_first_name'); ?></span>
				</div>

				<div class="form-group">
					<label for="last_name">Last Name : </label>
					<input class="form-control" name="last_name" placeholder="Last Name" type="text" value="<?php echo $user['upro_last_name']; ?>" />
					<span class="text-danger"><?php echo form_error('upro_last_name'); ?></span>
				</div>

				<div class="form-group">
					<label for="phone_number">Phone : </label>
					<input class="form-control" name="phone_number" placeholder="Phone" type="text" value="<?php echo $user['upro_phone']; ?>" />
					<span class="text-danger"><?php echo form_error('upro_phone'); ?></span>
				</div>

				<div class="form-group">
					<label for="email">Email : </label>
					<input class="form-control" name="email" placeholder="Email" type="text" value="<?php echo $user['uacc_email']; ?>" readonly="TRUE" />
					<span class="text-danger"><?php echo form_error('uacc_email'); ?></span>
				</div>
			</div>

			
		</div>

		<div class="row">
			<div class="col-md-8 col-md-offset-4">
				<div class="form-group">
					<button name="update_account" value="submit" type="submit" class="btn btn-default btn-block">Update Details</button>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>

<?php   $this->load->view('admin_panel/common/footer'); ?>