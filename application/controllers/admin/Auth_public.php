<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_public extends CI_Controller {
    
      private $client;
      private $base_url;
      private $client_url;
    
 
    function __construct() 
    {
        parent::__construct();

		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==1) 
		{
			$sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => true
			); 
			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(TRUE);
		}
		
		// Load required CI libraries and helpers.
		$this->load->database();
		$this->load->driver("session");
		$this->load->library('sma');
 		$this->load->helper('url');
 		$this->load->helper('form');


  		// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		$this->auth = new stdClass;


		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');


		// Check user is logged in via either password or 'Remember me'.
		// Note: Allow access to logged out users that are attempting to validate a change of their email address via the 'update_email' page/method.
		if (! $this->flexi_auth->is_logged_in() && $this->uri->segment(2) != 'update_email')
		{
                    // Set a custom error message.
                    $this->session->set_flashdata('msg', '<p class="alert alert-danger capital">You must login to access this area.p>');
                    redirect('auth');
		}
		
		// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
		
		$this->load->vars('base_url',base_url('admin')."/");
		$this->load->vars('includes_dir',base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		// Define a global variable to store data that is then used by the end view page.
		$this->data = null;
		$this->data['lang']  = $this->sma->get_lang_level('first');
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// flexi auth demo
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * Many of the functions within this controller load a custom model called 'demo_auth_model' that has been created for the purposes of this demo.
	 * The 'demo_auth_model' file is not part of the flexi auth library, it is included to demonstrate how some of the functions of flexi auth can be used.
	 *
	 * These demos show working examples of how to implement some (most) of the functions available from the flexi auth library.
	 * This particular controller 'auth_public', is used by users who have logged in and now wish to manage their account settings
	 * 
	 * All demos are to be used as exactly that, a demonstation of what the library can do.
	 * In a few cases, some of the examples may not be considered as 'Best practice' at implementing some features in a live environment.
	*/
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Dashboard
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * index
	 * Forwards to the public dashboard.
	 */ 
        
	function index(){
                 redirect('admin/auth_admin/dashboard');
	}
        
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Public Account Management
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

 	/**
 	 * update_account
 	 * Manage and update the account details of a logged in public user.
 	 */
	function update_account()
	{
            
            /*echo "<pre>";
            print_r($_SESSION); die;*/
            
		// If 'Update Account' form has been submitted, update the user account details.
		if ($this->input->post('update_account')) 
		{
			$this->load->model('arresto_auth_model');
			$this->arresto_auth_model->update_account();
		}
		
		// Get users current data.
		// This example does so via 'get_user_by_identity()', however, 'get_users()' using any other unqiue identifying column and value could also be used.
		$this->data['user'] = $this->flexi_auth->get_user_by_identity_row_array();
		//print_r($this->data['user']);die;
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
                
                $this->load->view('adminpanel/public/account_update_view', $this->data);
              
	}

 	/**
 	 * change_password
 	 * Manually update the logged in public users password, by submitting the current and new password.
 	 * This example requires that the length of the password must be between 8 and 20 characters, containing only alpha-numerics plus the following 
 	 * characters: periods (.), commas (,), hyphens (-), underscores (_) and spaces ( ). These customisable validation settings are defined via the auth config file.
 	 */
	function change_password()
	{
		// If 'Update Password' form has been submitted, validate and then update the users password.
		if ($this->input->post('change_password'))
		{
			$this->load->model('arresto_auth_model');
			$this->arresto_auth_model->change_password();
		}
				
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('adminpanel/public/password_update_view', $this->data);
	}

 	/**
 	 * update_email
 	 * Update the current logged in users email address via sending a verification email.
 	 * This example with send a verification email to the users newly entered email address, once they click a link within that email, their account will be
 	 * updated with the new email address. 
 	 * The purpose of verification via email ensures that a user enters their correct email address. If they were to unknowingly mispell the address, the next time
 	 * they tried to login to site, their email address would no longer be recognised, and they would then be completely locked out of their account.
 	 */
	function update_email($user_id = FALSE, $token = FALSE)
	{
		$this->load->model('demo_auth_model');

		// If 'Update Email' form has been submitted, send a verification email to the submitted email address.
		if ($this->input->post('update_email'))
		{
			$this->demo_auth_model->send_new_email_activation();
		}
		// Else if page has been accessed via a link set in the verification email, then validate the activation token and update the email address.
		else if (is_numeric($user_id) && strlen($token) == 40) // 40 characters = Email Activation Token length.
		{
			$this->demo_auth_model->verify_updated_email($user_id, $token);
		}

		// In this demo, the 'update_email' page is the only page in this controller that can be accessed without needing to be logged in.
		// This is because, some users may validate their change of email address at a later time, or from a different device that they are not logged in on.
		// Therefore, check that the user is logged in before granting them access to the 'update_email' page.
		if ($this->flexi_auth->is_logged_in())
		{
			// Set any returned status/error messages.
			$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
			
			$this->load->view('userpanel/public/email_update_view', $this->data);
		}
		else
		{
			redirect('auth/login');
		}
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Manage Address Book
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

 	/**
 	 * manage_address_book
 	 * Manage and update the address book of the logged in public user.
 	 * This page is simply an example of using the auth library to save miscellaneous user details to the database and then linking them to the auth user profile.
 	 */
	function manage_address_book()
	{
		// If 'Address Book' form has been submitted, then delete any checkbox checked address details.
		if ($this->input->post('update_addresses')) 
		{
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->manage_address_book();
		}

		// Get user id from session.
		$user_id = $this->flexi_auth->get_user_id();
		
		// Select address book data to be displayed, whilst filtering by addresses that match the user.
		$sql_select = array('uadd_id', 'uadd_alias', 'uadd_recipient', 'uadd_company', 'uadd_post_code');
		$sql_where = array('uadd_uacc_fk' => $user_id);
		// Note: The third argument is set as FALSE so that the query is not grouped by the user id - which would prevent multiple addresses being returned.
		$this->data['addresses'] = $this->flexi_auth->get_custom_user_data_array($sql_select, $sql_where, FALSE);
	
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('userpanel/public/address_view', $this->data);		
	}
	
 	/**
 	 * insert_address
 	 * Insert a new address to the logged in public users address book.
 	 * This page is simply an example of using the auth library to save miscellaneous user details to the database and then linking them to the auth user profile.
 	 */
	function insert_address()
	{
		// If 'Add Address' form has been submitted, then insert the new address details to the logged in users address book.
		if ($this->input->post('insert_address')) 
		{		
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->insert_address();
		}
				
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('userpanel/public/address_insert_view', $this->data);		
	}

 	/**
 	 * update_address
 	 * Update an existing address from the logged in public users address book.
 	 * This page is simply an example of using the auth library to save miscellaneous user details to the database and then linking them to the auth user profile.
 	 */
	function update_address($address_id = FALSE)
	{
		// Check the url parameter is a valid address id, else redirect to the dashboard.
		if (! is_numeric($address_id))
		{
			redirect('auth_public/dashboard');
		}
		// If 'Update Address' form has been submitted, then update the address details.
		else if ($this->input->post('update_address')) 
		{			
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->update_address($address_id);
		}
		
		// Get user id from session to use in the update function as a primary key.
		$user_id = $this->flexi_auth->get_user_id();
		$sql_where = array('uadd_id' => $address_id, 'uadd_uacc_fk' => $user_id);
		$this->data['address'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('userpanel/public/address_update_view', $this->data);		
	}
}
	
/* End of file auth_public.php */
/* Location: ./application/controllers/auth_public.php */	
