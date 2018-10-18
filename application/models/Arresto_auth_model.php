<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arresto_auth_model extends CI_Model {
	
	// The following method prevents an error occurring when $this->data is modified.
	// Error Message: 'Indirect modification of overloaded property Demo_cart_admin_model::$data has no effect'.
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Login
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * login
	 * Validate the submitted login details and attempt to log the user into their account.
	 */
	function login()
	{

		$this->load->library('form_validation');
		// Set validation rules.
		$this->form_validation->set_rules('login_identity', 'Identity (Email / Login)', 'required');
		$this->form_validation->set_rules('login_password', 'Password', 'required');

		// If failed login attempts from users IP exceeds limit defined by config file, validate captcha.
		if ($this->flexi_auth->ip_login_attempts_exceeded())
		{
			$this->form_validation->set_rules('recaptcha_response_field', 'Captcha Answer', 'required|validate_recaptcha');				
			
		}
                		
		// Run the validation.
		if ($this->form_validation->run())
		{

			// Check if user wants the 'Remember me' feature enabled.
			$remember_user = ($this->input->post('remember_me') == 1);
			// Verify login data.
			$this->flexi_auth->login($this->input->post('login_identity'), $this->input->post('login_password'), $remember_user);

			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());

			#echo $this->flexi_auth->get_messages(); die;
			// Reload page, if login was successful, sessions will have been created that will then further redirect verified users.
			#redirect('admin/login');
			redirect('admin/auth');
		}
		else
		{
			// Set validation errors.
			$msg = validation_errors('<p class="error_msg">', '</p>');
			$this->session->set_flashdata('msg',$msg);
			return FALSE;
		}
	}




	/**
	 * login_via_ajax
	 * Attempt to log a user in via ajax.
	 * This example is a much more simplified version of the above 'login' function example as it just returns a boolean value of
	 * whether or not the submitted details successfully logged a user in - no redirects or status messages are set.
	 */
	function login_via_ajax()
	{
		$remember_user = ($this->input->post('remember_me') == 1);

		// Verify login data.
		return $this->flexi_auth->login($this->input->post('login_identity'), $this->input->post('login_password'), $remember_user);
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Account Registration
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * register_account
	 * Create a new user account. 
	 * Then if defined via the '$instant_activate' var, automatically log the user into their account.
	 */
        function register_account()
	{
		$this->load->library('form_validation');

		// Set validation rules.
		// The custom rules 'identity_available' and 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'register_first_name', 'label' => 'First Name', 'rules' => 'required'),
			array('field' => 'register_last_name', 'label' => 'Last Name', 'rules' => 'required'),
			array('field' => 'register_phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
			//array('field' => 'register_newsletter', 'label' => 'Newsletter', 'rules' => 'integer'),
			array('field' => 'register_email_address', 'label' => 'Email Address', 'rules' => 'required|valid_email|identity_available'),
			array('field' => 'register_username', 'label' => 'Username', 'rules' => 'required|min_length[4]|identity_available'),
			array('field' => 'register_password', 'label' => 'Password', 'rules' => 'required|validate_password'),
			array('field' => 'register_confirm_password', 'label' => 'Confirm Password', 'rules' => 'required|matches[register_password]'),
			array('field' => 'register_company', 'label' => 'company', 'rules' => 'required'),
//			array('field' => 'register_country', 'label' => 'Country', 'rules' => 'required'),
//			array('field' => 'register_zone', 'label' => 'zone', 'rules' => 'required')
		);

		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get user login details from input.
			$email = $this->input->post('register_email_address');
			$username = $this->input->post('register_username');
			$password = $this->input->post('register_password');
			
			// Get user profile data from input.
			// You can add whatever columns you need to customise user tables.
			$profile_data = array(
				'upro_first_name' => $this->input->post('register_first_name'),
				'upro_last_name' => $this->input->post('register_last_name'),
				'upro_phone' => $this->input->post('register_phone_number'),
				'upro_company' => $this->input->post('register_company'),
				'upro_country_id' => $this->input->post('register_country'),
				'upro_zone_id' => $this->input->post('register_zone'),
				'upro_city_id' => $this->input->post('register_city'),
				'upro_newsletter' => ''
			);
                        $profile_data['emp_id']="";
                        $profile_data['uacc_color_code']=$this->input->post('register_color_code');

                        
                        $client_info = array(
                            'customer_company_name' => $this->input->post('register_company'),
                            'customer_address' => $this->input->post('register_company_address'),
                            'customer_company_slug' => trim($this->input->post('register_company_slug')),
                            //'customer_district' => 'client district',
                            //'customer_city' => 'client city',
                            'customer_theme_color'=>$this->input->post('register_color_code')
			);
                        
//                        print_r($client_info);
//                        die;

			// Set whether to instantly activate account.
			// This var will be used twice, once for registration, then to check if to log the user in after registration.
			$instant_activate = FALSE;
			$instant_activate = TRUE;
			// Note: An account activation email will be automatically sent if auto activate is FALSE, or if an activation time limit is set by the config file.
                        
                        #$default_group_id = '1'; // Arresto Root ID 
                        
                        $default_group_id = '3'; // Arresto Root ID 
                        
                        
                        // check if client group is not exist 
                        $client_slug=$client_group_name=$profile_data['upro_company'];
                        $client_slug=trim($this->input->post('register_company_slug'));
                        $group =    $this->flexi_auth->get_groups_row_array('',array('ugrp_name'=>$client_group_name));
                         
                         #print_r($group);
                         //die;

                        if(count($group)){
                            $this->session->set_flashdata('message','This Client Company Name is already reistered with Arresto System! try with other one ');
                            $this->session->set_flashdata('msg','This Client Company Name is already reistered with Arresto System! try with other one ');
                            return false;
                        }
                        
                      // uplaod the client logo and update the clientinfo 
                       if(isset($_FILES) && $_FILES['register_client_logo']['name']!=""){
                           //$image_path="./admin_uploads/images/users/";
                           $image_path="./clients_uploads/images/clients/";
                           
                            $image_info = $this->upload_image_new('register_client_logo',$image_path);                           
                            $profile_data['uacc_logo']=$image_info['image'];
                            $profile_data['uacc_logo_path']=$image_info['image_path'];
                            
                            $client_info['customer_logo']=$image_info['image'];
                            $client_info['customer_logo_path']=$image_info['image_path'];

                       }
                       
//                       echo "<pre>";
//                       print_r($profile_data);
                       //die;

		    $response = $this->flexi_auth->insert_user($email, $username, $password, $profile_data, $default_group_id, $instant_activate);
                        
			if ($response){
				// This is an example 'Welcome' email that could be sent to a new user upon registration.
				//$email_data = array('identity' => $email);
				
				//$this->flexi_auth->send_email($email, 'Welcome', 'registration_welcome.tpl.php', $email_data);
				// Note: The 'registration_welcome.tpl.php' template file is located in the '../views/includes/email/' directory defined by the config file.

                               // insert the othere information to the client 
                                $client_info['customer_uacc_fk']=$response;
                                $this->insert_data($client_info,"ar_clients_2018");
                                                                         
				###+++++++++++++++++###
                                //create the new group
                                $groupe_name=$profile_data['upro_company'];
                                $group_desc="Arresto Client Super Admin Group";
                                $group_pid=1; // arresto root id
                                $is_admin=1;        
                               # $client_group_id=$this->flexi_auth->insert_group($groupe_name,$group_desc,$is_admin,array('ugrp_pid'=>1,'ugrp_type'=>'ARRESTO_CLIENT','ugrp_client_fk'=>$response));
                                
                                // insert 22-08-2018 changes 
                                $client_group_data=array("cgrp_pid"=>1,  // arresto root id 
                                                    "cgrp_name"=>$groupe_name,
                                                    "cgrp_desc"=>$group_desc,
                                                    "cgrp_admin"=>1,
                                                    "cgrp_client_fk"=>$response,
                                                    "cgrp_type"=>"CLIENT_ROOTNODE"
                                                 );
                                
                               $client_group_id=$this->db->insert("client_groups",$client_group_data);

                                // set the default previlages to CLIENT ADMIN group
                                // can be read from config file 
                                $default_privilege=array(1,2,3,4,5,6,7,8,9,10,11);
                                if($client_group_id){
                                    foreach($default_privilege as $key=>$priv){
                                       # $this->flexi_auth->insert_user_group_privilege($client_group_id,$priv);
                                    }
                                }

				// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
				$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
                                
                                //echo "done"; die;
                               redirect($client_slug);
			}
                }else{
                  // Set validation errors.
                   // echo validation_errors();
                    $this->session->set_flashdata('message',validation_errors());
                    $this->data['message'] = validation_errors();
                    
                }

		return FALSE;
	}
        
        
    public function insert_data($data, $table_name) {
            $result = $this->db->insert($table_name, $data);
            if($result) {
                return $this->db->insert_id();
            } else {
               return 0; 
        }
    }
   
        
        
        
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Account Activation
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * resend_activation_token
	 * Resends a new account activation token to a users email address.
	 */
	function resend_activation_token()
	{	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('activation_token_identity', 'Identity (Email / Login)', 'required');
		
		// Run the validation.
		if ($this->form_validation->run())
		{					
			// Verify identity and resend activation token.
			//$email = $this->input->post('activation_token_identity');

			$response = $this->flexi_auth->resend_activation_token($this->input->post('activation_token_identity'));
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());

			// Redirect user.
			if(isset($_SESSION['user_from']) && $_SESSION['user_from']=='infonet'){
				($response) ? redirect('Infonet') : redirect('Infonet/resend_activation_token');
			}else{
				($response) ? redirect('auth') : redirect('auth/resend_activation_token');
			}
			
		}
		else
		{	
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			return FALSE;
		}
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Reseting Passwords
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * forgotten_password
	 * Sends a 'Forgotten Password' email to a users email address. 
	 * The email will contain a link that redirects the user to the site, a token within the link is verified, and the user can then manually reset their password.
	 */
	function forgotten_password()
	{

		$this->load->library('form_validation');

		$this->form_validation->set_rules('forgot_password_identity', 'Identity (Email / Login)', 'required');
		
		// Run the validation.
		if ($this->form_validation->run())
		{
			
			// The 'forgotten_password()' function will verify the users identity exists and automatically send a 'Forgotten Password' email.
			$response = $this->flexi_auth->forgotten_password($this->input->post('forgot_password_identity'));
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			// Redirect user.
                        redirect('admin/auth/');
                    
		}
		else
		{
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			return FALSE;
		}
	}
	
	/**
	 * manual_reset_forgotten_password
	 * This example lets the user manually reset their password rather than automatically sending them a new random password via email.
	 * The function validates the user via a token within the url of the current site page, then validates their current and newly submitted passwords are valid.
	 */
	function manual_reset_forgotten_password($user_id, $token)
	{
		$this->load->library('form_validation');

		// Set validation rules
		// The custom rule 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'required|validate_password|matches[confirm_new_password]'),
			array('field' => 'confirm_new_password', 'label' => 'Confirm Password', 'rules' => 'required')
		);
		
		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get password data from input.
			$new_password = $this->input->post('new_password');
			// The 'forgotten_password_complete()' function is used to either manually set a new password, or to auto generate a new password.
			// For this example, we want to manually set a new password, so ensure the 3rd argument includes the $new_password var, or else a  new password.
			// The function will then validate the token exists and set the new password.
			$this->flexi_auth->forgotten_password_complete($user_id, $token, $new_password);

			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			
			redirect('admin/auth');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			
			return FALSE;
		}
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Manage User Account
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * update_account
	 * Updates a users account and profile data.
	 */
	function update_account()
	{

		$this->load->library('form_validation');

		// Set validation rules.
		// The custom rule 'identity_available' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'update_first_name', 'label' => 'First Name', 'rules' => 'required'),
			array('field' => 'update_last_name', 'label' => 'Last Name', 'rules' => 'required'),
			array('field' => 'update_phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
		//	array('field' => 'update_newsletter', 'label' => 'Newsletter', 'rules' => 'integer'),
			array('field' => 'update_email', 'label' => 'Email', 'rules' => 'required|valid_email|identity_available'),
			array('field' => 'update_username', 'label' => 'Username', 'rules' => 'min_length[4]|identity_available')
		);
		
		$this->form_validation->set_rules($validation_rules);
		
		// Run the validation.
		if ($this->form_validation->run())
		{
			// Note: This example requires that the user updates their email address via a separate page for verification purposes.

			// Get user id from session to use in the update function as a primary key.
			$user_id = $this->flexi_auth->get_user_id();
			
			
			/* User Profile Image Section edited by Shakti Singh */
				$profile_image = $this->input->post('profile_image');

				if(isset($_FILES) && !empty($_FILES['user_image']['name'])){
					$new_image_name = $this->upload_image();
					if($profile_image != ''){
						$old_pic_path = "./uploads/images/users/".$profile_image;
						$del = $this->delete_old_pic($old_pic_path);
					}
				}
				
				if($profile_image !='' && !isset($new_image_name)){
					$imageName = $profile_image;
				}else{
					$imageName = (isset($new_image_name))? $new_image_name : '';
				}
				$this->session->set_userdata('userImage',$imageName);
				$this->session->set_userdata('firstName',$this->input->post('update_first_name'));
				$this->session->set_userdata('lastName',$this->input->post('update_last_name'));
			/* End of User Profile Image and his first and last name */
			
			// Get user profile data from input.
			// IMPORTANT NOTE: As we are updating multiple tables (The main user account and user profile tables), it is very important to pass the
			// primary key column and value in the $profile_data for any custom user tables being updated, otherwise, the function will not
			// be able to identify the correct custom data row.
			// In this example, the primary key column and value is 'upro_uacc_fk' => $user_id.
			$profile_data = array(
				'upro_uacc_fk' => $user_id,
				'upro_first_name' => $this->input->post('update_first_name'),
				'upro_last_name' => $this->input->post('update_last_name'),
				'upro_phone' => $this->input->post('update_phone_number'),
				'upro_image' => $imageName,
			//	'upro_newsletter' => $this->input->post('update_newsletter'),
				$this->flexi_auth->db_column('user_acc', 'email') => $this->input->post('update_email'),
				$this->flexi_auth->db_column('user_acc', 'username') => $this->input->post('update_username')
			);
			
			// This is added for Infonet Client registration. $user_group=11
			$profile_data['emp_id'] = (isset($_POST['emp_id']))? $this->input->post('emp_id') : '';
			$profile_data['emp_region'] = (isset($_POST['emp_region']))? $this->input->post('emp_region') : '';
			
			// If we were only updating profile data (i.e. no email or username included), we could use the 'update_custom_user_data()' function instead.
			$response = $this->flexi_auth->update_user($user_id, $profile_data);
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());

			// Redirect user.
			// echo "<pre>";
			// print_r($_SESSION);
			// die;
                        redirect('admin/auth_public/update_account');
		}
		else
		{
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			return FALSE;
		}
	}
	


	function update_profile()
	{

		$this->load->library('form_validation');

		// Set validation rules.
		// The custom rule 'identity_available' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'required'),
			array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'required'),
			array('field' => 'phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
		//	array('field' => 'update_newsletter', 'label' => 'Newsletter', 'rules' => 'integer'),
			array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|identity_available'),
		);
		
		$this->form_validation->set_rules($validation_rules);


		
		// Run the validation.
		if ($this->form_validation->run())
		{
			// Note: This example requires that the user updates their email address via a separate page for verification purposes.

			// Get user id from session to use in the update function as a primary key.
			$user_id = $this->flexi_auth->get_user_id();
			
			
			/* User Profile Image Section edited by Shakti Singh */
			/*	$profile_image = $this->input->post('profile_image');

				if(isset($_FILES) && !empty($_FILES['user_image']['name'])){
					$new_image_name = $this->upload_image();
					if($profile_image != ''){
						$old_pic_path = "./uploads/images/users/".$profile_image;
						$del = $this->delete_old_pic($old_pic_path);
					}
				}
				
				if($profile_image !='' && !isset($new_image_name)){
					$imageName = $profile_image;
				}else{
					$imageName = (isset($new_image_name))? $new_image_name : '';
				}
				$this->session->set_userdata('userImage',$imageName);
				$this->session->set_userdata('firstName',$this->input->post('update_first_name'));
				$this->session->set_userdata('lastName',$this->input->post('update_last_name'));*/


			/* End of User Profile Image and his first and last name */
			
			// Get user profile data from input.
			// IMPORTANT NOTE: As we are updating multiple tables (The main user account and user profile tables), it is very important to pass the
			// primary key column and value in the $profile_data for any custom user tables being updated, otherwise, the function will not
			// be able to identify the correct custom data row.
			// In this example, the primary key column and value is 'upro_uacc_fk' => $user_id.
                        
			$profile_data = array(
				'upro_uacc_fk' => $user_id,
				'upro_first_name' => $this->input->post('first_name'),
				'upro_last_name' => $this->input->post('last_name'),
				'upro_phone' => $this->input->post('phone_number')
			);
			if($imageName){
				$profile_data['upro_image']= $imageName;
			}
			
			
			// If we were only updating profile data (i.e. no email or username included), we could use the 'update_custom_user_data()' function instead.
			$response = $this->flexi_auth->update_user($user_id, $profile_data);
/*
			echo $this->db->last_query();
			die;*/
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());

			// Redirect user.
			// echo "<pre>";
			// print_r($_SESSION);
			// die;
			redirect('admin/auth_admin/profile');

		}
		else
		{
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			return FALSE;
		}
	}


	/*
		* upload_image
		* Upload user profile image.
		* Shakti Singh
	*/
        
        function upload_image_new($file_field,$file_path){
		$image_name = time();
		$file_name = "logo_".$image_name.".".pathinfo($_FILES[$file_field]["name"], PATHINFO_EXTENSION);
                
                if($this->check_directory($file_path)){
                     $new_loc = $file_path.$file_name;  
                }
		if(move_uploaded_file($_FILES[$file_field]["tmp_name"], $new_loc)){
                
                    return array('image'=>$file_name,'image_path'=>$new_loc);
                    //return $file_name;
		}
	}
        
        
        
	function upload_image(){
		
		$image_name = time();
		$file_name = $image_name.".".pathinfo($_FILES["user_image"]["name"], PATHINFO_EXTENSION);
		$new_path = "./uploads/images/users/";
		$this->check_directory($new_path);
		$new_loc = $new_path.$file_name;		
		if(move_uploaded_file($_FILES["user_image"]["tmp_name"], $new_loc)){
			
			$this->image_resize($file_name);
			$newImageName = $image_name.'_thumb'.".".pathinfo($_FILES["user_image"]["name"], PATHINFO_EXTENSION);
			$old_pic = "./uploads/images/users/".$file_name;
			$this->delete_old_pic($old_pic);
			return $userImageName	=	$newImageName;
		}
	}
	
	public function image_resize($file_name){
		
		$thumbPath = "./uploads/images/users/".$file_name;
		$config['image_library'] = 'gd2';
		$config['source_image']	= $thumbPath;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width']	= 300;
		$config['height']	= 600;

		$this->load->library('image_lib', $config);
		
		if ( ! $this->image_lib->resize()){
			$this->session->set_flashdata('msg',$this->image_lib->display_errors('<p>', '</p>'));
		}
	}
	
	
	
	/*
		* check_directory
		* check for directory if it exists in the specified place.
		* if does not exist then creats one directory.
		* Shakti Singh
	*/
	function check_directory($new_path){
		
		if(is_dir($new_path)) {
			return true;
        } else {
                $old_umask = umask(0);
                mkdir($new_path, 0777,1);
                umask($old_umask);
                //mkdir($new_path , 0777);
		return true;
		}
	}
	
	/*
		* delete_old_pic
		* if new profile image id uplaoded then delete the old stored pic from folder.
		* Shakti Singh
	*/
	function delete_old_pic($pic){
		if(file_exists($pic)){
			if(!unlink($pic)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;;
		}
	}
	
	/**
	 * change_password
	 * Updates a users password.
	*/
	function change_password()
	{
		$this->load->library('form_validation');

		// Set validation rules.
		// The custom rule 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'current_password', 'label' => 'Current Password', 'rules' => 'required'),
			array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'required|validate_password|matches[confirm_new_password]'),
			array('field' => 'confirm_new_password', 'label' => 'Confirm Password', 'rules' => 'required')
		);
		
		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get password data from input.
			$identity = $this->flexi_auth->get_user_identity();
			$current_password = $this->input->post('current_password');
			$new_password = $this->input->post('new_password');			
			
			// Note: Changing a password will delete all 'Remember me' database sessions for the user, except their current session.
			$response = $this->flexi_auth->change_password($identity, $current_password, $new_password);
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			// Redirect user.
			// Note: As an added layer of security, you may wish to email the user that their password has been updated.
                        redirect('admin/auth_admin/dashboard');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			return FALSE;
		}
	}
	

	
	/**
	 * send_new_email_activation
	 * This demo has 2 methods of updating a logged in users email address.
	 * The first option simply allows the users to change their email address along with the rest of their account data via entering it into a form fields.
	 * The second option requires users to verify their email address via clicking a link that is sent to that same email address.
	 * The purpose of the second option is to prevent users entering a mispelt email address, which would then prevent the user from logging back in.
	 */
	function send_new_email_activation()
	{
		$this->load->library('form_validation');

		// Set validation rules.
		// The custom rule 'identity_available' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array(
			array('field' => 'email_address', 'label' => 'Email', 'rules' => 'required|valid_email|identity_available'),
		);
		
		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			$user_id = $this->flexi_auth->get_user_id();
			
			// The 'update_email_via_verification()' function generates a verification token that is then emailed to the user.
			$this->flexi_auth->update_email_via_verification($user_id, $this->input->post('email_address'));
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			
			redirect('auth_public/dashboard');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			
			return FALSE;
		}
	}
	
	/**
	 * verify_updated_email
	 * Verifies a token within the current url and updates a users email address. 
	 */
	function verify_updated_email($user_id, $token)
	{
		// Verify the update email token and if valid, update their email address.
		$this->flexi_auth->verify_updated_email($user_id, $token);
		
		// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
		$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
		
		// Redirect user.
		// Logged in users are redirected to the restricted public user dashboard, otherwise the user is redirected to the login page.
		if ($this->flexi_auth->is_logged_in())
		{
			redirect('auth_public/dashboard');
		}
		else
		{
			if(isset($_SESSION['user_from']) && $_SESSION['user_from']=='infonet'){
				redirect('auth/login');
			}else{
				redirect('Infonet/login');
			}
		}
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Manage User Address Book
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * manage_address_book
	 * Loops through a POST array of all address IDs that where checked, and then proceeds to delete the addresses from the users address book.
	 * Note: The address book table ('demo_user_address') is used in this demo as an example of relating additional user data to the auth libraries account tables. 
	 */
	function manage_address_book()
	{
		// Delete addresses.
		if ($delete_addresses = $this->input->post('delete_address'))
		{
			foreach($delete_addresses as $address_id => $delete)
			{
				// Note: As the 'delete_address' input is a checkbox, it will only be present in the $_POST data if it has been checked,
				// therefore we don't need to check the submitted value.
				$this->flexi_auth->delete_custom_user_data('demo_user_address', $address_id);
			}
		}

		// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
		$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
		
		// Redirect user.
		redirect('auth_public/manage_address_book');
	}
	
	/**
	 * insert_address
	 * Inserts a new address to the users address book.
	 * Note: The address book table ('demo_user_address') is used in this demo as an example of relating additional user data to the auth libraries account tables. 
	 */
	function insert_address()
	{	
		$this->load->library('form_validation');

		// Set validation rules.
		$validation_rules = array(
			array('field' => 'insert_alias', 'label' => 'Address Alias', 'rules' => 'required'),
			array('field' => 'insert_recipient', 'label' => 'Recipient', 'rules' => 'required'),
			array('field' => 'insert_phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
			array('field' => 'insert_address_01', 'label' => 'Address Line #1', 'rules' => 'required'),
			array('field' => 'insert_city', 'label' => 'City / Town', 'rules' => 'required'),
			array('field' => 'insert_county', 'label' => 'County', 'rules' => 'required'),
			array('field' => 'insert_post_code', 'label' => 'Post Code', 'rules' => 'required'),
			array('field' => 'insert_country', 'label' => 'Country', 'rules' => 'required'),
			//array('field' => 'insert_company', 'label' => '', 'rules' => ''),
			//array('field' => 'insert_address_02', 'label' => '', 'rules' => '')
		);
		
		$this->form_validation->set_rules($validation_rules);
		
		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get user id from session to use in the insert function as a primary key.
			$user_id = $this->flexi_auth->get_user_id();
			
			// Get user address data from input.
			// You can add whatever columns you need to custom user tables.
			$address_data = array(
				'uadd_alias' => $this->input->post('insert_alias'),
				'uadd_recipient' => $this->input->post('insert_recipient'),
				'uadd_phone' => $this->input->post('insert_phone_number'),
				'uadd_company' => $this->input->post('insert_company'),
				'uadd_address_01' => $this->input->post('insert_address_01'),
				'uadd_address_02' => $this->input->post('insert_address_02'),
				'uadd_city' => $this->input->post('insert_city'),
				'uadd_county' => $this->input->post('insert_county'),
				'uadd_post_code' => $this->input->post('insert_post_code'),
				'uadd_country' => $this->input->post('insert_country')
			);		
	
			$response = $this->flexi_auth->insert_custom_user_data($user_id, $address_data);
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			
			// Redirect user.
			($response) ? redirect('auth_public/manage_address_book') : redirect('auth_public/insert_address');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			
			return FALSE;
		}
	}
	
	/**
	 * update_address
	 * Updates an address from the users address book.
	 * Note: The address book table ('demo_user_address') is used in this demo as an example of relating additional user data to the auth libraries account tables. 
	 */
	function update_address($address_id)
	{
		$this->load->library('form_validation');

		// Set validation rules.
		$validation_rules = array(
			array('field' => 'update_alias', 'label' => 'Address Alias', 'rules' => 'required'),
			array('field' => 'update_recipient', 'label' => 'Recipient', 'rules' => 'required'),
			array('field' => 'update_phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
			array('field' => 'update_address_01', 'label' => 'Address Line #1', 'rules' => 'required'),
			array('field' => 'update_city', 'label' => 'City / Town', 'rules' => 'required'),
			array('field' => 'update_county', 'label' => 'County', 'rules' => 'required'),
			array('field' => 'update_post_code', 'label' => 'Post Code', 'rules' => 'required'),
			array('field' => 'update_country', 'label' => 'Country', 'rules' => 'required'),
			//array('field' => 'update_company', 'label' => '', 'rules' => ''),
			//array('field' => 'update_address_02', 'label' => '', 'rules' => '')
		);
		
		$this->form_validation->set_rules($validation_rules);

		// Run the validation.
		if ($this->form_validation->run())
		{
			// Get user address data from input.
			// You can add whatever columns you need to custom user tables.
			$address_id = $this->input->post('update_address_id');
			
			$address_data = array(
				'uadd_alias' => $this->input->post('update_alias'),
				'uadd_recipient' => $this->input->post('update_recipient'),
				'uadd_phone' => $this->input->post('update_phone_number'),
				'uadd_company' => $this->input->post('update_company'),
				'uadd_address_01' => $this->input->post('update_address_01'),
				'uadd_address_02' => $this->input->post('update_address_02'),
				'uadd_city' => $this->input->post('update_city'),
				'uadd_county' => $this->input->post('update_county'),
				'uadd_post_code' => $this->input->post('update_post_code'),
				'uadd_country' => $this->input->post('update_country')
			);		
	
			// For added flexibility, to identify the table and row to update, you can either submit the table name and row id via the 
			// first 2 function arguments, or alternatively, submit the primary column name and row id value via the '$address_data' array.
			// An example of this is commented out just below. When using the second method, the function identifies the table automatically.
			$response = $this->flexi_auth->update_custom_user_data('demo_user_address', $address_id, $address_data);
			
			/**
			 *  Example of updating custom tables using just data within an array.
			 * 	$address_data = array(
			 * 		'uadd_id' => $address_id,
			 *		'uadd_alias' => $this->input->post('update_alias'),
			 *		'uadd_recipient' => $this->input->post('update_recipient')
			 * 		// ... etc ... // 
			 *	);
			 * 	$response = $this->flexi_auth->update_custom_user_data(FALSE, FALSE, $address_data);
			*/
							
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$this->session->set_flashdata('msg', $this->flexi_auth->get_messages());
			
			// Redirect user.
			($response) ? redirect('auth_public/manage_address_book') : redirect('auth_public/update_address');
		}
		else
		{		
			// Set validation errors.
			$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
			
			return FALSE;
		}
	}
        
        
     // new functions by sachin 
      function change_client_status(){
          
      }  
        
        

}
/* End of file demo_auth_model.php */
/* Location: ./application/models/demo_auth_model.php */