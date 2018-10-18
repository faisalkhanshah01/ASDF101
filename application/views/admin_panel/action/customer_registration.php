<?php
	//print_r($_SESSION);
?>

<div class="col-md-8 col-md-offset-2 after-login-forms" id="customer-registration-mail">

	<div class="col-md-8 col-md-offset-2 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<h4>Customer Registration Mail</h4>
		<?php $attributes = array("name" => "adminCustomerRegistrationMailForm", "class" => "form-horizontal customer-registration-mail");
		echo form_open_multipart("admin/Action_Controller/customer_registration_mail", $attributes);?>

		<div class="form-group">
			<input class="form-control" name="mail_to" placeholder="To (add multiple emails separated by comma)" type="text" value="<?php echo set_value('mail_to'); ?>" />
			<span class="text-danger"><?php echo form_error('mail_to'); ?></span>
		</div>

		<div class="form-group">
			<input class="form-control" name="mail_cc" id="mail_cc" placeholder="Cc (add multiple emails separated by comma)" type="text" value="<?php echo set_value('mail_cc'); ?>" />
			<span class="text-danger"><?php echo form_error('mail_cc'); ?></span>
			<!-- <div class="col-sm-2">
				<span class="glyphicon glyphicon-plus-sign add-more-field" title="Add more emails in cc"></span>
			</div> -->
		</div>

		<div class="form-group">
			<input class="form-control" name="mail_subject" id="mail_subject" placeholder="Subject" type="text" value="<?php echo set_value('mail_subject'); ?>" />
			<span class="text-danger"><?php echo form_error('mail_subject'); ?></span>
		</div>

		<div class="form-group">
			<input class="form-control" name="mail_from" id="mail_from" type="text" value="<?php echo $this->session->userdata('admin_email');?>" readonly />
			<span class="text-danger"><?php echo form_error('mail_from'); ?></span>
		</div>

		<div class="form-group">
			<textarea class="form-control" name="mail_message" id="mail_message" value="<?php echo $this->session->userdata('admin_message');?>" readonly rows="20" cols="50" />
				<?php echo strip_tags($this->config->item('notification_invite'))."\nThanking You,\n".$this->session->userdata('admin_first_name')." ".$this->session->userdata('admin_last_name')."\n"."Arresto Solutions"; ?>
			</textarea>
			<span class="text-danger"><?php echo form_error('mail_message'); ?></span>
		</div>

		<div class="form-group">
			<button name="submit" type="submit" class="btn btn-default btn-block">Send Mail</button>
		</div>

		<?php echo form_close(); ?>
	</div>

</div>