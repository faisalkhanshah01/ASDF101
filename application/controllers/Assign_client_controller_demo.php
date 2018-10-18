<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assign_client_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->model('Assign_client_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if(! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			$this->session->set_flashdata('message', '<div class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</div>');
			redirect('auth');
		}		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
	}
	

	/*public function assign_client($client_id=NULL){
		if($client_id==NULL){
			// Client User List
			$assign_client	=$this->Assign_client_model->get_client_list(); 
			
			// Client List
			$client			=$this->Assign_client_model->get_client_name();	

			// Assign Client data
			$client_data	=$this->Assign_client_model->get_client_data();	
			
			foreach($client_data as $cKey=>$CVal){
				$client_user = json_decode($CVal['client_users'],true);
				$client_name_list = json_decode($CVal['client_ids'],true);
				
				$client_user_name = array();
				if(!empty($client_user)){
					foreach($client_user as $clientVal){						
						$names	=$this->Assign_client_model->get_client_user_name_by_id($clientVal);
						$client_user_name[] = $names['client_name'].' ('.$names['client_district'].', '.$names['client_circle'].')';
					}
				}
				
				$userName= array();
				if(!empty($client_name_list)){
					foreach($client_name_list as $clientNameVal){
						//$userName[]['clientnamevalue'] = $clientNameVal;
						$users = $this->Assign_client_model->get_client_list_name_by_id($clientNameVal);
						$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
					}
				}
				
				$data['client_datas'][$cKey] = array(
											'id' 			=> $CVal['id'],
											'status' 		=> $CVal['status'],
											'client_names' 	=> $client_user_name,
											'user_name' 	=> $userName
				);
			}
			$data['assign_client']	= $assign_client;	
			$data['client_list']	= $client;
			//print_r($data);die;
			$this->load->view('assign_client/assign_client',$data);
			//$this->load->view('assign_client/assign_client_kare',$data);
		}
	}*/
	
	
	
	function search_client_ajax_get(){
		$clientType = $_REQUEST['clientType'];
		$this->load->model('assign_client_model');
		$searchResult = $this->assign_client_model->search_ajax_client_name($_REQUEST['search'],$clientType);
		//print_r($searchResult);die("123");
		if(!empty($searchResult) && is_array($searchResult)){
			$respose='';
			foreach($searchResult as $key => $value){
				if($clientType == 7){
					$respose.="<p>".$value;
					$respose.='<input class="pull-right" type="checkbox" name="client_id_7[]" id="chk_'.$key.'"';
					$respose.='value="'.$key.'"';
					$respose.='rel="'.$value.'"';
					$respose.='/></p>';
				}else if($clientType == 8){
					$respose.="<p>".$value;
					$respose.='<input class="pull-right" type="checkbox" name="client_id_8[]" id="chk_'.$key.'"';
					$respose.='value="'.$key.'"';
					$respose.='rel="'.$value.'"';
					$respose.='/></p>';
				}else if($clientType == 9){
					$respose.="<p>".$value;
					$respose.='<input class="pull-right" type="checkbox" name="client_id_9[]" id="chk_'.$key.'"';
					$respose.='value="'.$key.'"';
					$respose.='rel="'.$value.'"';
					$respose.='/></p>';
				}	
			}  
			echo $respose;
		}
	}
	
	function client_name(){
		$this->load->model('assign_client_model');
		if(!empty($_REQUEST['clientType']) && ($_REQUEST['type'] == 1)){
			$clientType = $_REQUEST['clientType'];
			$client_list = $this->assign_client_model->get_client_list_name($_REQUEST['clientType']);
			if(!empty($client_list) && is_array($client_list)){
				$respose='';
				foreach($client_list as $key => $value){
					if($clientType == 7){
						$respose.="<p>".$value;
						$respose.='<input class="pull-right" type="checkbox" name="client_id_7[]" id="chk_'.$key.'"';
						$respose.='value="'.$key.'"';
						$respose.='rel="'.$value.'"';
						$respose.='/></p>';
					}else if($clientType == 8){
						$respose.="<p>".$value;
						$respose.='<input class="pull-right" type="checkbox" name="client_id_8[]" id="chk_'.$key.'"';
						$respose.='value="'.$key.'"';
						$respose.='rel="'.$value.'"';
						$respose.='/></p>';
					}else if($clientType == 9){
						$respose.="<p>".$value;
						$respose.='<input class="pull-right" type="checkbox" name="client_id_9[]" id="chk_'.$key.'"';
						$respose.='value="'.$key.'"';
						$respose.='rel="'.$value.'"';
						$respose.='/></p>';
					}	
				} 
			}else{
				$respose.="<p> Data Empty</p>";
			}
			echo $respose;
		}
	}
	
	function search_array($array, $key, $value){
		 $results = array();
		if ( is_array($array) ){
			if ( $array[$key] == $value )
			{
				$results[] = $array;
			} 
				foreach ($array as $subarray) 
					$results = array_merge( $results, $this->search_array($subarray, $key, $value) );
			
		}
		return $results;
	}
	
	function searchClientAddress(){
		$this->load->model('assign_client_model');
		$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
			$data[$key]['siteID_id'] = $value['siteID_id'];
            $clientName  = $this->assign_client_model->get_clientName_siteID_Data(trim($value['site_jobcard']),trim($value['site_sms']));
			$data[$key]['clientName']  = $clientName['client_name'];
			$data[$key]['site_location']  = strtoupper($value['site_location']);
			$data[$key]['site_city']  = strtoupper($value['site_city']);
			$data[$key]['site_address']  = $value['site_address'];
			$data[$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
		}
		
		
		$respose = '';
		$respose1 = '';
		$temp = array();
		if (!empty($_REQUEST['site_location']) && ($_REQUEST['clientName'] == 'blank') && ($_REQUEST['site_city'] == 'blank')){
			$search_text = trim($_REQUEST['site_location']);
			$filterData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($filterData)){
				foreach($filterData as $key => $value){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
				}
			}else{
				$respose.='<p><textarea class="form-control" rows="2" id="client_address"> No Data.';
				$respose.='</textarea></p>';
			}	
			$temp['type'] = 1;
			$temp['respose']  = $respose;
		}else if(!empty($_REQUEST['site_location']) && !empty($_REQUEST['site_city']) && ($_REQUEST['clientName'] == 'blank')){
			$site_location = trim($_REQUEST['site_location']);
			$locationData = array_filter($data, function($v) use ($site_location) {
				if( stripos($v['site_location'], $site_location) !== false ){
					return true;
				}
				return false;
			});
			
			$site_city = trim($_REQUEST['site_city']);
			$cityData = array_filter($locationData, function($v) use ($site_city) {
				if( stripos($v['site_city'], $site_city) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($cityData)){
				foreach($cityData as $key => $value){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
				}
			}else{
				$respose.='<p><textarea class="form-control" rows="2" id="client_address"> No Data.';
				$respose.='</textarea></p>';
			}
			$temp['type'] = 2;
			$temp['respose']  = $respose;
		}else if(!empty($_REQUEST['clientName']) && ($_REQUEST['site_city'] == 'blank') && ($_REQUEST['site_location'] == 'blank')){
			$clientName = trim($_REQUEST['clientName']);
			$clientData = array_filter($data, function($v) use ($clientName) {
				if( stripos($v['clientName'], $clientName) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($clientData)){
				foreach($clientData as $key => $value){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
					
					$respose1.="<p>".$value['clientName'];
					$respose1.='<input class="pull-right check-element" type="checkbox" name="siteID_id1[]" id="chk_'.$value['siteID_id'].'"';
					$respose1.='value="'.$value['siteID_id'].'"';
					$respose1.='rel="'.$value['clientName'].'"';
					$respose1.='/></p>';
				}
			}else{
				$respose.='<p><textarea class="form-control" rows="2" id="client_address"> No Data.';
				$respose.='</textarea></p>';
			}
			$temp['type'] = 3;
			$temp['respose']  = $respose;
			$temp['clientName_view']  = $respose1;
		}else if(!empty($_REQUEST['clientName']) && !empty($_REQUEST['site_city']) && !empty($_REQUEST['site_location']) && ($_REQUEST['site_city'] != 'blank')){
			
			$site_location = trim($_REQUEST['site_location']);
			$locationData = array_filter($data, function($v) use ($site_location) {
				if( stripos($v['site_location'], $site_location) !== false ){
					return true;
				}
				return false;
			});
			
			$site_city = trim($_REQUEST['site_city']);
			$cityData = array_filter($locationData, function($v) use ($site_city) {
				if( stripos($v['site_city'], $site_city) !== false ){
					return true;
				}
				return false;
			});
			
			$clientName = trim($_REQUEST['clientName']);
			$clientData = array_filter($cityData, function($v) use ($clientName) {
				if( stripos($v['clientName'], $clientName) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($clientData)){
				foreach($clientData as $key => $value){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
					
					$respose1.="<p>".$value['clientName'];
					$respose1.='<input class="pull-right check-element" type="checkbox" name="siteID_id1[]" id="chk_'.$value['siteID_id'].'"';
					$respose1.='value="'.$value['siteID_id'].'"';
					$respose1.='rel="'.$value['clientName'].'"';
					$respose1.='/></p>';
				}
			}else{
				$respose.='<p><textarea class="form-control" rows="2" id="client_address"> No Data.';
				$respose.='</textarea></p>';
			}
			
			$temp['type'] = 4;
			$temp['respose']  = $respose;
			$temp['clientName_view']  = $respose1;
			
		}else if(!empty($_REQUEST['site_location']) && ($_REQUEST['site_city'] == 'blank') && !empty($_REQUEST['clientName'])){
			$site_location = trim($_REQUEST['site_location']);
			$locationData = array_filter($data, function($v) use ($site_location) {
				if( stripos($v['site_location'], $site_location) !== false ){
					return true;
				}
				return false;
			});
			
			$clientName = trim($_REQUEST['clientName']);
			$clientData = array_filter($locationData, function($v) use ($clientName) {
				if( stripos($v['clientName'], $clientName) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($clientData)){
				foreach($clientData as $key => $value){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
					
					$respose1.="<p>".$value['clientName'];
					$respose1.='<input class="pull-right check-element" type="checkbox" name="siteID_id1[]" id="chk_'.$value['siteID_id'].'"';
					$respose1.='value="'.$value['siteID_id'].'"';
					$respose1.='rel="'.$value['clientName'].'"';
					$respose1.='/></p>';
				}
			}else{
				$respose.='<p><textarea class="form-control" rows="2" id="client_address"> No Data.';
				$respose.='</textarea></p>';
			}
			$temp['type'] = 5;
			$temp['respose']  = $respose;
			$temp['clientName_view']  = $respose1;
		}
		
		echo json_encode($temp);
	}
	
	function searchClientSiteData (){
		$this->load->model('assign_client_model');
		$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
			$data[$key]['siteID_id'] = $value['siteID_id'];
            $clientName  = $this->assign_client_model->get_clientName_siteID_Data(trim($value['site_jobcard']),trim($value['site_sms']));
			$data[$key]['clientName']  = $clientName['client_name'];
			$data[$key]['site_location']  = strtoupper($value['site_location']);
			$data[$key]['site_city']  = strtoupper($value['site_city']);
			$data[$key]['site_address']  = $value['site_address'];
			$data[$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
		}
		
		$respose = '';
		if(!empty($_REQUEST['search']) && ($_REQUEST['type']==1) && ($_REQUEST['search'] != 'blank')){
			//$array = array_column($data,'clientName');
			$search_text = trim($_REQUEST['search']);
			$filterData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['clientName'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			foreach($filterData as $key => $value){
				$respose.="<p>".$value['clientName'];
				$respose.='<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="chk_'.$value['siteID_id'].'"';
				$respose.='value="'.$value['siteID_id'].'"';
				$respose.='rel="'.$value['clientName'].'"';
				$respose.='/></p>';
				
			}	
		}else if(($_REQUEST['search'] == 'blank') && ($_REQUEST['type']==1)){
			foreach($data as $key => $value){
				$respose.="<p>".$value['clientName'];
				$respose.='<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="chk_'.$value['siteID_id'].'"';
				$respose.='value="'.$value['siteID_id'].'"';
				$respose.='rel="'.$value['clientName'].'"';
				$respose.='/></p>';
			}
		}
		
		if(!empty($_REQUEST['siteID']) && ($_REQUEST['type']==2)){
			$siteID_text = explode(",",$_REQUEST['siteID']);
			foreach($data as $key => $value){
				if(in_array($value['siteID_id'],$siteID_text)){
					$respose.='<p><textarea class="form-control" rows="2" id="client_address">';
					$respose.='  Client Name : '.$value['clientName'];
					$respose.=' , Site Id : '.$value['site_id'];
					$respose.=' , Address : '.$value['site_address'];
					$respose.=' , State : '.$value['site_location'];
					$respose.=' , City : '.$value['site_city'];
					$respose.='</textarea></p>';
			    }		
			}	
		}
		
		if(!empty($_REQUEST['site_location']) && ($_REQUEST['type']==3)){
			$search_text = trim($_REQUEST['site_location']);
			$filterData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			$filter_Data = array_unique(array_column($filterData,'site_city'));
			$respose.='<option value="">Select City</option>';
			foreach($filter_Data as $key => $value){
				$respose.='<option value="'.$value.'">';
				$respose.= $value;
				$respose.='</option>';
				
			}
		}
		echo $respose;
	}
	
	/*$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
		$data['site_list'][$key] = $value;
		$clientName  = $this->assign_client_model->get_clientName_siteID_Data(trim($value['site_jobcard']),trim($value['site_sms']));
		$data['site_list'][$key]['client_name']  =  $clientName['client_name'];
		
		
		$reportNo = $this->form_model->check_report_numbers($value['siteID_id']);
		if(!is_array($reportNo)){
			$data['site_list'][$key]['report_no'] = '';
			$data['site_list'][$key]['inspected_status'] = 'No';
			$data['site_list'][$key]['approved_status'] = 'Pending';
		}else{
			$data['site_list'][$key]['report_no'] 			= $reportNo['report_no'];
			$data['site_list'][$key]['inspected_status'] 	= $reportNo['inspected_status'];
			$data['site_list'][$key]['approved_status'] 	= $reportNo['approved_status'];
		}
		
		$work_number = '';
		$work_no = $this->form_model->check_work_permit_number($value['siteID_id']);
		if(!$work_no){
			$work_number = 'WORK-000001';
		}else{
			$workNo_array 	= explode('-',$work_no['workPermit_number']);
			$newWorkNo		= $workNo_array[1] + 1;
			
			$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
		}
		$data['site_list'][$key]['workPermit_number'] 	= trim($work_number);
		
		}*/
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
		
	function view_site_detail(){
		if(!empty($_REQUEST['assginClientId'])){
			$this->load->model("inspector_inspection_model");
			$this->load->model("form_model");
			$this->load->model("assign_client_model");
			$client_data = $this->assign_client_model->get_client_data_by_id($_REQUEST['assginClientId']);
			
			$site_id = json_decode($client_data['site_id'],true);
			$this->load->model("siteid_model");
			$site_data = $this->siteid_model->get_site_data_by_id($site_id);
			
			if(!empty($site_data[0])){
				$this->load->model('api_model');
				$totalComponents 		= $this->api_model->get_totalCount('components');
				$totalsubComponent 		= $this->api_model->get_totalCount('sub_assets');
				$totalActionProposed 	= $this->_getAllActionProposed();
				$countAction 			= count($totalActionProposed);
				foreach($site_data as $key => $value){
					$temp[$key] = $value;
					

					$client_res = $this->siteid_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
					$temp[$key]['totalAsset'] 			= 	"$totalComponents";
					$temp[$key]['totalSubAsset']			=	"$totalsubComponent";
					$temp[$key]['totalAction_proposed']	=	"$countAction";
					if(is_object($client_res)){
							$clientid = $client_res->mdata_client;
							$clientName = $client_res->client_name;
							$temp[$key]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
					}



					
					$reportNo = $this->form_model->check_report_numbers($value['siteID_id']);
					if(!is_array($reportNo)){
							$temp[$key]['report_no'] = '';
							$temp[$key]['inspected_status'] = 'No';
							$temp[$key]['approved_status'] = 'Pending';
					}else{
							$temp[$key]['report_no'] 			= $reportNo['report_no'];
							$temp[$key]['inspected_status'] 	= $reportNo['inspected_status'];
							$temp[$key]['approved_status'] 	= $reportNo['approved_status'];
					}
					
					//$temp[$key]['assign_client'] = $this->Assign_client_model->get_site_list($value['siteID_id']);
					$assign_client = $this->assign_client_model->get_site_list($value['siteID_id']);
					
					if($assign_client['report_no'] == $reportNo['report_no']){
						$temp[$key]['asset_series'] = $assign_client['asset_series'];
						$temp[$key]['assgin_id'] = $assign_client['id'];
						$inspecte_name = $this->inspector_inspection_model->get_inspecte_name($assign_client['inspected_by']);
						$temp[$key]['upro_first_name'] = $inspecte_name['upro_first_name'];
						$temp[$key]['upro_last_name'] = $inspecte_name['upro_last_name'];
					}else{
						$temp[$key]['asset_series'] = '';
						$temp[$key]['assgin_id'] = '';
						$temp[$key]['upro_first_name'] = '';
						$temp[$key]['upro_last_name'] = '';
					}
				}
			}
		
			
			$data = array();
			if(!empty($temp)){
				foreach($temp as $key => $value){
					$data['siteDetail'][$key]['siteID_id'] = $value['siteID_id'];
					$data['siteDetail'][$key]['id'] = $value['assgin_id'];
					
					$data['siteDetail'][$key]['client_name'] = $value['client_name'];
					$data['siteDetail'][$key]['site_id'] = $value['site_id'];
					$data['siteDetail'][$key]['job_card'] = $value['site_jobcard'];
					$data['siteDetail'][$key]['sms'] = $value['site_sms'];
					
					$data['siteDetail'][$key]['site_id'] = $value['site_id'];
					$data['siteDetail'][$key]['job_card'] = $value['site_jobcard'];
					$data['siteDetail'][$key]['sms'] = $value['site_sms'];
					
					$data['siteDetail'][$key]['site_location'] = $value['site_location'];
					$data['siteDetail'][$key]['site_city'] = $value['site_city'];
					$data['siteDetail'][$key]['site_address'] = $value['site_address'];
					
					$data['siteDetail'][$key]['report_no'] = $value['report_no'];
					$data['siteDetail'][$key]['asset_series'] = $value['asset_series'];
					$data['siteDetail'][$key]['approved_status'] = $value['approved_status'];
					$data['siteDetail'][$key]['name'] = !empty($value['upro_first_name']) ? $value['upro_first_name'].' '.$value['upro_last_name'] : 'N/A';
					
				}
			}
			
			$this->load->view("assign_client/viewSiteDetail",$data);
			
		}
	}	
		
	function assign_siteID(){
		$data = array();
		
		$this->load->model("assign_client_model");
		$this->load->model("form_model");
		
		$client_data = $this->assign_client_model->get_client_data();
		
		foreach($client_data as $cKey=>$CVal){
			if(!empty($CVal['site_id'])){
				$site_id = json_decode($CVal['site_id'],true);
				$siteID = array();
				if(!empty($site_id)){
					foreach($site_id as $siteKey => $siteVal){
						$site = $this->assign_client_model->ajax_get_siteID($siteVal);
						$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
						//$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
						$siteID[] = $clientname['client_name'];
					}
				}
			}
			
			$client_name_list = json_decode($CVal['client_ids'],true);
			$userName= array();
			if(!empty($client_name_list) && !empty($CVal['client_type'])){
				foreach($client_name_list as $clientNameVal){
					$users = $this->assign_client_model->get_client_list_name_id($clientNameVal,$CVal['client_type']);
					$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
				}
			}

			$data['client_data'][$cKey] = array(
					'id' 			=> $CVal['id'],
					'status' 		=> $CVal['status'],
					'site_data' 	=> $siteID,
					'user_name' 	=> $userName
			);
			if($CVal['client_type'] == 7){
				$data['client_data'][$cKey]['client_type'] = 'Dealer';
			}else if($CVal['client_type'] == 8){
				$data['client_data'][$cKey]['client_type'] = 'Client';
			}else if($CVal['client_type'] == 9){
				$data['client_data'][$cKey]['client_type'] = 'Inspector';
			}
			
			$data['client_data'][$cKey]['date'] = date("M jS, Y", strtotime($CVal['assign_data']));
			
		}
		
		
		$this->load->view("assign_client/viewClientSitedata",$data);
	}
	
	function view_client_dealer_ajax(){
			$params = array();
            $params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
            $params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
			
			
			if(!empty($_REQUEST['clientType'])){
				$params['clientType'] =  $_REQUEST['clientType'];
			}
            $_REQUEST['fromDate'] = $params['startTime'];
			
            $this->load->model('assign_client_model');
            $clientData =  $this->assign_client_model->fetch_client_data($params);
			//print_r($clientData);die;
            $html = '';
            if(!empty($clientData) && is_array($clientData)){
                foreach($clientData as $cKey => $CVal){
                    if(!empty($CVal['site_id'])){
						$site_id = json_decode($CVal['site_id'],true);
						$siteID = array();
						if(!empty($site_id)){
							foreach($site_id as $siteKey => $siteVal){
								$site = $this->assign_client_model->ajax_get_siteID($siteVal);
								$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
								//$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
								$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
							}
						}
					}
					
					$client_name_list = json_decode($CVal['client_ids'],true);
					$userName= array();
					if(!empty($client_name_list) && !empty($CVal['client_type'])){
							foreach($client_name_list as $clientNameVal){
								$users = $this->assign_client_model->get_client_list_name_id($clientNameVal,$CVal['client_type']);
								$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
							}
					}

					$client_data[$cKey] = array(
							'id' 			=> $CVal['id'],
							'status' 		=> $CVal['status'],
							'site_data' 	=> $siteID,
							'user_name' 	=> $userName
					);
					if($CVal['client_type'] == 7){
						$client_data[$cKey]['client_type'] = 'Dealer';
					}else if($CVal['client_type'] == 8){
						$client_data[$cKey]['client_type'] = 'Client';
					}else if($CVal['client_type'] == 9){
						$client_data[$cKey]['client_type'] = 'Inspector';
					}
					
					$client_data[$cKey]['date'] = date("M jS, Y", strtotime($CVal['assign_data']));
                }
            }
			
			 $html = '<table id="kare_logs_view_datatable1" class="table table-bordered table-hover">
                    <thead>
                            <tr>
								<th>S.No.</th>
								<th>Client Name</th>
								<th>User Name</th>
								<th>Client Type</th>
								<th>status</th>
								<th>Site Detail</th>
								<th>Date</th>
                            </tr>
                    </thead>';
         if(!empty($client_data) && is_array($client_data)){
            $html .= '<tbody>';
            $c = 1;
		
             foreach ($client_data as $ky => $val) {
                $html .= '<td>'.$c.'</td>';
                $html .= '<td>'.implode('<br/>', $val['site_data']).'</td>';
                $html .= '<td>'.implode('<br/>', $val['user_name']).'</td>';
                $html .= '<td>'.$val['client_type'].'</td>';
                $html .= '<td>'.$val['status'].'</td>';
				$html .= '<td><a class="btn btn-success" href="'.base_url().'assign_client_controller/view_site_detail?assginClientId='.$val['id'].'" > Site Detail </a></td>';
                $html .= '<td>'.$val['date'].'</td>';
                $html .= '</tr>';
                $c++;
             }
           
            $html .= '</tbody>'; 
         }else{
             $html .= '<tbody><tr><td colspan="7" class="highlight_red"> No Data are available. </td> </tr></tbody>';
         }
         
         $html .= '</table>';
        
        
         print $html;
         exit();
			
	}
	
	public function assign_client_dealer(){
		$this->load->model('assign_client_model');
		
		$client_data = $this->assign_client_model->get_client_data();
		//$this->load->model('Siteid_model');
		
		if(!empty($client_data) && is_array($client_data)){
			foreach($client_data as $cKey=>$CVal){
				if(!empty($CVal['site_id'])){
					$site_id = json_decode($CVal['site_id'],true);
					$siteID = array();
					if(!empty($site_id)){
						foreach($site_id as $siteKey => $siteVal){
							$site = $this->assign_client_model->ajax_get_siteID($siteVal);
							$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
							$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
						}
					}
				}
				
				$client_name_list = json_decode($CVal['client_ids'],true);
				$userName= array();
				if(!empty($client_name_list) && !empty($CVal['client_type'])){
						foreach($client_name_list as $clientNameVal){
							$users = $this->assign_client_model->get_client_list_name_id($clientNameVal,$CVal['client_type']);
							$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
						}
				}

				$data['client_datas'][$cKey] = array(
						'id' 			=> $CVal['id'],
						'status' 		=> $CVal['status'],
						'site_data' 	=> $siteID,
						'user_name' 	=> $userName
				);
			}
		} 
		//print_r($data['client_datas']);die;
		$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
			$data['site_list'][$key]['siteID_id'] = $value['siteID_id'];
            $clientName  = $this->assign_client_model->get_clientName_siteID_Data(trim($value['site_jobcard']),trim($value['site_sms']));
			$data['site_list'][$key]['clientName']  = trim($clientName['client_name']);
			$data['site_list'][$key]['site_location']  = strtoupper($value['site_location']);
			$data['site_list'][$key]['site_city']  = strtoupper($value['site_city']);
			$data['site_list'][$key]['site_address']  = $value['site_address'];
			$data['site_list'][$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
		}
		$this->load->view('assign_client/assignClientDealer',$data);		
		
	}
	
	function _siteIds($state,$city,$client_name){
		$this->load->model('assign_client_model');
		$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
			$data[$key]['siteID_id'] = $value['siteID_id'];
            $clientName  = $this->assign_client_model->get_clientName_siteID_Data(trim($value['site_jobcard']),trim($value['site_sms']));
			$data[$key]['clientName']  = $clientName['client_name'];
			$data[$key]['site_location']  = strtoupper($value['site_location']);
			$data[$key]['site_city']  = strtoupper($value['site_city']);
			$data[$key]['site_address']  = $value['site_address'];
			$data[$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
		}
		
		if(!empty($state) && empty($city) && empty($client_name)){
			$search_text = trim($state);
			$filterData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
		}else if(!empty($state) && !empty($city) && empty($client_name)){
			$search_text = trim($state);
			$stateData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			$city_text = trim($city);
			$filterData = array_filter($stateData, function($v) use ($city_text) {
				if( stripos($v['site_city'], $city_text) !== false ){
					return true;
				}
				return false;
			});
		}else if(!empty($client_name) && empty($state) && empty($city)){
			$client_text = trim($client_name);
			$filterData = array_filter($data, function($v) use ($client_text) {
				if( stripos($v['clientName'], $client_text) !== false ){
					return true;
				}
				return false;
			});
		}else if(!empty($state) && !empty($client_name) && empty($city)){
			$search_text = trim($state);
			$stateData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			$client_text = trim($client_name);
			$filterData = array_filter($stateData, function($v) use ($client_text) {
				if( stripos($v['clientName'], $client_text) !== false ){
					return true;
				}
				return false;
			});
		}else if(!empty($state) && !empty($city) && !empty($client_name)){
			$search_text = trim($state);
			$stateData = array_filter($data, function($v) use ($search_text) {
				if( stripos($v['site_location'], $search_text) !== false ){
					return true;
				}
				return false;
			});
			
			$city_text = trim($city);
			$cityData = array_filter($stateData, function($v) use ($city_text) {
				if( stripos($v['site_city'], $city_text) !== false ){
					return true;
				}
				return false;
			});
			
			$client_text = trim($client_name);
			$filterData = array_filter($cityData, function($v) use ($client_text) {
				if( stripos($v['clientName'], $client_text) !== false ){
					return true;
				}
				return false;
			});
		}
		
		$siteid = array_column($filterData,'siteID_id');
		return $siteid ;
	}
	
	function search_site_id(){
		$this->load->model('assign_client_model');
		$client_data = $this->assign_client_model->get_client_data_by_id($_REQUEST['id']);
		
		if($_REQUEST['type'] == 2){
			$site_id = json_decode($client_data['site_id'],true);
			if(!empty($site_id)){
				foreach($site_id as $siteKey => $siteVal){
					//$site = $this->assign_client_model->ajax_get_siteID($siteVal);
					$site = $this->assign_client_model->ajax_get_siteID($siteVal);
					$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
					$data[$siteKey]['siteID'] = $site['siteID_id']; 
					$data[$siteKey]['site_name'] = $clientname['client_name'];
				}
			}
			
			$client_text = trim($_REQUEST['search']);
			$filterData = array_filter($data,function($v) use ($client_text) {
				if( stripos($v['site_name'], $client_text) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($filterData) && is_array($filterData)){
				$respose='';
				foreach($filterData as $key => $value){
					$respose.="<p>".$value['site_name'];
					$respose.='<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="chk_'.$value['siteID'].'"';
					$respose.='value="'.$value['siteID'].'"';
					$respose.='rel="'.$value['site_name'].'"';
					$respose.='/></p>';
				}  
			}
		}if($_REQUEST['type'] == 1){
			$client_name_list = json_decode($client_data['client_ids'],true);
			if(!empty($client_name_list) && !empty($client_data['client_type'])){
					foreach($client_name_list as $key => $clientNameVal){
						$user = $this->assign_client_model->get_client_list_name_id($clientNameVal,$client_data['client_type']);
						$data[$key]['user_id'] = $user['upro_id'];
						$data[$key]['user_name'] = $user['upro_first_name'].' '.$user['upro_last_name'];
					}
			}
			
			$client_text = trim($_REQUEST['search']);
			$filterData = array_filter($data,function($v) use ($client_text) {
				if( stripos($v['user_name'], $client_text) !== false ){
					return true;
				}
				return false;
			});
			
			if(!empty($filterData) && is_array($filterData)){
				$respose='';
				foreach($filterData as $key => $value){
					$respose.="<p>".$value['user_name'];
					$respose.='<input class="pull-right check-element" type="checkbox" name="client_ids[]" id="chk_'.$value['user_id'].'"';
					$respose.='value="'.$value['user_id'].'"';
					$respose.='rel="'.$value['user_name'].'"';
					$respose.='/></p>';
				}  
			}
		}
		echo $respose;
	}
	
	function update_assignClient(){
	   $data = array();
		$param = array();
		if(!empty($_REQUEST['submit']) && !empty($_REQUEST['id']) && !empty($_REQUEST['status']) && !empty($_REQUEST['site_id']) && !empty($_REQUEST['client_id'])){
		   
			$param['site_id'] = json_encode($_REQUEST['site_id']);
			$param['client_ids'] = json_encode($_REQUEST['client_id']);
			$param['status'] = ($_REQUEST['status'] == 1)?'Active':'Inactive';
			
			$result = $this->Assign_client_model->update_client_data($param,$_REQUEST['id']);
			if($result > 0){
				$data['responseType'] = 'success';
				$data['message'] = 'Successfully Update';
			}else{
				$data = array(  'responseType' => 'fail',
					'message' => 'Please try again!');
			}
		}else{
			$data = array(  'responseType' => 'fail',
							'message' => 'Please complete the field');
		}
		print json_encode($data);
		exit();
	}
        
	function update_assign_client($client_id){
		if(!empty($client_id)){
			$this->load->model('assign_client_model');
			$client_data = $this->assign_client_model->get_client_data_by_id($client_id);
			if(!empty($client_data) && is_array($client_data)){
				$site_id = json_decode($client_data['site_id'],true);
				$siteID = array();
				if(!empty($site_id)){
					foreach($site_id as $siteKey => $siteVal){
						//$site = $this->assign_client_model->ajax_get_siteID($siteVal);
						$site = $this->assign_client_model->ajax_get_siteID($siteVal);
						$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
						$data['siteID'][$siteKey]['siteID'] = $site['siteID_id']; 
						$data['siteID'][$siteKey]['site_name'] = $clientname['client_name'];
					}
				}

				
				
				$userName= array();
				$client_name_list = json_decode($client_data['client_ids'],true);
				if(!empty($client_name_list) && !empty($client_data['client_type'])){
						foreach($client_name_list as $clientNameVal){
							$data['userName'][] = $this->assign_client_model->get_client_list_name_id($clientNameVal,$client_data['client_type']);
						}
				}


				$data['client_data'] = $client_data;
			}
			
			$data['assign_client']	= $this->assign_client_model->get_client_list();	
			$data['client_list']	= $this->assign_client_model->get_client_name_list($client_data['client_type']);
			
			$site_list_array = $this->assign_client_model->ajax_get_siteID();
			foreach($site_list_array as $key => $value){
				$data['site_list_array'][$key]['siteID_id'] = $value['siteID_id'];
				$clientName  = $this->assign_client_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
				$data['site_list_array'][$key]['clientName']  = $clientName['client_name'];
				$data['site_list_array'][$key]['site_id']  =  $value['site_id'];
			}
			
			$this->load->view('assign_client/updateAssignClientDealer',$data);
			
		}    
	}
	
	public function insert_assign_client_dealer(){
		$data = array();
		$param = array();
		if(!empty($_REQUEST['submit']) && !empty($_REQUEST['status']) && !empty($_REQUEST['client_type'])){
			$param['status'] = ($_REQUEST['status'] == 1)?'Active':'Inactive';
			
			if(!empty($_REQUEST['client_type'])){
				$param['client_type'] =  $_REQUEST['client_type'];
			}
			
			if(!empty($_REQUEST['dealer_client_id']) && ($_REQUEST['client_type'] == 7)){
				$param['client_ids'] =  json_encode($_REQUEST['dealer_client_id']);
			}else if(!empty($_REQUEST['client_client_id']) && ($_REQUEST['client_type'] == 8)){
				$param['client_ids'] =  json_encode($_REQUEST['client_client_id']);
			}else if(!empty($_REQUEST['inspector_client_id']) && ($_REQUEST['client_type'] == 9)){
				$param['client_ids'] =  json_encode($_REQUEST['inspector_client_id']);
			}
			
			if(!empty($_REQUEST['site_id'])){
				$param['site_id'] = json_encode($_REQUEST['site_id']);
			}else if(!empty($_REQUEST['site_id_1'])){
				$param['site_id'] = json_encode($_REQUEST['site_id_1']);
			}else if(!empty($_REQUEST['state_name_popup']) && empty($_REQUEST['city_name_popup']) && empty($_REQUEST['client_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds($_REQUEST['state_name_popup'],'','');
				$param['site_id'] = json_encode($site_id);
			}else if(!empty($_REQUEST['state_name_popup']) && !empty($_REQUEST['city_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds($_REQUEST['state_name_popup'],$_REQUEST['city_name_popup'],'');
				$param['site_id'] = json_encode($site_id);
			}else if(!empty($_REQUEST['state_name_popup']) && !empty($_REQUEST['city_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds($_REQUEST['state_name_popup'],$_REQUEST['city_name_popup'],'');
				$param['site_id'] = json_encode($site_id);
			}else if(!empty($_REQUEST['client_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds('','',$_REQUEST['client_name_popup']);
				$param['site_id'] = json_encode($site_id);
			}else if(!empty($_REQUEST['client_name_popup']) && !empty($_REQUEST['state_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds($_REQUEST['state_name_popup'],'',$_REQUEST['client_name_popup']);
				$param['site_id'] = json_encode($site_id);
			}else if(!empty($_REQUEST['client_name_popup']) && !empty($_REQUEST['city_name_popup']) && !empty($_REQUEST['state_name_popup']) && empty($_REQUEST['site_id_1']) && empty($_REQUEST['site_id'])){
				$site_id = $this->_siteIds($_REQUEST['state_name_popup'],$_REQUEST['city_name_popup'],$_REQUEST['client_name_popup']);
				$param['site_id'] = json_encode($site_id);
			}
			
			if(!empty($_REQUEST['site_address'])){
				$explode_address =  explode("/.../",$_REQUEST['site_address']);
				$param['site_address'] = json_encode($explode_address);
			}
			
			
			$temp = array();
			if(!empty($_REQUEST['state_name_popup'])){
				$temp['state'] = $_REQUEST['state_name_popup'];
			}else{
				$temp['state'] = "";
			}
			if(!empty($_REQUEST['city_name_popup'])){
				$temp['city'] = $_REQUEST['city_name_popup'];
			}else{
				$temp['city'] = "";
			}
			if(!empty($_REQUEST['client_name_popup'])){
				$temp['client'] = $_REQUEST['client_name_popup'];
			}else{
				$temp['client'] = "";
			}
			$param["search_client"] = json_encode($temp);
			
			
			$this->load->model("Assign_client_model");
			$result = $this->Assign_client_model->insert_client_data($param);
			if($result > 0){
				$data['responseType'] = 'success';
				$data['message'] = 'Successfully Insert';
			}else{
				$data = array(  'responseType' => 'fail',
					'message' => 'Please Fill Mandatory Field!');
			}
		}else{
			$data = array(  'responseType' => 'fail',
							'message' => 'Please complete the field');
		}
		print json_encode($data);
		exit();
	}
	
	public function assign_client(){
		$this->load->model('assign_client_model');
		$client_data = $this->assign_client_model->get_client_data();
		//$this->load->model('Siteid_model');
		
		if(!empty($client_data) && is_array($client_data)){
			
			foreach($client_data as $cKey=>$CVal){
				$site_id = json_decode($CVal['site_id'],true);
				$client_name_list = json_decode($CVal['client_ids'],true);
				$siteID = array();
				if(!empty($site_id)){
					foreach($site_id as $siteKey => $siteVal){
						$site = $this->assign_client_model->ajax_get_siteID($siteVal);
						$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
						$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
					}
				}
				
				$userName= array();
				if(!empty($client_name_list)){
						foreach($client_name_list as $clientNameVal){
							$users = $this->assign_client_model->get_client_list_name_by_id($clientNameVal);
							$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
						}
				}

				$data['client_datas'][$cKey] = array(
						'id' 			=> $CVal['id'],
						'status' 		=> $CVal['status'],
						'site_data' 	=> $siteID,
						'user_name' 	=> $userName
				);
			}
		} 
		
		$data['assign_client']	= $this->assign_client_model->get_client_list();	
		$data['client_list']	= $this->assign_client_model->get_client_name();
		
		
		//$data['site_list_array'] = $this->assign_client_model->ajax_get_siteID();
		
		$site_list_array = $this->assign_client_model->ajax_get_siteID();
		foreach($site_list_array as $key => $value){
			$data['site_list_array'][$key]['siteID_id'] = $value['siteID_id'];
            $clientName  = $this->assign_client_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
			$data['site_list_array'][$key]['clientName']  = $clientName['client_name'];
			$data['site_list_array'][$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
		}
		
		
		$this->load->view('assign_client/assignClient',$data);		

	}

	
	
	
	function search_ajax_site_id(){
		$this->load->model('assign_client_model');
		$searchResult = $this->assign_client_model->search_ajax_site_id($_REQUEST['search']);
		//print_r($searchResult);die("123");
		if(!empty($searchResult) && is_array($searchResult)){
			$respose='';
			foreach($searchResult as $key => $value){
				$clientName  = $this->assign_client_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
				$respose.="<p>".$clientName['client_name'].' / '.$value['site_id'];
				$respose.='<input class="pull-right check-element" type="checkbox" name="siteID_id[]" id="chk_'.$value['siteID_id'].'"';
				$respose.='value="'.$value['siteID_id'].'"';
				$respose.='rel="'.$clientName['client_name'].' / '.$value['site_id'].'"';
				$respose.='/></p>';
			}  
			echo $respose;
		}
	}
	
	/*public function insert_assignClient($id=NULL){
		
		if(isset($_POST['save_assignInspector'])){
		
			$dbdata['client_users']		=	json_encode($this->input->post('clientUser'));
			$dbdata['client_ids']		=	json_encode($this->input->post('client_ids'));
			$dbdata['status']			=	$this->input->post('status');

			// submit form data
				if($this->Assign_client_model->insert_client_data($dbdata)){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Submited.</div>');
				   redirect('assign_client_controller/assign_client');		
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Error in data Submit.</div>');
				}
		}else{
			redirect('assign_client_controller/assign_client');	
		}
	}*/
	
	public function insert_assignClient(){
		$data = array();
		$param = array();
		if(!empty($_REQUEST['submit']) && !empty($_REQUEST['status']) && !empty($_REQUEST['site_id']) && !empty($_REQUEST['client_id'])){
		   
			$param['site_id'] = json_encode($_REQUEST['site_id']);
			$param['client_ids'] = json_encode($_REQUEST['client_id']);
			$param['status'] = ($_REQUEST['status'] == 1)?'Active':'Inactive';
			
			$result = $this->Assign_client_model->insert_client_data($param);
			if($result > 0){
				$data['responseType'] = 'success';
				$data['message'] = 'Successfully Insert';
			}else{
				$data = array(  'responseType' => 'fail',
					'message' => 'Please try again!');
			}
		}else{
			$data = array(  'responseType' => 'fail',
							'message' => 'Please complete the field');
		}
		print json_encode($data);
		exit();
	}
	

	
	/*public function assign_client_edit($client_id){
		
		if(isset($_POST['update_assignInspector'])){

			$dbdata['client_users']	=	json_encode($this->input->post('clientUser'));
			$dbdata['client_ids']	=	json_encode($this->input->post('client_ids'));
			$dbdata['status']		=	$this->input->post('status');
				// submit form data
				if($this->Assign_client_model->update_client_data($dbdata,$client_id)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Updated.</div>');
				   redirect('assign_client_controller/assign_client');	 	
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error In Data Updation.</div>');
					redirect('assign_client_controller/assign_client');	
				}
		}else{
			// Client User List
			$assign_client	=$this->Assign_client_model->get_client_list(); 
			
			// Client List
			$client			=$this->Assign_client_model->get_client_name();	

			// Assign Client data
			$client_data_by_id	=$this->Assign_client_model->get_client_data_by_id($client_id);	
			
			$client_user = json_decode($client_data_by_id['client_users'],true);
			$client_name_list = json_decode($client_data_by_id['client_ids'],true);
			
			foreach($client_user as $clientVal){
				$client_user_name[]	=$this->Assign_client_model->get_client_user_name_by_id($clientVal);
			}
			
			foreach($client_name_list as $clientNameVal){
				$clientName[] = $this->Assign_client_model->get_client_list_name_by_id($clientNameVal);
			}
			
			$data['assign_client']		= $assign_client;	
			$data['client_list']		= $client;
			$data['client_data']		= $client_data_by_id;
			$data['client_user_name']	= $client_user_name;
			$data['client_list_Name']	= $clientName;
			
			$this->load->view('assign_client/assign_client_edit',$data);		
		}
		
	}*/
	
	 public function delete_client($client_id){
           if($client_id!=null){
                if($this->Assign_client_model->delete_client($client_id)){
                    $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully.</div>');
                    redirect('assign_client_controller/assign_client');	
                }else{
                    $this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error In Deletion.</div>');
                    redirect('assign_client_controller/assign_client');	
                }  
            }	
	}
	
        function edit_assignClient(){
           $data = array();
            $param = array();
            if(!empty($_REQUEST['submit']) && !empty($_REQUEST['id']) && !empty($_REQUEST['status']) && !empty($_REQUEST['site_id']) && !empty($_REQUEST['client_id'])){
               
                $param['site_id'] = json_encode($_REQUEST['site_id']);
                $param['client_ids'] = json_encode($_REQUEST['client_id']);
                $param['status'] = ($_REQUEST['status'] == 1)?'Active':'Inactive';
                
                $result = $this->Assign_client_model->update_client_data($param,$_REQUEST['id']);
                if($result > 0){
                    $data['responseType'] = 'success';
                    $data['message'] = 'Successfully Update';
                }else{
                    $data = array(  'responseType' => 'fail',
                        'message' => 'Please try again!');
                }
            }else{
                $data = array(  'responseType' => 'fail',
                                'message' => 'Please complete the field');
            }
            print json_encode($data);
            exit();
        }
        
        function assign_client_edit($client_id){
            if(!empty($client_id)){
                $this->load->model('assign_client_model');
                $client_data = $this->assign_client_model->get_client_data_by_id($client_id);
                if(!empty($client_data) && is_array($client_data)){
                    $site_id = json_decode($client_data['site_id'],true);
                    $client_name_list = json_decode($client_data['client_ids'],true);
                    $siteID = array();
                    if(!empty($site_id)){
                        foreach($site_id as $siteKey => $siteVal){
							//$site = $this->assign_client_model->ajax_get_siteID($siteVal);
                            $site = $this->assign_client_model->ajax_get_siteID($siteVal);
							$clientname  = $this->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
                            $data['siteID'][$siteKey]['siteID'] = $site['siteID_id']; 
                            $data['siteID'][$siteKey]['site_name'] = $clientname['client_name'].' / '.$site['site_id'];
                        }
                    }

                    $userName= array();
                    if(!empty($client_name_list)){
                            foreach($client_name_list as $clientNameVal){
                                $data['userName'][] = $this->assign_client_model->get_client_list_name_by_id($clientNameVal);
                            }
                    }

                    $data['client_data'] = $client_data;
                }
                
                $data['assign_client']	= $this->assign_client_model->get_client_list();	
                $data['client_list']	= $this->assign_client_model->get_client_name();
                
				$site_list_array = $this->assign_client_model->ajax_get_siteID();
				foreach($site_list_array as $key => $value){
					$data['site_list_array'][$key]['siteID_id'] = $value['siteID_id'];
					$clientName  = $this->assign_client_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
					$data['site_list_array'][$key]['clientName']  = $clientName['client_name'];
					$data['site_list_array'][$key]['site_id']  =  $clientName['client_name'].' / '.$value['site_id'];
				}
                
                $this->load->view('assign_client/assign_client_edit',$data);
                
            }    
        }
	
		
}// end of controller class 
?>