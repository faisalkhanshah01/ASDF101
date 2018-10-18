<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    $config['admin_register_success'] 					= '<div class="alert alert-success text-center">You are Successfully Registered! Please login with your credentials!!!</div>';

    $config['admin_register_success_mail_not_send'] 	= '<div class="alert alert-success text-center">You are Successfully Registered! Please login with your credentials, but notification email has not sent to master admin!!!</div>';

    $config['admin_register_failed'] 					= '<div class="alert alert-error text-center">Registration failed, please try again !!!</div>';

    $config['admin_account_not_exists'] 				= '<div class="alert alert-danger text-center">Admin account with this email doesnt exists!!!</div>';

    $config['admin_password_incorrect'] 				= '<div class="alert alert-danger text-center">Incorrect password!!!</div>';

    $config['admin_forgot_password_success'] 			= '<div class="alert alert-success text-center">Password reset email has been sent!!!</div>';

    $config['admin_forgot_password_failed'] 			= '<div class="alert alert-danger text-center">Unable to reset password, please try again!!!</div>';

    $config['admin_forgot_password_token_invalid'] 		= '<div class="alert alert-danger text-center">Either Invalid token or token expired, please raise another request!!!</div>';

    $config['admin_forgot_password_change_success'] 	= '<div class="alert alert-success text-center">Password changed successfully!!!</div>';

    $config['admin_forgot_password_change_failed'] 		= '<div class="alert alert-danger text-center">Unable to change password, please try again!!!</div>';

    $config['admin_modification_success'] 				= '<div class="alert alert-success text-center">Admin Modified Successfully!!!</div>';

    $config['admin_modification_failed'] 				= '<div class="alert alert-danger text-center">Admin Modification Failed!!!</div>';

    $config['admin_profile_update_success'] 			= '<div class="alert alert-success text-center">Account details updated successfully!!!</div>';

    $config['admin_profile_update_failed'] 				= '<div class="alert alert-danger text-center">Account details updation failed, please try again !!!</div>';

    $config['admin_profile_update_password_success'] 	= '<div class="alert alert-success text-center">Password updated successfully!!!</div>';

    $config['admin_profile_update_password_failed'] 	= '<div class="alert alert-danger text-center">Password updation failed, please try again !!!</div>';

    $config['incorrect_old_password'] 					= '<div class="alert alert-danger text-center">Please enter correct old password !!!</div>';

    $config['incorrect_new_password'] 					= '<div class="alert alert-danger text-center">New password cannot be same as old password !!!</div>';

	$config['notification_invite'] 						= '
Dear Mam/Sir,<br><br>

Greetings from Arresto Solutions.<br><br>

We thank you for your interest in KARE software. To move forward we would need your company details. Kindly click on the link below and update the form. Please select the plan suitable for your organizations and also select the number of KARE Sole modules you would be requiring. <br><br>

You may make your payment by clicking on the ‘Make Payment’ tab.<br><br>

Once the payment is received, you will get a license key within 48 hrs to activate your software. We would be happy to appoint an account support manager who will help you through the installation process.

';
	
	$config['notification_invite_success'] 				= '<div class="alert alert-success text-center">Email sent successfully!!!</div>';

	$config['notification_invite_failure'] 				= '<div class="alert alert-danger text-center">Email sending failed!!!</div>';

	$config['not_logged_in'] 							= '<div class="alert alert-danger text-center">Either session timed out or you must be loggedin to view this page!!!</div>';

	$config['admin_registration'] 						= '
Dear Mam/Sir,<br><br>

Greetings from Arresto Solutions.<br><br>

We would like to inform you that a new admin has registered in Arresto.in.<br>
Kindly visit the Admin Account Management page and assign him/her proper roles and clients.

';
	
	$config['logout_success'] 							= '<div class="alert alert-danger text-center">Logged out successfully!!!</div>';

    $config['customer_registration_success']            = '<div class="alert alert-danger text-center">You are Successfully Registered! Please make the payment!!!</div>';

    $config['customer_registration_failed']             = '<div class="alert alert-danger text-center">Registration failed, please try again !!!</div>';

    $config['not_authorized']                           = '<div class="alert alert-danger text-center">You are not authorized to view this page!!!</div>';

    $config['customer_registration_subject']            = 'Arresto Registration Complete';

    $config['customer_activation_success']              = '<div class="alert alert-success text-center">Customer Activated Successfully!!!</div>';

    $config['customer_activation_failed']               = '<div class="alert alert-danger text-center">Customer Activation Failed!!!</div>';

?>