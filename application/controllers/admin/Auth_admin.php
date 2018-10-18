<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 #ini_set('display_errors',1);
 #error_reporting(E_ALL~E_NOTICE^E_WARNING);

class Auth_admin extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();
 		
		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2) 
		{
                    $sections = array(
                            'benchmarks' => TRUE, 'memory_usage' => TRUE, 
                            'config' => FALSE, 'controller_info' => TRUE, 'get' => FALSE, 'post' => FALSE, 'queries' => TRUE, 
                            'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
                    ); 
                    $this->output->set_profiler_sections($sections);
                    $this->output->enable_profiler(TRUE);
		}
		
		// Load required CI libraries and helpers.
		$this->load->database();
		$this->load->library('session');
		$this->load->library('sma');
 		$this->load->helper('url');
 		$this->load->helper('form');
 		$this->load->model('base_model');
 		$this->load->config('admin_msgs');
  		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password() ) 
		{
                    // Set a custom error message.
                    $this->session->set_flashdata('msg', '<p class="alert alert-danger capital">You must login as an admin to access this area.</p>');
                    redirect('admin');
		}

		// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
		$this->load->vars('base_url', base_url('admin')."/");
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		// Define a global variable to store data that is then used by the end view page.
		$this->data = null;
		$this->data['lang']  = $this->sma->get_lang_level('first');
		
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Quick Help Guide
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * db_column() function
	 * Columns defined using the 'db_column()' functions are referencing native table columns from the auth libraries 'user_accounts' table.
	 * Using the 'db_column()' function ensures if the column names are changed via the auth config file, then no further references to those table columns 
	 * within the CI installation should need to be updated, as the function will auto reference the config files updated column name.
	 * Native library column names can be defined without using this function, however, you must then ensure that all references to those column names are 
	 * updated throughout the site if later changed.
	 */

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Dashboard
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * index
	 * Forwards to the admin dashboard.
	 */ 
	function index(){
            $this->dashboard();
  
        }
        
    function dashboard(){
             //$this->load->view("admin_panel/dashboard",$data);
             
            $this->load->view("adminpanel/admin/dashboard",$data);
    }    


    
    function manage_client_accounts(){
        #echo "sachin"; die;
        $this->load->model('arresto_model');
        $data['clients']=$this->arresto_model->get_clients(); 
        //$this->load->view("admin_panel/client_accounts",$data);
        $this->load->view("adminpanel/admin/client_accounts",$data);
    }
                                         
        
  function send_email($data){
      
        $this->load->library('email');
         
	$config['smtp_host']	= 'smtp.elasticemail.com';
	$config['smtp_port']	= 2525;
	$config['smtp_user']	= 'b7de0525-1808-4214-83b6-dcfaabbbe996';
	$config['smtp_pass']	= 'b7de0525-1808-4214-83b6-dcfaabbbe996';
	$config['newline'] = "\r\n";
	$config['mailtype'] = 'html';
        
        $this->email->initialize($config);
        
        $this->email->to($data['mail_to']);
        $this->email->from($data['mail_from']);
        $this->email->cc($data['mail_cc']);
        $this->email->subject($data['mail_subject']);
        $this->email->message($data['mail_message']);
        
        if($this->email->send()){
            return true;            
        }else{
          
            $this->email->print_debugger();
            return false;
            echo 'mail not send';
        }
  }      

  
  
    function register(){
          #echo "register View"; die;
            $this->load->view("admin_panel/register",$data);
            
    }

    
    
    function client_edit($client_id){
        
        // get the client data 
        $this->load->model('arresto_model');
        
        $user=$this->arresto_model->get_clients($client_id);
      # echo "<pre>";
        #print_r($this->data);
        # print_r($user);

       if ($this->input->post('update_client')) {
           
            $this->load->model('arresto_auth_admin_model');
            $this->arresto_auth_admin_model->update_account_new($client_id);
        }

        // Get any status message that may have been set.
        $this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
        $this->data['header_data']=array('title'=>'Registration Form');

        $this->load->view('admin_panel/client_update_view',array('client'=>$user));

    } 
    
    
    function client_delete($client_id){

       if($client_id){
            $this->load->model('arresto_model');
             $user=$this->arresto_model->delete_client($client_id);
        }
        $data['clients']=$this->arresto_model->get_clients(); 
        $this->load->view("admin_panel/manage_client_accounts",$data);
    }
    
    
    function manage_language(){
        $this->load->model('arresto_model');
        
        if($this->input->post('lang_add')){
            //die;
           $action =$this->arresto_model->language_add();
           if($action){
               $this->session->set_flashdata('msg',"language added successfully");
               
           }else{
                //$this->session->flashdata('msg',"language added successfully");
           }
           redirect('admin/auth_admin/manage_language');
        }
        
        if($this->input->post('lang_upload_submit')){
                $this->import_language();
        }
        
        $lang_list=$this->arresto_model->get_lang_list();
        $this->load->view('adminpanel/admin/language_manage',array('lang_list'=>$lang_list));
        #$this->load->view('admin_panel/language_manage',array('lang_list'=>$lang_list));
    }
    
    
    
    public function import_language(){
		
		if(isset($_POST['lang_upload_submit'])&& $_SERVER['REQUEST_METHOD']=='POST'){
			
			$config['upload_path'] = "./uploads/xls/";
			$config['allowed_types'] = 'xls|xlsx|csv';
			//$config['max_size'] = '2048';
			$config['max_width'] = '0';
			$config['max_height'] = '0';	
			$this->load->library('upload',$config);
                        
      
			
			if(!$this->upload->do_upload('lang_file_upload')){
				// $error = array('error' => $this->upload->display_errors());
				 $error=$this->upload->display_errors();
				 $this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$error.'</div>');
				// print_r($error);
	
			}else{
				$upload_data=$this->upload->data();
                                
				// get uploaded file path 
                                $lang=$this->input->post('language');
				$file_path=$upload_data['full_path'];
				if($file_path){
					$result=$this->import_language_xls($lang,$file_path);
					if($result){
					  $this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
					//  unlink($file_path); // delete the uploded file 	
					 }else{
					  echo "<div class='alert alert-danger capital>file uploading problem</div>";	 
					}
				}
			}
		}
                
		redirect('admin/auth_admin/manage_language');
	}
    
    
    
        
        
       public function import_language_xls($lang,$file_path=null){
	
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
		//get only row iterator
		foreach($objWorksheet->getRowIterator() as $key=>$row){
			
			if($key==1) continue;
                        
                            if($this->isRowNotEmpty($row)){
                                
				$cellIterator=$row->getCellIterator();
                                
				foreach($cellIterator as $cell){   // component_sub_assets
					switch($cell->getColumn()){
						case 'B':
						$data[$key]['key']=trim($cell->getValue());
						break;
                                                case 'C':
						if($cell->getValue() != ''){
                                                    $data[$key]['text']=trim($cell->getValue());
							
						}else{
                                                    $data[$key]['text']='';
						}
                                                
						break;
					}// end of switch
					//$data[$key]['component_created_date'] = date('Y-m-d H:i:s',now());
				        $data[$key]['language'] = $lang;
				}// end celliterator
                }           
                                
                                
	    }// End row Iterator
            
            
            /*echo "<pre>"; 
            print_r($data);
            die;*/
            
            // insert data into database
            $result=$this->arresto_model->import_language_list($data);	
            if($result){
                    $this->session->set_flashdata('msg',"<div class='alert alert-success capital'>ASSETS LIST SUCCESSFULLY IMPORTED</div>");
                    return true;
            }else{
                    return false;
            }
		
	}

    
    
        function isRowNotEmpty($row)
	{
		foreach ($row->getCellIterator() as $cell) {
			if ($cell->getValue()) {
				return true;
			}
		}
		return false;
	}

      
    
    	function update_user_account12($user_id)
	{
		
		// Check user has privileges to update user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user accounts.</p>');
			redirect('auth_admin');
		}

		// If 'Update User Account' form has been submitted, update the users account details.
		if ($this->input->post('update_users_account')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_user_account($user_id);
		}
		
		// Get users current data.
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
	
		// Get user groups.
		$this->data['groups'] = $this->flexi_auth->get_groups_array();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/user_account_update_view', $this->data);
	}
    
    
    
    function client_delte($client_id){
       
       
       
    }




    function new_customer_invitation(){

    	#print_r($_POST);
    	#die;
    	#echo $this->config->item('notification_invite_success');
    	#echo 	$this->flexi_auth->get_user_identity() ; die;

        if($this->input->post('invite_submit')){

            $this->load->model('arresto_model');
               
	        $mail_to = explode(",", $_POST['mail_to']);
	        foreach($mail_to as $value_mail_to) {
	          $invite_data = array(
	            'invite_email'  => $value_mail_to,
	            'timestamp'     => time(),
	            'invite_code'   => $this->arresto_model->encrypt_decrypt_string($this->arresto_model->generate_token(),'e'),
	            'invite_flag'   => FALSE,
	            'invite_byuacc_fk'    => $this->flexi_auth->get_user_id(),
	          );

	          //print_r($invite_data);

	          $result = $this->arresto_model->insert_data($invite_data, 'ar_customer_invite');
	          $data['invite_code'] = $invite_data['invite_code'];
                  $data['invite_email']=$value_mail_to;

	          if($result) {
	            $email_data = array(
	              'mail_to'       => $value_mail_to,
	              'mail_cc'       => $_POST['mail_cc'],
	              'mail_subject'  => $_POST['mail_subject'],
	              'mail_from'     => $_POST['mail_from'],
	              'mail_message'  => $this->load->view('admin_panel/email_templates/admin_customer_invite', $data, true),
	            );

	            $this->session->set_flashdata('msg',$this->config->item('notification_invite_success'));
	            $result = $this->arresto_model->send_email($email_data);
	            redirect('admin/auth_admin/new_customer_invitation');
	          }
	        }  

        }else{

             #$this->session->set_flashdata('msg',$this->config->item('notification_invite_failure'));
       		 #$this->load->view('admin_panel/customer_invitation_mail');        
        }

        $this->load->view('admin_panel/customer_invitation_mail');  
        
    }




        
    
   function new_customer_approval(){
	 $this->load->view('admin_panel/customer_approval'); 
}






   function profile(){

   	#echo "profile"; die;
       $this->load->model('arresto_auth_model');
       
		if ($this->input->post('update_account')) 
		{
                $this->load->model('demo_auth_model');
                $this->arresto_auth_model->update_profile();
		}
		
       // get logged in user id 
    	$userid=$this->flexi_auth->get_user_id();
    	$user=$this->flexi_auth->get_user_by_id_row_array($userid);
        /*echo "<pre>";*/
    	#print_r($user);

       $data=array('user'=>$user);
       $this->load->view("admin_panel/account_profile",$data);

    }





    function update_password(){
    	 $data=array();
 		 $this->load->view('admin_panel/update_password');

    }


    function show_login(){

 		 $data = array(
					     'header_data' 	=> array (
								'title'		=> 'Arresto Customer Login Page',
								),
								'page'			=> 'admin_panel/login/login',
							);
                
           $this->load->view('admin_panel/tpl_login',$data);  
    }

    function forgot_password(){
    	#echo "sachin"; die;
         $data = array(
					     'header_data' 	=> array (
								'title'		=> 'Arresto Customer Login Page',
								),
								'page'			=> 'admin_panel/login/forgot_password',
							);
                
           $this->load->view('admin_panel/tpl_login',$data);          
     } 

    function reset_password(){
   			  $data = array(
					     'header_data' 	=> array (
								'title'		=> 'Arresto Customer Login Page',
								),
								'page'			=> 'admin_panel/login/reset_password',
							);
                
           $this->load->view('admin_panel/tpl_login',$data);  

   }



	
	function inspector_dashboard(){
		$loggedIn_user_id	= $this->session->flexi_auth['id'];
		$group_array 		= $this->session->flexi_auth['group'];
		foreach($group_array as $key=>$val){
			$this->data['group_id'] = $_SESSION['user_group'] = $group_id = $key;
			$this->data['group_name'] = $group_name = $val;
		}
		// echo "<pre>";
		// echo $group_id;
		// echo "</pre>";
	//	if($group_id != '9'){
		$this->data['msg'] = $this->session->flashdata('message');
		$this->load->model('Search_model');
		$result = $this->Search_model->get_client_dealer_detail_list();
		$result1 = $this->Search_model->get_district_circle_detail_list();
		$this->data['asset_series_data'] = $result2 = $this->Search_model->search_asset_series_data();		
		$this->data['asset_data'] = $result3 = $this->Search_model->search_asset_data();		
		$this->data['invoice_data'] = $result3 = $this->Search_model->search_invoice_data();

		// Insepection data Starts
		// Get Looged In User ID and search the values according to the logged in User.
		
		// $loggedIn_user_id	= $this->session->flexi_auth['id'];
		// $group_array 		= $this->session->flexi_auth['group'];
		// foreach($group_array as $key=>$val){
			// $this->data['group_id'] = $group_id = $key;
		// }
		
		//$this->data['alloted_inspection'] 		= $this->Search_model->search_alloted_inspection($loggedIn_user_id,$group_id);
		$this->data['approved_inspection'] 	= $approved_inspection	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Approved');
		$this->data['rejected_inspection'] 	= $rejected_inspection 	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Rejected');
		$this->data['pending_inspection'] 	= $pending_inspection	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Pending');
	

		$this->data['not_inspected_siteIDs'] 	= $not_inspected_siteIDs	= $this->Search_model->search_not_inspected_siteIDs($loggedIn_user_id,$group_id);
	
		$this->data['total_rejected'] 		= ($rejected_inspection !='0')? $rejected_inspection : '0';
		$this->data['total_approved'] 		= ($approved_inspection !='0')? $approved_inspection : '0';
		$this->data['total_pending'] 		= ($pending_inspection !='0')? $pending_inspection : '0';
		$this->data['total_not_inspected']	= ($not_inspected_siteIDs !='0')? $not_inspected_siteIDs : '0';
		// Insepection data End

		$client_array = array();
		$dealer_array = array();
		$circle_array = array();
		$district_array = array();
		//$asset_data_array = array();
		
		foreach($result as $value){
			$client_id = $value['client_id'];
			$client_name = $value['client_name'];
			$client_type = $value['client_type'];
			if($client_type == 15){
				$client_array[$client_id] = $client_name;
			}else if($client_type == 11){
				$dealer_array[$client_id] = $client_name;
			}
		}
		
		foreach($result1 as $value1){
			$circle = $value1['client_circle'];
			$circle_array[] = $circle;
			
			$district = $value1['client_district'];	
			$district_array[] = $district;			
		}
		
		
		$this->data['client']= $client_array;
		$this->data['dealer']= $dealer_array;
		$this->data['circle']= $circle_array;
		$this->data['district']= $district_array;
		
		$this->load->view('userpanel/admin/dashboard_view', $this->data);
	}



 	/**
 	 * dashboard (Admin)
 	 * The public account dashboard page that acts as the landing page for newly logged in public users.
 	 * The dashboard provides links to some examples of the features available from the flexi auth library.  
 	 */
	
	function dashboard_block_by_sachin(){
		$this->load->model('Search_model');
		$loggedIn_user_id	= $this->session->flexi_auth['id'];
		$group_array 		= $this->session->flexi_auth['group'];
		
        
		foreach($group_array as $key=>$val){
			$this->data['group_id'] = $_SESSION['user_group'] = $group_id = $key;
			$this->data['group_name'] = $group_name = $val;
		}
			
		//$this->data['alloted_inspection'] 		= $this->Search_model->search_alloted_inspection($loggedIn_user_id,$group_id);
		$this->data['approved_inspection'] 	= $approved_inspection	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Approved');
		$this->data['rejected_inspection'] 	= $rejected_inspection 	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Rejected');
		$this->data['pending_inspection'] 	= $pending_inspection	= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Pending');
	

		$this->data['not_inspected_siteIDs'] 	= $not_inspected_siteIDs	= $this->Search_model->search_not_inspected_siteIDs($loggedIn_user_id,$group_id);
	
		$this->data['total_rejected'] 		= ($rejected_inspection !='0')? $rejected_inspection : '0';
		$this->data['total_approved'] 		= ($approved_inspection !='0')? $approved_inspection : '0';
		$this->data['total_pending'] 		= ($pending_inspection !='0')? $pending_inspection : '0';
		$this->data['total_not_inspected']	= ($not_inspected_siteIDs !='0')? $not_inspected_siteIDs : '0';
		

		
		$this->data['asset_series_data'] = $result2 = $this->Search_model->search_asset_series_data();		
		$this->data['asset_data'] = $result3 = $this->Search_model->search_asset_data();
		
		$invoice_data = $this->Search_model->search_invoice();
		$invoice_data = array_column($invoice_data, 'mdata_material_invoice');
		$invoice_data = array_unique(array_diff(array_values(array_filter($invoice_data)),array('NA'))); 
		$this->data['invoice_data'] = explode(',',implode(',',$invoice_data));
		//print_r($this->data['invoice_data']);die;
		$this->data['client']= $this->sort_data('client_name');
		$this->data['circle']= $this->sort_data('client_circle');
		$this->data['district']= $this->sort_data('client_district');
		$this->load->view('userpanel/admin/dashboard', $this->data);
	}
	
	function sort_data($colume_name){
		if(!empty($colume_name)){
			$this->load->model('Search_model');
			$circle = $this->Search_model->fetch_district_circle_detail_list();
			$colume_data = array_column($circle, $colume_name);
			$colume_data = array_unique(array_values(array_filter($colume_data)));
			$columearray = array();
			foreach ($colume_data as $key => $value){
				$columearray[$key] = $value;
			}
			array_multisort($columearray, SORT_ASC, $colume_data);
			
			if(!empty($columearray) && is_array($columearray)){
				return $columearray;
			}else{
				return '';
			}
			
		}	
	}
	 
    function dashboard_old(){
		$groupArray = $_SESSION['flexi_auth']['group'];
		foreach($groupArray as $k=>$v){
			$groupID = $k;
		}
		if($groupID =='9'){
			$this->inspector_dashboard();
		}else{
			$loggedIn_user_id	= $this->session->flexi_auth['id'];
			$group_array 		= $this->session->flexi_auth['group'];
			foreach($group_array as $key=>$val){
				$this->data['group_id'] = $_SESSION['user_group'] = $group_id = $key;
				$this->data['group_name'] = $group_name = $val;
			}
			
			$this->data['message'] = $this->session->flashdata('message');
			$this->load->model('Search_model');
			$result = $this->Search_model->get_client_dealer_detail_list();
			$result1 = $this->Search_model->get_district_circle_detail_list();
			$this->data['asset_series_data'] = $result2 = $this->Search_model->search_asset_series_data();		
			$this->data['asset_data'] = $result3 = $this->Search_model->search_asset_data();		
			$this->data['invoice_data'] = $result3 = $this->Search_model->search_invoice_data();

			// Insepection data Starts
			// Get Looged In User ID and search the values according to the logged in User.
			
			// $loggedIn_user_id	= $this->session->flexi_auth['id'];
			// $group_array 		= $this->session->flexi_auth['group'];
			// foreach($group_array as $key=>$val){
				// $this->data['group_id'] = $group_id = $key;
			// }
			
			//$this->data['alloted_inspection'] 		= $this->Search_model->search_alloted_inspection($loggedIn_user_id,$group_id);
			$this->data['approved_inspection'] 		= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Approved');
			$this->data['rejected_inspection'] 		= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Rejected');
			$this->data['pending_inspection'] 		= $this->Search_model->search_inspection_results($loggedIn_user_id,$group_id, 'Pending');
		

			$this->data['not_inspected_siteIDs'] 		= $this->Search_model->search_not_inspected_siteIDs($loggedIn_user_id,$group_id);
		

			//$this->data['not_inspected_siteIDs'] 	= $this->Search_model->search_not_inspected_siteIDs($loggedIn_user_id,$group_id);
			// Insepection data End

			$client_array = array();
			$dealer_array = array();
			$circle_array = array();
			$district_array = array();
			//$asset_data_array = array();
			
			foreach($result as $value){
				$client_id = $value['client_id'];
				$client_name = $value['client_name'];
				$client_type = $value['client_type'];
				if($client_type == 15){
					$client_array[$client_id] = $client_name;
				}else if($client_type == 11){
					$dealer_array[$client_id] = $client_name;
				}
			}
			
			foreach($result1 as $value1){
				$circle = $value1['client_circle'];
				$circle_array[] = $circle;
				
				$district = $value1['client_district'];	
				$district_array[] = $district;			
			}
			
			
			$this->data['client']= $client_array;
			$this->data['dealer']= $dealer_array;
			$this->data['circle']= $circle_array;
			$this->data['district']= $district_array;
			
			$this->load->view('userpanel/admin/dashboard_view', $this->data);
		}
	
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Accounts
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/*
 	 manage_user_accounts
 	  View and manage a table of all users.
 	 This example allows accounts to be suspended or deleted via checkoxes within the page.
 	 The example also includes a search tool to lookup users via either their email, first name or last name. 
 	 */
        
        
    function manage_user_accounts()
    {
        
		$this->load->model('arresto_auth_admin_model');
		
		/* ********************************************************************************************** */
		
		// If 'Manage User Accounts' form has been submitted and user has privileges to update user accounts, then update the account details.
		if ($this->input->post('update_users')) 
		{
		  $this->demo_auth_admin_model->update_user_accounts();
		}

		// Get user account data for all users. 
		// If a search has been performed, then filter the returned users.
		$this->data['clients']=$this->arresto_auth_admin_model->get_user_accounts();
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		#$this->load->view('userpanel/admin/user_acccounts_view', $this->data);		
		$this->load->view('admin_panel/admin_acccounts_view', $this->data);
    }    
        
        
    function manage_user_accounts_blocked($group_id)
    {
       
		$this->load->model('demo_auth_admin_model');

		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');
		}

		/* *********************** Blocked by Shakti Singh *************************************** */
		// If 'Admin Search User' form has been submitted, this example will lookup the users email address and first and last name.
		if ($this->input->post('search_users') && $this->input->post('search_query')) 
		{
			// Convert uri ' ' to '-' spacing to prevent '20%'.
			// Note: Native php functions like urlencode() could be used, but by default, CodeIgniter disallows '+' characters.
			$search_query = str_replace(' ','-',$this->input->post('search_query'));
		
			// Assign search to query string.
			redirect('auth_admin/manage_user_accounts/search/'.$search_query.'/page/');
		}
		
		/* ********************************************************************************************** */
		
		// If 'Manage User Accounts' form has been submitted and user has privileges to update user accounts, then update the account details.
		else if ($this->input->post('update_users') && $this->flexi_auth->is_privileged('Update Users')) 
		{
			$this->demo_auth_admin_model->update_user_accounts();
		}

		// Get user account data for all users. 
		// If a search has been performed, then filter the returned users.
		$this->demo_auth_admin_model->get_user_accounts();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		#$this->load->view('userpanel/admin/user_acccounts_view', $this->data);		
		$this->load->view('admin_panel/admin_acccounts_view', $this->data);
    }

    
	
 	/**
 	 * update_user_account
 	 * Update the account details of a specific user.
 	 */
	function update_user_account($user_id)
	{
		
		// Check user has privileges to update user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user accounts.</p>');
			redirect('auth_admin');
		}

		// If 'Update User Account' form has been submitted, update the users account details.
		if ($this->input->post('update_users_account')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_user_account($user_id);
		}
		
		// Get users current data.
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
	
		// Get user groups.
		$this->data['groups'] = $this->flexi_auth->get_groups_array();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/user_account_update_view', $this->data);
	}
	
	
	 ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// all user password change
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
        
       /* function password_change(){
            $data = array();
	    $data['lang'] = $this->data['lang'];
            $status = 'active';
            $sql_where[$this->flexi_auth->db_column('user_acc', 'active')] = ($status == 'inactive') ? 0 : 1;
            $sql_select = array(
                    $this->flexi_auth->db_column('user_acc', 'id'),
                    $this->flexi_auth->db_column('user_acc', 'email'),
                    $this->flexi_auth->db_column('user_acc', 'active'),
                    $this->flexi_auth->db_column('user_group', 'name'),
                    // The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
                    'upro_first_name', 
                    'upro_last_name',
                    'uacc_date_last_login'   // added by Shakti Singh on 25-11-2015
            );
	    $data['users'] = $this->flexi_auth->get_users_array($sql_select, $sql_where);
            $this->load->view('userpanel/admin/user_password_change',$data);
        }*/
        
        function password_change(){
            
            $this->load->view('admin_panel/admin_change_password',$data);
        }
        
        
        function passwordChange(){
            
            
            $data = array();
            $param = array();
            $this->load->model('kare_model');
            if(!empty($_REQUEST['submit']) && !empty($_REQUEST['new_password']) && !empty($_REQUEST['userID'])){
                $param['new_password'] = $_REQUEST['new_password'];
                $param['userID'] = $_REQUEST['userID'];
                if(!empty($_REQUEST['emailID'])){
                    $param['emailID'] = $_REQUEST['emailID'];
                }
				//print_r($param);die("123");
                $this->load->model('flexi_auth_model');
                $result = $this->flexi_auth_model->update_passwordAdmin($param);
                if($result > 0){
                    $dataAjax['responseType'] = 'success';
                    $dataAjax['message'] = 'Successfully Update';
                } else {
                     $dataAjax['responseType'] = 'fail';
                    $dataAjax['message'] = 'Please try again!';		
                }
                print json_encode($dataAjax);
                exit();
            }
            
            $data['userDetail'] =  $this->kare_model->get_emailID($_REQUEST['userID']);
            $data['userDetail']['userID'] =  $_REQUEST['userID'];
            
            $this->load->view('userpanel/admin/user_change_password',$data);
	}
        
       
        function userView(){
            $data = array();
             $this->load->model('kare_model');
            $data['user'] =  $this->kare_model->get_userView($_REQUEST['userID']);
            $this->load->view('userpanel/admin/user_password_change_view',$data);
        }
        
        
        
        
        
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// API IMEI No. DATA
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
		
		function imei_no_demo(){
			$data =array();
			$this->load->model('kare_model');
            $imei_no =  $this->kare_model->get_imei_no();
			foreach($imei_no as $key => $value){
				$data['imeiNo'][$key] = $value;
				$data['imeiNo'][$key]['emil_id'] = $this->kare_model->get_imei_email_id($value['upro_uacc_fk']);	
			}
			$this->load->view('userpanel/admin/imei_no_view',$data);
		}
		
		function imei_no(){
			$data =array();
			$data['lang'] = $this->data['lang'];
			$this->load->model('kare_model');
            $imei_no =  $this->kare_model->get_imei_no();
			foreach($imei_no as $key => $value){
				$data['imeiNo'][$key] = $value;
				$data['imeiNo'][$key]['emil_id'] = $this->kare_model->get_imei_email_id($value['upro_uacc_fk']);	
			}
			
			$this->load->view('userpanel/admin/imei_no_view',$data);
		}
		
		function update_imei(){
			if(!empty($_REQUEST['upro_id']) && !empty($_REQUEST['upro_uacc_fk'])){
				$param = array();
				$this->load->model('kare_model');
				
				if(!empty($_REQUEST['upro_mob_imei'])){
					$param['upro_mob_imei'] = $_REQUEST['upro_mob_imei'];
				}
				if(!empty($_REQUEST['upro_mob_new_imei'])){
					$param['upro_mob_new_imei'] = $_REQUEST['upro_mob_new_imei'];
				}
				
				$result = $this->kare_model->update_imei_email_id($_REQUEST['upro_uacc_fk'],$param,$_REQUEST['upro_id']);
				if($result>0){
					$data['responseType'] = 'success';
					$data['message'] = 'Successfully Update';
				} else {
					$data = array(  'responseType' => 'fail',
							'message' => 'Please try again!');			
				}
				
			}else {
				$data = array(  'responseType' => 'fail',
					'message' => 'Please enter the required fields');
			} 
			
			print json_encode($data);
			exit();
		}
		
		function update_imei_no(){
			if(!empty($_REQUEST['user_id'])){
				$this->load->model('kare_model');
				$imeiData = $this->kare_model->fetch_imei_email_id($_REQUEST['user_id']);
				if($_REQUEST['user_id'] == $imeiData['upro_uacc_fk']){
					$param['upro_mob_imei'] = $imeiData['upro_mob_new_imei'];
					$result = $this->kare_model->update_imei_email_id($imeiData['upro_uacc_fk'],$param);
					if($result>0){
						$data['responseType'] = 'success';
						$data['message'] = 'Successfully Update';
					} else {
						$data = array(  'responseType' => 'fail',
								'message' => 'Please try again!');			
					}
				}	
			}else {
				$data = array(  'responseType' => 'fail',
					'message' => 'Please enter the required fields');
			} 
			
			print json_encode($data);
			exit();
		}
		
	 ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// API LOGS DATA
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
        
        function logs(){
            $data = array();
			$data['lang'] = $this->data['lang'];
            $params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
            $params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

            $_REQUEST['fromDate'] = $params['startTime'];
          
            //logs_data
            $this->load->model('kare_model');
            $logsData = $this->kare_model->get_logs_view($params);
            if(!empty($logsData) && is_array($logsData)){
                foreach($logsData as $key => $value){
                    $profileData = $this->_profileData($value['user_id']);
                    $data['logsData'][$key]['name'] = !empty($profileData['name'])?$profileData['name']:'';
                    $data['logsData'][$key]['email'] = !empty($profileData['email'])?$profileData['email']:'';
                    $data['logsData'][$key]['description_id'] = !empty($profileData['description_id'])?$profileData['description_id']:'';
                    $data['logsData'][$key]['description'] = !empty($profileData['description'])?$profileData['description']:'';
                    $data['logsData'][$key]['ip_address'] = $value['ip_address'];
                    $data['logsData'][$key]['process'] = $value['process'];
                    $data['logsData'][$key]['time'] = $value['time'];
                    $data['logsData'][$key]['date'] = date("M jS, Y", strtotime($value['timestamp']));
                }
            }
            $this->load->view('userpanel/admin/admin_logs_view',$data);
        }
		
		function admin_log(){
			$params = array();
            $params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
            $params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

            $_REQUEST['fromDate'] = $params['startTime'];
            //logs_data
            $this->load->model('kare_model');
            $logsData = $this->kare_model->get_logs_view($params);
            $html = '';
            if(!empty($logsData) && is_array($logsData)){
                foreach($logsData as $key => $value){
                    $profileData = $this->_profileData($value['user_id']);
                    $datalogsData[$key]['name'] = !empty($profileData['name'])?$profileData['name']:'';
                    $datalogsData[$key]['email'] = !empty($profileData['email'])?$profileData['email']:'';
                    $datalogsData[$key]['description_id'] = !empty($profileData['description_id'])?$profileData['description_id']:'';
                    $datalogsData[$key]['description'] = !empty($profileData['description'])?$profileData['description']:'';
                    $datalogsData[$key]['ip_address'] = $value['ip_address'];
                    $datalogsData[$key]['process'] = $value['process'];
                    $datalogsData[$key]['time'] = $value['time'];
                    $datalogsData[$key]['date'] = date("M jS, Y", strtotime($value['timestamp']));
                }
            }
            
         $html = '<table id="kare_logs_view_datatable1" class="table table-bordered table-hover">
                    <thead>
                            <tr>
                                    <th>SNo.</th>
                                     <th>Group Name</th>
                                    <th>Name</th>
                                    <th>Email Id</th>
                                    <th>Event Type</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                    <th>Date</th>
                            </tr>
                    </thead>';
         if(!empty($datalogsData) && is_array($datalogsData)){
            $html .= '<tbody>';
            $c = 1;
             foreach ($datalogsData as $ky => $val) {
                $html .= '<td>'.$c.'</td>';
                $html .= '<td>'.$val['description'].'</td>';
                $html .= '<td>'.$val['name'].'</td>';
                $html .= '<td>'.$val['email'].'</td>';
                $html .= '<td>'.$val['process'].'</td>';
                $html .= '<td>'.$val['ip_address'].'</td>';
                $html .= '<td>'.$val['time'].'</td>';
                $html .= '<td>'.$val['date'].'</td>';
                $html .= '</tr>';
                $c++;
             }
           
            $html .= '</tbody>'; 
         }else{
             $html .= '<tbody><tr><td colspan="7" class="highlight_red"> No Logs Data are available. </td> </tr></tbody>';
         }
         
         $html .= '</table>';
        
        
         print $html;
         exit();    
        }
        
        function _profileData($userID){
            $param = array();
            if(!empty($userID)){
                $logsData = $this->kare_model->get_logs_profileData($userID);
                $param['email'] = !empty($logsData['uacc_email'])?$logsData['uacc_email']:'';
                 $param['description_id'] = !empty($logsData['uacc_group_fk'])?$logsData['uacc_group_fk']:'';
                if($logsData['uacc_group_fk'] == 1){
                    $param['description'] = 'Public';
                }else if($logsData['uacc_group_fk'] == 2){
                    $param['description'] = 'Moderator';
                }else if($logsData['uacc_group_fk'] == 3){
                    $param['description'] = 'Master Admin';
                }else if($logsData['uacc_group_fk'] == 6){
                    $param['description'] = 'Factory';
                }else if($logsData['uacc_group_fk'] == 7){
                    $param['description'] = 'Dealer';
                }else if($logsData['uacc_group_fk'] == 8){
                    $param['description'] = 'Client';
                }else if($logsData['uacc_group_fk'] == 9){
                    $param['description'] = 'Inspector';
                }else if($logsData['uacc_group_fk'] == 10){
                    $param['description'] = 'KDManager';
                }else if($logsData['uacc_group_fk'] == 11){
                    $param['description'] = ' KDclient';
                }else if($logsData['uacc_group_fk'] == 12){
                    $param['description'] = 'Factory';
                }else {
                    $param['description'] = $logsData['uacc_group_fk'];
                }
                $param['name'] = (!empty($logsData['upro_first_name']) || !empty($logsData['upro_last_name']))?$logsData['upro_first_name'].' '.$logsData['upro_last_name']:'';
            }
            return $param;
        }


	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Groups
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_user_groups
 	 * View and manage a table of all user groups.
 	 * This example allows user groups to be deleted via checkoxes within the page.
 	 */
    function manage_user_groups()
    {
		// Check user has privileges to view user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user groups.</p>');
			redirect('auth_admin');		
		}
			
		// If 'Manage User Group' form has been submitted and user has privileges, delete user groups.
		if ($this->input->post('delete_group') && $this->flexi_auth->is_privileged('Delete User Groups')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->manage_user_groups();
		}

		// Define the group data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$this->load->model('demo_auth_admin_model');
		$this->demo_auth_admin_model->display_manage_user_groups();
		
		/*
		$sql_select = array(
			$this->flexi_auth->db_column('user_group', 'id'),
			$this->flexi_auth->db_column('user_group', 'name'),
			$this->flexi_auth->db_column('user_group', 'description'),
			$this->flexi_auth->db_column('user_group', 'admin')
		);
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select);
		*/		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/user_groups_view', $this->data);		
    }
	
 	/**
 	 * insert_user_group
 	 * Insert a new user group.
 	 */
	function insert_user_group()
	{
		// Check user has privileges to insert user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to insert new user groups.</p>');
			redirect('auth_admin/manage_user_groups');		
		}

		// If 'Add User Group' form has been submitted, insert the new user group.
		if ($this->input->post('insert_user_group')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->insert_user_group();
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/user_group_insert_view', $this->data);
	}
	
 	/**
 	 * update_user_group
 	 * Update the details of a specific user group.
 	 */
	function update_user_group($group_id)
	{
		// Check user has privileges to update user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user groups.</p>');
			redirect('auth_admin/manage_user_groups');		
		}

		// If 'Update User Group' form has been submitted, update the user group details.
		if ($this->input->post('update_user_group')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_user_group($group_id);
		}

		// Get user groups current data.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/user_group_update_view', $this->data);
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Privileges
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_privileges
 	 * View and manage a table of all user privileges.
 	 * This example allows user privileges to be deleted via checkoxes within the page.
 	 */
    function manage_privileges()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view user privileges.</p>');
			redirect('auth_admin');		
		}
		
		// If 'Manage Privilege' form has been submitted and the user has privileges to delete privileges.
		if ($this->input->post('delete_privilege') && $this->flexi_auth->is_privileged('Delete Privileges')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->manage_privileges();
		}

		// Define the privilege data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
				
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/privileges_view', $this->data);
	}
	
 	/**
 	 * insert_privilege
 	 * Insert a new user privilege.
 	 */
	function insert_privilege()
	{
		// Check user has privileges to insert user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to insert new user privileges.</p>');
			redirect('auth_admin/manage_privileges');		
		}

		// If 'Add Privilege' form has been submitted, insert the new privilege.
		if ($this->input->post('insert_privilege')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->insert_privilege();
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/privilege_insert_view', $this->data);
	}
	
 	/**
 	 * update_privilege
 	 * Update the details of a specific user privilege.
 	 */
	function update_privilege($privilege_id)
	{
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/manage_privileges');		
		}

		// If 'Update Privilege' form has been submitted, update the privilege details.
		if ($this->input->post('update_privilege')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_privilege($privilege_id);
		}
		
		// Get privileges current data.
		$sql_where = array($this->flexi_auth->db_column('user_privileges', 'id') => $privilege_id);
		$this->data['privilege'] = $this->flexi_auth->get_privileges_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('userpanel/admin/privilege_update_view', $this->data);
	}


	
 	/**
 	 * update_user_privileges
 	 * Update the access privileges of a specific user.
 	 */
        function update_user_privileges($user_id)
        {
                    // Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
                    if (! $this->flexi_auth->is_privileged('Update Privileges'))
                    {
                            $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
                            redirect('auth_admin/manage_user_accounts');		
                    }

                    // If 'Update User Privilege' form has been submitted, update the user privileges.
                    if ($this->input->post('update_user_privilege')) 
                    {
                            $this->load->model('demo_auth_admin_model');
                            $this->demo_auth_admin_model->update_user_privileges($user_id);
                    }

                    // Get users profile data.
                    $sql_select = array(
                            'upro_uacc_fk', 
                            'upro_first_name', 
                            'upro_last_name',
                            $this->flexi_auth->db_column('user_acc', 'group_id'),
                            $this->flexi_auth->db_column('user_group', 'name')
            );
                    $sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
                    $this->data['user'] = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);		

                    // Get all privilege data. 
                    $sql_select = array(
                            $this->flexi_auth->db_column('user_privileges', 'id'),
                            $this->flexi_auth->db_column('user_privileges', 'name'),
                            $this->flexi_auth->db_column('user_privileges', 'description')
                    );
                    $this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);

                    // Get user groups current privilege data.
                    $sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
                    $sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
                    $group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);

            $this->data['group_privileges'] = array();
            foreach($group_privileges as $privilege)
            {
                $this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
            }

                    // Get users current privilege data.
                    $sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
                    $sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $user_id);
                    $user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);

                    // For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
                    // The array can then be used within the view to check whether the user has a specific privilege, this data allows us to then format form input values accordingly. 
                    $this->data['user_privileges'] = array();
                    foreach($user_privileges as $privilege)
                    {
                            $this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
                    }

                    // Set any returned status/error messages.
                    $this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

            // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
            // privileges or user group privileges, load the settings array containing the current privilege sources. 
                    $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];

                    #$this->load->view('userpanel/admin/user_privileges_update_view', $this->data);	

                    $this->load->view('admin_panel/admin_privileges_update_view', $this->data);

        }


    
 	/**
 	 * update_group_privileges 
 	 * Update the access privileges of a specific user group.
 	 */
        function update_group_privileges($group_id)
        {
		// Check user has privileges to update group privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update group privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}

		// If 'Update Group Privilege' form has been submitted, update the privileges of the user group.
		if ($this->input->post('update_group_privilege')) 
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->update_group_privileges($group_id);
		}
		
		// Get data for the current user group.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
                
		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get data for the current privilege group.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
		// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
		// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege)
		{
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
        $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('userpanel/admin/user_group_privileges_update_view', $this->data);		
    }

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Activity
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * list_user_status
 	 * Display a list of active or inactive user accounts. 
 	 * The active status of an account is based on whether the user has verified their account after registering - typically via email activation. 
 	 * This demo example simply shows a table of users that have, and have not activated their accounts.
 	 */
	function list_user_status($status = FALSE)
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');		
		}

		// The view associated with this controller method is used by multiple methods, therefore set a page title.
		$this->data['page_title'] = ($status == 'inactive') ? 'Inactive Users' : 'Active Users';
		$this->data['status'] = ($status == 'inactive') ? 'inactive_users' : 'active_users'; // Indicate page function.
		
		// Get an array of all active/inactive user accounts, using the sql select and where statements defined below.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name', 
			'upro_last_name',
			'uacc_date_last_login'   // added by Shakti Singh on 25-11-2015
		);
		$sql_where[$this->flexi_auth->db_column('user_acc', 'active')] = ($status == 'inactive') ? 0 : 1;
		if (! $this->flexi_auth->in_group('Master Admin'))
		{
			// For this example, prevent any 'Master Admin' users being listed to non master admins.
			$sql_where[$this->flexi_auth->db_column('user_group', 'id').' !='] = 2;
		}
		$this->data['users'] = $this->flexi_auth->get_users_array($sql_select, $sql_where);
			
		$this->load->view('userpanel/admin/users_view', $this->data);
	}

 	/**
 	 * delete_unactivated_users
 	 * Display a list of all user accounts that have not been activated within a define time period. 
 	 * This demo example allows the option to then delete all of the unactivated accounts.
 	 */
	function delete_unactivated_users()
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');		
		}

		// Filter accounts old than set number of days.
		$inactive_days = 28;
	
		// If 'Delete Unactivated Users' form has been submitted and user has privileges to delete users.
		if ($this->input->post('delete_unactivated') && $this->flexi_auth->is_privileged('Delete Users'))
		{
			$this->load->model('demo_auth_admin_model');
			$this->demo_auth_admin_model->delete_users($inactive_days);
		}

		// Get an array of all user accounts that have not been activated within the defined limit ($inactive_days), using the sql select and where statements defined below.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name',
			'upro_last_name'
		);
		$this->data['users'] = $this->flexi_auth->get_unactivated_users_array($inactive_days, $sql_select);
				
		$this->load->view('userpanel/admin/users_unactivated_view', $this->data);
	}
	
 	/**
 	 * failed_login_users
 	 * Display a list of all user accounts that have too many failed login attempts since the users last successful login. 
	 * The purpose of this example is to highlight user accounts that have either struggled to login, or that may be the subject of a brute force hacking attempt.
 	 */
	function failed_login_users()
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view this page.</p>');
			redirect('auth_admin');		
		}

		// The view associated with this controller method is used by other methods, therefore set a page title.
		$this->data['page_title'] = 'Failed Login Users';
		$this->data['status'] = 'failed_login_users'; // Indicate page function.
		
		// Get an array of all user accounts that have more than 3 failed login attempts since their last successfuly login.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'failed_logins'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name',
			'upro_last_name'
		);
		$sql_where[$this->flexi_auth->db_column('user_acc', 'failed_logins').' >='] = 3; // Get users with 3 or more failed login attempts.
		if (! $this->flexi_auth->in_group('Master Admin'))
		{
			// For this example, prevent any 'Master Admin' users being listed to non master admins.
			$sql_where[$this->flexi_auth->db_column('user_group', 'id').' !='] = 2;
		}
		$this->data['users'] = $this->flexi_auth->get_users_array($sql_select, $sql_where);
		
		$this->load->view('userpanel/admin/users_view', $this->data);
	}
	
	
/* Ravindra Changes 22-07-2016 */
	function search_asset_data()
	{
		
		//$asset = $_POST['asset'];	
		$this->load->model('Search_model');
		$result = $this->Search_model->search_asset_data();

		if($result){
			return $asset_data = $result;			
		}
		
	}

/* Ravindra Changes 22-07-2016 */
	
	

}

/* End of file auth_admin.php */
/* Location: ./application/controllers/auth_admin.php */
