<!DOCTYPE html>
<html>
	<body>
		<h3>
		<div id="main" style="padding:20px; background-color:#eee; width:750px; margin: 0 auto; color:#666 !important; ">
			<div id="content" style="color:#666 !important; font-size:14px;">
				Dear <strong><?php echo $customer_contact_person; ?></strong>,<br><br>

				Greetings from Arresto Solutions.<br><br><br>

				We thank you for your registering successfully on Areresto. To move forward we would need you to make the payment. Kindly click on the link below and make the payment.<br><br>

				Once the payment is received, you will get a license key within 48 hrs to activate your software. We would be happy to appoint an account support manager who will help you through the installation process.

				You have opted for <strong><?php echo $customer_plan; ?> plan</strong>.
			</div>

			<div id="content" style="color:#666 !important; font-size:14px;">
				<p>Please click below link to make payment - </p>
				<a href='http://www.arresto.in/payment.html?id=<?php echo $customer_id; ?>'>Make Payment</a>
			</div>
			
			<div if="footer">
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top:50px; color:#666; ">Thanking You,</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; "><?php $this->session->userdata('admin_first_name')."".$this->session->userdata('admin_first_name'); ?></p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Arresto Solutions</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Arresto Solutions</p>
				<img src="http://www.arresto.com/assets/images/system/arresto-logo.jpg" alt="logo" width="150">
			</div>
		</div>
		</h3>
	</body>
</html>