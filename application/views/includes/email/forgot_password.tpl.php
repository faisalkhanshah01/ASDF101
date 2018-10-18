<html>
<body>
	<h1>Reset Password for <?php echo $identity;?></h1>
	<p>Please click this link to <?php echo anchor('auth/manual_reset_forgotten_password/'.$user_id.'/'.$forgotten_password_token, 'Reset Your Password');?>.</p>
</body>
</html>