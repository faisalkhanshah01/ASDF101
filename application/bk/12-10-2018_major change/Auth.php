<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$GLOBALS['client_slug']="";


class Auth extends CI_Controller {
    
    private $client;
    private $client_url;
    
 
    function __construct() 
    {
        parent::__construct();

      //ini_set('display_errors',1);
      //error_reporting(E_ALL);

		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==1)		 
		{
                        $sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
			); 

			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(TRUE);
		}
		
		// Load required CI libraries and helpers.
		 $this->load->database();
		 $this->load->driver("session");
 		 $this->load->helper('url');
 		 $this->load->helper('form');
 		 
  		// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');	

		//$this->verify_client_slug();

		/*echo "###############";
		echo "<pre>";
                print_r($_SESSION); 
                die;*/
                
                if($this->verify_client_slug())
                {
                    if ($this->flexi_auth->is_logged_in_via_password() && uri_string() != $this->client_url.'/auth/logout') 
                    {

                        //echo "logged-in";    
                        //redirect($this->client_name."/auth_admin/dashboard");
                        redirect($this->client_url."/auth_admin/dashboard");
                    }
                }else{
                    // move to 404 page 
                    
                }

             
/*
     	// Redirect users logged in via password (However, not 'Remember me' users, as they may wish to login properly).
		if ($this->flexi_auth->is_logged_in_via_password() && uri_string() != 'auth/logout') 
		{
			// Preserve any flashdata messages so they are passed to the redirect page.
			if ($this->session->flashdata('msg')) { $this->session->keep_flashdata('msg'); }
			
			// Redirect logged in admins (For security, admin users should always sign in via Password rather than 'Remember me'.
			
			if ($this->flexi_auth->is_admin()){
				$this->load->model('Common_model');

				$groupID = $this->Common_model->get_loggedIn_user_dashboard();
			}
			else{
				echo "dashboard"; die;
				redirect('auth_public/dashboard');
			}
		}

*/		
		// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
		$this->load->vars('base_url', base_url().$this->client_url."/");
		$this->load->vars('includes_dir', base_url().'includes/');
		//$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		// Define a global variable to store data that is then used by the end view page.
		$this->data = null;
	}




	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// flexi auth demo
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * Many of the functions within this controller load a custom model called 'demo_auth_model' that has been created for the purposes of this demo.
	 * The 'demo_auth_model' file is not part of the flexi auth library, it is included to demonstrate how some of the functions of flexi auth can be used.
	 *
	 * These demos show working examples of how to implement some (most) of the functions available from the flexi auth library.
	 * This particular controller 'auth', is used as the main login page, user registration, and for forgotten password requests.
	 * 
	 * All demos are to be used as exactly that, a demonstation of what the library can do.
	 * In a few cases, some of the examples may not be considered as 'Best practice' at implementing some features in a live environment.
	*/

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Login / Registration
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * index
	 * Forwards to 'login'.
	 */
        
   function show_phpinfo(){
       echo phpinfo();
   }
        
        
        

   function index()
    {

        #echo "client _index fnction ";

         if($this->verify_client_slug()){
                $this->login();
         }else{

            #echo "not a valid URL";
           show_404();
         } 
    }


   function get_client_settings(){
        
       
        //$client_name = $this->session->userdata('client_name');
        $client=$this->session->userdata('client');      
        $client_name = $client['name'];
        $this->load->model('base_model');
       
//        $user_name 	= $_SERVER['QUERY_STRING'];
//        $user_name =$this->uri->segment(1); 
       $settings=$this->base_model->get_clients_settings($client_name);
//       echo "<pre>";
//       print_r($settings); die;
       
       if(count($settings)){
              $_SESSION['client']['color_code'] = "#".$settings['customer_theme_color'];
              $_SESSION['client']['logo'] = str_replace("./","",$settings['customer_logo_path']);
       }else{
           
              $_SESSION['client']['color_code'] = '#f7c02f';
              $_SESSION['client']['logo'] = 'images/system/arresto-logo.jpg';
       }
        	
}	



    function verify_client_slug(){
    $client_slug	= $this->uri->segment(1);
    $service_slug =   $this->uri->segment(2);
    $arresto_services=array('kare','knowledge-tree','asm'); 
    $sflag=false;

    if($service_slug!=""){

			if(in_array($service_slug,$arresto_services)){
					$sflag=true;
			}else{
				//wrong service provided 
                  echo "wrong service url";
                  $sflag=false;
            }

    }
    else{
         //  get default web service from DB 
        $service_slug='kare';
    	$sflag=true;
    }
    
    $this->db->where('customer_company_slug',$client_slug);
    $query=$this->db->get('ar_clients_2018');
    if($query->num_rows()){
         $client=$query->row_array();
    }
    
    $client_id=$client['customer_uacc_fk'];

    //$client_group=$this->flexi_auth->get_groups_row_array('',array('ugrp_name'=>$client_slug));
    $this->db->where('cgrp_client_fk',$client_id);
    $query=$this->db->get('client_groups');
    if($query->num_rows()){
         $client_group=$query->row_array();
    }
    
   # print_r($client_group); die;
    
    if(count($client_group) && $sflag){
    	  $this->client_url=$client_slug."/".$service_slug;
    	 // $_SESSION['client_url']=$this->client_url;
          
         //$_SESSION['client_name']=$client_group['ugrp_name'];
         //$_SESSION['client_id']=$client_group['ugrp_id'];
          $_SESSION['client']['url_slug']=$this->client_url;
    	  $_SESSION['client']['name']=$client_group['cgrp_name'];
          $_SESSION['client']['group_id']=$client_group['cgrp_id'];
          
          $_SESSION['client']['client_id']=$client_group['cgrp_client_fk'];
          $_SESSION['client']['client_grpid']=$client_group['cgrp_id'];
          
          $this->get_client_settings();
          return 1;
    }else{
    	return 0;
    }
}


    function login_new(){

    if($this->input->post('login_user')){
            $this->load->model('demo_auth_model');   	   
         	$this->demo_auth_model->login();
    }else{

          // show the error msg and login view 
        		$data = array(
		     		'header_data' 	=> array (
							'title'		=> 'Arresto Customer Login Page',
										),
                                                'page'			=> 'admin_panel/login/login',
				);

		$data['msg'] = (! isset($this->data['message'])) ? $this->session->flashdata('msg') : $this->data['message'];		
		$this->load->view('admin_panel/tpl_login', $this->data);
    }

}


	/**
	 * login
	 * Login page used by all user types to log into their account.
	 * This demo includes 3 example accounts that can be logged into via using either their email address or username. The login details are provided within the view page.
	 * Users without an account can register for a new account.
	 * Note: This page is only accessible to users who are not currently logged in, else they will be redirected.
	 */
        function login()
        {

            /*$this->load->model('base_model');
            $user_name 	= $_SERVER['QUERY_STRING'];
            $user_name =$this->uri->segment(1); 
            $logo 		= $this->base_model->field_value_fetch('uacc_logo', 'uacc_username', $user_name, 'user_accounts');
            if($logo != '') {
                    $_SESSION['logo'] = $logo;
            } else {
                    $_SESSION['logo'] = 'images/system/arresto-logo.jpg';
            }
            $color 		= $this->base_model->field_value_fetch('uacc_color_code', 'uacc_username', $user_name, 'user_accounts');
            if($color != '') {
                    $_SESSION['color_code'] = '#'.$color;
            } else {
                    $_SESSION['color_code'] = '#f7c02f';
            }*/

                if(isset($_SESSION['user_from'])){
                        unset($_SESSION['user_from']);
                }
                $login_flag = TRUE;
                // If 'Login' form has been submited, attempt to log the user in.
                if ($this->input->post('login_user'))
                {	

                        #echo "into login"; 
                         $customer_id 		= $this->base_model->field_value_fetch('customer_id', 'uacc_username', $_POST['login_identity'], 'user_accounts');
                         $activation_flag 	= $this->base_model->field_value_fetch('activation_flag', 'customer_id', $customer_id, 'ar_customer_account');

                         $activation_flag =1;

                        if($activation_flag) {
                                #echo "into login"; die;
                                $this->load->model('demo_auth_model');
                                $this->demo_auth_model->login();
                        } else {
                                $login_flag = FALSE;
                        }
                }

                    // CAPTCHA Example
                    // Check whether there are any existing failed login attempts from the users ip address and whether those attempts have exceeded the defined threshold limit.
                    // If the user has exceeded the limit, generate a 'CAPTCHA' that the user must additionally complete when next attempting to login.
                    if ($this->flexi_auth->ip_login_attempts_exceeded())
                    {
                            /**
                             * reCAPTCHA
                             * http://www.google.com/recaptcha
                             * To activate reCAPTCHA, ensure the 'recaptcha()' function below is uncommented and then comment out the 'math_captcha()' function further below.
                             *
                             * A boolean variable can be passed to 'recaptcha()' to set whether to use SSL or not.
                             * When displaying the captcha in a view, if the reCAPTCHA theme has been set to one of the template skins (See https://developers.google.com/recaptcha/docs/customization),
                             *  then the 'recaptcha()' function generates all the html required.
                             * If using a 'custom' reCAPTCHA theme, then the custom html must be PREPENDED to the code returned by the 'recaptcha()' function.
                             * Again see https://developers.google.com/recaptcha/docs/customization for a template 'custom' html theme. 
                             * 
                             * Note: To use this example, you will also need to enable the recaptcha examples in 'models/demo_auth_model.php', and 'views/demo/login_view.php'.
                            */
                            $this->data['captcha'] = $this->flexi_auth->recaptcha(FALSE);

                            /**
                             * flexi auths math CAPTCHA
                             * Math CAPTCHA is a basic CAPTCHA style feature that asks users a basic maths based question to validate they are indeed not a bot.
                             * For flexibility on CAPTCHA presentation, the 'math_captcha()' function only generates a string of the equation, see the example below.
                             * 
                             * To activate math_captcha, ensure the 'math_captcha()' function below is uncommented and then comment out the 'recaptcha()' function above.
                             *
                             * Note: To use this example, you will also need to enable the math_captcha examples in 'models/demo_auth_model.php', and 'views/demo/login_view.php'.
                            */
                            # $this->data['captcha'] = $this->flexi_auth->math_captcha(FALSE);
                    }


                    // Get any status message that may have been set.
                    if($login_flag) {
                            $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
                            #echo "loding login view"; die;
                            $this->load->view('userpanel/login_view', $this->data);
                    } else {
                            $this->session->set_flashdata('msg', '<p class="alert alert-danger capital">Account not activated!!!</p>');
                            $this->load->view('userpanel/login_view', $this->data);
                    }
        }

	/**
	 * login_via_ajax
	 * A simplified version of the above 'login' method that instead uses ajax to submit a users credentials.
	 * This demo includes 3 example accounts that can be logged into via using either their email address or username. The login details are provided within the view page.
	 * Note: This page is only accessible to users who are not currently logged in, else they will be redirected.
	 */ 
        function login_via_ajax()
        {
                    if ($this->input->is_ajax_request())
                    {
                            $this->load->model('demo_auth_model');

                            $this->demo_auth_model->login_via_ajax();

                            die($this->flexi_auth->is_logged_in());
                    }
                    else{
                            $this->load->view('userpanel/login_via_ajax_view', $this->data);
                    }
        }

	/**
	 * register_account
	 * User registration page used by all new users wishing to create an account.
	 * Note: This page is only accessible to users who are not currently logged in, else they will be redirected.
	 */ 
	function register_account()
	{
            
            
           /* $data=array('identity'=>'sachin',
                         'user_id'=>'12',
                         'activation_token'=>"56789990ddgddgdfftrrytryyccbcbv"
                        );     
            
           echo  $this->load->view("includes/email/activate_account",$data,true);
           die;*/
               
		#echo "into client function";
		$this->load->model('kare_model');
		$countryList  = $this->kare_model->get_manage_country_filt_result_list();		
		if(!empty($countryList) && is_array($countryList)){
			$country  = array();
			foreach($countryList as $val){   
				$country[$val['id']] = $val['name'];
			}
			$this->data['country'] = $country;
		}
		if(isset($_SESSION['user_from'])){
			unset($_SESSION['user_from']);
		}
		// Redirect user away from registration page if already logged in.
		if ($this->flexi_auth->is_logged_in()) 
		{
			redirect($this->client_url.'auth');
		}
		// If 'Registration' form has been submitted, attempt to register their details as a new account.
		else if ($this->input->post('register_user'))
		{
                    //echo "here"; die;
			
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->register_account();
		}
		
		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/public/register_view', $this->data);
	}




	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Account Activation
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * activate_account
	 * User account activation via email.
	 * The default setup of this demo requires that new account registrations must be authenticated via email before the account is activated.
	 * In this demo, this page is accessed via an activation link in the 'views/includes/email/activate_account.tpl.php' email template.
	 */ 
	function activate_account($user_id, $token = FALSE)
	{
		// The 3rd activate_user() parameter verifies whether to check '$token' matches the stored database value.
		// This should always be set to TRUE for users verifying their account via email.
		// Only set this variable to FALSE in an admin environment to allow activation of accounts without requiring the activation token.
		$this->flexi_auth->activate_user($user_id, $token, TRUE);
		// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		redirect($this->client_url.'auth');
	}
	
	/**
	 * resend_activation_token
	 * Resend user an activation token via email.
	 * If a user has not received/lost their account activation email, they can request a new activation email to be sent to them.
	 * In this demo, this page is accessed via a link on the login page.
	 */ 
	function resend_activation_token()
	{
		// If the 'Resend Activation Token' form has been submitted, resend the user an account activation email.
		if ($this->input->post('send_activation_token')) 
		{
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->resend_activation_token();
		}
		
		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/public/resend_activation_token_view', $this->data);		
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Forgotten Password
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * forgotten_password
	 * Send user an email to verify their identity. Via a unique link in this email, the user is redirected to the site so they can then reset their password.
	 * In this demo, this page is accessed via a link on the login page.
	 *
	 * Note: This is step 1 of an example of allowing users to reset a forgotten password manually. 
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 */ 
	public function forgotten_password()
	{
		// If the 'Forgotten Password' form has been submitted, then email the user a link to reset their password.
		if ($this->input->post('send_forgotten_password')) 
		{
			$this->load->model('Demo_auth_model');
			$this->Demo_auth_model->forgotten_password();
		}
		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/public/forgot_password_view', $this->data);		
	}
	
	/**
	 * manual_reset_forgotten_password
	 * This is step 2 (The last step) of an example of allowing users to reset a forgotten password manually. 
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/manual_reset_forgotten_password/...'.
	 */
	function manual_reset_forgotten_password($user_id = FALSE, $token = FALSE)
	{
		// If the 'Change Forgotten Password' form has been submitted, then update the users password.
		if ($this->input->post('change_forgotten_password')) 
		{
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->manual_reset_forgotten_password($user_id, $token);
		}
		
		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/public/forgot_password_update_view', $this->data);
	}

	/**
	 * auto_reset_forgotten_password
	 * This is an example of automatically reseting a users password as a randomised string that is then emailed to the user. 
	 * See the manual_reset_forgotten_password() function above for the manual method of changing a forgotten password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/auto_reset_forgotten_password/...'.
	 */
	function auto_reset_forgotten_password($user_id = FALSE, $token = FALSE)
	{
		// forgotten_password_complete() will validate the token exists and reset the password.
		// To ensure the new password is emailed to the user, set the 4th argument of forgotten_password_complete() to 'TRUE' (The 3rd arg manually sets a new password so set as 'FALSE').
		// If successful, the password will be reset and emailed to the user.
		$this->flexi_auth->forgotten_password_complete($user_id, $token, FALSE, TRUE);
			
		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		
		redirect('auth');
	}
		
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Logout
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * logout
	 * This example logs the user out of all sessions on all computers they may be logged into.
	 * In this demo, this page is accessed via a link on the demo header once a user is logged in.
	 */
	function logout() 
	{
        #echo "into logout"; die;
		$this->session->sess_destroy();
		// By setting the logout functions argument as 'TRUE', all browser sessions are logged out.
		$this->flexi_auth->logout(TRUE);		
		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
                //echo $this->session->keep_flashdata('message'); //die;
		redirect($this->client_url.'/auth');
        }
	
	/**
	 * logout_session
	 * This example logs the user only out of their CURRENT browser session (e.g. Internet Cafe), but no other logged in sessions (e.g. Home and Work).
	 * In this demo, this controller method is actually not linked to. It is included here as an example of logging a user out of only their current session.
	 */
	function logout_session()
	{
		$this->session->sess_destroy();
		// By setting the logout functions argument as 'FALSE', only the current browser session is logged out.
		$this->flexi_auth->logout(FALSE);

		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());		
        
		redirect($this->client_url.'auth');
    }	
	
	function ajax_get_zone_list(){
		$country_id = $_REQUEST['country_id'];		
		$this->load->model('kare_model');			
		$respose ='';		
		if( $country_id != ''){  
			$filtArray  = array('country_id'=>$country_id);
			$zone_list = $this->kare_model->get_manage_zone_filt_result_list($filtArray);
			foreach($zone_list as $key=>$val){
				$id	= $val['id'];
				$name		= $val['name'];
				$respose	.= '<option value="'.$id.'" >'.$name.'</option>';
				
			}
		}else{
		    
		}
		echo $respose;
	}

	function ajax_get_cities_list(){
		$state_id = $_REQUEST['province_id'];
		$this->load->model('kare_model');			
		$respose ='';		
		if( $state_id != ''){  
			$filtArray  = array('state_id'=>$state_id);
			$zone_list = $this->kare_model->get_manage_city_filt_result_list($filtArray);
			foreach($zone_list as $key=>$val){
				$id	= $val['city_id'];
				$name		= $val['city_name'];
				$respose	.= '<option value="'.$id.'" >'.$name.'</option>';
				
			}
		}else{
		    
		}
		echo $respose;
	}


}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
