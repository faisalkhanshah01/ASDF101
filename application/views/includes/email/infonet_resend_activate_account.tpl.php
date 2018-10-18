
<html>
<body>
	
	<div id="main">
		<div style="font-size:13px;  margin-bottom: 0;">
			Hello <strong><?php echo ucwords($user_name); ?></strong>,
		</div>
		  <p>We have received your request to activate your KARAM Infonet account. Click the link below to activate your account to start using KARAM Infonet:</p>
			<p><?php echo anchor('Infonet/activate_account/'.$user_id.'/'.$activation_token, 'Resend Activation Token');?>.</p>
			<?php //echo anchor('Infonet/activate_account/'. $user_id .'/'. $activation_token, '<button type="button" title="Click This Button To Activate Your Account" style="color: #16a9cd;" >Click here to verify your Email ID.</button>') ;?>
			<p>Thank you for registering in KARAM Infonet.</p>
		
		
		<p style="font-size:13px;  margin-bottom: 0;"><strong>Team Infonet</strong></p>
		<a href="#" style="font-size:13px; color: #16a9cd; text-decoration: none;">Karam.in/kare/Infonet</a>
		<hr style="border-bottom: 3px solid #000000; margin-top: 15px; margin-bottom: 2px;">
		<address style="font-size:13px;">This is an auto-generated email. Do not reply to this email.</address>
	</div>
	
</body>
</html>