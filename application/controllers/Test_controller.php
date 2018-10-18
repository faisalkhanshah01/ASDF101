<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct(){
        parent::__construct();
 		
        echo "here"; die;
		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2) 
		{
			$sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
			); 
			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(TRUE);
		}
		
		// Load required CI libraries and helpers.
		$this->load->database();
		$this->load->library('session');
 		$this->load->helper('url');
 		$this->load->helper('form');
 		$this->load->model('base_model');
  		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');	

		/*if (! $this->flexi_auth->is_logged_in_via_password() || ! $this->flexi_auth->is_admin()) 
		{
			// Set a custom error message.
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">You must login as an admin to access this area.</p>');
			redirect('auth');
		}*/
		
		
		if($_SESSION['user_group'] !='2' && $_SESSION['user_group'] !='3' && $_SESSION['user_group'] !='9' && $_SESSION['user_group'] !='11'){
			//If User is Client Redirect him back to it's own controller.
			$this->load->model('Common_model');
			$groupID = $this->Common_model->get_loggedIn_user_dashboard();
			exit();
		}
		if(isset($_SESSION['user_from'])){
			unset($_SESSION['user_from']);
		}
		$logo 		= $this->base_model->field_value_fetch('uacc_logo', 'uacc_id', $_SESSION['flexi_auth']['id'], 'user_accounts');
    	if($logo != '') {
    		$_SESSION['logo'] = $logo;
    	} else {
    		$_SESSION['logo'] = 'images/system/arresto-logo.jpg';
    	}
    	$color 		= $this->base_model->field_value_fetch('uacc_color_code', 'uacc_id', $_SESSION['flexi_auth']['id'], 'user_accounts');
    	if($color != '') {
    		$_SESSION['color_code'] = '#'.$color;
    	} else {
    		$_SESSION['color_code'] = '#f7c02f';
    	}
		// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		// Define a global variable to store data that is then used by the end view page.
		$this->data = null;
	}
		
		
	function dashboard(){    
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
		$this->load->view('testdashboard', $this->data);
		
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
	
	function pdf($purchase_id = NULL, $view = NULL, $save_bufffer = NULL) {
		$this->load->library('sma');
		$data = '';
		$name = "test.pdf";		
		$html = $this->load->view('pdftest', $this->data, TRUE);
		if($view) {
			$this->load->view('pdftest', $this->data);
		} elseif($save_bufffer) {
			return $this->sma->generate_pdf($html, $name, $save_bufffer);
		} else {
			$this->sma->generate_pdf($html, $name);
		}

	}


	function savepdf($purchase_id = NULL, $view = NULL, $save_bufffer = NULL) {
		$this->load->library('sma');
		$data = '';
		$name = "test.pdf";
		
		$html = $this->load->view('pdftest', $this->data, TRUE);
		if($view) {
			$this->load->view('pdftest', $this->data);
		} elseif($save_bufffer) {
			return $this->sma->generate_pdf($html, $name, $save_bufffer);
		} else {
			 $name = $this->sma->generate_pdf($html, $name, 'S');

		}

	}
	
}// end of controller class 
?>