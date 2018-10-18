<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct(){
		parent::__construct();
		$this->load->model('Search_model');
		$this->load->helper(array('url','date','form','kare'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data = null;
	}
		
		
  	public function index(){ 
		echo "Shakti";
	}
	
	
	function searchHistory(){
		if(!empty($_REQUEST['search'])){
			$param = array();
			if(!empty($_REQUEST['fromDate'])){
				$param['startTime'] = date( 'Y-m-d', strtotime($_REQUEST['fromDate']));
			}
			if(!empty($_REQUEST['toDate'])){
				$param['endTime'] = date( 'Y-m-d', strtotime($_REQUEST['toDate']));
			}
			if(!empty($_REQUEST['client'])){
				$param['client'] = $_REQUEST['client'];
			}
			if(!empty($_REQUEST['district'])){
				$param['district'] = $_REQUEST['district'];
			}
			if(!empty($_REQUEST['circle'])){
				$param['circle'] = $_REQUEST['circle'];
			}
			if(!empty($_REQUEST['invoice'])){
				$param['invoice'] = $_REQUEST['invoice'];
			}
			if(!empty($_REQUEST['asset_series'])){
				$param['asset_series'] = $_REQUEST['asset_series'];
			}
			if(!empty($_REQUEST['asset'])){
				$param['asset'] = $_REQUEST['asset'];
			}
			
			
			$this->load->library('email/search');
			$searchData = $this->search->search_dashboard($param);
			$html = '';
			$html = '<table id="kare_search_view" class="table table-bordered table-hover">
                    <thead>
						<tr>
							<th>SNo.</th>
							 <th>Client Name</th>
							<th>Asset</th>
							<th>Asset Series</th>
							<th>Report No.</th>
							<th>Site Id</th>
							<th>Job Card</th>
							<th>SMS</th>
							<th>Status</th>
							<th>Date</th>
						</tr>
                    </thead>';
			if(!empty($searchData) && is_array($searchData)){
				$html .= '<tbody>';
				$c = 1;
				 foreach ($searchData as $ky => $val) {
					$html .= '<td>'.$c.'</td>';
					$html .= '<td>'.$val['client_name'].'</td>';
					$html .= '<td>'.$val['asset'].'</td>';
					$html .= '<td>'.$val['asset_series'].'</td>';
					$html .= '<td>'.$val['report_no'].'</td>';
					$html .= '<td>'.$val['site_id'].'</td>';
					$html .= '<td>'.$val['job_card'].'</td>';
					$html .= '<td>'.$val['sms'].'</td>';
					$html .= '<td>'.$val['status'].'</td>';
					$html .= '<td>'.date("M jS, Y", strtotime($val['time'])).'</td>';
					$html .= '</tr>';
					$c++;
				 }
			   
				$html .= '</tbody>'; 
			 }else{
				 $html .= '<tbody><tr><td colspan="7" class="highlight_red"> No Data are available. </td> </tr></tbody>';
			 }
			 
			 $html .= '</table>';
			
			 $data['success'] = 'success';
			 $data['data'] = $html;
			 print json_encode($data);
			 exit(); 							
		}
	}
	
	function dashboard_values(){
		$this->data['message'] = $this->session->flashdata('message');
		
		$this->load->model('Search_model');
		$result = $this->Search_model->get_client_dealer_detail_list();
		$result1 = $this->Search_model->get_district_circle_detail_list();
		$this->data['asset_series_data'] = $result2 = $this->Search_model->search_asset_series_data();		
		$this->data['asset_data'] = $result3 = $this->Search_model->search_asset_data();		
		$this->data['invoice_data'] = $result4 = $this->Search_model->search_invoice_data();
		
		// Insepection data Starts
		
		$this->data['alloted_inspection'] = $this->Search_model->search_alloted_inspection();
		$this->data['approved_inspection'] = $this->Search_model->search_approved_inspection();
		$this->data['rejected_inspection'] = $this->Search_model->search_rejected_inspection();	
		$this->data['pending_inspection'] = $this->Search_model->search_pending_inspection();	
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
		$this->data['client_name']= $client_array;
		$this->data['dealer']= $dealer_array;
		$this->data['circle']= $circle_array;
		$this->data['district']= $district_array;
		
		return $this->data;
	}
	
	
	function inspection_reports_details(){
		$inpection_status = $_GET['status'];
		
		$loggedIn_user_id	= $this->session->flexi_auth['id'];
		$group_array 		= $this->session->flexi_auth['group'];
		foreach($group_array as $key=>$val){
			$group_id = $key;
			$group_name = $val;
		}

		//$this->load->model('Inspector_inspection_model');
		if($inpection_status == 'NotInspected'){
			
			if($group_id =='9'){
				$this->data['user_type'] = 'inspector';
			}else{
				$this->data['user_type'] = 'admin';
			}
			$this->data['notInspected_status'] = $this->Search_model->get_not_inspected_siteID_data($loggedIn_user_id, $group_id);
			
		//	$this->data['notInspected_status'] = $this->Search_model->get_not_inspected_siteID_data();
		}else{
			$this->data['inspection_status'] = $this->Search_model->get_approved_rejected_pending_list($inpection_status,$loggedIn_user_id,$group_id);
		}
		
		$this->load->view('inspection_reports_detail',$this->data); 
	}

}// end of controller class 
?>