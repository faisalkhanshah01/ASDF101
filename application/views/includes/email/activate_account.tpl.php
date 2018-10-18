<html>
    <head>
        <style>
            body{
            }
            div#main{
               font-weight:bold;  
            } 
        </style>
    </head>
<body>
    
        <div id="main" style="background-color:#f3f3f3;">
             <p><img src="<?php echo base_url($_SESSION['client']['logo']); ?>" width="60px;" alt="<?php echo $_SESSION['client']['name']; ?>" title="<?php echo $_SESSION['client']['name']; ?>" /></p>
		<div>
			Dear <?php echo $identity;?>,
		</div>
                <div id="content" style="text-align: center;">
                        <p>The email address associated with your Kare account is not confirmed. </p>
                        <p>To confirm your email address, please click the following link: </p>

                        <div id="btn">
                          <p>
                              <?php echo anchor('auth/activate_account/'. $user_id .'/'. $activation_token, '<button type="button" style="color: #ffffff; background-color:'.$_SESSION['client']['color_code'].';border:2px solid #F2E6DF" >Activate Your Account</button>') ;?></p>
                        </div>
                        
                        <p>If you didn't ask for this email, you can safely ignore it.</p>
                        <div if="footer">
                                <p>Best Regards,</p>
                                </p><?php echo $_SESSION['client']['name']; ?> Team</p>
                        </div>     
                        
		</div>
	</div>
    <div style="text-align: center;">
        <p>TM and copyright © 2016 Arresto. ARRESTO SOLUTIONS PVT. E-17,Sector-3, Noida – 201301.</p>
        <p>All Rights Reserved   |   Privacy Policy</p>
        <p>As explained in Arresto's privacy policy, we may use your personal information to contact you to participate in market
research surveys. You must be 16 years of age or older to participate. Participation in this or any Arresto survey
is optional. Unsubscribe from surveys here. </p>
    </div>
    
    
</body>
</html>