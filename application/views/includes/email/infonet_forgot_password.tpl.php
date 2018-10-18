
<html>
<body>
	
	<div id="main">
		<div style="font-size:13px;  margin-bottom: 0;">
			Hello <strong><?php  echo ucwords($user_name);  ?></strong>,
		</div>
		  <p>We have received your request to reset your KARAM Infonet password. Click the link below to choose a new one:</p>
		  
			<p><?php echo anchor('auth/manual_reset_forgotten_password/'.$user_id.'/'.$forgotten_password_token, 'Reset Your Password');?>.</p>
		
		<p>If you do not want to change your password or did not make this request, please ignore and delete this mail.</p>
		
		<p style="font-size:13px;  margin-bottom: 0;"><strong>Team Infonet</strong></p>
		<a href="#" style="font-size:13px; color: #16a9cd; text-decoration: none;">Karam.in/kare/Infonet</a>
		<hr style="border-bottom: 3px solid #000000; margin-top: 15px; margin-bottom: 2px;">
		<address style="font-size:13px;">This is an auto-generated email. Do not reply to this email.</address>
	</div>
	
</body>
</html>