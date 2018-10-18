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
		$this->client_name = $_SESSION['client_slug'];

		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');
                                
                if($this->validate_client_slug()){
                    $this->client=$_SESSION['client'];
                    $this->client_url=$_SESSION['client']['url_slug']."/";
                }


		// Check user is logged in via either password or 'Remember me'.
		// Note: Allow access to logged out users that are attempting to validate a change of their email address via the 'update_email' page/method.
		if (! $this->flexi_auth->is_logged_in() && $this->uri->segment(2) != 'update_email')
		{
			// Set a custom error message.
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">You must login to access this area.p>');
			redirect('auth');
		}
		
		// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
		
		$this->load->vars('base_url',base_url().$this->client_url);
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
		redirect($this->client_url.'auth_public/dashboard');
	}
        
        
        function validate_client_slug(){
        $client_slug  = $this->uri->segment(1);
        $service_slug =   $this->uri->segment(2);

        //check session if valid client already set 
            $client=$this->session->userdata('client');
            if(isset($client['url_slug'])){
                  $urlArr= explode("/", $client['url_slug']);
                  if( $urlArr[0]==$client_slug && $urlArr[1]==$service_slug){
                        return 1;
                  }else{
                       redirect($client['url_slug'].'/auth/logout');
                       return 0;   
                  }

            }
        }
        
        
 
 	/**
 	 * dashboard (Public)
 	 * The public account dashboard page that acts as the landing page for newly logged in public users.
 	 * The dashboard provides links to some examples of the features available from the flexi auth library.  
 	 */
	function dashboard()
	{
		$groupArray = $_SESSION['flexi_auth']['group'];
		foreach($groupArray as $k=>$v){
			$groupID = $k;
		}
		
		if($groupID =='8'){
			$this->client_dashboard();
		}else{
			$this->data['message'] = $this->session->flashdata('message');
			$this->load->view('userpanel/public/dashboard_view', $this->data);
		}
		
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// client deshbord
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###

	function _getAllActionProposed(){
		$this->load->model('Subassets_model');
		$inspection_result_list	=	$this->Subassets_model->get_unique_observation_list_api();
		if($inspection_result_list){
			$array = array();
			foreach($inspection_result_list as $key=>$val){
				$res = '';
				$res	= $this->Subassets_model->get_inspection_observation_list_api($val);
				$array[] = array('id' => $val, 'value' => $res);		
			}
		}else{
			 $array = array('error' => 'No Data Found');
		}
		return $array;
	}
	
	function _masterData($siteid){
		$this->load->model('Assign_client_model');
		$masterdata = array();
		if(!empty($siteid)){
			$inspector_data = $this->Assign_client_model->inspecterData();
			//print_r($inspector_data);die;
			foreach($inspector_data as $k => $v){
				if(($siteid == $v['siteid'])){
					$masterdata = $v['inspector_address'];
				}
			}
		}
		return $masterdata;
	}
	
	function client_dashboard(){
	
		$userID = $this->session->flexi_auth['id'];
		$this->load->model('M_client_user_dashboard');
		$groupID = 8;
		$client_data = $this->M_client_user_dashboard->get_client_data_by_user($userID,$groupID);
		
		foreach($client_data as $cKey=>$CVal){
			$client_name_list = json_decode($CVal['client_ids'],true);
			if(in_array($userID,$client_name_list)){
				 $client_site_id[] = json_decode($CVal['site_id'],true);
				 if(!empty($client_site_id[0]) && is_array($client_site_id)){
					$siteid_marge = $client_site_id[0];
					$count = count($client_site_id)-1;
					for($i=1;$i<=$count;$i++){
						$siteid_marge = array_merge($siteid_marge,$client_site_id[$i]);
					}
				 }
			}
		}
		
		$siteid_marge = explode('/',implode('/',array_unique($siteid_marge)));
	
		if(!empty($siteid_marge)){
			$this->load->model('Assign_client_model');
			$this->load->model('Form_model');
			$this->load->model('Siteid_model');
			
			$sdata = $this->Assign_client_model->get_site_data($siteid_marge);
			
			$temp = array();
			if(!empty($sdata) && is_array($sdata)){
				$this->load->model('Api_model');
				$totalComponents 		= $this->Api_model->get_totalCount('components');
				$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
				$totalActionProposed 	= $this->_getAllActionProposed();
				$countAction 			= count($totalActionProposed);
				$inspector_address = '';
				foreach($sdata as $sKey=>$sVal){
						$temp[$sKey]['siteID_id'] = $sVal['siteID_id'];
						$temp[$sKey]['site_jobcard'] = $sVal['site_jobcard'];
						$temp[$sKey]['site_sms'] = $sVal['site_sms'];
						
						$clientName  = $this->Assign_client_model->get_clientName_siteID_Data(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
						$temp[$sKey]['clientName']  = $clientName['client_name'];
					   
						$temp[$sKey]['site_id'] = $sVal['site_id'];
						$temp[$sKey]['site_location'] = $sVal['site_location'];
						$temp[$sKey]['site_city'] = $sVal['site_city'];
						$temp[$sKey]['site_address'] = $sVal['site_address'];
						$temp[$sKey]['site_lattitude'] = $sVal['site_lattitude'];
						$temp[$sKey]['site_longitude'] = $sVal['site_longitude'];
						$temp[$sKey]['site_contact_name'] = $sVal['site_contact_name'];
						$temp[$sKey]['site_contact_number'] = $sVal['site_contact_number'];
						$temp[$sKey]['site_contact_email'] = $sVal['site_contact_email'];
						$temp[$sKey]['status'] = $sVal['status'];
						$temp[$sKey]['created_date'] = $sVal['created_date'];
						$temp[$sKey]['master_id'] = $sVal['master_id'];

						$inspector_address = $this->_masterData($sVal['siteID_id']);
						if(!empty($inspector_address)){
							$temp[$sKey]['inspector_details'] =  $inspector_address;
						}else{
							$temp[$sKey]['inspector_details'] = '';
						}

						$client_res = $this->Siteid_model->get_clientName_siteID(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
						$temp[$sKey]['totalAsset'] 			= 	"$totalComponents";
						$temp[$sKey]['totalSubAsset']			=	"$totalsubComponent";
						$temp[$sKey]['totalAction_proposed']	=	"$countAction";
						if(is_object($client_res)){
								$clientid = $client_res->mdata_client;
								$clientName = $client_res->client_name;
								$temp[$sKey]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
						}



                                                $reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
						if(!is_array($reportNo)){
								$temp[$sKey]['report_no'] = '';
								$temp[$sKey]['inspected_status'] = 'No';
								$temp[$sKey]['approved_status'] = 'Pending';
						}else{
								$temp[$sKey]['report_no'] 			= $reportNo['report_no'];
								$temp[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
								$temp[$sKey]['approved_status'] 	= $reportNo['approved_status'];
						}

							$work_number = '';
								$work_no = $this->Form_model->check_work_permit_number($sVal['siteID_id']);
								if(!$work_no){
										$work_number = 'WORK-000001';
								}else{
										$workNo_array 	= explode('-',$work_no['workPermit_number']);
										$newWorkNo		= $workNo_array[1] + 1;
										
										$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
								}
								$temp[$sKey]['workPermit_number'] 	= trim($work_number);

						
				}
			}
		}
		
		$data['client_site_data'] = $temp;
		
		$this->load->view('clientuser_dashboard',$data);
		
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
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->update_account();
		}
		
		// Get users current data.
		// This example does so via 'get_user_by_identity()', however, 'get_users()' using any other unqiue identifying column and value could also be used.
		$this->data['user'] = $this->flexi_auth->get_user_by_identity_row_array();
		//print_r($this->data['user']);die;
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='11'){
			$this->load->view('userpanel/infonet/account_update_view', $this->data);
		}else{
			$this->load->view('userpanel/public/account_update_view', $this->data);
		}
		
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
			$this->load->model('demo_auth_model');
			$this->demo_auth_model->change_password();
		}
				
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('userpanel/public/password_update_view', $this->data);
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
			redirect($this->client_url.'auth/login');
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
			redirect($this->client_url.'auth_public/dashboard');
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
