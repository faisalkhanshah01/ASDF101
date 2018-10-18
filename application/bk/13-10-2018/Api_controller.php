<?php
require ('./application/libraries/REST_Controller.php');

class Api_controller extends REST_Controller
{	
	function __construct() 
    {
                 parent::__construct();
 		$this->auth = new stdClass;
		$this->load->library('flexi_auth');
		$this->load->library('encrypt');
		$this->load->database();
		$this->load->library('flexi_auth');
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data = null;
                ini_set('display_errors',1);
                error_reporting(E_ALL && ~E_NOTICE &&  ~E_WARNING);
               
                
	}
	function get_scheduler_sites_get(){
		$this->load->model('api_model');
		if(!empty($_GET['user_id'])){
			$siteid = $this->api_model->get_data('*', 's_user_id', $_GET['user_id'], 'scheduler');
			$scheduler_data = array();
			//$scheduler_data[$i]['master_id'] = array();
			$i = 0;
			foreach ($siteid as $value) {
				$siteid_data = $this->api_model->get_data('*', 'siteID_id', $value['s_site_id'], 'siteID_data');
				// $master_data = $this->api_model->get_data('*', 'mdata_id', $siteid_data[0]['master_id'], 'master_data');
				// $scheduler_data[$i] = array_merge($siteid_data[0],$master_data[0]);	
				
				$scheduler_data[$i]= $siteid_data[0];
				$this->load->model('Siteid_model');
			    $this->load->model('Form_model');
				$scheduler_data[$i]['scheduled_date'] 			= 	$value['s_scheduler_date'];
				
				$client_res = $this->Siteid_model->get_clientName_siteID(trim($siteid_data[0]['site_jobcard']),trim($siteid_data[0]['site_sms']));
				//print_r($client_res);die();
				$scheduler_data[$i]['totalAsset'] 			= 	"$totalComponents";
				$scheduler_data[$i]['totalSubAsset']		=	"$totalsubComponent";
				$scheduler_data[$i]['totalAction_proposed']	=	"$countAction";
				if(is_object($client_res)){
					$clientid = $client_res->mdata_client;
					$clientName = $client_res->client_name;
					$scheduler_data[$i]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
				}
				
				/*
				$reportNo = $this->Form_model->check_report_numbers($siteid_data[0]['siteID_id']);
				if(!is_array($reportNo)){
					$scheduler_data[$i]['report_no'] = '';
					$scheduler_data[$i]['inspected_status'] = 'No';
					$scheduler_data[$i]['approved_status'] = 'Pending';
				}else{
					$scheduler_data[$i]['report_no'] 			= $reportNo['report_no'];
					$scheduler_data[$i]['inspected_status'] 	= $reportNo['inspected_status'];
					$scheduler_data[$i]['approved_status'] 	= $reportNo['approved_status'];
				}
				*/

				/// Start care fully
				if( !empty($siteid_data[0]['siteID_id'])){
					$reportNo = $this->Form_model->check_report_numbers($siteid_data[0]['siteID_id']);
					if(!is_array($reportNo)){
						$scheduler_data[$i]['report_no']			= '';
						$scheduler_data[$i]['inspected_status']	= 'No';
						$scheduler_data[$i]['approved_status']	= 'Pending';
					}else{
						$scheduler_data[$i]['report_no'] 			= $reportNo['report_no'];
						$scheduler_data[$i]['inspected_status'] 	= $reportNo['inspected_status'];
						$scheduler_data[$i]['approved_status'] 	= $reportNo['approved_status'];
					}					
				}else if( !empty($siteid_data[0]['mdata_item_series'])){	
					    $mdata_item_seriesArry = json_decode($siteid_data[0]['mdata_item_series'], true) ; 						
						foreach($mdata_item_seriesArry AS $key1 => $val1){
							$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
							if(!is_array($reportasset_seriesno)){
								$scheduler_data[$i]['report_no']			= '';
								$scheduler_data[$i]['inspected_status']	= 'No';
								$scheduler_data[$i]['approved_status']	= 'Pending';
							}else{
								$scheduler_data[$i]['report_no'] 			= $reportasset_seriesno['report_no'];
								$scheduler_data[$i]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
								$scheduler_data[$i]['approved_status'] 	= $reportasset_seriesno['approved_status'];
							}
						
						}
				}else{
					$scheduler_data[$i]['report_no']			= '';
					$scheduler_data[$i]['inspected_status']	= 'No';
					$scheduler_data[$i]['approved_status']	= 'Pending';				
				}
				////  End care fully 


				
					$work_number = '';
					$work_no = $this->Form_model->check_work_permit_number($siteid_data[0]['siteID_id']);
					if(!$work_no){
						$work_number = 'WORK-000001';
					}else{
						$workNo_array 	= explode('-',$work_no['workPermit_number']);
						$newWorkNo		= $workNo_array[1] + 1;
						
						$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
					}
					$scheduler_data[$i]['workPermit_number'] 	= trim($work_number);
				
				
			//	$scheduler_data[$i] = $siteid_data[0];
				//$scheduler_data[$i]['master_id'] = $master_data[0];
			
				//if(!empty($master_data[0]['mdata_asset'])) {
					//$scheduler_data[$i]['master_id']['mdata_asset'] = array();
				//	$mdata_asset = json_decode($master_data[0]['mdata_asset']);
					//$k = 0;
				//	foreach($mdata_asset as $value_mdata_asset) {
				//		$asset = $this->api_model->get_data('*', 'component_code', $value_mdata_asset, 'components');
				//		$scheduler_data[$i]['master_id']['asset'][$k] = $asset[0];
					//	if(!empty($asset[0]['component_inspectiontype'])) {
					//		$component_inspectiontype = $this->api_model->get_and_data('*', 'id', $asset[0]['component_inspectiontype'], 'type_category', 'status', 1);
					//		$scheduler_data[$i]['master_id']['asset'][$k]['component_inspectiontype'] = $component_inspectiontype;
					//	}
						//$scheduler_data[$i]['master_id']['asset'][$k]['component_expectedresult'] = array();
						//if(!empty($asset[0]['component_expectedresult'])) {
						//	$component_expected_result = json_decode($asset[0]['component_expectedresult']);
						//	$l = 0;
						//	foreach ($component_expected_result as $value_component_expected_result) {
						//		$component_expected_result_val = $this->api_model->get_and_data('*', 'id', $value_component_expected_result, 'type_category', 'status', 1);
						//		$scheduler_data[$i]['master_id']['asset'][$k]['component_expectedresult'][$l] = $component_expected_result_val[0];
						//		$l++;
						//	}
						//}
						//$scheduler_data[$i]['master_id']['asset'][$k]['component_observation'] = array();
						//if(!empty($asset[0]['component_observation'])) {
						//	$component_observation = json_decode($asset[0]['component_observation']);
						//	$l = 0;
						//	foreach ($component_observation as $value_component_observation) {
						//		$component_observation_val = $this->api_model->get_and_data('*', 'id', $value_component_observation, 'type_category', 'status', 1);
						//		$scheduler_data[$i]['master_id']['asset'][$k]['component_observation'][$l] = $component_observation_val[0];
						//		$l++;
						//}
						//}
					//	$k++;
					//}
				//} elseif(!empty($master_data[0]['mdata_item_series'])) {
				//	$mdata_asset_series = json_decode($master_data[0]['mdata_item_series']);
				//	$j = 0;
				//	foreach($mdata_asset_series as $value_mdata_asset_series) {
				//		$asset_series = $this->api_model->get_data('*', 'product_code', $value_mdata_asset_series, 'products');
						//print_r($asset_series);die();
				//		$scheduler_data[$i]['master_id']['asset_series_data'][$j] = $asset_series[0];
					//	$j++;
					//}
			
				//print_r($scheduler_data);die();
				$i++;
			}
			$this->response(array("msg" => "Success","msg_code"=>'200', 'scheduler_data'=>$scheduler_data));
			
			
			
			// Code by Basant start from here
			
			// $siteid =  implode(",", $siteid);
			// $site = explode(',',$siteid);
			// $this->load->model('Siteid_model');
			// $this->load->model('Form_model');
			// if(!empty($site) && is_array($site)){
			// 	foreach($site as $key =>$value){
			// 		$sdata[$key]=$this->Siteid_model->get_siteid_item($value);
			// 	}
				
			// 	if(!empty($sdata) && is_array($sdata)){
			// 		/* Get total count of components, sub-somponents and action proposed for offline api */
			// 			$this->load->model('Api_model');
			// 			$totalComponents 		= $this->Api_model->get_totalCount('components');
			// 			$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
			// 			$totalActionProposed 	= $this->_getAllActionProposed();
			// 			$countAction 			= count($totalActionProposed);
			// 		/* End */
			// 		foreach($sdata as $sKey=>$sVal){
			// 			/* Get Client ID */
			// 			$client_res = $this->Siteid_model->get_clientName_siteID(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
						
			// 			$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
			// 			$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
			// 			$sdata[$sKey]['totalAction_proposed']	=	"$countAction";
			// 			if(is_object($client_res)){
			// 				$clientid = $client_res->mdata_client;
			// 				$clientName = $client_res->client_name;
			// 				$sdata[$sKey]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
			// 			}
			// 			/* Get Report Number from inspection_list_1 table */
			// 			$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
			// 			if(!is_array($reportNo)){
			// 				$sdata[$sKey]['report_no'] = '';
			// 				$sdata[$sKey]['inspected_status'] = 'No';
			// 				$sdata[$sKey]['approved_status'] = 'Pending';
			// 			}else{
			// 				$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
			// 				$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
			// 				$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
			// 			}
			// 			/* End of Report Number from inspection_list_1 table */
			// 			/* Check for any work Permit Number, If exist then increment the last work permit no. */
			// 				$work_number = '';
			// 				$work_no = $this->Form_model->check_work_permit_number($sVal['siteID_id']);
			// 				if(!$work_no){
			// 					$work_number = 'WORK-000001';
			// 				}else{
			// 					$workNo_array 	= explode('-',$work_no['workPermit_number']);
			// 					$newWorkNo		= $workNo_array[1] + 1;
			// 					/* To Get the string the specific format */
			// 					$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
			// 				}
			// 				$sdata[$sKey]['workPermit_number'] 	= trim($work_number);
			// 			/* End of Work Permit Number */
			// 		}
				
			// 		$this->response($sdata,200);
			// 	}else{
			// 		$this->response(array("message" => "No Site ID is assign to you","message_code"=>'404'));
			// 	}
				
			// }else{
			// 	$this->response(array("message" => "No Site ID is assign to you","message_code"=>'404'));
			// }
		}else{
			$this->response(array("msg" => "parameter missing !","msg_code"=>'404'));
		}	
	}

	function add_scheduler_post() {
		$this->load->model('api_model');
		if(empty($_POST['user_id']) || empty($_POST['site_id'])) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else {
			$data = array(
				's_user_id' 		=> $_POST['user_id'],
				's_site_id'			=> $_POST['site_id'],
				's_scheduler_date'	=> !empty($_POST['scheduler_date'])?$_POST['scheduler_date']:'',
			);
			if($this->api_model->field_exists_check('s_user_id', $data['s_user_id'] , 'scheduler', 's_site_id', $data['s_site_id']) > 0) {
				$update_scheduler = $this->api_model->update_data(array('s_scheduler_date'=>$data['s_scheduler_date']), 'scheduler', 's_user_id', $data['s_user_id'], 's_site_id', $data['s_site_id']);
				if($update_scheduler) {
					$response = array(
						'msg_code'	=> 200,
						'msg'		=> 'Scheduler updated successfully'
					);
				} else {
					$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'Scheduler updation failed'
					);
				}
			} else {
				$add_scheduler = $this->api_model->insert_data($data, 'scheduler');
				if($add_scheduler) {
					$response = array(
						'msg_code'	=> 200,
						'msg'		=> 'Scheduler added successfully'
					);
				} else {
					$response = array(
						'msg_code'	=> 200,
						'msg'		=> 'Scheduler adding failed'
					);
				}
			}
		}
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
	}
	
	function search_product_barcode_get(){
		if(!empty($_REQUEST['code']) && !empty($_REQUEST['type_code'])){
			//110550000153
			$item_request_dat = array();
			$this->load->model('api_model');
			$this->load->model('ProductCategory_model');
			//$category = $this->ProductCategory_model->get_product_barcode($_REQUEST['code'],$_REQUEST['type_code']);
			if($_REQUEST['type_code'] == 'uin') {
				$type_code = 'mdata_uin';
			} elseif($_REQUEST['type_code'] == 'rfid') {
				$type_code = 'mdata_rfid';
			} elseif($_REQUEST['type_code'] == 'barcode') {
				$type_code = 'mdata_barcode';
			}
			$item_request = $this->api_model->get_data("*", $type_code, $_REQUEST['code'], 'master_data');
			if(!empty($item_request)) {
				$item_series_val = json_decode($item_request[0]['mdata_item_series']);
				$i = 0;
				$cat_data = array();
				foreach ($item_series_val as $value) {
					$cat_data_val = $this->api_model->get_data('*', 'cat_name', $value, 'manage_categories');
					if(!empty($cat_data_val)) {
						$cat_data[$i] = $cat_data_val[0];
						$i++;
					}
				}
				$result = array('success'=>$cat_data);
				$results = (object)$result;
			} else {
				$result = array('error'=>'KD-0001');
				$results = (object)$result;
			}
			//print_r($item_request);die();
			
		}else{
			// Error  KD-0001: No Category Found in table( manage_categories ) !! 
			$result = array('error'=>'KD-0001');
			$results = (object)$result;
		}
		$this->response($results,200);
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
	
	function search_data_get(){
		$param = array();
		$this->load->model('Search_model');
		$invoice_data = $this->Search_model->search_invoice();
		$invoice_data = array_column($invoice_data, 'mdata_material_invoice');
		$invoice_data = array_unique(array_diff(array_values(array_filter($invoice_data)),array('NA'))); 
		$param['invoice_data'] = explode(',',implode(',',$invoice_data));
		
		$param['client_name'] = $this->sort_data('client_name');
		$param['client_circle'] = $this->sort_data('client_circle');
		$param['client_district'] = $this->sort_data('client_district');
		
		$this->response($param, 200);
	}
	
	function searchHistoryGet_get(){
		$param = array();
		$where = '';
		$where = "1 = 1";
		
		if(!empty($_REQUEST['district'])){
			$where .= " AND D.client_district = '".$_REQUEST['district']."'";
			$param['client_district'] = $_REQUEST['district'];
		}
		if(!empty($_REQUEST['circle'])){
			$where .= " AND D.client_circle = '".$_REQUEST['circle']."'";
			$param['client_circle'] = $_REQUEST['circle'];
		}
		if(!empty($_REQUEST['client'])){
			$where .= " AND D.client_name = '".$_REQUEST['client']."'";
			$param['client_name'] = $_REQUEST['client'];
		}
		if(!empty($_REQUEST['asset_series'])){
			$where .= " AND A.asset_series = '".$_REQUEST['asset_series']."'";
			$param['asset_series'] = $_REQUEST['asset_series'];
		}
		if(!empty($_REQUEST['asset'])){
			$where .= " AND B.asset_name = '".$_REQUEST['asset']."'";
			$param['asset_name'] = $_REQUEST['asset'];
		}
		if(!empty($_REQUEST['invoice'])){
			$where .= " AND C.mdata_material_invoice = '".$_REQUEST['invoice']."'";
			$param['mdata_material_invoice'] = $_REQUEST['invoice'];
		}
		
		
		if(!empty($_REQUEST['client']) || !empty($_REQUEST['district']) || !empty($_REQUEST['circle']) || !empty($_REQUEST['invoice']) || !empty($_REQUEST['asset_series']) || !empty($_REQUEST['asset'])){
			
			if(!empty($_REQUEST['fromDate'])){
				$startTime = strtotime($_REQUEST['fromDate']);
			}
			if(!empty($_REQUEST['toDate'])){
				$endTime = strtotime($_REQUEST['toDate']);
			}
		}else{
			$this->response(array("message" => "parameter missing","message_code"=>'404'));
		}	
		//print_r($_REQUEST);die;
		$this->load->model('search_model');
		$this->load->model('Siteid_model');
		
		if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id']) && ($_REQUEST['group_id'] == 9)){
			if(!empty($_REQUEST['asset'])){
				$where .= " AND A.inspected_by = '".$_REQUEST['user_id']."'";
				$result = $this->search_model->fetch_search_data($where);
			}else{
				$where .= " AND A.inspected_by = '".$_REQUEST['user_id']."'";
				$result = $this->search_model->fetch_search_inspector($where);
				if(is_array($result)){
					foreach($result as $key => $value){
						$asset_series = $this->search_model->fetch_asset_name_inspector($value['id']);
						$result[$key]['asset_name'] = $asset_series['asset_name'];
					}
				}
			}
			
			/****************************site table search**************************************/
				$param['group_id'] = $_REQUEST['group_id'];
				$param['user_id'] = $_REQUEST['user_id'];
				$siteData = $this->search_model->get_siteid_list_of_inspector($param);
			/***********************************************************************/
			
			/****************************assign_client_data table search**************************************/
				$param['group_id'] = $_REQUEST['group_id'];
				$param['user_id'] = $_REQUEST['user_id'];
				$assignClient = $this->search_model->get_assign_client($param);
			//print_r($assign_client);die;
			/***********************************************************************/
			
			$sitedataCount = ($siteData > 0)?$siteData:'';
			$resultCount = ($result > 0)?$result:'';
			$assign_client =($assignClient > 0)?$assignClient:'';
			
			if(!empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$siteResult = array_merge($sitedataCount,$resultCount);
				$searchData = array_merge($siteResult,$assign_client);
			}elseif(!empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$searchData = array_merge($sitedataCount,$resultCount);
			}elseif(empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$searchData = array_merge($resultCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$searchData = array_merge($sitedataCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && empty($assign_client)){
				$searchData = $sitedataCount;
			}elseif(empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$searchData = $resultCount;
			}elseif(empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$searchData = $assign_client;
			}else{
				$searchData = '';
			}
			
		}elseif(!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id']) && ($_REQUEST['group_id'] == 8)){
			$param['group_id'] = $_REQUEST['group_id'];
			$param['user_id'] = $_REQUEST['user_id'];
			$assignClient = $this->search_model->get_assign_client($param);
			
			$searchData =($assignClient > 0)?$assignClient:'';
		}else{
			if(!empty($_REQUEST['asset'])){
				$result = $this->search_model->fetch_search_data($where);
			}else{
				$result = $this->search_model->fetch_search_inspector($where);
				if(is_array($result)){
					foreach($result as $key => $value){
						$asset_series = $this->search_model->fetch_asset_name_inspector($value['id']);
						$result[$key]['asset_name'] = $asset_series['asset_name'];
					}
				}
			}
			
			if($result  > 0){
				if(!empty($result) && is_array($result)){
					$temp = array();
					$c = 0;
					foreach ($result as $kay => $value) {
						$searchData[$c]['asset_series'] = $value['asset_series'];
						$searchData[$c]['asset'] = $value['asset_name'];
						$searchData[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
						$searchData[$c]['report_no'] = $value['report_no'];
						$searchData[$c]['site_id'] = $value['site_id'];
						$searchData[$c]['job_card'] = $value['job_card'];
						$searchData[$c]['sms'] = $value['sms'];
						$searchData[$c]['client_name'] = $value['client_name'];
						$searchData[$c]['status'] = $value['approved_status'];
						$searchData[$c]['time'] = strtotime($value['created_date']);
						$c++;
					}
				}
			}else{
				$searchData = '';
			}	
			
		}
		
		
		if(!empty($searchData)){
			if(is_array($searchData)){
				$c1 = 0;
				foreach($searchData as $key => $value){
					if(!empty($startTime) && !empty($endTime) && ($value['time'] >= $startTime) && ($value['time'] <= $endTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}elseif(!empty($startTime) && empty($endTime) && ($value['time'] >= $startTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}elseif(empty($startTime) && empty($endTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}
					$c1++;
				}
				
				if(count($data) > 0){
					if(is_array($data)){
						//$searchData = array();
						$c1= 0;
						foreach($data as $k =>$v){
							$searchData[$c1] = $v;
							$c1++;
						}
					}
					$this->response($searchData,200);
				}else{
					$this->response(array("message" => "No Data","message_code"=>'404'));
				}
			}else{
				$this->response(array("message" => "No Data","message_code"=>'404'));
			}
		}else{
			$this->response(array("message" => "No Data","message_code"=>'404'));
		}	
	}
	
	function searchHistory_post(){
		$param = array();
		$where = '';
		$where = "1 = 1";
		
		if(!empty($_REQUEST['district'])){
			$where .= " AND D.client_district = '".$_REQUEST['district']."'";
			$param['client_district'] = $_REQUEST['district'];
		}
		if(!empty($_REQUEST['circle'])){
			$where .= " AND D.client_circle = '".$_REQUEST['circle']."'";
			$param['client_circle'] = $_REQUEST['circle'];
		}
		if(!empty($_REQUEST['client'])){
			$where .= " AND D.client_name = '".$_REQUEST['client']."'";
			$param['client_name'] = $_REQUEST['client'];
		}
		if(!empty($_REQUEST['asset_series'])){
			$where .= " AND A.asset_series = '".$_REQUEST['asset_series']."'";
			$param['asset_series'] = $_REQUEST['asset_series'];
		}
		if(!empty($_REQUEST['asset'])){
			$where .= " AND B.asset_name = '".$_REQUEST['asset']."'";
			$param['asset_name'] = $_REQUEST['asset'];
		}
		if(!empty($_REQUEST['invoice'])){
			$where .= " AND C.mdata_material_invoice = '".$_REQUEST['invoice']."'";
			$param['mdata_material_invoice'] = $_REQUEST['invoice'];
		}
		
		
		if(!empty($_REQUEST['client']) || !empty($_REQUEST['district']) || !empty($_REQUEST['circle']) || !empty($_REQUEST['invoice']) || !empty($_REQUEST['asset_series']) || !empty($_REQUEST['asset'])){
			
			if(!empty($_REQUEST['fromDate'])){
				$startTime = strtotime($_REQUEST['fromDate']);
			}
			if(!empty($_REQUEST['toDate'])){
				$endTime = strtotime($_REQUEST['toDate']);
			}
		}else{
			$this->response(array("message" => "parameter missing","message_code"=>'404'));
		}	
		//print_r($_REQUEST);die;
		$this->load->model('search_model');
		$this->load->model('Siteid_model');
		if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id']) && ($_REQUEST['group_id'] == 9)){
			if(!empty($_REQUEST['asset'])){
				$where .= " AND A.inspected_by = '".$_REQUEST['user_id']."'";
				$result = $this->search_model->fetch_search_data($where);
			}else{
				$where .= " AND A.inspected_by = '".$_REQUEST['user_id']."'";
				$result = $this->search_model->fetch_search_inspector($where);
				if(is_array($result)){
					foreach($result as $key => $value){
						$asset_series = $this->search_model->fetch_asset_name_inspector($value['id']);
						$result[$key]['asset'] = $asset_series['asset_name'];
						$result[$key]['status'] = $result[$key]['approved_status'];
						unset($result[$key]['approved_status']);
					}
				}
			}
			
			/****************************site table search**************************************/
			$param['group_id'] = $_REQUEST['group_id'];
			$param['user_id'] = $_REQUEST['user_id'];
			$siteData = $this->search_model->get_siteid_list_of_inspector($param);
			/***********************************************************************/
			
			/****************************assign_client_data table search**************************************/
			$param['group_id'] = $_REQUEST['group_id'];
			$param['user_id'] = $_REQUEST['user_id'];
			$assignClient = $this->search_model->get_assign_client($param);
			//print_r($assign_client);die;
			/***********************************************************************/
			
			$sitedataCount = ($siteData > 0)?$siteData:'';
			$resultCount = ($result > 0)?$result:'';
			$assign_client =($assignClient > 0)?$assignClient:'';
			
			if(!empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$siteResult = array_merge($sitedataCount,$resultCount);
				$searchData = array_merge($siteResult,$assign_client);
			}elseif(!empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$searchData = array_merge($sitedataCount,$resultCount);
			}elseif(empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$searchData = array_merge($resultCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$searchData = array_merge($sitedataCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && empty($assign_client)){
				$searchData = $sitedataCount;
			}elseif(empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$searchData = $resultCount;
			}elseif(empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$searchData = $assign_client;
			}else{
				$searchData = '';
			}
			
		}else if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id']) && ($_REQUEST['group_id'] == 8)){
			$param['group_id'] = $_REQUEST['group_id'];
			$param['user_id'] = $_REQUEST['user_id'];
			$assignClient = $this->search_model->get_assign_client($param);
			
			$searchData =($assignClient > 0)?$assignClient:'';
		}else{
			if(!empty($_REQUEST['asset'])){
				$result = $this->search_model->fetch_search_data($where);
			}else{
				$result = $this->search_model->fetch_search_inspector($where);
				if(is_array($result)){
					foreach($result as $key => $value){
						$asset_series = $this->search_model->fetch_asset_name_inspector($value['id']);
						$result[$key]['asset'] = $asset_series['asset_name'];
						$result[$key]['status'] = $result[$key]['approved_status'];
						unset($result[$key]['approved_status']);
					}
				}
			}
			
			if($result  > 0){
				if(!empty($result) && is_array($result)){
					$temp = array();
					$c = 0;
					foreach ($result as $kay => $value) {
						$searchData[$c]['asset_series'] = $value['asset_series'];
						$searchData[$c]['asset'] = $value['asset_name'];
						$searchData[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
						$searchData[$c]['report_no'] = $value['report_no'];
						$searchData[$c]['site_id'] = $value['site_id'];
						$searchData[$c]['job_card'] = $value['job_card'];
						$searchData[$c]['sms'] = $value['sms'];
						$searchData[$c]['client_name'] = $value['client_name'];
						$searchData[$c]['status'] = $value['approved_status'];
						//$temp[$c]['time'] = date("d-m-Y",strtotime($value['created_date']));
						$searchData[$c]['time'] = strtotime($value['created_date']);
						$c++;
					}
				}
			}else{
				$searchData = '';
			}	
			
		}
		
		
		if(!empty($searchData)){
			if(is_array($searchData)){
				$c1 = 0;
				foreach($searchData as $key => $value){
					if(!empty($startTime) && !empty($endTime) && ($value['time'] >= $startTime) && ($value['time'] <= $endTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}elseif(!empty($startTime) && empty($endTime) && ($value['time'] >= $startTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}elseif(empty($startTime) && empty($endTime)){
						$data[$c1] = $value;
						$data[$c1]['time'] = date("d-m-Y",$value['time']);
					}
					$c1++;
				}
				
				if(count($data) > 0){
					if(is_array($data)){
						//$searchData = array();
						$c1= 0;
						foreach($data as $k =>$v){
							$searchData[$c1] = $v;
							$c1++;
						}
					}
					$this->response($searchData,200);
				}else{
					$this->response(array("message" => "No Data","message_code"=>'404'));
				}
			}else{
				$this->response(array("message" => "No Data","message_code"=>'404'));
			}
		}else{
			$this->response(array("message" => "No Data","message_code"=>'404'));
		}	
	}
	
	
	function searchHistory_old_8_2_2014_post(){
		$where = '';
		$where = "1 = 1";
		
		if(!empty($_REQUEST['district'])){
			$where .= " AND D.client_district = '".$_REQUEST['district']."'";
		}
		if(!empty($_REQUEST['circle'])){
			$where .= " AND D.client_circle = '".$_REQUEST['circle']."'";
		}
		if(!empty($_REQUEST['client'])){
			$where .= " AND D.client_name = '".$_REQUEST['client']."'";
		}
		if(!empty($_REQUEST['asset_series'])){
			$where .= " AND A.asset_series = '".$_REQUEST['asset_series']."'";
		}
		if(!empty($_REQUEST['asset'])){
			$where .= " AND B.asset_name = '".$_REQUEST['asset']."'";
		}
		if(!empty($_REQUEST['invoice'])){
			$where .= " AND C.mdata_material_invoice = '".$_REQUEST['invoice']."'";
		}
		
		
		if(!empty($_REQUEST['client']) || !empty($_REQUEST['district']) || !empty($_REQUEST['circle']) || !empty($_REQUEST['invoice']) || !empty($_REQUEST['asset_series']) || !empty($_REQUEST['asset'])){
			
			if(!empty($_REQUEST['fromDate'])){
				$startTime = strtotime($_REQUEST['fromDate']);
			}
			if(!empty($_REQUEST['toDate'])){
				$endTime = strtotime($_REQUEST['toDate']);
			}
		}else{
			$this->response(array("message" => "parameter missing","message_code"=>'404'));
		}	
		//print_r($where);die;
		$this->load->model('search_model');
		$resultData = $this->search_model->fetch_search_data($where);
		
		if(count($resultData)  > 0){
			if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id']) && ($_REQUEST['group_id'] == 9)){
				$result = array();
				foreach($resultData as $kay => $value){
					if($value['inspected_by'] == $_REQUEST['user_id']){
						$result[$kay] = $value;
					}	
				}
			}else{
				$result = $resultData;
			}
			
			if(!empty($result) && is_array($result)){
				$temp = array();
				$c = 0;
				foreach ($result as $kay => $value) {
					$temp[$c]['asset_series'] = $value['asset_series'];
					$temp[$c]['asset'] = $value['asset_name'];
					$temp[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
					$temp[$c]['report_no'] = $value['report_no'];
					$temp[$c]['site_id'] = $value['site_id'];
					$temp[$c]['job_card'] = $value['job_card'];
					$temp[$c]['sms'] = $value['sms'];
					$temp[$c]['client_name'] = $value['client_name'];
					$temp[$c]['status'] = $value['approved_status'];
					//$temp[$c]['time'] = date("d-m-Y",strtotime($value['created_date']));
					$temp[$c]['time'] = strtotime($value['created_date']);
					$c++;
				}
				//print_r($temp);die('123');
				if(is_array($temp)){
					$c1 = 0;
					foreach($temp as $key => $value){
						if(!empty($startTime) && !empty($endTime) && ($value['time'] >= $startTime) && ($value['time'] <= $endTime)){
							$data[$c1] = $value;
							$data[$c1]['time'] = date("d-m-Y",$value['time']);
						}elseif(!empty($startTime) && empty($endTime) && ($value['time'] >= $startTime)){
							$data[$c1] = $value;
							$data[$c1]['time'] = date("d-m-Y",$value['time']);
						}elseif(empty($startTime) && empty($endTime)){
							$data[$c1] = $value;
							$data[$c1]['time'] = date("d-m-Y",$value['time']);
						}
						$c1++;
					}
					
					if(count($data) > 0){
						if(is_array($data)){
							//$searchData = array();
							$c1= 0;
							foreach($data as $k =>$v){
								$searchData[$c1] = $v;
								$c1++;
							}
						}
						$this->response($searchData,200);
					}else{
						$this->response(array("message" => "No Data","message_code"=>'404'));
					}
				}else{
					$this->response(array("message" => "No Data","message_code"=>'404'));
				}
			}else{
				$this->response(array("message" => "No Data","message_code"=>'404'));
			}	
		}else{
			$this->response(array("message" => "No Data","message_code"=>'404'));
		}	
	}
	
	 function update_factory_post(){
		if(!empty($_POST) && !empty($_POST['user_id'])){
			$param = array();
			$param['manual_client_user_id'] = $_POST['user_id'];
			$param['manual_client_name'] = (($_POST['client_name'] == null) || ($_POST['client_name'] == NULL))?'':$_POST['client_name'];
			$param['manual_client_address'] = (($_POST['client_address'] == null) || ($_POST['client_address'] == NULL))?'':$_POST['client_address'];
			$param['manual_serial_number'] = (($_POST['serial_no_data'] == null) || ($_POST['serial_no_data'] == NULL))?'':$_POST['serial_no_data'];
			$param['manual_batch_code'] = (($_POST['batch_code'] == null) || ($_POST['batch_code'] == NULL))?'':$_POST['batch_code'];
			$param['manual_invoice_number'] = (($_POST['invoice_number'] == null) || ($_POST['invoice_number'] == NULL))?'':$_POST['invoice_number'];
			$param['manual_assets_series'] = (($_POST['asset_series'] == null) || ($_POST['asset_series'] == NULL))?'':$_POST['asset_series'];
		   
			$this->load->model('api_model');
			$result = $this->api_model->update_factory_data($param);
			if($result > 0){
				$this->response("Data insert successfully",200);
			}else{
				$this->response("Server Error",500);	
			}
			 
		}else{
			$this->response("Parameter Error",200);
		}
		
	}
	
	//URL: http://karam.in/kare_demo/api_controller/update_dealer
	function update_dealer_post(){
		if(!empty($_POST['user_id'])){
			$param = array();
			$param['dealer_user_id'] = $_POST['user_id'];
			$param['dealer_name'] = $_POST['dealer_name'];
			$param['dealer_address'] = $_POST['dealer_address'];
			$param['dealer_contactNo'] = $_POST['dealer_contactNo'];
			$param['dealer_email'] = $_POST['dealer_email'];
			$param['dealer_input_data'] = $_POST['dealer_input_data'];
			
			$this->load->model('api_model');
			$result = $this->api_model->update_dealer_data($param);
			if($result > 0){
				$this->response("Data insert successfully",200);
			}else{
				$this->response("Server Error",500);	
			}
			 
		}else{
			$this->response("Parameter Error",200);
		}
	}
	
	
	  public function update_profile_post() {
        if (isset($_POST) && !empty($_REQUEST['user_id'])) {
			$user_id = trim($_POST['user_id']); 
			if(!empty($_POST['upro_company'])){
				$data['upro_company'] = $_POST['upro_company'];
		    }	
			if(!empty($_POST['upro_company_address'])){
				$data['upro_company_address'] = $_POST['upro_company_address'];
		    }			
			if(!empty($_POST['first_name'])){
				$data['upro_first_name'] = $_POST['first_name'];
		    }	
			if(!empty($_POST['last_name'])){
				$data['upro_last_name'] =  $_POST['last_name'];
		    }	
			if(!empty($_POST['mobile_no'])){
				$data['upro_phone'] = $_POST['mobile_no'];
		    }	
			if(!empty($_POST['landline'])){
				$data['upro_landline'] =  $_POST['landline'];
		    }	
			if(!empty($_POST['emp_region'])){
				$data['emp_region'] = $_POST['emp_region'];
		    }	
            if(!empty($_POST['emp_id'])){
				$data['emp_id'] =  $_POST['emp_id'];
		    }
			if(!empty($_POST['address'])){
				 $data['upro_address'] = $_POST['address'];
		    }
            if(!empty($_POST['dob'])){
				 $data['upro_dob'] = $_POST['dob'];
		    }

            if(!empty($_POST['country_id'])){
				 $data['upro_country_id'] = $_POST['country_id'];
		    }
            if(!empty($_POST['state_id'])){
				 $data['upro_zone_id'] = $_POST['state_id'];
		    }
            if(!empty($_POST['city_id'])){
				 $data['upro_city_id'] = $_POST['city_id'];
		    }
			
			$this->load->model("api_model");
			$check_profile_id = $this->api_model->hasprofile($_REQUEST['user_id']);
			
			if(!empty($_POST['mob_imei']) && !empty($check_profile_id['upro_mob_imei'])){
				//$data['upro_mob_imei'] = $check_profile_id['upro_mob_imei'];
				$data['upro_mob_new_imei'] = $_POST['mob_imei'];
			}else if(!empty($_POST['mob_imei']) && empty($check_profile_id['upro_mob_imei'])){
				$data['upro_mob_imei'] = $_POST['mob_imei'];
				//$data['upro_mob_new_imei'] = '';
			}
			
            foreach ($data as $key => $value) {
                $data[$key] = trim($value);
            }
			
	        /*if( isset($_FILES['user_image']) ){  
                $config['upload_path']          = './uploads/images/profile/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 1024*2;
                $this->load->library('upload',$config);
                
                if (! $this->upload->do_upload('user_image')){
                  $error = array('error' => $this->upload->display_errors());
                }else{
                    //print_r($imgdata);
                    $imginfo= $this->upload->data();
                    $img_path="/uploads/images/profile/".$imginfo['file_name'];
                    $data['upro_image']=$img_path;
                }
                
            }*/
			if(!empty($_REQUEST['from']) && ($_REQUEST['from'] == 'ios')){
				if(isset($_REQUEST['user_img'])){
						$image_name = base64_decode($_REQUEST['user_img']) . 'jpg';
					    $fileName = uniqid() . '.jpg';
					     $file = './uploads/images/users/'.$fileName;
					
					    $success 	=  file_put_contents($file,  $image_name);
						//print_r($ext);print '<br/>';
						if($success){
							//$data['upro_image'] =	"/uploads/images/users/".$fileName;
							$data['upro_image'] =	$fileName;
						}else{
							$data['upro_image'] = '';
						}
				}
			}else{
				if(isset($_FILES)){
					if($_FILES['user_image'] != ''){	
						$random_code=md5(uniqid(rand()));
						$image_path= $random_code.$_FILES ["user_image"] ["name"]; 
						$dir = FCPATH."/uploads/images/users/";
						move_uploaded_file($_FILES ["user_image"] ["tmp_name"], $dir.$image_path);
						$path= $image_path;
						$data['upro_image'] = $path;

					}
				}
			}
			
			//print_r($data);die("123");
            // update the profile info 
            if (count($data) > 0) {
				if($check_profile_id['upro_uacc_fk'] == $_REQUEST['user_id']){
					$this->db->select("upro_mob_imei");
					$this->db->where('upro_uacc_fk', $_REQUEST['user_id']);
					$query123 = $this->db->get('demo_user_profiles');
					$old_imei = $query123->result_array();
					if(empty($old_imei['0']['upro_mob_imei'])) {
						$data['upro_mob_new_imei'] = $data['upro_mob_imei'];
						//print_r($data);die();
					}
					$rs_pro = $this->api_model->update_profile($_REQUEST['user_id'],$data);
					if ($rs_pro){
						$res_array = array("update_profile"=>1,"msg" => "Information updated successfully");
						$this->response($res_array, 200);
					} else {
						$this->response(array("update_profile"=>0,"msg" => "No information updated"), 200);
					}
                }else{
                  $this->response(array("update_profile"=>0,"msg" => "No profile found "), 200);
                }
            }
        }
    }
	
	
	public function user_profile_get() {
		if(!empty($_REQUEST['user_id'])){
			$this->load->model('api_model');
			$user_profile = $this->api_model->user_info($_REQUEST['user_id']);
			$profile=array();
			if(!empty($user_profile)){
				foreach($user_profile as $key => $value){
				if((strpos($key,"upro_") !== false) || (strpos($key,"emp_") !== false)){
					$profile[$key]=$value;
				}
			}
			//print_r($user_profile);die("123");
			$response['user_id']=$user_profile['uacc_id'];
			$response['active']=$user_profile['uacc_active'];
			$response['email']=$user_profile['uacc_email'];
			$response['profile']=$profile;
            $this->load->model('kare_model');
			if( $response['profile']['upro_country_id'] > 0 ){				 
					$filtArray  = array('id'=>$response['profile']['upro_country_id']);
					$country_list = $this->kare_model->get_manage_country_filt_result_list($filtArray);
					if($country_list){
						$country_name  = $country_list[0]['name'];
						$response['profile']['upro_country_id'] = $country_name;
					}
			}
			if( $response['profile']['upro_zone_id'] > 0 ){
					
					$filtArray  = array('id'=>$response['profile']['upro_zone_id']);
					$zone_list = $this->kare_model->get_manage_zone_filt_result_list($filtArray);
					if($zone_list){
						$state_name  = $zone_list[0]['name'];
						$response['profile']['upro_zone_id'] = $state_name;
					}
			}
			if( $response['profile']['upro_city_id'] > 0 ){
					$filtArray  = array('city_id'=>$response['profile']['upro_city_id']);
					$city_list = $this->kare_model->get_manage_city_filt_result_list($filtArray);
					if($city_list){
						
						$city_name  = $city_list[0]['city_name'];
						$response['profile']['upro_city_id'] = $city_name;
					}
			}
			$response['profile']["upro_image"] = !empty($profile['upro_image'])?'http://'.$_SERVER['SERVER_NAME'].'/kare/uploads/images/users/'.$profile['upro_image']:'';
			
			$response['msg']="infomation get successfully";
			$this->response($response,200);  
			}else{
				
				$this->response(array("msg"=>"NO Data found","user_profile"=>0),200);
			} 
		}

	}
	
	
	function register_karam_post() {
		if(!empty($_POST['email'])  && !empty($_POST['password']) && !empty($_POST['imei_no'])){
			  $this->load->model('Api_model');
			  $userInfo =  $this->Api_model->register_account();
				$userInfo = json_decode($userInfo,true);
			if($userInfo['register'] > 0){
				$data= array("register"=>1,"email"=>$userInfo['email'],"error"=>$userInfo['error'],'profile'=>$userInfo['profile'],'message'=>$userInfo['message']);  
				$this->response($data, 200); 
			}else{
				$data= array("register"=>0,"email"=>$userInfo['email'],"error"=>$userInfo['error'],'profile'=>$userInfo['profile'],'message'=>$userInfo['message']);  
				$this->response($data, 200); 
			}	
		}else{
			$error[] = array("message"=>"pls. complete fill input box.");
			$this->response($error,200);
		}
	}
	
	  // manual reset password 
	function forgotten_password_get() {
		if (isset($_GET['email'])) {
		   // $this->load->model('flexi_auth_model');
			$this->load->library('flexi_auth');
			$email = $_GET['email'];
			// The 'forgotten_password()' function will verify the users identity exists and automatically send a 'Forgotten Password' email.
			$response = $this->flexi_auth->forgotten_password($email);
			
			// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
			$message = strip_tags($this->flexi_auth->get_messages());
			$this->response(array("message" => $message), 200);
		}
	}
	
	/* http://192.168.1.3/Mysites/karam/kare/api_controller/authenticate?email=varun@flashonmind.com&password=password123 */
	
	function authenticate_inspector_get(){
		$email = $_GET['email'];
		$password = $_GET['password'];
		//$imei_no = $_GET['imei_no'];
		
		$this->load->model('Api_model');
		$user = $this->Api_model->verify_user_login($email,$password);
		if($user){
			
			// Inspector Login
			if($user['userGroupID']=='9'){
					// When A user login it will check the date of today's date with the date stored in today_date table
					// If date is different then truncate temp tables and update todats date in table today_date
					// This function is used so that when a user logged in for first time today 
					// then we should empty the temp tables.
					$this->load->model('Form_model');
					$this->Form_model->truncate_tables();
			}
			$this->response($user, 200); /* 200 being the HTTP response code */
		}else{
			$this->response(NULL, 404);
		}
		
	}
	
	function authenticate_new_get(){
		$email = $_GET['email'];
		$password = $_GET['password'];
		$imei_no = $_GET['imei_no'];
		
		$this->load->model('Api_model');
		// if($email == 'anuj.tyagi@flashonmind.com'){
			// $user = $this->Api_model->verify_user_login($email,$password);
		// }else{	
			// $user = $this->Api_model->verify_user_login_new($email,$password,$imei_no);
		// }	
			$user = $this->Api_model->verify_user_login($email,$password);
		if($user){
			
			// Inspector Login
			if($user['userGroupID']=='9'){
					// When A user login it will check the date of today's date with the date stored in today_date table
					// If date is different then truncate temp tables and update todats date in table today_date
					// This function is used so that when a user logged in for first time today 
					// then we should empty the temp tables.
					$this->load->model('Form_model');
					$this->Form_model->truncate_tables();
			}
			$this->response($user, 200); /* 200 being the HTTP response code */
		}else{
			$this->response(NULL, 404);
		}
		
	}

	function authenticate_get(){
	     $email = $_GET['email'];
             $password = $_GET['password'];
		
		$this->load->model('Api_model');
                $user = $this->Api_model->verify_user_login($email, $password);
                
        if($user){
            
                 $token=$this->get('token');
                 $token= ($token)?$token:'';
                 $device_type=$this->get('device_type'); 
                 $this->load->model('asm_model'); 
                 $this->asm_model->update_table('user_accounts',array('uacc_device_token'=>$token,'uacc_device_type'=>$device_type), array('uacc_id'=>$user['userID']));
			// Inspector Login
			if($user['userGroupID']=='9'){
				// When A user login it will check the date of today's date with the date stored in today_date table
				// If date is different then truncate temp tables and update todats date in table today_date
				// This function is used so that when a user logged in for first time today 
				// then we should empty the temp tables.
				$this->load->model('Form_model');
				$this->Form_model->truncate_tables();
			}
			
			
			
			$this->response($user, 200); /* 200 being the HTTP response code */
        }
        else{
		   $this->response(NULL, 404);
        }
    }
/* http://karam.in/kare/api_controller/products */
/* http://karam.in/kare/api_controller/products/id */
/* http://karam.in/kare/api_controller/components */
/* http://karam.in/kare/api_controller/components/id or code */

// http://192.168.1.3/Mysites/karam/kare/api_controller/authenticate?email=varun@flashonmind.com&password=password123	

/* http://192.168.1.3/Mysites/karam/kare/api_controller/subassets */
/* http://192.168.1.3/Mysites/karam/kare/api_controller/subassets/id */

/* http://192.168.1.3/Mysites/karam/kare/api_controller/products */
/* http://192.168.1.3/Mysites/karam/kare/api_controller/products/id */
/* http://192.168.1.3/Mysites/karam/kare/api_controller/components */
/* http://192.168.1.3/Mysites/karam/kare/api_controller/components/id or code */
	
	function subassets_get($id=NULL){
		$this->load->model('Subassets_model');
		if($id==NULL){
			$sub_assets=$this->Subassets_model->get_sub_assets_list();
		}else{
			$sub_assets[]=$this->Subassets_model->get_sub_assets($id);
		}
		
		$this->load->model('kare_model');
		if(!empty($sub_assets[0])){
			foreach($sub_assets as $key=>$val){
				$request_id = $val['sub_assets_id'];
				$request_type = 'subAsset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				$sub_assets[$key]['error']='0';
				
				/* For Inspection */
				if($val['sub_assets_inspection']!=''){
					$sub_assets_inspection=$this->Subassets_model->get_inspection_value($val['sub_assets_inspection']);
					if($sub_assets_inspection){
						$sub_assets[$key]['sub_assets_inspection_id'] = $val['sub_assets_inspection'];
						$sub_assets[$key]['sub_assets_inspection'] = $sub_assets_inspection[$val['sub_assets_inspection']];
					}
				}
				
				if($val['sub_assets_result']!=''){
					$result_arrays = json_decode($val['sub_assets_result']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = '0'; 
						}
					}
					//$sub_assets[$key]['sub_assets_result'] = implode('#',$result_array);
					$sub_assets[$key]['sub_assets_result'] = implode('##',$result_array);
				}
				
				if($val['sub_assets_observation']!=''){
					$obs_arrays = json_decode($val['sub_assets_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						if($resultObs){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = '0'; 
						}
					}
					$sub_assets[$key]['sub_assets_observation'] = implode('##',$obs_array);
				}
				
				if(is_array($featuredData)){
					$sub_assets[$key]['pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$sub_assets[$key]['fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$sub_assets[$key]['repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$sub_assets[$key]['pass_imagepath'] = '0';
					$sub_assets[$key]['fail_imagepath'] = '0';
					$sub_assets[$key]['repair_imagepath'] = '0';
				}
			}
		}
		if(!empty($sub_assets[0])){
			$this->response($sub_assets, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Subasset Data Found");
			$this->response($error,200);
        }
	}

	function subassets_old_get($id=NULL){
		$this->load->model('Subassets_model');
		if($id==NULL){
			$sub_assets=$this->Subassets_model->get_sub_assets_list();
		}else{
			$sub_assets[]=$this->Subassets_model->get_sub_assets($id);
		}
		
		if(!empty($sub_assets[0])){
			foreach($sub_assets as $key=>$val){
				$this->load->model('kare_model');
				$request_id = $val['sub_assets_id'];
				$request_type = 'subAsset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				$sub_assets[$key]['error']='0';
				
				/* For Inspection */
				if($val['sub_assets_inspection']!=''){
					$sub_assets_inspection=$this->Subassets_model->get_inspection_value($val['sub_assets_inspection']);
					if($sub_assets_inspection){
						$sub_assets[$key]['sub_assets_inspection_id'] = $val['sub_assets_inspection'];
						$sub_assets[$key]['sub_assets_inspection'] = $sub_assets_inspection[$val['sub_assets_inspection']];
					}
				}
				
				if($val['sub_assets_result']!=''){
					$result_arrays = json_decode($val['sub_assets_result']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = '0'; 
						}
					}
					$sub_assets[$key]['sub_assets_result'] = implode('#',$result_array);
				}
				
				if($val['sub_assets_observation']!=''){
					$obs_arrays = json_decode($val['sub_assets_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						if($resultObs){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = '0'; 
						}
					}
					$sub_assets[$key]['sub_assets_observation'] = implode('##',$obs_array);
				}
				
				if(is_array($featuredData)){
					$sub_assets[$key]['pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$sub_assets[$key]['fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$sub_assets[$key]['repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$sub_assets[$key]['pass_imagepath'] = '0';
					$sub_assets[$key]['fail_imagepath'] = '0';
					$sub_assets[$key]['repair_imagepath'] = '0';
				}
			}
		}
		if(!empty($sub_assets[0])){
			$this->response($sub_assets, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Subasset Data Found");
			$this->response($error,200);
        }
	}
	
	
	function subassets_new_get($id=NULL){
		$this->load->model('Subassets_model');
		if($id==NULL){
			$sub_assets=$this->Subassets_model->get_sub_assets_list();
		}else{
			$sub_assets[]=$this->Subassets_model->get_sub_assets($id);
		}
		
		$this->load->model('kare_model');
		if(!empty($sub_assets[0])){
			foreach($sub_assets as $key=>$val){
				$request_id = $val['sub_assets_id'];
				$request_type = 'subAsset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				$sub_assets[$key]['error']='0';
				
				/* For Inspection */
				if($val['sub_assets_inspection']!=''){
					$sub_assets_inspection=$this->Subassets_model->get_inspection_value($val['sub_assets_inspection']);
					if($sub_assets_inspection){
						$sub_assets[$key]['sub_assets_inspection_id'] = $val['sub_assets_inspection'];
						$sub_assets[$key]['sub_assets_inspection'] = $sub_assets_inspection[$val['sub_assets_inspection']];
					}
				}
				
				if($val['sub_assets_result']!=''){
					$result_arrays = json_decode($val['sub_assets_result']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = '0'; 
						}
					}
					$sub_assets[$key]['sub_assets_result'] = implode('#',$result_array);
				}
				
				if($val['sub_assets_observation']!=''){
					$obs_arrays = json_decode($val['sub_assets_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						if($resultObs){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = '0'; 
						}
					}
					$sub_assets[$key]['sub_assets_observation'] = implode('##',$obs_array);
				}
				
				if(is_array($featuredData)){
					$sub_assets[$key]['pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$sub_assets[$key]['fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$sub_assets[$key]['repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$sub_assets[$key]['pass_imagepath'] = '0';
					$sub_assets[$key]['fail_imagepath'] = '0';
					$sub_assets[$key]['repair_imagepath'] = '0';
				}
			}
		}
		if(!empty($sub_assets[0])){
			$this->response($sub_assets, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Subasset Data Found");
			$this->response($error,200);
        }
	}
	
	function subassetImage($sub_assets_code){
		$this->load->model('kare_model');
		if(!empty($sub_assets_code)){
			$image =$this->kare_model->get_sub_assets_image($sub_assets_code);
			return base_url().$image;
		}else{
			return -1;
		}
	}
	
	/*
	*	FUNCTION : components_get
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/components
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/components/id
	* 	URL: http://karam.in/kare_demo/api_controller/components
	*	URL: http://karam.in/kare/api_controller/components
	*/
	
	function components_new_get($component_id=NULL){
		$this->load->model('kare_model');
		if($component_id==NULL){
			$components=$this->kare_model->get_components_list();
		}else{
			$components[]=$this->kare_model->get_component($component_id);
		}
		//print_r($components);die('123');
		if(!empty($components[0])){
			foreach($components as $key=>$val){
				if($val['component_sub_assets'] != "" && $val['component_sub_assets'] != "0" && $val['component_sub_assets'] != '[""]'){
					$sub_component_array =	json_decode($val['component_sub_assets'],true);
					$newDatas = array();
					foreach($sub_component_array as $cKey=>$qVal){
						$newDatas[] = $qVal.'#'.$this->subassetImage($qVal);
					}
					$sub_components_array = implode('##',$newDatas);
					$components[$key]['component_sub_assets'] = !empty($sub_components_array)?$sub_components_array:'';
				}else{
					$components[$key]['component_sub_assets'] = '0';
				}
				$components[$key]['component_imagepath'] = !empty($val['component_imagepath'])?str_replace('FCPATH',base_url(),$val['component_imagepath']):'';
				$components[$key]['component_description'] =  !empty($val['component_description'])?trim($val['component_description']):'';
				$components[$key]['error'] = '0';
				
				$components[$key]['component_fimages'] = !empty($val['component_fimages'])?$val['component_fimages']:'0';
				
				$request_id = $val['component_id'];
				$request_type = 'asset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				if(is_array($featuredData)){
					$components[$key]['component_pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$components[$key]['component_fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$components[$key]['component_repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$components[$key]['component_pass_imagepath'] = '0';
					$components[$key]['component_fail_imagepath'] = '0';
					$components[$key]['component_repair_imagepath'] = '0';
				}
					
				$this->load->model('Subassets_model');
					/* For Inspection */
				if($val['component_inspectiontype']!=''){
					$component_inspectiontype=$this->Subassets_model->get_inspection_value($val['component_inspectiontype']);
					if($component_inspectiontype){
						$components[$key]['component_inspectiontype_id'] = !empty($val['component_inspectiontype'])?$val['component_inspectiontype']:'';
						$components[$key]['component_inspectiontype'] = !empty($val['component_inspectiontype'])?$component_inspectiontype[$val['component_inspectiontype']]:'';
					}
				}
			
				if($val['component_expectedresult']!=''){
					$result_arrays = json_decode($val['component_expectedresult']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = ''; 
						}
					}
					$linksArray = array_filter($result_array);
					//print_r($result_array);print_r($linksArray);die;
					$components[$key]['component_expectedresult'] = implode('##',$linksArray);
				}
				
				if($val['component_observation']!=''){
					$obs_arrays = json_decode($val['component_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						
						if(!empty($resultObs)){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = ''; 
						}
					}
					
					$components[$key]['component_observation'] = implode('##',array_filter($obs_array));
				}
			}
		}
		
		if(!empty($components[0])){
			$this->response($components, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Asset Data Found");
			$this->response($error,200);
		   //$this->response(NULL, 404);
        }
	}
	
	function components_get($component_id=NULL){
		$this->load->model('kare_model');
		if($component_id==NULL){
			$components=$this->kare_model->get_components_list();
		}else{
			$components[]=$this->kare_model->get_component($component_id);
		}
		//print_r($components);die('123');
		if(!empty($components[0])){
			foreach($components as $key=>$val){
				if($val['component_sub_assets'] != "" && $val['component_sub_assets'] != "0"){
					$sub_component_array =	json_decode($val['component_sub_assets'],true);
					$newDatas = array();
					foreach($sub_component_array as $cKey=>$qVal){
						$newDatas[] = $qVal;
					}
					$sub_components_array = implode('##',$newDatas);
					$components[$key]['component_sub_assets'] = !empty($sub_components_array)?$sub_components_array:'';
				}else{
					$components[$key]['component_sub_assets'] = '0';
				}
				$components[$key]['component_imagepath'] = !empty($val['component_imagepath'])?str_replace('FCPATH',base_url(),$val['component_imagepath']):'';
				$components[$key]['component_description'] =  !empty($val['component_description'])?trim($val['component_description']):'';
				$components[$key]['error'] = '0';
				
				$components[$key]['component_fimages'] = !empty($val['component_fimages'])?$val['component_fimages']:'0';
				
				$request_id = $val['component_id'];
				$request_type = 'asset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				if(is_array($featuredData)){
					$components[$key]['component_pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$components[$key]['component_fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$components[$key]['component_repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$components[$key]['component_pass_imagepath'] = '0';
					$components[$key]['component_fail_imagepath'] = '0';
					$components[$key]['component_repair_imagepath'] = '0';
				}
					
				$this->load->model('Subassets_model');
					/* For Inspection */
				if($val['component_inspectiontype']!=''){
					$component_inspectiontype=$this->Subassets_model->get_inspection_value($val['component_inspectiontype']);
					if($component_inspectiontype){
						$components[$key]['component_inspectiontype_id'] = !empty($val['component_inspectiontype'])?$val['component_inspectiontype']:'';
						$components[$key]['component_inspectiontype'] = !empty($val['component_inspectiontype'])?$component_inspectiontype[$val['component_inspectiontype']]:'';
					}
				}
			
				if($val['component_expectedresult']!=''){
					$result_arrays = json_decode($val['component_expectedresult']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = ''; 
						}
					}
					$linksArray = array_filter($result_array);
					//print_r($result_array);print_r($linksArray);die;
					$components[$key]['component_expectedresult'] = implode('##',$linksArray);
				}
				
				if($val['component_observation']!=''){
					$obs_arrays = json_decode($val['component_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						
						if(!empty($resultObs)){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = ''; 
						}
					}
					
					$components[$key]['component_observation'] = implode('##',array_filter($obs_array));
				}
			}
		}
		
		if(!empty($components[0])){
			$this->response($components, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Asset Data Found");
			$this->response($error,200);
		   //$this->response(NULL, 404);
        }
	}
	
	
	function components_old_get($component_id=NULL){
		$this->load->model('kare_model');
		if($component_id==NULL){
			$components=$this->kare_model->get_components_list();
		}else{
			$components[]=$this->kare_model->get_component($component_id);
		}

		if(!empty($components[0])){
			foreach($components as $key=>$val){
				if($val['component_sub_assets'] != "" && $val['component_sub_assets'] != "0"){
					$sub_component_array =	json_decode($val['component_sub_assets'],true);
					$newDatas = array();
					foreach($sub_component_array as $cKey=>$qVal){
						$newDatas[] = $qVal;
					}
					$sub_components_array = implode('##',$newDatas);
					$components[$key]['component_sub_assets'] = $sub_components_array;
				}else{
					$components[$key]['component_sub_assets'] = '0';
				}
				$components[$key]['component_imagepath'] = str_replace('FCPATH',base_url(),$val['component_imagepath']);
				$components[$key]['component_description'] = trim($val['component_description']);
				$components[$key]['error'] = '0';
				
				$request_id = $val['component_id'];
				$request_type = 'asset';
				$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
				if(is_array($featuredData)){
					$components[$key]['component_pass_imagepath'] = base_url().'uploads/images/featured/pass/'.$featuredData['pass_image'];
					$components[$key]['component_fail_imagepath'] = base_url().'uploads/images/featured/fail/'.$featuredData['fail_image'];
					$components[$key]['component_repair_imagepath'] = base_url().'uploads/images/featured/repair/'.$featuredData['repair_image'];
				}else{
					$components[$key]['component_pass_imagepath'] = '0';
					$components[$key]['component_fail_imagepath'] = '0';
					$components[$key]['component_repair_imagepath'] = '0';
				}
					
				$this->load->model('Subassets_model');
					/* For Inspection */
				if($val['component_inspectiontype']!=''){
					$component_inspectiontype=$this->Subassets_model->get_inspection_value($val['component_inspectiontype']);
					if($component_inspectiontype){
						$components[$key]['component_inspectiontype_id'] = $val['component_inspectiontype'];
						$components[$key]['component_inspectiontype'] = $component_inspectiontype[$val['component_inspectiontype']];
					}
				}
			
				if($val['component_expectedresult']!=''){
					$result_arrays = json_decode($val['component_expectedresult']);
					$result_array = array();
					foreach($result_arrays as $resKey=>$resVal){
						$result	=	$this->Subassets_model->get_inspection_value($resVal);
						if($result){
							$result_array[$resKey] = $result[$resVal];
						}else{
							$result_array[$resKey] = '0'; 
						}
					}
					$components[$key]['component_expectedresult'] = implode('##',$result_array);
				}
				
				if($val['component_observation']!=''){
					$obs_arrays = json_decode($val['component_observation']);
					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						if($resultObs){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resId.'#'.$resValue; 
						}else{
							$obs_array[$obsKey] = '0'; 
						}
					}
					$components[$key]['component_observation'] = implode('##',$obs_array);
				}
			}
		}
		
		if(!empty($components[0])){
			$this->response($components, 200); /* 200 being the HTTP response code */
        }
        else{
			$error[] = array("error"=>"No Asset Data Found");
			$this->response($error,200);
		   //$this->response(NULL, 404);
        }
	}
	

	public function products_old_get($product_id=NULL){
		$this->load->model('kare_model');
		
		if($product_id!=NULL){
		   $products[]=$this->kare_model->get_product($product_id);
		}else{
		  $products=$this->kare_model->get_products_list();
		}
		if(!empty($products[0])){
			foreach($products as $key=>$val){
				$products[$key]['product_imagepath'] = ($products[$key]['product_imagepath']!='')? str_replace('FCPATH',base_url(),$val['product_imagepath']) :'';
				if($val['product_components'] != ""){
					$component_array =	json_decode($val['product_components'],true);
				
					$newData = array();
					foreach($component_array as $cKey=>$qVal){
						$newData[] = $qVal;
					}
					$component_array = implode('##',$newData);
					$products[$key]['product_components'] = $component_array;
				}else{
					$products[$key]['product_components'] = $val['product_components'];
				}
				$products[$key]['product_description'] = trim($val['product_description']);
			
				$this->load->model('Subassets_model');
					/* For Inspection */
				if($val['product_inspectiontype']!=''){
					$product_inspectiontype=$this->Subassets_model->get_inspection_value($val['product_inspectiontype']);
					if($product_inspectiontype){
						$products[$key]['product_inspectiontype_id'] = $val['product_inspectiontype'];
						$products[$key]['product_inspectiontype'] = $product_inspectiontype[$val['product_inspectiontype']];
					}
				}
			}
		}
		if($products){	
			$this->response($products,200);
		}else{
			$error[] = array("error"=>"No Asset Series Data Found");
			$this->response($error,200);
        }
	}
	
	
	public function products_get($product_id=NULL){
		$this->load->model('kare_model');
		
		if($product_id!=NULL){
		   $products[]=$this->kare_model->get_product($product_id);
		}else{
		  $products=$this->kare_model->get_products_list();
		}
		
		
		if(!empty($products[0])){
			foreach($products as $key=>$val){
				$products[$key]['product_imagepath'] = ($products[$key]['product_imagepath']!='')? str_replace('FCPATH',base_url(),$val['product_imagepath']) :'';
				if($val['product_components'] != ""){
					$component_array =	json_decode($val['product_components'],true);
				
					$newData = array();
					foreach($component_array as $cKey=>$qVal){
						$newData[] = $qVal;
					}
					$component_array = implode('##',$newData);
					$products[$key]['product_components'] = $component_array;
				}else{
					$products[$key]['product_components'] = $val['product_components'];
				}
				$products[$key]['product_description'] = trim($val['product_description']);
			
				$this->load->model('Subassets_model');
					/* For Inspection */
				if($val['product_inspectiontype']!=''){
					$product_inspectiontype=$this->Subassets_model->get_inspection_value($val['product_inspectiontype']);
					if($product_inspectiontype){
						$products[$key]['product_inspectiontype_id'] = $val['product_inspectiontype'];
						$products[$key]['product_inspectiontype'] = $product_inspectiontype[$val['product_inspectiontype']];
					}
				}
			}
		}
		if($products){	
			$this->response($products,200);
		}else{
			$error[] = array("error"=>"No Asset Series Data Found");
			$this->response($error,200);
        }
	}

	/********ip address*********/
	function _idAddress($ip){
		//$ip = $_SERVER['REMOTE_ADDR'];     
        if($ip){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $ip;
        }
	}
	/*****************/
			
	/*/////old http://192.168.1.3/Mysites/karam/kare/api_controller/mdata/13///////// */
	/*// http://www.karam.in/kare/api_controller/mdata/247 */
	/* http://192.168.1.3/Mysites/karam/kare/api_controller/mdata?userID=5&userGroupID=9&mdata_id=2 */
	/* http://192.168.1.3/Mysites/karam/kare/api_controller/mdata?userID=5&userGroupID=2 */
	
	function mdata_get(){   // mdata_asset        mdata_item_series
		$mdata_id 		= (isset($_REQUEST['mdata_id']))? $_REQUEST['mdata_id'] : '';
		$userID 		= $_REQUEST['userID'];
		$userGroupID 	= $_REQUEST['userGroupID'];
		
		$this->load->model('kare_model');
		if($mdata_id==''){
			$mdata=$this->kare_model->get_mdata_list();	 
		}else{
			$mdata[]=$this->kare_model->get_mdata_item($mdata_id);
		}
		$mdata_item_seriesFlag	= 0;
		$mdata_assetFlag		= 0;
		// print_r($mdata);die();
		if($mdata && !empty($mdata[0])){
			foreach($mdata as $key=>$val){    
			/* Client & Delaler */
				$this->load->model('Client_model');
				$client = $this->Client_model->get_clientDealer_name($val['mdata_client']);
				if($client){
					$mdata[$key]['mdata_client_id'] = 	(($client['client_id'] == null) || ($client['client_id'] == NULL))?'':$client['client_id'];
					$mdata[$key]['mdata_client'] = (($client['client_name'] == null) || ($client['client_name'] == NULL))?'':$client['client_name'];
				}
				$dealer = $this->Client_model->get_clientDealer_name($val['mdata_dealer']);
				if($dealer){
					$mdata[$key]['mdata_dealer_id'] = 	(($dealer['client_id'] == null) || ($dealer['client_id'] == NULL))?'':$dealer['client_id'];
					$mdata[$key]['mdata_dealer'] = (($dealer['client_name'] == null) || ($dealer['client_name'] == NULL))?'':$dealer['client_name'];
				}
			/* Images */
				$asset_image = '0'; $item_arrays ='0';
				if($val['mdata_item_series'] != null ){
					$counter = 0;
					$item_array = json_decode($val['mdata_item_series'],true);
					
					$count = count($item_array);
					if($count > 1){
						foreach($item_array as $itemVal){
							if($counter == 0){
								$asset_image_return = $this->get_assetSeries_image($itemVal);
								if($asset_image_return != 0){
									$asset_images = $asset_image_return;
									$asset_image = str_replace('FCPATH',base_url(),$asset_images);
									$counter++;
								}else{
									$asset_image = '0';
								}
							}
						}
						 $item_arrays1 = implode('##',$item_array);
						 $mdata[$key]['mdata_item_series']=$item_arrays1;
						$assetCodes	  = '';
					}else{
						$assetCodes   = $this->get_asset_from_assetSeriesCode($item_array[0],$val['mdata_jobcard'],$val['mdata_sms']);
						$asset_images = $this->get_assetSeries_image($item_array[0]);
						$asset_image  = str_replace('FCPATH',base_url(),$asset_images);
						$item_arrays1 = $item_array[0];
						$mdata[$key]['mdata_item_series']=$item_arrays1;
					}
				}
				/*Component Part*/
				if($val['mdata_asset'] != null ){
					$counter = 0;
					$item_array = json_decode($val['mdata_asset'],true);
					$count=0;
					
					foreach($item_array as $itemVal){
						$component_data[$count] = $this->get_component_from_componentCode($itemVal);
						$asset_images = $this->get_component_image($itemVal);
						$asset_image = str_replace('FCPATH',base_url(),$asset_images);
						$count++;
					}
					$countAssetCodes = 0;
					foreach ($component_data as $valueAssetCodes) {
						if($countAssetCodes == 0) {
							$assetCodeData = $valueAssetCodes['component_array'];
						} else {
							$assetCodeData = $assetCodeData.'####'.$valueAssetCodes['component_array'];
						}
						$assetCodes['product_geo_fancing'] = $valueAssetCodes['product_geo_fancing'];
					    $assetCodes['product_work_permit'] = $valueAssetCodes['product_work_permit'];
						$countAssetCodes++;
					} 
					
					  // print_r($component_data);die();
					$item_arrays1 = implode('##',$item_array);
					$assetCodes['component_array'] = $assetCodeData;
						if(empty($val['mdata_item_series'])){
						    $mdata[$key]['mdata_item_series']=$item_arrays1;
				     	}
				}
			
				$mdata[$key]['assetCodes'] = ( ($assetCodes['component_array'] == null) ||  ($assetCodes['component_array'] == NULL))?'0': $assetCodes['component_array'];
				$mdata[$key]['product_geo_fancing'] = ($assetCodes['product_geo_fancing'] == null)?'':$assetCodes['product_geo_fancing'];
				$mdata[$key]['product_work_permit'] = ($assetCodes['product_work_permit'] == null)?'':$assetCodes['product_work_permit'];

                //  Start to three dates 
				
				if( !empty($val['mdata_asset'] )){  
				   $mdata_assetArray			= !empty($val['mdata_asset'])?json_decode($val['mdata_asset'], true): NULL;
				   if( isset($mdata_assetArray) && ( is_array($mdata_assetArray))){						
						$component_frequency_asset_first = $mdata_assetArray[0];
						$this->load->model('kare_model'); 
						$productComp = $this->kare_model->get_component($component_frequency_asset_first);  
						$frequency_month = (int)$productComp['component_frequency_asset'];
						if( $frequency_month >= 0 ){							
							$val['frequency_month'] = $frequency_month;
						} 
				   }
				}else if( !empty($val['mdata_item_series'] )){ 
				   $mdata_item_seriesArray			= !empty($val['mdata_item_series'])?json_decode($val['mdata_item_series'], true): NULL;
				   if( isset($mdata_item_seriesArray) && ( is_array($mdata_item_seriesArray))){
						$product_frequency_asset_first = $mdata_item_seriesArray[0];					  
						$this->load->model('kare_model');
						$productComp = $this->kare_model->get_product($product_frequency_asset_first);  
						$frequency_month = (int)$productComp['product_frequency_asset'];
						if( $frequency_month >=0  ){							
							$val['frequency_month'] = $frequency_month;
						}											   
				   }
				}

				$threeDateArray  = array(
					'frequency_month'				=> $val['frequency_month'],
					'mdata_material_invoice_date'	=> $mdata[$key]['mdata_material_invoice_date'], 
					'date_of_first_use'				=> $mdata[$key]['date_of_first_use'], 
					'date_of_inspection'			=> $mdata[$key]['date_of_inspection'], 
					'inspection_due_date'			=> $mdata[$key]['inspection_due_date']
				);
				$this->load->model('api_model');
				$threeDaysArry = $this->api_model->frequency_month_mdata_dates($threeDateArray);  
				$mdata[$key]['mdata_material_invoice_date']	= ( $threeDaysArry['mdata_material_invoice_date'] !='' )?  $threeDaysArry['mdata_material_invoice_date'] : '' ;
				$mdata[$key]['date_of_first_use']			= ( $threeDaysArry['date_of_first_use'] !='' )?  $threeDaysArry['date_of_first_use'] : '' ;
				$mdata[$key]['date_of_inspection']			= ( $threeDaysArry['date_of_inspection'] !='' )?  $threeDaysArry['date_of_inspection'] : '' ;
				$mdata[$key]['inspection_due_date']			= ( $threeDaysArry['inspection_due_date'] !='' )?  $threeDaysArry['inspection_due_date'] : '' ;
				//  End to three dates 


				//mdata_asset
				
			}



		}else{
			$mdata[] = array('error' =>"No Data Is Present In Master Data Inspection Form");
		}

		
		$this->response($mdata,200);
	}


	public function mdata_update_post() {
		if(empty($_POST['date_of_first_use']) && empty($_POST['date_of_inspection']) && empty($_POST['inspection_due_date']) && empty($_POST['mdata_id'])) {
			$response = array(
				'msg_code' 	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else {
			if(!empty($_POST['date_of_first_use'])) {
				$data['date_of_first_use'] = date("Y-m-d", strtotime($_POST['date_of_first_use']));
			}
			if(!empty($_POST['date_of_inspection'])) {
				$data['date_of_inspection'] = date("Y-m-d", strtotime($_POST['date_of_inspection']));
			}
			if(!empty($_POST['inspection_due_date'])) {
				$data['inspection_due_date'] = date("Y-m-d", strtotime($_POST['inspection_due_date']));
			}
			$this->db->where('mdata_id', $_POST['mdata_id']);
			$update = $this->db->update('master_data', $data);
			if($update) {
				$response = array(
					'msg_code' 	=> 200,
					'msg'		=> 'Dates updated successfully'
				);
			} else {
				$response = array(
					'msg_code' 	=> 404,
					'msg'		=> 'Dates updation failed'
				);
			}
		}
		$this->response(array('msg_code'=>$response['msg_code'],'msg'=>$response['msg']));
	}
	
		
	
	function get_asset_from_assetSeriesCode($assetId,$jobCard,$sms){
		
		$this->load->model('kare_model');
		$asset=$this->kare_model->get_asset_from_assetSeriesCode($assetId);
		//print_r($asset);die("123");
		if($asset){
			$param = array();
			if($asset['product_components'] != ""){
				$newData = array();
				$component_array =	json_decode($asset['product_components'],true);
					
				foreach($component_array as $qKey=>$qVal){
					$this->load->model('Form_model');
					$get_asset_uom 	= $this->Form_model->get_asset_values($qVal);
					$asset_quantity = $this->kare_model->get_asset_quantity($qVal,$assetId,$jobCard,$sms);
					$asset_image = (isset($get_asset_uom['image']) && $get_asset_uom['image']!='' && $get_asset_uom['image'] !='0' && $get_asset_uom['image']!='null')?  str_replace('FCPATH',base_url(),$get_asset_uom['image']) : 'NO';
					if(is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#'.round($asset_quantity['item_quantity']).'##'.$get_asset_uom['uom'].'###'.$asset_image;
						//$newData[$qKey] = $qVal.'#'.$asset_quantity['item_quantity'].'##'.$get_asset_uom['uom'].'###'.$asset_image;
					}else if(!is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#NO##'.$get_asset_uom['uom'].'###'.$asset_image;
					}else if(is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#'.round($asset_quantity['item_quantity']).'##NOO'.'###'.$asset_image;
					}else if(!is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#NO##NOO'.'###'.$asset_image;
					}
				}
				$param['component_array'] = implode('####',$newData);
				$param['product_geo_fancing'] = $asset['product_geo_fancing'];
				$param['product_work_permit'] = $asset['product_work_permit'];
				
				return $param;
				//return $component_array = implode('####',$newData);
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	
	function get_assetSeries_image($assetSeries){
		$this->load->model('kare_model');
		$resultImage = $this->kare_model->get_assetSeries_image($assetSeries);
		if($resultImage){
			return $resultImage->product_imagepath;
		}else{
			return '0';
		}
	}

	function get_component_from_componentCode($component_code){
		
		$this->load->model('kare_model');
		$asset=$this->kare_model->get_component_from_componentCode($component_code);
		//print_r($asset);die("123");
		if($asset){
			$param = array();
				$newData = array();
				//$component_array =	json_decode($asset['product_components'],true);
					
				//foreach($component_array as $qKey=>$qVal){
					$this->load->model('Form_model');
					$get_asset_uom 	= $this->Form_model->get_asset_values($component_code);
					$asset_quantity = 1;
					$asset_image = (isset($get_asset_uom['image']) && $get_asset_uom['image']!='' && $get_asset_uom['image'] !='0' && $get_asset_uom['image']!='null')?  str_replace('FCPATH',base_url(),$get_asset_uom['image']) : 'NO';
					if(is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[0] = $component_code.'#'.round($asset_quantity['item_quantity']).'##'.$get_asset_uom['uom'].'###'.$asset_image;
						}else if(!is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[0] = $component_code.'#1##'.$get_asset_uom['uom'].'###'.$asset_image;
					}else if(is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[0] = $component_code.'#'.round($asset_quantity['item_quantity']).'##NOO'.'###'.$asset_image;
					}else if(!is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[0] = $component_code.'#1##NOO'.'###'.$asset_image;
					}
				//}
				
				$param['component_array'] = $newData[0];
				$param['product_geo_fancing'] = $asset['component_geo_fancing'];
				$param['product_work_permit'] = $asset['component_work_permit'];
				return $param;
				//return $component_array = implode('####',$newData);
		}else{
			return '0';
		}
	}

	function get_component_image($assetSeries){
		$this->load->model('kare_model');
		$resultImage = $this->kare_model->get_component_image($assetSeries);
		if($resultImage){
			return $resultImage->product_imagepath;
		}else{
			return '0';
		}
	}
	
	function mdata_old_get(){
		$mdata_id 		= (isset($_REQUEST['mdata_id']))? $_REQUEST['mdata_id'] : '';
		$userID 		= $_REQUEST['userID'];
		$userGroupID 	= $_REQUEST['userGroupID'];
		
		$this->load->model('kare_model');
		if($mdata_id==''){
			$mdata=$this->kare_model->get_mdata_list();	 
		}else{
			$mdata[]=$this->kare_model->get_mdata_item($mdata_id);
		}

		if($mdata && !empty($mdata[0])){
			foreach($mdata as $key=>$val){
			/* Client & Delaler */
				$this->load->model('Client_model');
				$client = $this->Client_model->get_clientDealer_name($val['mdata_client']);
				if($client){
					$mdata[$key]['mdata_client_id'] = 	$client['client_id'];
					$mdata[$key]['mdata_client'] = $client['client_name'];
				}
				$dealer = $this->Client_model->get_clientDealer_name($val['mdata_dealer']);
				if($dealer){
					$mdata[$key]['mdata_dealer_id'] = 	$dealer['client_id'];
					$mdata[$key]['mdata_dealer'] = $dealer['client_name'];
				}
			/* Images */
				$asset_image = '0'; $item_arrays ='0';
				if($val['mdata_item_series'] != null && $val['mdata_item_series'] != 'null' && $val['mdata_item_series'] != ''){
					$counter = 0;
					$item_array = json_decode($val['mdata_item_series'],true);
					$count = count($item_array);
					if($count > 1){
						foreach($item_array as $itemVal){
							if($counter == 0){
								$asset_image_return = $this->get_assetSeries_image($itemVal);
								if($asset_image_return != 0){
									$asset_images = $asset_image_return;
									$$asset_image = str_replace('FCPATH',base_url(),$asset_images);
									$counter++;
								}else{
									$asset_image = '0';
								}
							}
						}
						$item_arrays = implode('##',$item_array);
						$assetCodes = '';
					}else{
						$assetCodes = $this->get_asset_from_assetSeriesCode($item_array[0],$val['mdata_jobcard'],$val['mdata_sms']);
						
						$asset_images = $this->get_assetSeries_image($item_array[0]);
						$asset_image = str_replace('FCPATH',base_url(),$asset_images);
						$item_arrays = $item_array[0];
					}
				}
				$mdata[$key]['assetCodes'] = $assetCodes;
				$mdata[$key]['mdata_item_series'] = $item_arrays;
				$mdata[$key]['mdata_item_series_image'] = $asset_image;
			}
		}else{
			$mdata[] = array('error' =>"No Data Is Present In Master Data Inspection Form");
		}
		$this->response($mdata,200);
	}
	
	/*function get_asset_from_assetSeriesCode($assetId,$jobCard,$sms){
		
		$this->load->model('kare_model');
		$asset=$this->kare_model->get_asset_from_assetSeriesCode($assetId);
		
		if($asset){
			if($asset['product_components'] != ""){
				$newData = array();
				$component_array =	json_decode($asset['product_components'],true);
					
				foreach($component_array as $qKey=>$qVal){
					$this->load->model('Form_model');
					$get_asset_uom 	= $this->Form_model->get_asset_values($qVal);
					$asset_quantity = $this->kare_model->get_asset_quantity($qVal,$assetId,$jobCard,$sms);
					$asset_image = (isset($get_asset_uom['image']) && $get_asset_uom['image']!='' && $get_asset_uom['image'] !='0' && $get_asset_uom['image']!='null')?  str_replace('FCPATH',base_url(),$get_asset_uom['image']) : 'NO';
					if(is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#'.$asset_quantity['item_quantity'].'##'.$get_asset_uom['uom'].'###'.$asset_image;
					}else if(!is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#NO##'.$get_asset_uom['uom'].'###'.$asset_image;
					}else if(is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#'.$asset_quantity['item_quantity'].'##NOO'.'###'.$asset_image;
					}else if(!is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal.'#NO##NOO'.'###'.$asset_image;
					}
				}
				return $component_array = implode('####',$newData);
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	
	function get_assetSeries_image($assetSeries){
		//print_r($assetSeries);die("123");
		$this->load->model('kare_model');
		$resultImage = $this->kare_model->get_assetSeries_image($assetSeries);
		if($resultImage){
			return $resultImage->product_imagepath;
		}else{
			return '0';
		}
	}*/
	
	/*
	*	FUNCTION : actionProposed_get
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/actionProposed?actionProposed=19
	* 	URL: http://karam.in/kare_demo/api_controller/actionProposed?actionProposed=19
	*	URL: http://karam.in/kare/api_controller/actionProposed?actionProposed=19
	*/
	function actionProposed_get(){
		
		$actionProposed = $_REQUEST['actionProposed'];
		$this->load->model('Form_model');
		$result = $this->Form_model->get_action_proposed($actionProposed,$type='api');
		if($result){
			$result = array('success'=>$result);
			$results = (object)$result;
		}else{
			$result = array('success'=>'No Action Proposed Data Found');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	function actionProposed_old_get(){
		/* http://karam.in/kare_demo/api_controller/actionProposed?actionProposed=19 */
		/* http://192.168.1.3/Mysites/karam/kare/api_controller/actionProposed?actionProposed=19 */
		
		$actionProposed = $_REQUEST['actionProposed'];
		$this->load->model('Form_model');
		$result = $this->Form_model->get_action_proposed($actionProposed,$type='api');
		if($result){
			$result = array('success'=>$result);
			$results = (object)$result;
		}else{
			$result = array('success'=>'No Action Proposed Data Found');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	
	
	/* http://192.168.1.3/Mysites/karam/kare/api_controller/siteid?userID=13&userGroupID=9&site_id=3 */
	/* http://192.168.1.3/Mysites/karam/kare/api_controller/siteid?userID=13&userGroupID=9 */
	/* http://karam.in/kare/api_controller/siteid?userID=13&userGroupID=9 */
	
	function siteid_old_get(){
		
		$site_id 		= (isset($_REQUEST['site_id']))? $_REQUEST['site_id'] : '';
		$userID 		= $_REQUEST['userID'];
		$userGroupID 	= $_REQUEST['userGroupID'];
		
		$this->load->model('Form_model');
		$checkResult = $this->Form_model->check_data_by_user_in_tmp_table($userID, 'api');
		
		$this->load->model('Siteid_model');
		if($userGroupID == 9){  /* Inspector Site Id in database is 9 */
			if($site_id==''){
				$siteData = $this->Siteid_model->get_siteid_list_of_inspector('',$userGroupID,$userID);
				if(is_array($siteData)){
					$sdata = $siteData;
				}else{
					$error = $siteData;
				}
			}else{
				$sitedata=$this->Siteid_model->get_siteid_item($site_id);
				if($sitedata){
					$sdata[] = $sitedata;
				}else{
					$error = '0002';
				}
			}
		}else{
			if($site_id==''){
				$siteData = $this->Siteid_model->get_siteid_list();
				if(is_array($siteData)){
					$sdata = $siteData;
				}else{
					$error = $siteData;
				}
			}else{
				$sitedata[]=$this->Siteid_model->get_siteid_item($site_id);
				if($sitedata){
					$sdata[] = $sitedata;
				}else{
					$error = '0002';
				}
			}
		}
		
		if(!isset($error)){
			/* Get total count of components, sub-somponents and action proposed for offline api */
				$this->load->model('Api_model');
				$totalComponents 		= $this->Api_model->get_totalCount('components');
				$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
				$totalActionProposed 	= $this->getAllActionProposed();
				$countAction 			= count($totalActionProposed);
			/* End */
			
			foreach($sdata as $sKey=>$sVal){
				/* Get Client ID */
				$client_res = $this->Siteid_model->get_clientName_siteID(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
				$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
				$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
				$sdata[$sKey]['totalAction_proposed']	=	"$countAction";
				if(is_object($client_res)){
					$clientid = $client_res->mdata_client;
					$clientName = $client_res->client_name;
					$sdata[$sKey]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
				}

				/* Get Report Number from inspection_list_1 table */
				/*
				$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
				if(!is_array($reportNo)){
					$sdata[$sKey]['report_no'] = '';
					$sdata[$sKey]['inspected_status'] = 'No';
					$sdata[$sKey]['approved_status'] = 'Pending';
				}else{
					$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
					$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
					$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
				}
				*/

				/// Start care fully
				if( !empty($sVal['siteID_id'])){
					$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
					if(!is_array($reportNo)){
						$sdata[$sKey]['report_no']			= '';
						$sdata[$sKey]['inspected_status']	= 'No';
						$sdata[$sKey]['approved_status']	= 'Pending';
					}else{
						$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
						$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
						$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
					}					
				}else if( !empty($sVal['mdata_item_series'])){	
					    $mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
						foreach($mdata_item_seriesArry AS $key1 => $val1){
							$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
							if(!is_array($reportasset_seriesno)){
								$sdata[$sKey]['report_no']			= '';
								$sdata[$sKey]['inspected_status']	= 'No';
								$sdata[$sKey]['approved_status']	= 'Pending';
							}else{
								$sdata[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
								$sdata[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
								$sdata[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
							}
						
						}
				}else{
					$sdata[$sKey]['report_no']			= '';
					$sdata[$sKey]['inspected_status']	= 'No';
					$sdata[$sKey]['approved_status']	= 'Pending';				
				}
				////  End care fully 

				/* End of Report Number from inspection_list_1 table */
				/* Check for any work Permit Number, If exist then increment the last work permit no. */
					$work_number = '';
					$work_no = $this->Form_model->check_work_permit_number($sVal['siteID_id']);
					if(!$work_no){
						$work_number = 'WORK-000001';
					}else{
						$workNo_array 	= explode('-',$work_no['workPermit_number']);
						$newWorkNo		= $workNo_array[1] + 1;
						/* To Get the string the specific format */
						$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
					}
					$sdata[$sKey]['workPermit_number'] 	= trim($work_number);
				/* End of Work Permit Number */
			}
			
		}else{
			if($error=='0001'){
				// ERROR 0001 : "No Site ID is assign to you";
				$err = "No Site ID is assign to you";
			}else if($error=='0002'){
				// ERROR 0002 : "Site IDs assigned to the Inspector are not present in siteID_data table.";
				$err = "Error code: 0002 ! Please contact Admin";
			}
			$sdata = array(
						'0'=>array(
								'error' =>$err
							));
		}
		$this->response($sdata,200);	
		
	}
	
	function searchSiteidBarcode_get(){
		$temp = array();
		$temp['mdata_uin'] = !empty($_REQUEST['uin'])?$_REQUEST['uin']:'';
		$temp['mdata_barcode'] = !empty($_REQUEST['barcode'])?$_REQUEST['barcode']:'';
		$temp['mdata_rfid'] = !empty($_REQUEST['rfid'])?$_REQUEST['rfid']:'';		
		$this->load->model('m_api_model');
		$this->load->model('api_model');
		$siteData = $this->m_api_model->get_siteid_inspector($temp);
		 // print_r($siteData);die;	  inspected_status
		if(!empty($siteData[0]['master_id']) && is_array($siteData)){			
			$this->load->model('Api_model');
			$totalComponents 		= $this->Api_model->get_totalCount('components');
			$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
			$totalActionProposed 	= $this->_getAllActionProposed();
			$countAction 			= count($totalActionProposed);
			$this->load->model('Siteid_model');
			$this->load->model('Form_model');
			$sdata = $siteData;			
			
			foreach($sdata as $sKey=>$sVal){      
				$client_res = $this->api_model->get_data('client_name', 'client_id', $sdata[$sKey]['mdata_client'], 'clients');
			    $sdata[$sKey]['client_name']			=$client_res[0]['client_name'];				
				$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
				$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
				$sdata[$sKey]['totalAction_proposed']	=	"$countAction";
				
				if( !empty($sVal['siteID_id'])){
					$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
					if(!is_array($reportNo)){
						$sdata[$sKey]['report_no']			= '';
						$sdata[$sKey]['inspected_status']	= 'No';
						$sdata[$sKey]['approved_status']	= 'Pending';
					}else{
						$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
						$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
						$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
					}					
				}else if( !empty($sVal['mdata_item_series'])){	
					    $mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
						foreach($mdata_item_seriesArry AS $key1 => $val1){
							$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
							if(!is_array($reportasset_seriesno)){
								$sdata[$sKey]['report_no']			= '';
								$sdata[$sKey]['inspected_status']	= 'No';
								$sdata[$sKey]['approved_status']	= 'Pending';
							}else{
								$sdata[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
								$sdata[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
								$sdata[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
							}
						
						}
				}else{
					$sdata[$sKey]['report_no']			= '';
					$sdata[$sKey]['inspected_status']	= 'No';
					$sdata[$sKey]['approved_status']	= 'Pending';				
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
					$sdata[$sKey]['workPermit_number'] 	= trim($work_number);				
			}			
		}else{			
			$sdata = array('0'=>array('error' =>"No data found !"));
		} 
		$this->response($sdata,200);
	}
	
	function siteid_get(){
		if(!empty($_REQUEST['userID']) && !empty($_REQUEST['userGroupID']) && ($_REQUEST['userGroupID'] == 8)){
			$temp = $this->clientID($_REQUEST['userID'],$_REQUEST['userGroupID']);
			$this->response($temp,200);
		}else{
		$site_id 		= (isset($_REQUEST['site_id']))? $_REQUEST['site_id'] : '';
		$userID 		= $_REQUEST['userID'];
		$userGroupID 	= $_REQUEST['userGroupID'];
		
		$this->load->model('Form_model');
		$checkResult = $this->Form_model->check_data_by_user_in_tmp_table($userID, 'api');
		
		
		$this->load->model('Siteid_model');
		if($userGroupID == 9){  /* Inspector Site Id in database is 9 */
			if($site_id==''){
				$siteData = $this->Siteid_model->get_siteid_list_of_inspector('',$userGroupID,$userID);
				if(is_array($siteData)){
					$sdata = $siteData;
				}else{
					$error = $siteData;
				}
			}else{
				$sitedata=$this->Siteid_model->get_siteid_item($site_id);
				if($sitedata){
					$sdata[] = $sitedata;
				}else{
					$error = '0002';
				}
			}
		}else{
			if($site_id==''){
				$siteData = $this->Siteid_model->get_siteid_list();
				if(is_array($siteData)){
					$sdata = $siteData;
				}else{
					$error = $siteData;
				}
			}else{
				$sitedata[]=$this->Siteid_model->get_siteid_item($site_id);
				if($sitedata){
					$sdata[] = $sitedata;
				}else{
					$error = '0002';
				}
			}
		}
		
		if(!isset($error)){
			/* Get total count of components, sub-somponents and action proposed for offline api */
				$this->load->model('Api_model');
				$totalComponents 		= $this->Api_model->get_totalCount('components');
				$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
				$totalActionProposed 	= $this->_getAllActionProposed();
				$countAction 			= count($totalActionProposed);
			/* End */
			foreach($sdata as $sKey=>$sVal){
				/* Get Client ID */
				$client_res = $this->Siteid_model->get_clientName_siteID(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
				//print_r($client_res);die();
				$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
				$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
				$sdata[$sKey]['totalAction_proposed']	=	"$countAction";
				if(is_object($client_res)){
					$clientid = $client_res->mdata_client;
					$clientName = $client_res->client_name;
					$sdata[$sKey]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
				}
				/* Get Report Number from inspection_list_1 table */
				/*
				$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
				if(!is_array($reportNo)){
					$sdata[$sKey]['report_no'] = '';
					$sdata[$sKey]['inspected_status'] = 'No';
					$sdata[$sKey]['approved_status'] = 'Pending';
				}else{
					$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
					$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
					$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
				}
				*/

				/// Start care fully
				if( !empty($sVal['siteID_id'])){
					$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
					if(!is_array($reportNo)){
						$sdata[$sKey]['report_no']			= '';
						$sdata[$sKey]['inspected_status']	= 'No';
						$sdata[$sKey]['approved_status']	= 'Pending';
					}else{
						$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
						$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
						$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
					}					
				}else if( !empty($sVal['mdata_item_series'])){	
					    $mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
						foreach($mdata_item_seriesArry AS $key1 => $val1){
							$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
							if(!is_array($reportasset_seriesno)){
								$sdata[$sKey]['report_no']			= '';
								$sdata[$sKey]['inspected_status']	= 'No';
								$sdata[$sKey]['approved_status']	= 'Pending';
							}else{
								$sdata[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
								$sdata[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
								$sdata[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
							}
						
						}
				}else{
					$sdata[$sKey]['report_no']			= '';
					$sdata[$sKey]['inspected_status']	= 'No';
					$sdata[$sKey]['approved_status']	= 'Pending';				
				}
				////  End care fully 

				/* End of Report Number from inspection_list_1 table */
				/* Check for any work Permit Number, If exist then increment the last work permit no. */
					$work_number = '';
					$work_no = $this->Form_model->check_work_permit_number($sVal['siteID_id']);
					if(!$work_no){
						$work_number = 'WORK-000001';
					}else{
						$workNo_array 	= explode('-',$work_no['workPermit_number']);
						$newWorkNo		= $workNo_array[1] + 1;
						/* To Get the string the specific format */
						$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
					}
					$sdata[$sKey]['workPermit_number'] 	= trim($work_number);
				/* End of Work Permit Number */
			}
			
		}else{
				if($error=='0001'){
					// ERROR 0001 : "No Site ID is assign to you";
					$err = "No Site ID is assign to you";
				}else if($error=='0002'){
					// ERROR 0002 : "Site IDs assigned to the Inspector are not present in siteID_data table.";
					$err = "Error code: 0002 ! Please contact Admin";
				}
				$sdata = array(
							'0'=>array(
									'error' =>$err
								));
			}
			$this->response($sdata,200);	
        }    
		
	}
	
	function _masterDataClient($siteid){
		$masterdata = array();
		if(!empty($siteid)){
			$inspector_data = $this->Assign_client_model->inspecterDataClient($siteid);
			/*print_r($inspector_data);die;
			foreach($inspector_data as $k => $v){
				if(($siteid == $v['siteid'])){
					$masterdata = $v['inspector_address'];
				}
			}*/
			if(!empty($inspector_data)){
				$masterdata = $inspector_data;
			}else{
				$masterdata = '';
			}	
		}
		return $masterdata;
	}
	
    function _masterData($siteid){
		$masterdata = array();
		if(!empty($siteid)){
			$inspector_data = $this->Assign_client_model->inspecterData();
			foreach($inspector_data as $k => $v){
				if(($siteid == $v['siteid'])){
					$masterdata = $v['inspector_address'];
				}
			}
		}
		return $masterdata;
	}
      
	
	  
		
        // function clientID_get(){
        // http://192.168.1.3/Mysites/karam/kare/api_controller/clientID?userID=160&userGroupID=8
            // if(!empty($_REQUEST['userID']) && !empty($_REQUEST['userGroupID']) && ($_REQUEST['userGroupID'] == 8)){
    function clientID($userID,$groupID){
            if(!empty($userID) && ($groupID == 8)){
				
                $data = array();
                $userID = $userID;
               
                $this->load->model('Siteid_model');
                $this->load->model('Form_model');
                
                $this->load->model('Assign_client_model');
                $client_data = $this->Assign_client_model->get_client_data();
				//print_r($client_data);die('123');
                $client_user_name = array();
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
				//print_r($siteid_marge);die('1234');
                $siteid_marge = explode('/',implode('/',array_unique($siteid_marge)));
				//print_r($siteid_marge);die('12345');
                if(!empty($siteid_marge)){
                    $sdata = $this->Assign_client_model->get_site_data($siteid_marge);
                    
                    $temp = array();
                    if(!empty($sdata) && is_array($sdata)){
                        $this->load->model('Api_model');
                        $totalComponents 		= $this->Api_model->get_totalCount('components');
                        $totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
                        $totalActionProposed 	= $this->_getAllActionProposed();
                        $countAction 			= count($totalActionProposed);
                        /* End */
                        $inspector_address = '';
                        foreach($sdata as $sKey=>$sVal){
                                /* Get Client ID */
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

								$inspector_address = $this->_masterDataClient($sVal['siteID_id']);
                                if(!empty($inspector_address)){
                                    $temp[$sKey]['inspector_details'] =  $inspector_address;
                                }else{
                                    $temp[$sKey]['inspector_details'] = '';
                                }

								$client_res = $this->Siteid_model->get_clientName_siteID(trim($sVal['site_jobcard']),trim($sVal['site_sms']));
								$temp[$sKey]['totalAsset'] 			= 	$totalComponents;
                                $temp[$sKey]['totalSubAsset']			=	$totalsubComponent;
                                $temp[$sKey]['totalAction_proposed']	=	$countAction;
                                if(is_object($client_res)){
                                        $clientid = $client_res->mdata_client;
                                        $clientName = $client_res->client_name;
                                        $temp[$sKey]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
                                }



                                /*
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
								*/

								/// Start care fully
								if( !empty($sVal['siteID_id'])){
									$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
									if(!is_array($reportNo)){
										$temp[$sKey]['report_no']			= '';
										$temp[$sKey]['inspected_status']	= 'No';
										$temp[$sKey]['approved_status']	= 'Pending';
									}else{
										$temp[$sKey]['report_no'] 			= $reportNo['report_no'];
										$temp[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
										$temp[$sKey]['approved_status'] 	= $reportNo['approved_status'];
									}					
								}else if( !empty($sVal['mdata_item_series'])){	
										$mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
										foreach($mdata_item_seriesArry AS $key1 => $val1){
											$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
											if(!is_array($reportasset_seriesno)){
												$temp[$sKey]['report_no']			= '';
												$temp[$sKey]['inspected_status']	= 'No';
												$temp[$sKey]['approved_status']	= 'Pending';
											}else{
												$temp[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
												$temp[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
												$temp[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
											}
										
										}
								}else{
									$temp[$sKey]['report_no']			= '';
									$temp[$sKey]['inspected_status']	= 'No';
									$temp[$sKey]['approved_status']	= 'Pending';				
								}
								////  End care fully 

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
						//print_r($temp);die;
                    }else{
                        $temp = array(
                            '0'=>array(
                                'error' =>'No Client ID is assign to you'
                            ));
                        }
                }else{
                    $temp = array(
                    '0'=>array(
                        'error' =>'No Site ID is assign to you'
                    ));
                }        
            }else{
                 $err = "paramet missing";
                $temp = array(
                    '0'=>array(
                    'error' =>$err
                 ));
            }
           // print_r($temp);die("123");
            return $temp;
	}
	
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
	
	/* To get work permit form details and insert the values in db.
		http://192.168.1.3/Mysites/karam/kare/api_controller/workPermit
		http://karam.in/kare_demo/api_controller/workPermit
	*/
	
	function workPermit_post(){
		/*if(!empty($_REQUEST['from']) && ($_REQUEST['from'] == 'ios')){
			if(isset($_REQUEST['inspector_image'])){
					$image_name = base64_decode($_REQUEST['inspector_image']) . 'jpg';
					$fileName = uniqid() . '.jpg';
					 $file = './uploads/inspected_img/'.$fileName;
				
					$success 	=  file_put_contents($file,  $image_name);
					//print_r($ext);print '<br/>';
					if($success){
						$ins_image =	"/uploads/inspected_img/".$fileName;
					}else{
						$ins_image = '';
					}
			}
		}else{*/
		//print_r($_FILES['inspector_image']);die;
			if(isset($_FILES)){
				if(isset($_FILES['inspector_image'])){   
					//$random_code=md5(uniqid(rand()));
					//$image_path= $random_code.$_FILES ["inspector_image"]["name"];
					$image_path= uniqid() . '.jpg';
					$dir = FCPATH."./uploads/inspected_img/";
					if(move_uploaded_file($_FILES ["inspector_image"]["tmp_name"], $dir.$image_path)) {
						$path= "/uploads/inspected_img".'/'.$image_path;
						$ins_image = $path;
					}else{
						$ins_image = '';
					}	
				}else{
					$ins_image = '';
				}
			}
		//}	
			
		
		$this->load->model('Form_model');
		$dbdata=array(
				'wpermit_id'	=>'',
				'siteID_id'		=>$_REQUEST['siteID_name'],
				'job_card'		=>'',
				'sms'			=>'', 
				'created_date'	=>$_REQUEST['today_date'],
				'site_id'		=>'',
				'asset_series'	=>$_REQUEST['workPermit_asset_series'],
				'lattitude'		=>'', 
				'longitude'		=>'',
				'inspected_by'	=>'', 
				'approved_by'	=>'',
				'input_method'	=>'',
				'input_value'	=>'',
				'report_no'		=>'',
				'inspector_signature_image'	=>  '',
				'client_name'				=>	'',
				'client_designation'		=>	'',
				'client_signature_image'	=>	'',
				'client_remark'				=>	''
			);
			
		$return_temp1_id = $this->Form_model->first_slot_data_insert($dbdata);
		
		if($return_temp1_id){
			$data	= array(
					'client_name'					=>	!empty($_REQUEST['client_name'])?$_REQUEST['client_name']:'',
					'ins_image'						=>	$ins_image,
					'today_date'					=>	!empty($_REQUEST['today_date'])?$_REQUEST['today_date']:'',
					'workPermit_number'				=>	!empty($_REQUEST['workPermit_number'])?$_REQUEST['workPermit_number']:'',
					'siteId_name'					=>	$return_temp1_id,
					'permitDate_from'				=>	!empty($_REQUEST['permitDate_from'])?$_REQUEST['permitDate_from']:'',
					'permitValid_upto'				=>	!empty($_REQUEST['permitValid_upto'])?$_REQUEST['permitValid_upto']:'',
					'siteID_address'				=>	!empty($_REQUEST['siteID_address'])?$_REQUEST['siteID_address']:'',
					'gprs_lattitude'				=>	!empty($_REQUEST['workPermit_lattitude'])?$_REQUEST['workPermit_lattitude']:'',
					'gprs_longitude'				=>	!empty($_REQUEST['workPermit_longitude'])?$_REQUEST['workPermit_longitude']:'',
					'asset_series'					=>	!empty($_REQUEST['workPermit_asset_series'])?$_REQUEST['workPermit_asset_series']:'',
					'batch_no'						=>	!empty($_REQUEST['workPermit_batch_no'])?$_REQUEST['workPermit_batch_no']:'',
					'serial_no'						=>	!empty($_REQUEST['workPermit_serial_no'])?$_REQUEST['workPermit_serial_no']:'',
					'harness'						=>	!empty($_REQUEST['harness'])?$_REQUEST['harness']:'',
					'helmet'						=>	!empty($_REQUEST['helmet'])?$_REQUEST['helmet']:'',
					'shoes'							=>	!empty($_REQUEST['shoes'])?$_REQUEST['shoes']:'',
					'gloves'						=>	!empty($_REQUEST['gloves'])?$_REQUEST['gloves']:'',
					'goggle'						=>	!empty($_REQUEST['goggle'])?$_REQUEST['goggle']:'',
					'work_position'					=>	!empty($_REQUEST['work_position'])?$_REQUEST['work_position']:'',
					'equipment_use'					=>	!empty($_REQUEST['equipment_use'])?$_REQUEST['equipment_use']:'',
					'worker_height'					=>	!empty($_REQUEST['worker_height'])?$_REQUEST['worker_height']:'',
					'weather_good'					=>	!empty($_REQUEST['weather_good'])?$_REQUEST['weather_good']:'',
					'unguarded_edges'				=>	!empty($_REQUEST['unguarded_edges'])?$_REQUEST['unguarded_edges']:'',
					'equipment_calibrated'			=>	!empty($_REQUEST['equipment_calibrated'])?$_REQUEST['equipment_calibrated']:'',
					'physically_fitness'			=>	!empty($_REQUEST['physically_fitness'])?$_REQUEST['physically_fitness']:'',
					'alcohol_influence'				=>	!empty($_REQUEST['alcohol_influence'])?$_REQUEST['alcohol_influence']:'',
					'mediclaim_insured'				=>	!empty($_REQUEST['mediclaim_insured'])?$_REQUEST['mediclaim_insured']:'',
					'client_approval'				=>	!empty($_REQUEST['client_approval'])?$_REQUEST['client_approval']:'',
					'documentation_with_client'		=>	!empty($_REQUEST['documentation_with_client'])?$_REQUEST['documentation_with_client']:'',
			);
		//print_r($data);die();
		/*if($return_temp1_id){
			$data	= array(
					'client_name'					=>	$_REQUEST['client_name'],
					'ins_image'						=>	$ins_image,
					'today_date'					=>	$_REQUEST['today_date'],
					'workPermit_number'				=>	$_REQUEST['workPermit_number'],
					'siteId_name'					=>	$return_temp1_id,
					'permitDate_from'				=>	$_REQUEST['permitDate_from'],
					'permitValid_upto'				=>	$_REQUEST['permitValid_upto'],
					'siteID_address'				=>	$_REQUEST['siteID_address'],
					'gprs_lattitude'				=>	$_REQUEST['workPermit_lattitude'],
					'gprs_longitude'				=>	$_REQUEST['workPermit_longitude'],
					'asset_series'					=>	$_REQUEST['workPermit_asset_series'],
					'batch_no'						=>	$_REQUEST['workPermit_batch_no'],
					'serial_no'						=>	$_REQUEST['workPermit_serial_no'],
					'harness'						=>	$_REQUEST['harness'],
					'helmet'						=>	$_REQUEST['helmet'],
					'shoes'							=>	$_REQUEST['shoes'],
					'gloves'						=>	$_REQUEST['gloves'],
					'goggle'						=>	$_REQUEST['goggle'],
					'work_position'					=>	$_REQUEST['work_position'],
					'equipment_use'					=>	$_REQUEST['equipment_use'],
					'worker_height'					=>	$_REQUEST['worker_height'],
					'weather_good'					=>	$_REQUEST['weather_good'],
					'unguarded_edges'				=>	$_REQUEST['unguarded_edges'],
					'equipment_calibrated'			=>	$_REQUEST['equipment_calibrated'],
					'physically_fitness'			=>	$_REQUEST['physically_fitness'],
					'alcohol_influence'				=>	$_REQUEST['alcohol_influence'],
					'mediclaim_insured'				=>	$_REQUEST['mediclaim_insured'],
					'client_approval'				=>	$_REQUEST['client_approval'],
					'documentation_with_client'		=>	$_REQUEST['documentation_with_client']
			);*/
		
		//print_r($data);die("1233");
			$result = $this->Form_model->insert_work_permit($data);
			
			if($result && !isset($error_img)){
				$result = array('workPermitID'=>$result);
				$results = (object)$result;
			}else{
				if(isset($error_img)){
					// Error 00016, 0017 : Error in Inspector Image in Work Permit Data in function insert_work_permit.
					$result = array('success'=>$error_img.' ! Please contact Admin');
					$results = (object)$result;
				}else{
					// Error 0003 : Error in Inserting Work Permit Data in function insert_work_permit.
					$result = array('success'=>'Error code: 0003 ! Please contact Admin');
					$results = (object)$result;
				}
			}
		}else{
			// Error 0004 : Error in Inserting Data In First Table Before Work Permit Data.
			$result = array('success'=>'Error code: 0004 ! Please contact Admin');
			$results = (object)$result;
		}

		$this->response($results,200);
	}
	
	function workPermit_old_post(){
		$this->load->model('Form_model');
		$dbdata=array(
				'wpermit_id'	=>'',
				'siteID_id'		=>$_REQUEST['siteID_name'],
				'job_card'		=>'',
				'sms'			=>'', 
				'created_date'	=>$_REQUEST['today_date'],
				'site_id'		=>'',
				'asset_series'	=>$_REQUEST['workPermit_asset_series'],
				'lattitude'		=>'', 
				'longitude'		=>'',
				'inspected_by'	=>'', 
				'approved_by'	=>'',
				'input_method'	=>'',
				'input_value'	=>'',
				'report_no'		=>'',
				'inspector_signature_image'	=>  '',
				'client_name'				=>	'',
				'client_designation'		=>	'',
				'client_signature_image'	=>	'',
				'client_remark'				=>	''
			);
			
		$return_temp1_id = $this->Form_model->first_slot_data_insert($dbdata);
		
		if($return_temp1_id){
			$data	= array(
					'client_name'					=>	$_REQUEST['client_name'],
					'today_date'					=>	$_REQUEST['today_date'],
					'workPermit_number'				=>	$_REQUEST['workPermit_number'],
				//	'siteId_name'					=>	$_REQUEST['siteID_name'],
					'siteId_name'					=>	$return_temp1_id,
					'permitDate_from'				=>	$_REQUEST['permitDate_from'],
					'permitValid_upto'				=>	$_REQUEST['permitValid_upto'],
					'siteID_address'				=>	$_REQUEST['siteID_address'],
					'gprs_lattitude'				=>	$_REQUEST['workPermit_lattitude'],
					'gprs_longitude'				=>	$_REQUEST['workPermit_longitude'],
					'asset_series'					=>	$_REQUEST['workPermit_asset_series'],
					'batch_no'						=>	$_REQUEST['workPermit_batch_no'],
					'serial_no'						=>	$_REQUEST['workPermit_serial_no'],
					'harness'						=>	$_REQUEST['harness'],
					'helmet'						=>	$_REQUEST['helmet'],
					'shoes'							=>	$_REQUEST['shoes'],
					'gloves'						=>	$_REQUEST['gloves'],
					'goggle'						=>	$_REQUEST['goggle'],
					'work_position'					=>	$_REQUEST['work_position'],
					'equipment_use'					=>	$_REQUEST['equipment_use'],
					'worker_trained'				=>	$_REQUEST['worker_trained'],
					'worker_safety'					=>	$_REQUEST['worker_safety'],
					'worker_height'					=>	$_REQUEST['worker_height'],
					'worker_defensive'				=>	$_REQUEST['worker_defensive'],
					'weather_good'					=>	$_REQUEST['weather_good'],
					'enough_light'					=>	$_REQUEST['enough_light'],
					'site_accessable'				=>	$_REQUEST['site_accessable'],
					'equipment_available'			=>	$_REQUEST['equipment_available'],
					'unguarded_edges'				=>	$_REQUEST['unguarded_edges'],
					'equipment_condition'			=>	$_REQUEST['equipment_condition'],
					'wiring_condition'				=>	$_REQUEST['wiring_condition'],
					'equipment_calibrated'			=>	$_REQUEST['equipment_calibrated'],
					'physically_fitness'			=>	$_REQUEST['physically_fitness'],
					'work_at_height'				=>	$_REQUEST['work_at_height'],
					'alcohol_influence'				=>	$_REQUEST['alcohol_influence'],
					'mediclaim_insured'				=>	$_REQUEST['mediclaim_insured'],
					'carry_firstAid'				=>	$_REQUEST['carry_firstAid'],
					'client_approval'				=>	$_REQUEST['client_approval'],
					'documentation_with_client'		=>	$_REQUEST['documentation_with_client']
				);
		
		
			$result = $this->Form_model->insert_work_permit($data);
			if($result){
				$result = array('workPermitID'=>$result);
				$results = (object)$result;
			}else{
				// Error 0003 : Error in Inserting Work Permit Data in function insert_work_permit.
				$result = array('success'=>'Error code: 0003 ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			// Error 0004 : Error in Inserting Data In First Table Before Work Permit Data.
			$result = array('success'=>'Error code: 0004 ! Please contact Admin');
			$results = (object)$result;
		}

		$this->response($results,200);
	}
	
	/*  Saving Inspection Report Values from API into database using Kare App 
		http://karam.in/kare_demo/api_controller/inspection?reportNo=121221&siteID=12siteName=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsj
		http://192.168.1.3/Mysites/karam/kare/api_controller/inspection?reportNo=121221&siteID=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdj
	*/
	
	/*
	*	FUNCTION : inspection_post
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/inspection?reportNo=121221&siteID=12siteName=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsj
	* 	URL: http://karam.in/kare_demo/api_controller/inspection?reportNo=121221&siteID=12siteName=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsj
	*	URL: http://karam.in/kare/api_controller/inspection?reportNo=121221&siteID=12siteName=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsj
	*/
	function inspection_6_2_2018_old_post(){
		
		$this->load->model('Form_model');
		$insp_temp1_id = $this->Form_model->get_temp_1_inserted_id($_REQUEST['workPermitID']);
		
		if($insp_temp1_id){
			$dbdata=array(
						'siteID_id'		=>	$_REQUEST['siteID'],
						'report_no'		=>	$_REQUEST['reportNo'],
						'wpermit_id'	=>	$_REQUEST['workPermitID'],
						'site_id'		=>	$_REQUEST['siteName'],
						'job_card'		=>	$_REQUEST['jobCard'],
						'sms'			=>	$_REQUEST['sms'],
						'created_date'	=>	$_REQUEST['todayDate'],
						'asset_series'	=>	$_REQUEST['assetSeries'],
						'lattitude'		=>	$_REQUEST['lattitude'],
						'longitude'		=>	$_REQUEST['longitude'],
						'inspected_by'	=>	$_REQUEST['userID'],
						'approved_by'	=>	'',
						'input_method'	=>	$_REQUEST['inputType'], 
						'input_value'	=>	$_REQUEST['inputValue'],
						'inspector_signature_image'	=>	'',
						'client_name'				=>	'',
						'client_designation'		=>	'',
						'client_signature_image'	=>	'',
						'client_remark'				=>	''
						);

			if($dbdata['lattitude'] =='' || $dbdata['longitude']==''){
				$dbdata['map_image'] = $image_path = "FCPATH/uploads/images/users/default.jpg";
			}else{
				$this->load->model('Form_model');
				$dbdata['map_image'] = $this->Form_model->get_google_map_image($dbdata['lattitude'],$dbdata['longitude']);
			}
		
		
			$this->load->model('Form_model');
			//$result	= $this->Form_model->first_slot_data_insert($dbdata);
			$result	= $this->Form_model->first_slot_data_update($insp_temp1_id,$dbdata);
			if($result){
				$result = array('success'=>$result);
				$results = (object)$result;
			}else{
				// Error 0006: Error In Updating Data of Inspection Form 1(RFID/UIN) according to Returned ID.
				$result = array('success'=>'Error code: 0006 ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			// Error 0005: Data Not Found In Temp Table 1 According to provided Site Id and Today's date.
			$result = array('success'=>'Error code: 0005 ! Please contact Admin');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	function inspection_post(){
		
		$this->load->model('Form_model');
		$dbdata=array(
					'siteID_id'		=>	$_REQUEST['siteID'],
					'report_no'		=>	$_REQUEST['reportNo'],
					//'wpermit_id'	=>	$_REQUEST['workPermitID'],
					'site_id'		=>	$_REQUEST['siteName'],
					'job_card'		=>	$_REQUEST['jobCard'],
					'sms'			=>	$_REQUEST['sms'],
					'created_date'	=>	$_REQUEST['todayDate'],
					'asset_series'	=>	$_REQUEST['assetSeries'],
					'lattitude'		=>	$_REQUEST['lattitude'],
					'longitude'		=>	$_REQUEST['longitude'],
					'inspected_by'	=>	$_REQUEST['userID'],
					'approved_by'	=>	'',
					'input_method'	=>	$_REQUEST['inputType'], 
					'input_value'	=>	$_REQUEST['inputValue'],
					'inspector_signature_image'	=>	'',
					'client_name'				=>	'',
					'client_designation'		=>	'',
					'client_signature_image'	=>	'',
					'client_remark'				=>	''
					);

		if($dbdata['lattitude'] =='' || $dbdata['longitude']==''){
			$dbdata['map_image'] = $image_path = "FCPATH/uploads/images/users/default.jpg";
		}else{
			$this->load->model('Form_model');
			$dbdata['map_image'] = $this->Form_model->get_google_map_image($dbdata['lattitude'],$dbdata['longitude']);
		}
		
		$this->load->model('Form_model');
		if(!empty($_REQUEST['workPermitID'])){
			$insp_temp1_id = $this->Form_model->get_temp_1_inserted_id($_REQUEST['workPermitID']);
			$dbdata['wpermit_id']	=	$insp_temp1_id;
			$result	= $this->Form_model->first_slot_data_update($insp_temp1_id,$dbdata);
		}elseif(empty($_REQUEST['workPermitID'])){
			$dbdata['wpermit_id']	=	'';
			$result	= $this->Form_model->first_slot_data_insert($dbdata);
		}else{
			// Error 0005: Data Not Found In Temp Table 1 According to provided Site Id and Today's date.
			$result = array('success'=>'Error code: 0005 ! Please contact Admin');
			$results = (object)$result;
		}
		
		if($result){
			$result = array('success'=>$result);
			$results = (object)$result;
		}else{
			// Error 0006: Error In Updating Data of Inspection Form 1(RFID/UIN) according to Returned ID.
			$result = array('success'=>'Error code: 0006 ! Please contact Admin');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	
	function inspection_old_post(){
		$this->load->model('Form_model');
		$insp_temp1_id = $this->Form_model->get_temp_1_inserted_id($_REQUEST['workPermitID']);
		if($insp_temp1_id){
			$dbdata=array(
						'siteID_id'		=>	$_REQUEST['siteID'],
						'report_no'		=>	$_REQUEST['reportNo'],
						'wpermit_id'	=>	$_REQUEST['workPermitID'],
						'site_id'		=>	$_REQUEST['siteName'],
						'job_card'		=>	$_REQUEST['jobCard'],
						'sms'			=>	$_REQUEST['sms'],
						'created_date'	=>	$_REQUEST['todayDate'],
						'asset_series'	=>	$_REQUEST['assetSeries'],
						'lattitude'		=>	$_REQUEST['lattitude'],
						'longitude'		=>	$_REQUEST['longitude'],
						'inspected_by'	=>	$_REQUEST['userID'],
						'approved_by'	=>	'',
						'input_method'	=>	$_REQUEST['inputType'], 
						'input_value'	=>	$_REQUEST['inputValue'],
						'inspector_signature_image'	=>	'',
						'client_name'				=>	'',
						'client_designation'		=>	'',
						'client_signature_image'	=>	'',
						'client_remark'				=>	''
						);

			if($dbdata['lattitude'] =='' || $dbdata['longitude']==''){
				$dbdata['map_image'] = $image_path = "FCPATH/uploads/images/users/default.jpg";
			}else{
				$this->load->model('Form_model');
				$dbdata['map_image'] = $this->Form_model->get_google_map_image($dbdata['lattitude'],$dbdata['longitude']);
			}
		
		
			$this->load->model('Form_model');
			//$result	= $this->Form_model->first_slot_data_insert($dbdata);
			$result	= $this->Form_model->first_slot_data_update($insp_temp1_id,$dbdata);
			if($result){
				$result = array('success'=>$result);
				$results = (object)$result;
			}else{
				// Error 0006: Error In Updating Data of Inspection Form 1(RFID/UIN) according to Returned ID.
				$result = array('success'=>'Error code: 0006 ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			// Error 0005: Data Not Found In Temp Table 1 According to provided Site Id and Today's date.
			$result = array('success'=>'Error code: 0005 ! Please contact Admin');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	
	
	/*
		http://karam.in/kare_demo/api_controller/inspection?reportNo=124#2016-0008&siteID=94&jobCard=TEST_JOB_CARD&sms=TEST_SMS&todayDate=11-8-2016&assetSeries=TEST_ASSET_SERIES&poNumber=test%20po&batchNumber=Test%20Batch&serialNumber=Test%20Serial&lattitude=12&longitude=2121&userID=124%232016-0008&inputType=RFID&inputValue=Testrfid
		http://192.168.1.3/Mysites/karam/kare/api_controller/inspection?reportNo=124#2016-0008&siteID=94&jobCard=TEST_JOB_CARD&sms=TEST_SMS&todayDate=11-8-2016&assetSeries=TEST_ASSET_SERIES&poNumber=test%20po&batchNumber=Test%20Batch&serialNumber=Test%20Serial&lattitude=12&longitude=2121&userID=124%232016-0008&inputType=RFID&inputValue=Testrfid
	

		http://karam.in/kare_demo/api_controller/inspection?reportNo=121221&siteID=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsjdsdjs
		http://192.168.1.3/Mysites/karam/kare/api_controller/inspection?reportNo=121221&siteID=121221&jobCard=abc&sms=SMS&client=id&todayDate=djshdh&assetSeries=PN4000(OH)(8MM)&poNumber=INS-4000(FP)&batchNumber=INS-4000(FP)&serialNumber=INS-4000(FP)&lattitude=INS-4000(FP)&longitude=INS-4000(FP)&userID=INS-4000(FP)&inputType=RFID&inputValue=sdjdjsjdsdjs
	*/
	
	function after_image_uploadios_post(){
		$folder_path = FCPATH.'/uploads/inspected_img/'.date('d-m-Y');
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		if(!empty($_REQUEST['after_image'])){
			$after_image = json_decode($_REQUEST['after_image'] , true);
			if(!empty($after_image) && is_array($after_image)){
				foreach($after_image as $key => $value){
					$image_name[$key] = base64_decode($value) . 'jpg';
					$file[$key] =  './uploads/inspected_img/'.date('d-m-Y'). '/' .uniqid() . '.jpg';
					file_put_contents($file[$key],  $image_name[$key]);
				}
				if(count($file) == count($after_image)){
					$string1 = '/';
					foreach($file as $k => $v){
						$afterImage[$k]['file_name'] = trim(strstr(strstr($v,$string1),'/'),'/'); 
					}
						$return = json_encode($afterImage);
				}else{
						$return = '';
				}
			}else{
					$return = '';
			}
		}
		
		print_r($return);
	}
	
	
	function after_image_upload_ios_31_07_2018($after_image){
		$folder_path = FCPATH.'/uploads/inspected_img/'.date('d-m-Y');
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		if(!empty($after_image)){
			$after_image = json_decode($after_image , true);
			if(!empty($after_image) && is_array($after_image)){
				foreach($after_image as $key => $value){
					$image_name[$key] = base64_decode($value) . 'jpg';
					$file[$key] =  './uploads/inspected_img/'.date('d-m-Y'). '/' .uniqid() . '.jpg';
					file_put_contents($file[$key],  $image_name[$key]);
				}
				if(count($file) == count($after_image)){
					$string1 = $_REQUEST['user_id'].'/';
					foreach($file as $k => $v){
						$afterImage[$k]['file_name'] = trim(strstr(strstr($v,$string1),'/'),'/'); 
					}
						$return = json_encode($afterImage);
				}else{
						$return = '';
				}
			}else{
					$return = '';
			}
		}
		
		return $return;
	}

	function before_image_upload_ios_31_07_2018($before_image){
		$folder_path = FCPATH.'/uploads/inspected_img/'.date('d-m-Y');
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		if(!empty($before_image)){
			$before_image = json_decode($before_image , true);
			if(!empty($before_image) && is_array($before_image)){
				foreach($before_image as $key => $value){
					$image_name[$key] = base64_decode($value) . 'jpg';
					$file[$key] =  './uploads/inspected_img/'.date('d-m-Y'). '/' .uniqid() . '.jpg';
					file_put_contents($file[$key],  $image_name[$key]);
				}
				if(count($file) == count($before_image)){
					$string1 = $_REQUEST['user_id'].'/';
					foreach($file as $k => $v){
						$beforeImage[$k]['file_name'] = trim(strstr(strstr($v,$string1),'/'),'/'); 
					}
						$return = json_encode($beforeImage);
				}else{
						$return = '';
				}
			}else{
					$return = '';
			}
		}
		return $return;
	}
	

	function before_image_upload_ios($before_image){
		
		$folder_path = FCPATH.'/uploads/inspected_img/'.date('d-m-Y');
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		$return = array();
		if(!empty($before_image)){
			    $before_imageArray = json_decode($before_image , true);
				if(!empty($before_imageArray) && (is_array($before_imageArray) ) ){			
				   foreach($before_imageArray AS $key=>$val){
							$datetime	= date("Y-m-d h:i:s");
							$timestamp	= $key.'_before_'.strtotime($datetime);
							$image		= $val;
							$imgdata	= base64_decode($val);
							$f			= finfo_open();
							$mime_type  = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
							$temp		=explode('/',$mime_type);
							$path		= FCPATH."./uploads/inspected_img/".date('d-m-Y')."/$timestamp.$temp[1]";
							$fileName   = "$timestamp.$temp[1]";
							$success    = file_put_contents($path,base64_decode($image));
							if($success){
								$return[] =	'FCPATH/uploads/inspected_img/'.date('d-m-Y').'/'.$fileName;
							}else{
								$user_img = '';
							}
				   } 
				   return $return;
				}else{				
				   return $return;
				}
				
		}else{
		   return $return;
		}
	}


	function after_image_upload_ios($after_image){
		$folder_path = FCPATH.'/uploads/inspected_img/'.date('d-m-Y');
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		$return = array();
		if(!empty($after_image)){
				$after_imageArray = json_decode($after_image , true);
				if(!empty($after_imageArray) && (is_array($after_imageArray) ) ){			
				   foreach($after_imageArray AS $key=>$val){
							$datetime	= date("Y-m-d h:i:s");
							$timestamp	= $key.'_after_'.strtotime($datetime);
							$image		= $val;
							$imgdata	= base64_decode($val);
							$f			= finfo_open();
							$mime_type	= finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
							$temp		=	explode('/',$mime_type);
							$path		= FCPATH."./uploads/inspected_img/".date('d-m-Y')."/$timestamp.$temp[1]";
							$fileName   = "$timestamp.$temp[1]";
							$success    = file_put_contents($path,base64_decode($image));
							if($success){
								$return[] =	'FCPATH/uploads/inspected_img/'.date('d-m-Y').'/'.$fileName;
							}else{
								$user_img = '';
							}
				   } 
				}
				return $return;
		}else{
		  return $return;
		}
	}

	function inspectionForm_ios_post(){
		    //  Start to test 
            /*
			$_REQUEST['before_image'][] = $_REQUEST['before_image1'];
			$_REQUEST['before_image'][] = $_REQUEST['before_image2'];
			$_REQUEST['before_image'][] = $_REQUEST['before_image3'];


            $_REQUEST['after_image'][] = $_REQUEST['before_image1'];
			$_REQUEST['after_image'][] = $_REQUEST['before_image2'];
			$_REQUEST['after_image'][] = $_REQUEST['before_image3'];

			if(!empty($_REQUEST['before_image'])){
				$before_repair_image = $this->before_image_upload_ios($_REQUEST['before_image']);
			}	
			
			if(!empty($_REQUEST['after_image'])){
				$after_repair_image = $this->after_image_upload_ios($_REQUEST['after_image']);
			}	

				$dbdata	=	array(
					'inspection_list_id'		=>	$_REQUEST['returnID'],
					'asset_name'				=>	$_REQUEST['asset'],
					'subAsset_name'				=>	$_REQUEST['subAsset'],
					'before_repair_image'		=>	!empty($before_repair_image)?json_encode($before_repair_image):'',
					'after_repair_image'		=>	!empty($after_repair_image)?json_encode($after_repair_image):'',
					'observation_type'			=>	json_encode($obs),
					'action_proposed'			=>	'',
					'result'					=>	''						
				);
           */  
		// End to test

			
		 if(!empty($_REQUEST['asset']) && !empty($_REQUEST['returnID'])){
			
			$obs = array();
			$param = array();
			if (strpos($_REQUEST['observation'], '##') !== false){
				$observe = explode('##',$_REQUEST['observation']);
				$act = explode('##',$_REQUEST['actionProposed']);
				$res = explode('##',$_REQUEST['result']);
				
				foreach($observe as $key=>$val){
					$obs[$key] = array(
						'observation_type' =>$val,
						'action_proposed'=>$act[$key],
						'result'=>$res[$key],
					);
				}
			}else{
				$obs[] = array(
					'observation_type' =>$_REQUEST['observation'],
					'action_proposed'=>$_REQUEST['actionProposed'],
					'result'=>$_REQUEST['result'],
				);
			}
			
			if(!empty($_REQUEST['before_image'])){
				$before_repair_image = $this->before_image_upload_ios($_REQUEST['before_image']);
			}	
			
			if(!empty($_REQUEST['after_image'])){
				$after_repair_image = $this->after_image_upload_ios($_REQUEST['after_image']);
			}	
			
			if(!isset($_REQUEST['after_image'])){
				// Error 00014 : After Image File Not Uploaded.
				$result = array('success'=>'After Image are compulsary');
			}else{
				// save into db
				$dbdata	=	array(
					'inspection_list_id'		=>	$_REQUEST['returnID'],
					'asset_name'				=>	$_REQUEST['asset'],
					'subAsset_name'				=>	$_REQUEST['subAsset'],
					'before_repair_image'		=>	!empty($before_repair_image)?json_encode($before_repair_image):'',
					'after_repair_image'		=>	!empty($after_repair_image)?json_encode($after_repair_image):'',
					'observation_type'			=>	json_encode($obs),
					'action_proposed'			=>	'',
					'result'					=>	''						
				);
				
				//print_r($dbdata);die("123");
				$this->load->model('Form_model');
				$result	= $this->Form_model->second_slot_data_inserted_api($dbdata);
				if($result){
					$result = array('success'=>$result);
					$results = (object)$result;
				}else{
					// Error 0013: Error In Inserting Data in function second_slot_data_inserted_api.
					$result = array('success'=>'Error code: 0013 ! Please contact Admin');
					$results = (object)$result;
				}
			}	
		}else{
			$result = array('success'=>'Please Fill All Data Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($result,200);	
	}
	
	
	function inspectionForm_post(){
		$obs = array();
		
		if (strpos($_REQUEST['observation'], '##') !== false){
			$observe = explode('##',$_REQUEST['observation']);
			$act = explode('##',$_REQUEST['actionProposed']);
			$res = explode('##',$_REQUEST['result']);
			
			foreach($observe as $key=>$val){
				$obs[$key] = array(
							'observation_type' =>$val,
							'action_proposed'=>$act[$key],
							'result'=>$res[$key],
						);
			}
		}else{
			$obs[] = array(
							'observation_type' =>$_REQUEST['observation'],
							'action_proposed'=>$_REQUEST['actionProposed'],
							'result'=>$_REQUEST['result'],
						);
		}
		
		$dbdata=array(
							'asset_name' 			=> $_REQUEST['asset'],
							'subAsset_name' 		=> $_REQUEST['subAsset'],
							'inspection_list_id' 	=> $_REQUEST['returnID'],
							'observation_type' 		=> json_encode($obs),
							'action_proposed' 		=> '',
							'result' 				=> ''
			);
		
		if($_REQUEST['asset'] !=''){
			if(!isset($_FILES['after_file'])){
				// Error 00014 : After Image File Not Uploaded.
				$result = array('success'=>'After Image are compulsary');
			}else{
				/* Start Of Sign Image Adding */
				$new_path = "./uploads/inspected_img/".date('d-m-Y')."/";
				$image_path = "FCPATH/uploads/inspected_img/".date('d-m-Y')."/";
				$type = "asset_images";
				$datas = $this->insImage($new_path,$image_path,$type);
				if(!isset($datas['error'])){
					if(!empty($datas[0])){
						$dbdata['before_repair_image'] = $datas[0];
					}
					if(!empty($datas[1])){
						$dbdata['after_repair_image'] = $datas[1];
					}	
					/* End of Single Image */
					$this->load->model('Form_model');
					//print_r($dbdata);die("123");
					$result	= $this->Form_model->second_slot_data_inserted_api($dbdata);
					if($result){
						$result = array('success'=>$result);
						$results = (object)$result;
					}else{
						// Error 0013: Error In Inserting Data in function second_slot_data_inserted_api.
						$result = array('success'=>'Error code: 0013 ! Please contact Admin');
						$results = (object)$result;
					}
				}else{
					// Error 00011, 00012 : Image File Not Uploaded.
					$error = $datas['error'];
					$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
					//$result = array('success'=>'Image File Not Uploaded');
					$results = (object)$result;
				}
			}
		}else{
			$result = array('success'=>'Please Fill All Data Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($result,200);
	}
	
	function inspectionForm_old_post(){  
		/*
			http://karam.in/kare_demo/api_controller/inspectionForm?asset=test&subAsset=subTest&returnID=1&observation=7&result=2&actionProposed=22
			http://192.168.1.3/Mysites/karam/kare/api_controller/inspectionForm?asset=test&subAsset=subTest&returnID=1&observation=7&result=2&actionProposed=22
		*/
		
		$obs = array();
		if (strpos($_REQUEST['observation'], '##') !== false){
			$observe = explode('##',$_REQUEST['observation']);
			$act = explode('##',$_REQUEST['actionProposed']);
			$res = explode('##',$_REQUEST['result']);
			
			foreach($observe as $key=>$val){
				$obs[$key] = array(
							'observation_type' =>$val,
							'action_proposed'=>$act[$key],
							'result'=>$res[$key],
						);
			}
		}else{
			$obs[] = array(
							'observation_type' =>$_REQUEST['observation'],
							'action_proposed'=>$_REQUEST['actionProposed'],
							'result'=>$_REQUEST['result'],
						);
		}
		
		$dbdata=array(
							'asset_name' 			=> $_REQUEST['asset'],
							'subAsset_name' 		=> $_REQUEST['subAsset'],
							'inspection_list_id' 	=> $_REQUEST['returnID'],
							'observation_type' 		=> json_encode($obs),
							'action_proposed' 		=> '',
							'result' 				=> ''
			);
		
		if($_REQUEST['asset'] !=''){
			if(!isset($_FILES['after_file'])){
				$result = array('success'=>'After Image are compulsary');
			}else{
				/* Start Of Singh Image Adding */
				$new_path = "./uploads/inspected_img/".date('d-m-Y')."/";
				$image_path = "FCPATH/uploads/inspected_img/".date('d-m-Y')."/";
				$type = "asset_images";
				$datas = $this->insImage_post($new_path,$image_path,$type);
				if(!isset($datas['error'])){
					$dbdata['before_repair_image'] = $datas[0];
					$dbdata['after_repair_image'] = $datas[1];
					/* End of Single Image */
					$this->load->model('Form_model');
					$result	= $this->Form_model->second_slot_data_inserted_api($dbdata);
					if($result){
						$result = array('success'=>$result);
						$results = (object)$result;
					}else{
						// Error 0013: Error In Inserting Data in function second_slot_data_inserted_api.
						$result = array('success'=>'Error code: 0013 ! Please contact Admin');
						$results = (object)$result;
					}
				}else{
					// Error 00011, 00012 : Image File Not Uploaded.
					$error = $datas['error'];
					$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
					//$result = array('success'=>'Image File Not Uploaded');
					$results = (object)$result;
				}
			}
		}else{
			$result = array('success'=>'Please Fill All Data Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($result,200);
	}
	
	/* On Final Submission, Inspector data will be taken 
		http://karam.in/kare_demo/api_controller/inspectorInformation?returnID=1&signImage=fkgfjkhjkh
		http://192.168.1.3/Mysites/karam/kare/api_controller/inspectorInformation?returnID=1&signImage=fkgfjkhjkh
	*/
	function inspectorInformation_old_post(){

		if($_FILES['signImage']!=''){
			$dbdata=array();
			$id = $_REQUEST['returnID'];
			
			$new_path = "./uploads/inspector_sign/".date('d-m-Y')."/";
			$image_path = "FCPATH/uploads/inspector_sign/".date('d-m-Y')."/";
			$type = "inspector_sign_image";
			$signImage = $this->insImage_post($new_path,$image_path,$type);
			if(!isset($signImage['error'])){
				$dbdata['inspector_signature_image'] = $signImage;
				
				$this->load->model('Form_model');
				$res	= $this->Form_model->third_slot_data_insert($id,$dbdata);
				if($res){
						$result = array('success'=>'Data Submitted');
						$results = (object)$result;
				}else{
					// ERROR 0014 : Error In Updating data of Inspector Form in function third_slot_data_insert
					$result = array('success'=>'Error code: 0014 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	
	function _single_image_upload($signImage,$clientImage){
		if(isset($signImage) && empty($clientImage)){
			$folder_path = FCPATH.'/uploads/inspector_sign/'.date('d-m-Y');
		}else if(empty($signImage) && !empty($clientImage)){
			$folder_path = FCPATH.'/uploads/client_sign/'.date('d-m-Y');
		}
			
		if (!file_exists($folder_path)) {
		  mkdir($folder_path, 0777, true);
		}
		
		if(isset($signImage) && empty($clientImage)){
			$image_name = base64_decode($signImage) . 'jpg';
			$fileName = uniqid() . '.jpg';
			 $file = './uploads/inspector_sign/'.date('d-m-Y').'/'.$fileName;
		
			$success 	=  file_put_contents($file, $image_name);
			//print_r($ext);print '<br/>';
			if($success){
				$return =	'FCPATH/uploads/inspector_sign/'.date('d-m-Y').'/'.$fileName;
			}else{
				$return = '';
			}
		}else if(empty($signImage) && !empty($clientImage)){
			$image_name = base64_decode($clientImage) . 'jpg';
			$fileName = uniqid() . '.jpg';
			 $file = './uploads/client_sign/'.date('d-m-Y').'/'.$fileName;
		
			$success 	=  file_put_contents($file, $image_name);
			//print_r($ext);print '<br/>';
			if($success){
				$return =	'FCPATH/uploads/client_sign/'.date('d-m-Y').'/'.$fileName;
			}else{
				$return = '';
			}
		}
		//print_r($return);die("123");
		return $return;
	}
	
	function inspectorInformationIos_post(){
		if(!empty($_REQUEST['signImage']) && !empty($_REQUEST['returnID'])){
			$dbdata = array();
			$sign_image = $this->_single_image_upload($_REQUEST['signImage'],'');
			if($sign_image != ''){
				$id = $_REQUEST['returnID'];
				$dbdata['inspector_signature_image'] = $sign_image;
				$this->load->model('Form_model');
				$res	= $this->Form_model->third_slot_data_insert($id,$dbdata);
				if($res){
						$result = array('success'=>'Data Submitted');
						$results = (object)$result;
				}else{
					// ERROR 0014 : Error In Updating data of Inspector Form in function third_slot_data_insert
					$result = array('success'=>'Error code: 0014 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	
	function inspectorInformation_post(){
		
		if($_FILES['signImage']!=''){
			$dbdata=array();
			$id = $_REQUEST['returnID'];
			
			$new_path = "./uploads/inspector_sign/".date('d-m-Y')."/";
			$image_path = "FCPATH/uploads/inspector_sign/".date('d-m-Y')."/";
			$type = "inspector_sign_image";
			$signImage = $this->insImage($new_path,$image_path,$type);
			if(!isset($signImage['error'])){
				$dbdata['inspector_signature_image'] = $signImage;
				
				$this->load->model('Form_model');
				$res	= $this->Form_model->third_slot_data_insert($id,$dbdata);
				if($res){
						$result = array('success'=>'Data Submitted');
						$results = (object)$result;
				}else{
					// ERROR 0014 : Error In Updating data of Inspector Form in function third_slot_data_insert
					$result = array('success'=>'Error code: 0014 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	/* On Final Submission, Inspector data will be taken 
		http://karam.in/kare_demo/api_controller/clientInformation?returnID=1&clientName=dddshdj#hfdjh&designation=ffdsfdfttttr$remark=kjfkljfdjfjdjf
		http://192.168.1.3/Mysites/karam/kare/api_controller/clientInformation?returnID=1&clientName=dddshdj#hfdjh&designation=ffdsfdf&remark=kfjkfljdjfdjl
	*/
	
	function clientInformationIos_post(){	
		if(!empty($_POST['signImage']) && !empty($_POST['returnID'])){
			$dbdata = array();
			
			$id = $_POST['returnID'];
			if (strpos($_POST['clientName'], '#') !== false) {
				$client_name 	= str_replace("#"," ",$_POST['clientName']);
			}else{
				$client_name = $_POST['clientName'];
			}
			
			if (strpos($_POST['designation'], '#') !== false) {
				$designation 	= str_replace("#"," ",$_POST['designation']);
			}else{
				$designation = $_POST['designation'];
			}
			
			if (strpos($_POST['remark'], '#') !== false) {
				$remark 	= str_replace("#"," ",$_POST['remark']);
			}else{
				$remark = $_POST['remark'];
			}
			$dbdata = array(
				'client_name' 				=> trim($client_name),
				'client_designation' 		=> trim($designation),
				'client_remark' 			=> trim($remark),
				'inspected_status' 			=> 'Yes',
				'approved_status' 			=> 'Pending'
			);
			$sign_image = $this->_single_image_upload('',$_POST['signImage']);
			//print_r($sign_image);die("123");
			if($sign_image != ''){
				
				$dbdata['client_signature_image'] = $sign_image;
				$this->load->model('Form_model');
				$res	= $this->Form_model->fourth_slot_data_insert($id,$dbdata);
				if($res){
						$final_result = $this->Form_model->insert_main_table_data($id);
						$result = array('success'=>'Form Submitted Successfully');
						$results = (object)$result;
				}else{
					// ERROR 0014 : Error In Updating data of Inspector Form in function third_slot_data_insert
					$result = array('success'=>'Error code: 0014 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	function clientInformation_post(){
		
		if($_FILES['signImage']!=''){
			$dbdata=array();
			
			$id = $_REQUEST['returnID'];
			if (strpos($_REQUEST['clientName'], '#') !== false) {
				$client_name 	= str_replace("#"," ",$_REQUEST['clientName']);
			}else{
				$client_name = $_REQUEST['clientName'];
			}
			
			if (strpos($_REQUEST['designation'], '#') !== false) {
				$designation 	= str_replace("#"," ",$_REQUEST['designation']);
			}else{
				$designation = $_REQUEST['designation'];
			}
			
			if (strpos($_REQUEST['remark'], '#') !== false) {
				$remark 	= str_replace("#"," ",$_REQUEST['remark']);
			}else{
				$remark = $_REQUEST['remark'];
			}
			$dbdata = array(
							'client_name' 				=> trim($client_name),
							'client_designation' 		=> trim($designation),
							'client_remark' 			=> trim($remark),
							'inspected_status' 			=> 'Yes',
							'approved_status' 			=> 'Pending'
							);
			$new_path = "./uploads/client_sign/".date('d-m-Y')."/";
			$image_path = "FCPATH/uploads/client_sign/".date('d-m-Y')."/";
			$type = "client_sign_image";
			$signImage = $this->insImage_post($new_path,$image_path,$type);
			if(!isset($signImage['error'])){
				$dbdata['client_signature_image'] = $signImage;
				$this->load->model('Form_model');
				//print_r($dbdata);die("  123");
				$res	= $this->Form_model->fourth_slot_data_insert($id,$dbdata);
				
				if($res){
					
						$final_result = $this->Form_model->insert_main_table_data($id);
						$result = array('success'=>'Form Submitted Successfully');
						$results = (object)$result;
				}else{
					// Error  0015: Error in In Submitting Client Form in function fourth_slot_data_insert
					$result = array('success'=>'Error code: 0015 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	
	function clientInformation_old_post(){

		if($_FILES['signImage']!=''){
			$dbdata=array();
			
			$id = $_REQUEST['returnID'];
			if (strpos($_REQUEST['clientName'], '#') !== false) {
				$client_name 	= str_replace("#"," ",$_REQUEST['clientName']);
			}else{
				$client_name = $_REQUEST['clientName'];
			}
			
			if (strpos($_REQUEST['designation'], '#') !== false) {
				$designation 	= str_replace("#"," ",$_REQUEST['designation']);
			}else{
				$designation = $_REQUEST['designation'];
			}
			
			if (strpos($_REQUEST['remark'], '#') !== false) {
				$remark 	= str_replace("#"," ",$_REQUEST['remark']);
			}else{
				$remark = $_REQUEST['remark'];
			}
			$dbdata = array(
							'client_name' 				=> trim($client_name),
							'client_designation' 		=> trim($designation),
							'client_remark' 			=> trim($remark),
							'inspected_status' 			=> 'Yes',
							'approved_status' 			=> 'Pending'
							);
			$new_path = "./uploads/client_sign/".date('d-m-Y')."/";
			$image_path = "FCPATH/uploads/client_sign/".date('d-m-Y')."/";
			$type = "client_sign_image";
			$signImage = $this->insImage_post($new_path,$image_path,$type);
			if(!isset($signImage['error'])){
				$dbdata['client_signature_image'] = $signImage;
				$this->load->model('Form_model');
				$res	= $this->Form_model->fourth_slot_data_insert($id,$dbdata);
				if($res){
						$final_result = $this->Form_model->insert_main_table_data($id);
						$result = array('success'=>'Form Submitted Successfully');
						$results = (object)$result;
				}else{
					// Error  0015: Error in In Submitting Client Form in function fourth_slot_data_insert
					$result = array('success'=>'Error code: 0015 ! Please contact Admin');
					$results = (object)$result;
				}
			}else{
				// Error 0009, 0010 : Image File Not Uploaded.
				$error = $signImage['error'];
				$result = array('success'=>'Error code: '.$error.' ! Please contact Admin');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Please Sign Before Submitting Form');
			$results = (object)$result;
		}
		
		$this->response($results,200);
	}
	
	
	function insImage($new_path,$image_path,$type) {
		
		$this->load->model('Demo_auth_model');
		$this->Demo_auth_model->check_directory($new_path);
		
		if($type == 'map_image'){
			/* For Map Images */
			if(isset($_FILES['map_image'])){
				$image_name = date('ymdhis').'.jpg';
				$new_loc = $new_path.$image_name;
				if(move_uploaded_file($_FILES['map_image']['tmp_name'], $new_loc)) {
					return $image_path.$image_name;;
				} else{
					// Error  0008: Error In Uploading Map Image. File Not Upload.
					return $result =  array('error'=>"0008");
				}
					//return $image_path.$image_name;
			} else{
				// Error  0007: Did not get Map Image to Upload. Image Uploading Failed.
				return $result =  array('error'=>"0007");
			}
		}else{
			if($type != 'asset_images'){
				/* For Inspector and Client Signature Images */
				if(isset($_FILES['signImage'])){
					$image_name = $_FILES['signImage']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['signImage']['tmp_name'], $new_loc)) {
						return $image_path.$image_name;
					}else{
						// Error  0010: Error In Uploading Signature Image. File Not Upload.
						return $result =  array('error'=>"0010");
					}
				}else{
					// Error  0009: Did not get signImage to Upload. Image Uploading Failed.
					return $result =  array('error'=>"0009");
				}
			}else{
				/* For Asset Images */
				if(isset($_FILES['after_file'])){
					$image_name = $_FILES['after_file']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['after_file']['tmp_name'], $new_loc)) {
						$return = $this->zipExtract($new_path,$new_loc,$image_path,$type);
						return $return;
					} else{
						// Error  0012: Asset Images File Not Upload.
						return $result =  array('error'=>"0012");
					}
				} else{
					// Error  0011: Did not get after_file to Upload. Fail !!
					return $result =  array('error'=>"0011");
				}
			}
		}
	}


	function insImage_post($new_path,$image_path,$type) {
		
		$this->load->model('Demo_auth_model');
		$this->Demo_auth_model->check_directory($new_path);
		
		if($type == 'map_image'){
			/* For Map Images */
			if(isset($_FILES['map_image'])){
				$image_name = date('ymdhis').'.jpg';
				$new_loc = $new_path.$image_name;
				if(move_uploaded_file($_FILES['map_image']['tmp_name'], $new_loc)) {
					return $image_path.$image_name;;
				} else{
					// Error  0008: Error In Uploading Map Image. File Not Upload.
					return $result =  array('error'=>"0008");
				}
					//return $image_path.$image_name;
			} else{
				// Error  0007: Did not get Map Image to Upload. Image Uploading Failed.
				return $result =  array('error'=>"0007");
			}
		}else{
			if($type != 'asset_images'){
				/* For Inspector and Client Signature Images */
				if(isset($_FILES['signImage'])){
					$image_name = $_FILES['signImage']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['signImage']['tmp_name'], $new_loc)) {
						return $image_path.$image_name;
					}else{
						// Error  0010: Error In Uploading Signature Image. File Not Upload.
						return $result =  array('error'=>"0010");
					}
				}else{
					// Error  0009: Did not get signImage to Upload. Image Uploading Failed.
					return $result =  array('error'=>"0009");
				}
			}else{
				/* For Asset Images */
				if(isset($_FILES['after_file'])){
					$image_name = $_FILES['after_file']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['after_file']['tmp_name'], $new_loc)) {
						return $this->zipExtract($new_path,$new_loc,$image_path,$type);
					} else{
						// Error  0012: Asset Images File Not Upload.
						return $result =  array('error'=>"0012");
					}
				} else{
					// Error  0011: Did not get after_file to Upload. Fail !!
					return $result =  array('error'=>"0011");
				}
			}
		}
	}

	function insImage_old_post($new_path,$image_path,$type) {

		$this->load->model('Demo_auth_model');
		$this->Demo_auth_model->check_directory($new_path);
		
		if($type == 'map_image'){
			/* For Map Images */
			if(isset($_FILES['map_image'])){
				$image_name = date('ymdhis').'.jpg';
				$new_loc = $new_path.$image_name;
				if(move_uploaded_file($_FILES['map_image']['tmp_name'], $new_loc)) {
					return $image_path.$image_name;;
				} else{
					// Error  0008: Error In Uploading Map Image. File Not Upload.
					return $result =  array('error'=>"0008");
				}
					//return $image_path.$image_name;
			} else{
				// Error  0007: Did not get Map Image to Upload. Image Uploading Failed.
				return $result =  array('error'=>"0007");
			}
		}else{
			if($type != 'asset_images'){
				/* For Inspector and Client Signature Images */
				if(isset($_FILES['signImage'])){
					$image_name = $_FILES['signImage']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['signImage']['tmp_name'], $new_loc)) {
						return $image_path.$image_name;
					}else{
						// Error  0010: Error In Uploading Signature Image. File Not Upload.
						return $result =  array('error'=>"0010");
					}
				}else{
					// Error  0009: Did not get signImage to Upload. Image Uploading Failed.
					return $result =  array('error'=>"0009");
				}
			}else{
				/* For Asset Images */
				if(isset($_FILES['after_file'])){
					$image_name = $_FILES['after_file']['name'];
					$new_loc = $new_path.$image_name;
					if(move_uploaded_file($_FILES['after_file']['tmp_name'], $new_loc)) {
						return $this->zipExtract($new_path,$new_loc,$image_path,$type);
					} else{
						// Error  0012: Asset Images File Not Upload.
						return $result =  array('error'=>"0012");
					}
				} else{
					// Error  0011: Did not get after_file to Upload. Fail !!
					return $result =  array('error'=>"0011");
				}
			}
		}
	}

	
	/* 
		http://192.168.1.3/Mysites/karam/kare/api_controller/zipExtract
	*/
	function zipExtract($new_path,$new_loc,$image_path,$type) {

		$zip = new ZipArchive;
		$data = array();
		
		if ($zip->open($new_loc) === true) {
			$new_before_image_name = array();
				for($i = 0; $i < $zip->numFiles; $i++) {
					 $old_name = $zip->getNameIndex($i);
					if (strpos($old_name, 'before') !== false) {
						$newName = 'before_'.date('ymdhis').rand().'.jpg';
						$new_name = $zip->renameName($old_name,$newName);
						$new_before_image_name[] = $image_path.$newName;
					}
					else if (strpos($old_name, 'after') !== false) {
						$newName = 'after_'.date('ymdhis').rand().'.jpg';
						$new_name = $zip->renameName($old_name,$newName);
						$new_after_image_name[] = $image_path.$newName;
					}
					$zip->extractTo($new_path, array($zip->getNameIndex($i)));				
				}
				
				if(empty($new_before_image_name)){
					$before_image = json_encode(array('0'=>'FCPATH/uploads/images/users/default.jpg'));
				}else{
					$before_image = json_encode($new_before_image_name);
				}
				
			$zip->close();
			//unlink($new_loc);
			return $data = array($before_image , json_encode($new_after_image_name));
		}

	} // end function

	
	
	
	//	http://karam.in/kare_demo/api_controller/inspectorInspectionList?userID=5
	//	http://192.168.1.3/Mysites/karam/kare/api_controller/inspectorInspectionList?userID=13
	
	function inspectorInspectionList_get(){
		if(!empty($_REQUEST['userID']) && !empty($_REQUEST['groupID'])){
			$this->load->model('Inspector_inspection_model');
			
			if($_REQUEST['groupID'] == 9){
				$result =  $this->Inspector_inspection_model->get_inspectionData_for_inspector($_REQUEST['userID'],'');
		    }else{
				$result =  $this->Inspector_inspection_model->get_client_siteID($_REQUEST['userID']);
			}
			
			if($result > 0 && !empty($result)){
				$results = $result;
			}else{
				$result = array('success'=>'No Data Found');
				$results = (object)$result;
			}
		}else{
			$result = array('success'=>'Parameter missing');
			$results = (object)$result;
		}	
		$this->response($results,200);
	}
	function productInspection_history_get(){
		// print($_REQUEST['code']);die(' 68768968 ');
		if(!empty($_REQUEST['code'])){
			// print($_REQUEST['code']);die(' dsgfg ');
			
			$this->load->model('Inspector_inspection_model');
				$result =  $this->Inspector_inspection_model->get_inspectionData_by_product($_REQUEST['code'],'');
					if($result > 0 && !empty($result)){
						$results = $result;
					}else{
						$result = array('success'=>'No Data Found');
						$results = (object)$result;
					}
		}else{
			$result = array('success'=>'Parameter missing');
			$results = (object)$result;
		}	
		$this->response($results,200);
	}
	
	function inspectorInspectionList_old_get(){
		$userID = $_REQUEST['userID'];
		$this->load->model('Inspector_inspection_model');
		$result =  $this->Inspector_inspection_model->get_inspectionData_for_inspector($userID,'');
	
		if($result){
			$result = array('success'=>$result);
			$results = (object)$result;
		}else{
			$result = array('success'=>'No Data Found');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	//	http://karam.in/kare_demo/api_controller/inspectorInspectionListbyID?id=1&userID=5
	//	http://192.168.1.3/Mysites/karam/kare/api_controller/inspectorInspectionListbyID?id=1&userID=13
	
	/*
	*	FUNCTION : inspectorInspectionListbyID_get
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/inspectorInspectionListbyID?id=1&userID=13
	* 	URL: http://karam.in/kare_demo/api_controller/inspectorInspectionListbyID?id=1&userID=5
	*	URL: http://karam.in/kare/api_controller/inspectorInspectionListbyID?id=1&userID=5
	*/
	function inspectorInspectionListbyID_get(){
		$id 		= $_REQUEST['id'];
		$userID 	= $_REQUEST['userID'];
		
		$data = $this->_get_inspection_data($id,$userID);
		$this->load->view('admin_inspection/inspection_details_mobile', $data);
	}
	
	function _get_inspection_data($id,$userID){
		$this->load->model('Inspector_inspection_model');
		$this->data['userID'] 	=	$userID;
		$this->data['inspection_values'] 	= $result1 =  $this->Inspector_inspection_model->get_inspectionData_for_inspector('',$id);
		/* get Client Name from Master data according to Job Card and SMS. */
		$this->data['master_values'] 	= $result = $this->Inspector_inspection_model->get_client_masterData_details($result1['job_card'],$result1['sms']);
		$this->data['client_address'] 	= $this->Inspector_inspection_model->get_client_address($result1['siteID_id']);
		$this->data['inspection_data']	= $this->Inspector_inspection_model->get_inspectionDetails_for_admin($id,$result1['job_card'],$result1['sms'],$result1['asset_series']);
		
		return $this->data;
	}
	
	
	
	/* To get all action proposed on behalf of observations
		http://192.168.1.3/Mysites/karam/kare/api_controller/actionProposedAll
		http://karam.in/kare_demo/api_controller/actionProposedAll
	*/
	function actionProposedAll_old_get(){
		$result = $this->getAllActionProposed();
		$this->response($result,200);
	}
	
	function actionProposedAll_get(){
		$result = $this->_getAllActionProposed();
		$this->response($result,200);
	}
	

	function getAllActionProposed(){
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
	
	
	// Client Login API
		//http://localhost/karam/kare/api_controller/clientDashboard?userID=13&userGroupID=8
		//http://192.168.1.3/Mysites/karam/kare/api_controller/clientDashboard?userID=13&userGroupID=8
		//http://karam.in/kare_demo/api_controller/clientDashboard?userID=13&userGroupID=8
	function clientDashboard_old_get(){
		$userID = $_REQUEST['userID'];
		$groupID = $_REQUEST['userGroupID'];
		if($groupID=='8'){
			$this->load->model('Assign_client_model');
			$client_data = $this->Assign_client_model->get_client_data_by_user($userID);
			if(is_array($client_data)){
				$result = $client_data;
			}else{
				$result = array('error'=>'No Client Assigned to You. Please Contact Admin');
			}
			$results = (object)$result;
		}else{
			$result = array('error'=>'You are Not Authorised to Access This Area. ');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	function clientDashboard_get(){
		$userID = $_REQUEST['userID'];
		$groupID = $_REQUEST['userGroupID'];
		if($groupID=='8'){
			$this->load->model('Assign_client_model');
			$client_data = $this->Assign_client_model->get_client_data_by_user($userID);
			if(is_array($client_data)){
				$result = $client_data;
			}else{
				$result = array('error'=>'No Client Assigned to You. Please Contact Admin');
			}
			$results = (object)$result;
		}else{
			$result = array('error'=>'You are Not Authorised to Access This Area. ');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	
	// http://192.168.1.3/Mysites/karam/kare/api_controller/kdCategory
		//http://karam.in/kare_demo/api_controller/kdCategory
	
	function kdCategory_old_get(){
		$this->load->model('ProductCategory_model');
		$category = $this->ProductCategory_model->get_all_categories();
		
		if($category){
			foreach($category as $key=>$val){
				if($val['cat_image']!=''){
					$category[$key]['cat_image'] = str_replace('FCPATH',base_url(),$val['cat_image']);
				}
			}
			
			$result = array('success'=>$category);
			$results = (object)$result;
		}else{
			// Error  KD-0001: No Category Found in table( manage_categories ) !! 
			$result = array('error'=>'KD-0001');
			$results = (object)$result;
		}
		$this->response($results,200);
		
	}
	
	function kdCategory_get(){
		
		$this->load->model('ProductCategory_model');
		$category = $this->ProductCategory_model->get_all_categories();
		
		if($category){
			foreach($category as $key=>$val){
				if($val['cat_image']!='' || ($val['cat_image'] != NULL)){
					//$category[$key]['cat_image'] = str_replace('FCPATH',knowledgetree_url(),$val['cat_image']);
					$url = 'http://arresto.in/knowledgetree/';
					$category[$key]['cat_image'] = str_replace('FCPATH',$url,$val['cat_image']);
				}else{
					$category[$key]['cat_image'] = '';
				}
				$category[$key]['cat_parentname'] = ($val['cat_parentname'] != null)?$val['cat_parentname']:'';
			}
			
			$result = array('success'=>$category);
			$results = (object)$result;
		}else{
			// Error  KD-0001: No Category Found in table( manage_categories ) !! 
			$result = array('error'=>'KD-0001');
			$results = (object)$result;
		}
		$this->response($results,200);
		
	}
	
	
	/*
	*	FUNCTION : itemsInCategory_get
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/itemsInCategory?catID=2&parentID=1
	* 	URL: http://karam.in/kare_demo/api_controller/itemsInCategory?catID=2&parentID=1
	*	URL: http://karam.in/kare/api_controller/itemsInCategory?catID=2&parentID=1
	*/
	function itemsInCategory_get(){
		
		$catID 		= $_REQUEST['catID'];
		$parentID 	= $_REQUEST['parentID'];
		
		$this->load->model('ProductCategory_model');
		$result = $this->ProductCategory_model->get_all_products($catID, $parentID);
		if($result){
			$assets	 		= json_decode($result['assets'],true);
			$sub_assets 	= json_decode($result['sub_assets'],true);
			$assets_series 	= json_decode($result['assets_series'],true);
			if(!empty($assets)){
				$aseet_arr = array();
				foreach($assets as $aVal){
					$tableName = 'components';
					$this->load->model('Specification_model');
					$res = $this->Specification_model->get_all_values($aVal, $tableName);
					$image = (empty($res['imagePath']) || $res['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$res['imagePath']) ;
					$asset_arr[] = array('name'=>$aVal, 'url'=>$image);
				}
			}
			
			if(!empty($assets_series)){
				$aseet_sarr = array();
				foreach($assets_series as $asVal){
					$tableName = 'products';
					$this->load->model('Specification_model');
					$res = $this->Specification_model->get_all_values($asVal, $tableName);
					$image = (empty($res['imagePath']) || $res['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$res['imagePath']) ;
					$asset_sarr[] = array('name'=>$asVal, 'url'=>$image);
				}
			}
			
			$result['assets']	 		= $asset_arr;
			$result['sub_assets'] 		= json_decode($result['sub_assets'],true);
			$result['assets_series'] 	= $asset_sarr;
			$results = (object)$result;
		}else{
			// Error  KD-0002: No Assets, Sub-assets, Asset Series data Found in table( manage_products ) !! 
			$result = array('error'=>'KD-0002');
			$results = (object)$result;
		}
		$this->response($results,200);
	}	
		
	function itemsInCategory_old_get(){
		$catID 		= $_REQUEST['catID'];
		$parentID 	= $_REQUEST['parentID'];
		
		$this->load->model('ProductCategory_model');
		$result = $this->ProductCategory_model->get_all_products($catID, $parentID);
		if($result){
			$assets	 		= json_decode($result['assets'],true);
			$sub_assets 	= json_decode($result['sub_assets'],true);
			$assets_series 	= json_decode($result['assets_series'],true);
			if(!empty($assets)){
				$aseet_arr = array();
				foreach($assets as $aVal){
					$tableName = 'components';
					$this->load->model('Specification_model');
					$res = $this->Specification_model->get_all_values($aVal, $tableName);
					$image = (empty($res['imagePath']) || $res['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$res['imagePath']) ;
					$asset_arr[] = array('name'=>$aVal, 'url'=>$image);
				}
			}
			
			if(!empty($assets_series)){
				$aseet_sarr = array();
				foreach($assets_series as $asVal){
					$tableName = 'products';
					$this->load->model('Specification_model');
					$res = $this->Specification_model->get_all_values($asVal, $tableName);
					$image = (empty($res['imagePath']) || $res['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$res['imagePath']) ;
					$asset_sarr[] = array('name'=>$asVal, 'url'=>$image);
				}
			}
			
			$result['assets']	 		= $asset_arr;
			$result['sub_assets'] 		= json_decode($result['sub_assets'],true);
			$result['assets_series'] 	= $asset_sarr;
			$results = (object)$result;
		}else{
			// Error  KD-0002: No Assets, Sub-assets, Asset Series data Found in table( manage_products ) !! 
			$result = array('error'=>'KD-0002');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	// http://localhost/karam/kare/api_controller/assetValues?type=assets&typeCode=ACC-106-008A
	// http://192.168.1.3/Mysites/karam/kare/api_controller/assetValues?type=assets&typeCode=ACC-106-008A
		//http://karam.in/kare_demo/api_controller/assetValues?type=assets&typeCode=ACC-106-008A
	function assetValues_old_get(){
		$type 		= $_REQUEST['type'];
		$typeCode 	= $_REQUEST['typeCode'];
		
		if($type == 'assets'){
			$tableName = 'components';
		}else if($type == 'sub_assets'){
			$tableName = 'sub_assets';
		}else if($type == 'asset_series'){
			$tableName = 'products';
		}
		
		
		$this->load->model('Specification_model');
		$result = $this->Specification_model->get_all_values($typeCode, $tableName);
		if($result){
			$result['imagePath'] = (empty($result['imagePath']) || $result['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$result['imagePath']) ;
			$result['file']	= json_decode($result['file'],true);
			$results = (object)$result;
		}else{
			// Error  KD-0002: No Assets, Sub-assets, Asset Series data Found in table( manage_products ) !! 
			$result = array('error'=>'KD-0002');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	function assetValues_get(){
		
		$type 		= $_REQUEST['type'];
		$typeCode 	= $_REQUEST['typeCode'];
		
		if($type == 'assets'){
			$tableName = 'components';
		}else if($type == 'sub_assets'){
			$tableName = 'sub_assets';
		}else if($type == 'asset_series'){
			$tableName = 'products';
		}
		
		$this->load->model('Specification_model');
		$result = $this->Specification_model->get_all_values($typeCode, $tableName);
		if($result){
			$result['imagePath'] = (empty($result['imagePath']) || $result['imagePath']==null)? null : str_replace('FCPATH/', base_url(),$result['imagePath']) ;
			$result['file']	= json_decode($result['file'],true);
			$results = (object)$result;
		}else{
			// Error  KD-0003: No Assets, Sub-assets, Asset Series data Found in table( manage_subassets, manage_components, manage_products ) !! 
			$result = array('error'=>'KD-0003');
			$results = (object)$result;
		}
		$this->response($results,200);
	}
	
	 /*
	*	FUNCTION : clientdata
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/clientdata
	*	URL: http://karam.in/kare_demo/api_controller/clientdata
	*	URL: http://karam.in/kare/api_controller/clientdata
	*/	
	function clientdata_get(){
		
		$this->load->model('Client_model');
		$res = $this->Client_model->client_view('api');
		if($res){
			$this->response($res,200);
		}else{
			//Error  CL-0001: No Client Data Found in table( clients ) !! 
			$error = array('error'=>'CL-0001'); 
			$this->response($error,200);
		}
	}
	
	/*
	*	FUNCTION : manual_insdata_post
	* 	URL: http://192.168.1.3/Mysites/karam/kare/api_controller/manual_insdata
	*	URL: http://karam.in/kare_demo/api_controller/manual_insdata
	*	URL: http://karam.in/kare/api_controller/manual_insdata
	*/
	function manual_insdata_post(){
			$data = array(
				'mins_name' 			=> $_POST['cus_name'],
				'mins_address' 			=> $_POST['cus_add'],
				'mins_contactNo' 		=> $_POST['cus_phone'],
				'mins_email' 			=> $_POST['cus_email'],
				'mins_location' 		=> $_POST['cus_location'],
				'mins_input_type'		=> $_POST['cus_input_type'],
				'mins_input_value'		=> $_POST['cus_input_value'],
				'mins_asset_series' 	=> (!empty($_POST['assetSeries']))? $_POST['assetSeries'] : '',
				'mins_assets' 			=> (!empty($_POST['asset']))? $_POST['asset'] : '',
				'mins_insID' 			=> $_POST['user_id'],
				'mins_status' 			=> 'Pending'
			);
			
			$tableName = 'manual_inspector_data';
			$this->load->model('Common_model');
			$res = $this->Common_model->insert_table_data($data, $tableName);
			if($res){
				$result = array('success'=>'Information Saved Succesfully');
				$this->response($result,200);
			}else{
				//Error  INS-0002: Error In saving Inspector Manula Data in table ( manual_inspector_data )  !! 
				$error = array('error'=>'INS-0001'); 
				$this->response($error,200);
			}
	}
	
	
	function logs_event_get(){
		if(!empty($_REQUEST['userID']) && !empty($_REQUEST['eventType'])){
			$logData = array(
				'user_id' => $_REQUEST['userID'],
				'process' => $_REQUEST['eventType'],
				'ip_address'=>$this->_idAddress($_SERVER['REMOTE_ADDR']),
				'time' => date('h:i:s a', time()),
			);
			$this->load->model("Kare_model");
			$logResult = $this->Kare_model->store_logs($logData);
			if($logResult > 0){
				$error[] = array("error"=>"successfully Logs data.");
				$this->response($error,200);
			}else{
				$error[] = array("error"=>"pls. try again.");
				$this->response($error,200);
			}
		}else{
			$error[] = array("error"=>"Parameter Error");
			$this->response($error,200);
		}
	}
	
	function manage_inspector_get(){
		if(!empty($_REQUEST['userID']) && !empty($_REQUEST['group_id'])){
			$temp = array();
			$this->load->model('Inspector_inspection_model');
			if($_REQUEST['group_id'] == 9){
				$assign_inspector_data =  $this->Inspector_inspection_model->get_assign_inspector_data($_REQUEST['group_id']);
				foreach($assign_inspector_data as $key => $value){
					$inspectorids =  json_decode($value['client_ids'],true);
					$inspectorid[$value['site_id']] = $inspectorids[0];
				}
			}else{
				$inspector_data =  $this->Inspector_inspection_model->get_inspector_data();
				foreach($inspector_data as $key => $value){
					$inspectorids =  json_decode($value['inspector_ids'],true);
					$inspectorid[$value['site_id']] = $inspectorids[0];
				}
			}
			
			foreach($inspectorid as $key =>$value){
				if($_REQUEST['userID'] == $value){
					$siteID[] = json_decode($key,true);
					 if(!empty($siteID[0]) && is_array($siteID)){
						$siteid_marge = $siteID[0];
						$count = count($siteID)-1;
						for($i=1;$i<=$count;$i++){
							$siteid_marge = array_merge($siteid_marge,$siteID[$i]);
						}
					 }
				}
			}
			
			$siteid_marge = explode('/',implode('/',array_unique($siteid_marge)));
			$this->load->model('Siteid_model');
			$this->load->model('Form_model');
			$this->load->model('Assign_client_model');
			$site_data = $this->Assign_client_model->get_site_data($siteid_marge);
			if(!empty($site_data[0])){
				$this->load->model('Api_model');
				$totalComponents 		= $this->Api_model->get_totalCount('components');
				$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
				$totalActionProposed 	= $this->_getAllActionProposed();
				$countAction 			= count($totalActionProposed);
				foreach($site_data as $key => $value){
					$temp[$key] = $value;
					

					$client_res = $this->Siteid_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
					$temp[$key]['totalAsset'] 			= 	"$totalComponents";
					$temp[$key]['totalSubAsset']			=	"$totalsubComponent";
					$temp[$key]['totalAction_proposed']	=	"$countAction";
					if(is_object($client_res)){
							$clientid = $client_res->mdata_client;
							$clientName = $client_res->client_name;
							$temp[$key]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
					}



					/*
					$reportNo = $this->Form_model->check_report_numbers($value['siteID_id']);
					if(!is_array($reportNo)){
							$temp[$key]['report_no'] = '';
							$temp[$key]['inspected_status'] = 'No';
							$temp[$key]['approved_status'] = 'Pending';
					}else{
							$temp[$key]['report_no'] 			= $reportNo['report_no'];
							$temp[$key]['inspected_status'] 	= $reportNo['inspected_status'];
							$temp[$key]['approved_status'] 	= $reportNo['approved_status'];
					}
					*/

					/// Start care fully
					if( !empty($value['siteID_id'])){
						$reportNo = $this->Form_model->check_report_numbers($value['siteID_id']);
						if(!is_array($reportNo)){
							$temp[$key]['report_no']			= '';
							$temp[$key]['inspected_status']	= 'No';
							$temp[$key]['approved_status']	= 'Pending';
						}else{
							$temp[$key]['report_no'] 			= $reportNo['report_no'];
							$temp[$key]['inspected_status'] 	= $reportNo['inspected_status'];
							$temp[$key]['approved_status'] 	= $reportNo['approved_status'];
						}					
					}else if( !empty($value['mdata_item_series'])){	
							$mdata_item_seriesArry = json_decode($value['mdata_item_series'], true) ; 						
							foreach($mdata_item_seriesArry AS $key1 => $val1){
								$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
								if(!is_array($reportasset_seriesno)){
									$temp[$key]['report_no']			= '';
									$temp[$key]['inspected_status']	= 'No';
									$temp[$key]['approved_status']	= 'Pending';
								}else{
									$temp[$key]['report_no'] 			= $reportasset_seriesno['report_no'];
									$temp[$key]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
									$temp[$key]['approved_status'] 	= $reportasset_seriesno['approved_status'];
								}
							
							}
					}else{
						$temp[$key]['report_no']		= '';
						$temp[$key]['inspected_status']	= 'No';
						$temp[$key]['approved_status']	= 'Pending';				
					}
					////  End care fully 
					
					//$temp[$key]['assign_client'] = $this->Assign_client_model->get_site_list($value['siteID_id']);
					$assign_client = $this->Assign_client_model->get_site_list($value['siteID_id']);
					
					if($assign_client['report_no'] == $reportNo['report_no']){
						$temp[$key]['asset_series'] = $assign_client['asset_series'];
						$temp[$key]['assgin_id'] = $assign_client['id'];
						$inspecte_name = $this->Inspector_inspection_model->get_inspecte_name($assign_client['inspected_by']);
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
		
			//$inspector_list =  $this->Inspector_inspection_model->get_inspector_list($siteid_marge);
			$temp1=array();
			if(!empty($temp[0])){
				foreach($temp as $key => $value){
					$temp1[$key]['id'] = !empty($value['assgin_id'])?$value['assgin_id']:'';
					$temp1[$key]['report_no'] = !empty($value['report_no'])?$value['report_no']:'';
					$temp1[$key]['site_id'] = !empty($value['site_id'])?$value['site_id']:'';
					$temp1[$key]['job_card'] = !empty($value['site_jobcard'])?$value['site_jobcard']:'';
					$temp1[$key]['sms'] = !empty($value['site_sms'])?$value['site_sms']:'';
					$temp1[$key]['asset_series'] = !empty($value['asset_series'])?$value['asset_series']:'';
					$temp1[$key]['approved_status'] = !empty($value['approved_status'])?$value['approved_status']:'';
					$temp1[$key]['upro_first_name'] = !empty($value['upro_first_name'])?$value['upro_first_name']:'';
					$temp1[$key]['upro_last_name'] = !empty($value['upro_last_name'])?$value['upro_last_name']:'';
					
				}
			}else{
				$result = array('success'=>'No Data Found');
				$temp1 = (object)$result;
			}
			
		}else{
			$result = array('success'=>'Parameter Missing');
			$temp1 = (object)$result;
		}
		
		$this->response($temp1,200);
	}
	
	
	/* 
		/////////////////////////////// Error Codes For The App ///////////////////////////////////////
		
		// Error 	0001 : "No Site ID is assign to you";
		// Error 	0002 : "Site IDs assigned to the Inspector are not present in siteID_data table.";
		// Error 	0003 : Error in Inserting Work Permit Data in function insert_work_permit.
		// Error 	0004 : Error in Inserting Data In First Table Before Work Permit Data.
		// Error 	0005 : Data Not Found In Temp Table 1 According to provided Site Id and Today's date.
		// Error 	0006 : Error In Updating Data of Inspection Form 1(RFID/UIN) according to Returned ID.
		// Error  	0007 : Did not get Map Image to Upload. Image Uploading Failed.
		// Error  	0008 : Error In Uploading Map Image. File Not Upload.
		// Error  	0009 : Did not get signImage to Upload. Image Uploading Failed.
		// Error  	0010 : Error In Uploading Signature Image. File Not Upload.
		// Error  	0011 : Did not get after_file to Upload. Fail !!
		// Error  	0012 : Asset Images File Not Upload.
		// Error 	00011, 00012 : Image File Not Uploaded.
		// Error 	0013 : Error In Inserting Data in function second_slot_data_inserted_api.
		// Error 	0014 : Error In Updating data of Inspector Form in function third_slot_data_insert
		// Error  	0015 : Error in In Submitting Client Form in function fourth_slot_data_insert
		// Error  	0016 : Did not get Inspector Image to Upload. Image Uploading Failed. in Work Permit Form
		// Error  	0017 : Error In Uploading Inspector Image. File Not Upload.


		// Error  	KD-0001: No Category Found in table( manage_categories ) !! 
		// Error  	KD-0002: No Assets, Sub-assets, Asset Series data Found in table( manage_products ) !! 
		// Error 	KD-0003: No Assets, Sub-assets, Asset Series data Found in table( manage_subassets, manage_components, manage_products ) !! 
		
		// Error  	CL-0001: No Client Data Found in table( clients ) !! 
		
		// Error  	INS-0001: Error In saving Inspector Manula Data in table ( manual_inspector_data )  !! 
		
	*/

	public function periodic_maintenance_get() {
		if(empty($_GET['data'])) {
			$response = array(
				'msg_code'		=> 404,
				'msg'			=> 'Fields cannot be blank',
				'data'			=> []
			);
		} else {
			$this->load->model('api_model');
			$master_data = $this->api_model->get_data('*', 'mdata_uin', $_GET['data'], 'master_data', 'mdata_barcode', $_GET['data'], 'mdata_rfid', $_GET['data']);
			if(!empty($master_data)) {
				if(!empty($master_data[0]['mdata_asset'])) {
					$mdata_asset = json_decode($master_data[0]['mdata_asset']);
					$i = 0;
					foreach($mdata_asset as $value_mdata_asset) {
						$asset = $this->api_model->get_data('*', 'component_code', $value_mdata_asset, 'components');
						$master_data[0]['asset'][$i] = $asset[0];
						if(!empty($asset[0]['component_inspectiontype'])) {
							$component_inspectiontype = $this->api_model->get_and_data('*', 'id', $asset[0]['component_inspectiontype'], 'type_category', 'status', 1);
							$master_data[0]['asset'][$i]['component_inspectiontype'] = $component_inspectiontype;
						}
						$master_data[0]['asset'][$i]['component_expectedresult'] = array();
						if(!empty($asset[0]['component_expectedresult'])) {
							$component_expected_result = json_decode($asset[0]['component_expectedresult']);
							$j = 0;
							foreach ($component_expected_result as $value_component_expected_result) {
								$component_expected_result_val = $this->api_model->get_and_data('*', 'id', $value_component_expected_result, 'type_category', 'status', 1);
								$master_data[0]['asset'][$i]['component_expectedresult'][$j] = $component_expected_result_val[0];
								$j++;
							}
						}
						$master_data[0]['asset'][$i]['component_observation'] = array();
						if(!empty($asset[0]['component_observation'])) {
							$component_observation = json_decode($asset[0]['component_observation']);
							$j = 0;
							foreach ($component_observation as $value_component_observation) {
								$component_observation_val = $this->api_model->get_and_data('*', 'id', $value_component_observation, 'type_category', 'status', 1);
								$master_data[0]['asset'][$i]['component_observation'][$j] = $component_observation_val[0];
								$j++;
							}
						}
						$i++;
					}
					$response = array(
						'msg_code'		=> 200,
						'msg'			=> 'Asset available',
						'data'			=> $master_data
					);
				} elseif(!empty($master_data[0]['mdata_item_series'])) {
					$mdata_asset_series = json_decode($master_data[0]['mdata_item_series']);
					$i = 0;
					foreach($mdata_asset_series as $value_mdata_asset_series) {
						$asset_series = $this->api_model->get_data('*', 'product_code', $value_mdata_asset_series, 'products');
						$master_data[0]['asset_series_data'][$i] = $asset_series[0];
						$i++;
					}
					$response = array(
						'msg_code'		=> 200,
						'msg'			=> 'Asset Series available',
						'data'			=> $master_data
					);
				} else {
					$response = array(
						'msg_code'		=> 200,
						'msg'			=> 'Asset or Asset Series not available',
						'data'			=> $master_data
					);
				}
			} else {
				$response = array(
					'msg_code'		=> 404,
					'msg'			=> 'No data found',
					'data'			=> []
				);
			}
		}
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'data'=>$response['data']));
	}

	function searchRegisterDataUserId_06_07_2018_get(){
		$temp = array();
		$temp['user_id']		= !empty($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
		
		$this->load->model('m_api_model');
		$this->load->model('api_model');
		$regData = $this->m_api_model->get_register_user_data($temp['user_id']);
	     // echo "<pre>"; print_r($regData); echo "</pre>"; 
		if(!empty($regData) && is_array($regData)){
			$this->load->model('Form_model');
			$count = 0;
			foreach($regData as $sVal){	
					$siteData[$count] = $this->m_api_model->get_data_register($sVal);
					// print_r($sdata);die('   test ');
				if(!empty($siteData[$count]['master_id']) && is_array($siteData)){
						$this->load->model('Api_model');
						$totalComponents 		= $this->Api_model->get_totalCount('components');
						$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
						$totalActionProposed 	= $this->_getAllActionProposed();
						$countAction 			= count($totalActionProposed);
						$this->load->model('Siteid_model');
						$this->load->model('Form_model');
						$sdata = $siteData;
						
						foreach($sdata as $sKey=>$sVal){	
									$client_res = $this->api_model->get_data('client_name', 'client_id', $sdata[$sKey]['mdata_client'], 'clients');
									$sdata[$sKey]['client_name']=$client_res[0]['client_name'];
									$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
									$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
									$sdata[$sKey]['totalAction_proposed']	=	"$countAction";
									
									/*
									$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
									if(!is_array($reportNo)){
										$sdata[$sKey]['report_no'] = '';
										$sdata[$sKey]['inspected_status'] = 'No';
										$sdata[$sKey]['approved_status'] = 'Pending';
									}else{
										$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
										$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
										$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
									}
									*/
									
									/// Start care fully
									if( !empty($sVal['siteID_id'])){
										$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
										if(!is_array($reportNo)){
											$sdata[$sKey]['report_no']			= '';
											$sdata[$sKey]['inspected_status']	= 'No';
											$sdata[$sKey]['approved_status']	= 'Pending';
										}else{
											$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
											$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
											$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
										}					
									}else if( !empty($sVal['mdata_item_series'])){	
											$mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
											foreach($mdata_item_seriesArry AS $key1 => $val1){
												$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
												if(!is_array($reportasset_seriesno)){
													$sdata[$sKey]['report_no']			= '';
													$sdata[$sKey]['inspected_status']	= 'No';
													$sdata[$sKey]['approved_status']	= 'Pending';
												}else{
													$sdata[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
													$sdata[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
													$sdata[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
												}
											
											}
									}else{
										$sdata[$sKey]['report_no']			= '';
										$sdata[$sKey]['inspected_status']	= 'No';
										$sdata[$sKey]['approved_status']	= 'Pending';				
									}
									////  End care fully 

										$work_number = '';
										$work_no = $this->Form_model->check_work_permit_number($sVal['siteID_id']);
										if(!$work_no){
											$work_number = 'WORK-000001';
										}else{
											$workNo_array 	= explode('-',$work_no['workPermit_number']);
											$newWorkNo		= $workNo_array[1] + 1;
											$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
										}
										$sdata[$sKey]['workPermit_number'] 	= trim($work_number);				
								}
					}
			$count++;
			}
		}else{			
			$sdata = array('0'=>array('error' =>"No data found !"));
		} 	
		$this->response($sdata,200);
	}

	function searchRegisterDataUserId_get(){
		$temp = array();
		$temp['user_id']    = !empty($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
		$temp['filter']		= !empty($_REQUEST['filter'])?$_REQUEST['filter']:'';
		
		$this->load->model('m_api_model');
		$this->load->model('api_model');
		$regData = $this->m_api_model->get_register_user_data($temp['user_id']);

		

		if(!empty($regData) && is_array($regData)){
			$this->load->model('Form_model');
			$this->load->model('kare_model');
			$toDateTimeStamp = strtotime(date("Y-m-d"));
			$count = 0;
			$regDataCount		= 0;
			$wrgInsDateCount	= 0;
			$InsDueCount		= 0;
			$InsOverDueCount	= 0;
			foreach($regData as $sVal){	 
                    $scheduled_date = $sVal['scheduled_date'];
					$checkMasterData = $this->kare_model->get_mdata_item($sVal['master_data_id']);
					$date_of_inspection = $checkMasterData['date_of_inspection'];
					
					if( ( $temp['filter'] != '' ) && ($date_of_inspection != '' ) ){ 
						$inspectionpDateTimeStamp = strtotime($date_of_inspection);
						if( ( $temp['filter'] == 'due' )){
							if($toDateTimeStamp <= $inspectionpDateTimeStamp){
							$diffAfterTodate = ( $inspectionpDateTimeStamp - $toDateTimeStamp )/24/3600; 
								if( $diffAfterTodate > 30 ){
									$InsDueCount++;									
									$resultArray	  = $this->m_api_model->get_data_register($sVal);
									if($resultArray){
									$siteData[$count] = $resultArray;									
										$siteDataArray = $this->_get_register_data_user_id($siteData[$count],$scheduled_date);
										if( isset($siteDataArray) && ( is_array($siteDataArray) ) ){
											$sdata[$count]  = $siteDataArray[0];
											$count++;
										}
									}
								}
							}
						} else if( ( $temp['filter'] == 'over' )){  
							if($toDateTimeStamp > $inspectionpDateTimeStamp){
								$diffBeforeTodate = ( $toDateTimeStamp - $inspectionpDateTimeStamp )/24/3600; 
								$InsOverDueCount++;
								$resultArray	  = $this->m_api_model->get_data_register($sVal);
								if($resultArray){
								$siteData[$count] = $resultArray;				
									$siteDataArray = $this->_get_register_data_user_id($siteData[$count], $scheduled_date);
									if( isset($siteDataArray) && ( is_array($siteDataArray) ) ){
										$sdata[$count]  = $siteDataArray[0];
										$count++;
									}
							   }
							}
						}else{
							$resultArray	  = $this->m_api_model->get_data_register($sVal);
							if($resultArray){
								$siteData[$count] = $resultArray;					
								$siteDataArray = $this->_get_register_data_user_id($siteData[$count], $scheduled_date);							
								if( isset($siteDataArray) && ( is_array($siteDataArray) ) ){
									$sdata[$count]  = $siteDataArray[0];
									$count++;
								}
							}
						}
					}else{	
							$resultArray	  = $this->m_api_model->get_data_register($sVal);
							if($resultArray){
								$siteData[$count] = $resultArray;								
								$siteDataArray = $this->_get_register_data_user_id($siteData[$count], $scheduled_date);							
								if( isset($siteDataArray) && ( is_array($siteDataArray) ) ){
									$sdata[$count]  = $siteDataArray[0];
									$count++;
								}
							}
							
					}	
			}

			if( !isset($sdata) ){
				$sdata = array('0'=>array('error' =>"No data found !"));
			}
		}else{			
			$sdata = array('0'=>array('error' =>"No data found !"));
		} 
		$this->response($sdata,200);
	}

	function _get_register_data_user_id( $siteDataArray = array(), $scheduled_date = '' ){ 
		if(!empty($siteDataArray) && is_array($siteDataArray)){
			$this->load->model('Api_model');
			$totalComponents 		= $this->Api_model->get_totalCount('components');
			$totalsubComponent 		= $this->Api_model->get_totalCount('sub_assets');
			$totalActionProposed 	= $this->_getAllActionProposed();
			$countAction 			= count($totalActionProposed);
			$this->load->model('Siteid_model');
			$this->load->model('Form_model');
			$sdata[0] = $siteDataArray;

			foreach($sdata as $sKey=>$sVal){	
			$client_res = $this->api_model->get_data('client_name', 'client_id', $sdata[$sKey]['mdata_client'], 'clients');
			$sdata[$sKey]['client_name']=$client_res[0]['client_name'];
			$sdata[$sKey]['totalAsset'] 			= 	"$totalComponents";
			$sdata[$sKey]['totalSubAsset']			=	"$totalsubComponent";
			$sdata[$sKey]['totalAction_proposed']	=	"$countAction";

			if( !empty($sVal['siteID_id'])){
				$reportNo = $this->Form_model->check_report_numbers($sVal['siteID_id']);
				if(!is_array($reportNo)){
					$sdata[$sKey]['report_no']			= '';
					$sdata[$sKey]['inspected_status']	= 'No';
					$sdata[$sKey]['approved_status']	= 'Pending';
				}else{
					$sdata[$sKey]['report_no'] 			= $reportNo['report_no'];
					$sdata[$sKey]['inspected_status'] 	= $reportNo['inspected_status'];
					$sdata[$sKey]['approved_status'] 	= $reportNo['approved_status'];
				}					
			}else if( !empty($sVal['mdata_item_series'])){	
					$mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ; 						
					foreach($mdata_item_seriesArry AS $key1 => $val1){
						$reportasset_seriesno = $this->Form_model->check_report_asset_seriesnumbers($val1);
						if(!is_array($reportasset_seriesno)){
							$sdata[$sKey]['report_no']			= '';
							$sdata[$sKey]['inspected_status']	= 'No';
							$sdata[$sKey]['approved_status']	= 'Pending';
						}else{
							$sdata[$sKey]['report_no'] 			= $reportasset_seriesno['report_no'];
							$sdata[$sKey]['inspected_status'] 	= $reportasset_seriesno['inspected_status'];
							$sdata[$sKey]['approved_status'] 	= $reportasset_seriesno['approved_status'];
						}
					
					}
			}else{
				$sdata[$sKey]['report_no']			= '';
				$sdata[$sKey]['inspected_status']	= 'No';
				$sdata[$sKey]['approved_status']	= 'Pending';				
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
				$sdata[$sKey]['workPermit_number'] 	= trim($work_number);
				$sdata[$sKey]['scheduled_date'] 	= ($scheduled_date !='')? $scheduled_date:'0000-00-00' ;	
			}
			
			if( $sdata ){
				return $sdata;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function registerSite_post(){   
		if( !empty($_REQUEST['user_id']) && !empty($_REQUEST['master_data_id'])){	 
				$temp = array();
				$temp['user_id']		= !empty($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
				$temp['site_id']		= !empty($_REQUEST['site_id'])?$_REQUEST['site_id']:'';
				$temp['master_data_id'] = !empty($_REQUEST['master_data_id'])?$_REQUEST['master_data_id']:'';
				$this->load->model('m_api_model');
				//$register_data_count = $this->m_api_model->get_register_data_rows($temp);
				$register_data_count = $this->m_api_model->get_chk_register_data_rows($temp);
				if($register_data_count  ){  
					$response = array(
						'msg_code'		=> 404,
						'msg'			=> 'This data is already registered, please contact to admin.',
						'data'			=> []);
				}else{  
					$data = array();
					$data['user_id']		= $temp['user_id'];
					$data['site_id']		= $temp['site_id'];
					$data['master_data_id']	= $temp['master_data_id'];								
					$this->load->model('Api_model');	
					$regResponce 		= $this->Api_model->register_data_insert($data);
					if($regResponce > 0){  
						$response = array(
						'msg_code'		=> 200,
						'msg'			=> 'Register successfully',
						'data'			=> []);						
					}else
					{  $response = array(
						'msg_code'		=> 404,
						'msg'			=> 'Could not save, Try again!',
						'data'			=> []);
					}					
			  }
		}else{	
			$response = array(
			'msg_code'		=> 404,
			'msg'			=> 'There is no proper data to feed!',
			'data'			=> []);
			
		} 		
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));		
	}


	function deleteRegisteredSite_get(){   
		if( !empty($_REQUEST['user_id']) && !empty($_REQUEST['master_data_id'])){	 
				$temp = array();
				$temp['user_id']		= !empty($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
				$temp['site_id']		= !empty($_REQUEST['site_id'])?$_REQUEST['site_id']:'';
				$temp['master_data_id'] = !empty($_REQUEST['master_data_id'])?$_REQUEST['master_data_id']:'';
				$this->load->model('m_api_model');			
				$register_data_count = $this->m_api_model->get_register_data_rows($temp);			
				if($register_data_count  ){   
					if($this->m_api_model->del_register_data($temp)){ 						
						$response = array(
						'msg_code'		=> 200,
						'msg'			=> 'Delete successfully.',
						'data'			=> []);					 
					}else{  
						$response = array(
						'msg_code'		=> 404,
						'msg'			=> 'Could not delete, Try again!',
						'data'			=> []);						  	
					}
				}else{
					$response = array(
					'msg_code'		=> 404,
					'msg'			=> 'There is invalid parameter.',
					'data'			=> []);											
				}				
		}else{	
			$response = array(
			'msg_code'		=> 404,
			'msg'			=> 'There is no proper data to delete!',
			'data'			=> []);				
		} 		
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));		
	}

	function reportCertificateView_get(){
		$type 				= $_REQUEST['type'];
		$ref_id 			= $_REQUEST['ref_id'];
		$serial_id 			= $_REQUEST['serial_id'];
		$batch_no 			= $_REQUEST['batch_no'];
		$manufacture_date 	= $_REQUEST['manufacture_date'];		
		$data = $this->_get_report_certificate_data($type, $ref_id,$serial_id, $batch_no, $manufacture_date);
		$this->load->view('admin_inspection/report_certificate_view', $data);
		
	}

	function _get_report_certificate_data($type, $ref_id,$serial_id, $batch_no, $manufacture_date){
		$this->load->model('kare_model');
		$this->data['type'] 			= $type;
		$this->data['ref_id'] 			= $ref_id;
		$this->data['serial_id']		= $serial_id;
		$this->data['batch_no']			= $batch_no;
		$this->data['manufacture_date'] = $manufacture_date;
		if($type == 'assets' ){
			$temp['component_code']		  = $ref_id;
			$this->data['product_values'] = $result1 =  $this->kare_model->get_componentData_for_report($temp);	
			return $this->data;
		}else if( $type == 'asset_series' ){
			$temp['product_code']		  = $ref_id;
			$this->data['product_values'] = $result1 =  $this->kare_model->get_productData_for_report($temp);	
			return $this->data;		
		}
	}	

	function searchRegisterDataCountWithUserId_get(){
		$temp = array();
		$temp['user_id']		= !empty($_REQUEST['user_id'])?$_REQUEST['user_id']:'';	
		$temp['group_id']		= !empty($_REQUEST['group_id'])?$_REQUEST['group_id']:'';	
		$this->load->model('m_api_model');
		$this->load->model('api_model');		
		$regData = $this->m_api_model->get_register_user_data($temp['user_id']);      	   
		$records_data		= array();
		$regDataCount		= 0;
		$wrgInsDateCount	= 0;
		$InsDueCount		= 0;
		$InsOverDueCount	= 0;
		if(!empty($regData) && is_array($regData)){
			$this->load->model('kare_model');		
			$toDateTimeStamp = strtotime(date("Y-m-d"));
			foreach($regData as $sVal){	
				if( $sVal['master_data_id'] > 0 ){ 
					$checkMasterData = $this->kare_model->get_mdata_item($sVal['master_data_id']);
					
					$date_of_inspection = $checkMasterData['date_of_inspection'];
					if( ($date_of_inspection != '' ) ){ 
						$inspectionpDateTimeStamp = strtotime($date_of_inspection);
						if( $toDateTimeStamp < $inspectionpDateTimeStamp ){
							$diffAfterTodate = ( $inspectionpDateTimeStamp - $toDateTimeStamp )/24/3600; 
							if( $diffAfterTodate > 30 ){
							   $InsDueCount++;
							}
						} else if( $toDateTimeStamp == $inspectionpDateTimeStamp ){
							//
						} else if( $toDateTimeStamp > $inspectionpDateTimeStamp ){  
							$diffBeforeTodate = ( $toDateTimeStamp - $inspectionpDateTimeStamp )/24/3600; 
							$InsOverDueCount++;
						}
					}else{
						$wrgInsDateCount++;
					}
					$regDataCount++;
				}
			}	
			$records_data['product_data_count'] = $regDataCount;
			$records_data['inspection_due']		= $InsDueCount;
			$records_data['inspection_over_due']= $InsOverDueCount;

			$response = array(
				'msg_code'		=> 200,
				'msg'			=> 'Data found.',
				'data'			=> $records_data);	
		}else{			
			$response = array(
				'msg_code'		=> 404,
				'msg'			=> 'No data found !',
				'data'			=> []);											
		}
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'data'=>$response['data']));
	}



   function asset_assestseries_get($type=NULL, $id=NULL){
		
		$this->load->model('kare_model'); 
		if( ( ( $type == 'assets' ) || ( $type == 'asset_series')) && ( $id != '') ){
				if($type == 'assets' ){
					if($id==NULL){ 
						$components=$this->kare_model->get_components_list();
					}else{
						$components[]=$this->kare_model->get_component($id);
					}
						
						if(!empty($components[0])){
							foreach($components as $key=>$val){    
								$tempCompArray['product_code'] = (!empty($val['component_code']))? $val['component_code']:'';
								$tempCompArray['product_imagepath'] = !empty($val['component_imagepath'])?$val['component_imagepath']:'';
								$tempCompArray['product_description'] =  !empty($val['component_description'])?trim($val['component_description']):'';
								$tempCompArray['product_uom'] =  !empty($val['component_uom'])?trim($val['component_uom']):'';
								
								$request_id = $val['component_id'];
								$request_type = 'asset';
								$this->load->model('Subassets_model');
								if($val['component_inspectiontype']!=''){
									$component_inspectiontype=$this->Subassets_model->get_inspection_value($val['component_inspectiontype']);
									
									if($component_inspectiontype){
										
										$tempCompArray['product_inspectiontype'] = !empty($val['component_inspectiontype'])?$component_inspectiontype[$val['component_inspectiontype']]:'';
									}
								}
							
								if($val['component_expectedresult']!=''){
									$result_arrays = json_decode($val['component_expectedresult']);
									$result_array = array();
									foreach($result_arrays as $resKey=>$resVal){
										$result	=	$this->Subassets_model->get_inspection_value($resVal);
										if($result){
											$result_array[$resKey] = $result[$resVal];
										}else{
											$result_array[$resKey] = ''; 
										}
									}
									$linksArray = array_filter($result_array);
									$tempCompArray['product_expectedresult'] = implode('##',$linksArray);
								}
								if($val['component_observation']!=''){
									$obs_arrays = json_decode($val['component_observation']);
									$obs_array = array();
									foreach($obs_arrays as $obsKey=>$obsVal){
										$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
										
										if(!empty($resultObs)){
											$resId 		= $obsVal;
											$resValue 	= $resultObs[$obsVal];
											$obs_array[$obsKey] = $resValue; 
										}else{
											$obs_array[$obsKey] = ''; 
										}
									}
									$tempCompArray['product_observation'] = implode('##',array_filter($obs_array));
								}
								if($val['component_sub_assets'] != "" && $val['component_sub_assets'] != "0" && $val['component_sub_assets'] != '[""]'){
									$sub_component_array	=	json_decode($val['component_sub_assets'],true);
									if( is_array($sub_component_array)){  
										$tempSubAssetArray  = $this->_get_component_subassets_get( $sub_component_array );
										$subProductsArray	= $tempCompArray;
										$subProductsArray['data']= $tempSubAssetArray;
									}
								}else{
								   $subProductsArray	= $tempCompArray;
								   $subProductsArray['data']= array();
								}
							}
						}
						
						if(!empty( $subProductsArray )){
							$this->response( $subProductsArray, 200); 
						}
						else{
							$error = array("error"=>"No Asset Data Found");
							$this->response($error,200);
						}

				}else if($type == 'asset_series' ){  
					  unset($productsArray);
					  $productsArray = array();
					if($id==NULL){ 
						$products=$this->kare_model->get_products_list();
					}else{
						$products[]=$this->kare_model->get_product($id);
					}
					if(!empty($products[0])){
							foreach($products as $key=>$val){  
								$tempSeriesProduct['product_code']			= $val['product_code']; 
								$tempSeriesProduct['product_description']	= $val['product_description']; 
								$tempSeriesProduct['product_imagepath']		= $val['product_imagepath']; 
                                
								$this->load->model('Subassets_model');
								if($val['product_inspectiontype']!=''){
									$product_inspectiontype	=	$this->Subassets_model->get_inspection_value($val['product_inspectiontype']);	
									if($product_inspectiontype){ 
										$tempSeriesProduct['product_inspectiontype'] = !empty($val['product_inspectiontype'])?$product_inspectiontype[$val['product_inspectiontype']]:'';
									}
								}else{
									$tempSeriesProduct['product_inspectiontype'] = '';
								}
								
								$prod_compArray  = json_decode($val["product_components"], true);	
								
								$tempAssetArray  = $this->_get_asset_component_get( $prod_compArray ); 
							}
							if( !empty($tempAssetArray)){
								$tempSeriesProduct['data']= $tempAssetArray;
								$productsArray= $tempSeriesProduct;
							}else{
								$tempSeriesProduct['data']= array();
								$productsArray= $tempSeriesProduct;							
							}
							
					}
				  
					if(!empty( $productsArray )){
						$this->response( $productsArray, 200); /* 200 being the HTTP response code */
					}
					else{
						$error = array("error"=>"No Asset Data Found");
						$this->response($error,200);
					   
					}
				}
		}else{

			$error = array("error"=>"No Asset Data Found");
			$this->response($error,200);
		
		}

   }


	function _get_component_subassets_get( $subassestArray ){
	    $this->load->model('Subassets_model');  
		if(is_array($subassestArray)){   
		    $count	= 0; 			
			$baseURL= $this->config->item('base_url');
			foreach($subassestArray AS $key1){    
				$subcomponents = $this->Subassets_model->get_sub_assets($key1);  
				
				$tempSubAsset[$count]['component_code']			=  $key1;
				$tempSubAsset[$count]['component_description']	=  !empty($subcomponents['sub_assets_description'])?trim($subcomponents['sub_assets_description']):'';

				$sub_assets_imagepath   = !empty($subcomponents['sub_assets_imagepath'])?str_replace('FCPATH',base_url(),$subcomponents['sub_assets_imagepath']):'';
				if( $sub_assets_imagepath != ''){
						$pos = strpos($sub_assets_imagepath, $baseURL);
						if ($pos === false) {
						 $tempSubAsset[$count]['component_imagepath']   = $baseURL.$subcomponents['sub_assets_imagepath'];
						}
				}else{
					$tempSubAsset[$count]['component_imagepath']   = '';
				}
				
				if($subcomponents['sub_assets_inspection']!=''){
						  $component_inspectiontype=$this->Subassets_model->get_inspection_value($subcomponents['sub_assets_inspection']);
								if($component_inspectiontype){																	
									  $tempSubAsset[$count]['component_inspectiontype'] = !empty($subcomponents['sub_assets_inspection'])?$component_inspectiontype[$subcomponents['sub_assets_inspection']]:'';
								}
				}else{
					$tempSubAsset[$count]['component_inspectiontype'] =  '';
				}


				
				$tempSubAsset[$count]['component_uom']			  =  $subcomponents['sub_assets_uom'];

				if($subcomponents['sub_assets_result']!=''){
						$result_arrays = json_decode($subcomponents['sub_assets_result']);
						$result_array = array();
						foreach($result_arrays as $resKey=>$resVal){
							$result	=	$this->Subassets_model->get_inspection_value($resVal);
							if($result){
								$result_array[$resKey] = $result[$resVal];
							}else{
								$result_array[$resKey] = ''; 
							}
						}
						$linksArray = array_filter($result_array);
						$tempSubAsset[$count]['component_expectedresult'] = implode('##',$linksArray);
				}
				if($subcomponents['sub_assets_observation']!=''){
					$obs_arrays = json_decode($subcomponents['sub_assets_observation']);

					$obs_array = array();
					foreach($obs_arrays as $obsKey=>$obsVal){
						$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
						if(!empty($resultObs)){
							$resId 		= $obsVal;
							$resValue 	= $resultObs[$obsVal];
							$obs_array[$obsKey] = $resValue; 
						}else{
							$obs_array[$obsKey] = ''; 
						}
					}
					$tempSubAsset[$count]['component_observation'] = implode('##',array_filter($obs_array));
				}							
				$count++;
			}
			
			if( $tempSubAsset ){
				return $tempSubAsset;
			}else{
				return false;
			}			
		} else{
		 return false;
		}
	}

	function _get_asset_component_get( $componentArray ){
	    $this->load->model('kare_model');
		if(is_array($componentArray)){  
		$count=0;
			foreach($componentArray AS $key1){   
				$components = $this->kare_model->get_component($key1);
				if($components)
				{					
					$tempAsset[$count]['component_code']		=  (!empty( $components['component_code']))? $components['component_code']: ''; 
					$tempAsset[$count]['component_description']	=  (!empty( $components['component_description']))? $components['component_description']: '';  										
					$tempAsset[$count]['component_imagepath']	= !empty($components['component_imagepath'])?str_replace('FCPATH',base_url(),$components['component_imagepath']):'';
					$tempAsset[$count]['component_uom']			= (!empty( $components['component_uom']))? $components['component_uom']: '';
					$this->load->model('Subassets_model');
					if($components['component_inspectiontype']!=''){
							  $component_inspectiontype=$this->Subassets_model->get_inspection_value($components['component_inspectiontype']);
									if($component_inspectiontype){																	
										  $tempAsset[$count]['component_inspectiontype'] = !empty($components['component_inspectiontype'])?$component_inspectiontype[$components['component_inspectiontype']]:'';
									}
					}
					if($components['component_expectedresult']!=''){
							$result_arrays = json_decode($components['component_expectedresult']);
							$result_array = array();
							foreach($result_arrays as $resKey=>$resVal){
								$result	=	$this->Subassets_model->get_inspection_value($resVal);
								if($result){
									$result_array[$resKey] = $result[$resVal];
								}else{
									$result_array[$resKey] = ''; 
								}
							}
							$linksArray = array_filter($result_array);
							
							$tempAsset[$count]['component_expectedresult'] = implode('##',$linksArray);
					}
						
						if($components['component_observation']!=''){
							$obs_arrays = json_decode($components['component_observation']);

							$obs_array = array();
							foreach($obs_arrays as $obsKey=>$obsVal){
								$resultObs	=	$this->Subassets_model->get_inspection_value($obsVal);
								if(!empty($resultObs)){
									$resId 		= $obsVal;
									$resValue 	= $resultObs[$obsVal];
									$obs_array[$obsKey] = $resValue; 
								}else{
									$obs_array[$obsKey] = ''; 
								}
							}
							$tempAsset[$count]['component_observation'] = implode('##',array_filter($obs_array));
					}	
					$count++;
				}				
			}
			return $tempAsset;
		} else{
		 return false;
		}
	}


	function country_get(){				
		$this->load->model('kare_model');
		if(($_REQUEST['country_id']) ){
			$country_id  = $_REQUEST['country_id'];
			$filtArray  = array('id'=>$country_id);
			$zone_list = $this->kare_model->get_manage_country_filt_result_list($filtArray);
        }else{
			$zone_list = $this->kare_model->get_manage_country_filt_result_list();
		}	
		if($zone_list){
			$countryArray	= array();
			$count = 0;
			foreach($zone_list as $key=>$val){
				$id				= $val['id'];
				$abb			= $val['abb'];
				$name			= $val['name'];
				$json			= array(
					"id"        => $id,
					"abb"       => $abb,
					"name"      => $name						
				);
				$countryArray[$count] = $json;
				$count++;
			}
		}else{
			$response = array(
				'msg_code' 	=> 404,
				'msg'		=> 'There is no data'
			);			
		}
		if(isset( $countryArray )){
			$dataArray = array('country'=>$countryArray);
			$countryArray1  =	json_encode($dataArray);			
			header('Content-type: application/json;charset=utf-8'); 
			echo $countryArray1; exit;
		}else{
			$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
		}
	}

	function state_get(){
		if(!empty($_REQUEST['country_id']) ){
			$country_id	= $_REQUEST['country_id'];			
			$this->load->model('kare_model');			
			$filtArray  = array('country_id'=>$country_id);
			$zone_list = $this->kare_model->get_manage_zone_filt_result_list($filtArray);
			if($zone_list){
				$stateArray	= array();
				$count = 0;
				foreach($zone_list as $key=>$val){
					$id				= $val['id'];
					$name			= $val['name'];
					$json			= array(
						"id"        => $id,
						"name"      => $name						
					);
					$stateArray[$count] = $json;
					$count++;
				}
			}else{
				$response = array(
					'msg_code' 	=> 404,
					'msg'		=> 'There is no data'
				);			
			}
		}else{
			$response = array(
				'msg_code' 	=> 404,
				'msg'		=> 'There is no data'
			);
		}
		if(isset( $stateArray )){
			$dataArray = array('state'=>$stateArray);
			$stateArray1  =	json_encode($dataArray);			
			header('Content-type: application/json;charset=utf-8'); 
			echo $stateArray1; exit;
		}else{
			$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
		}
	}

	function city_get(){
		if(!empty($_REQUEST['state_id']) ){
			$state_id	= $_REQUEST['state_id'];			
			$this->load->model('kare_model');			
			$filtArray  = array('state_id'=>$state_id);
			if( !empty($_REQUEST['city_id'])){
				$city_id = $_REQUEST['city_id'];
			    $filtArray  = array('state_id'=>$state_id, 'city_id'=>$city_id);
			}
			$cities_list = $this->kare_model->get_manage_city_filt_result_list($filtArray);			
			if($cities_list){
				$citiesArray	= array();
				$count = 0;
				foreach($cities_list as $key=>$val){
					$id				= $val['city_id'];
					$name			= $val['city_name'];
					$json			= array(
						"city_id"        => $id,
						"city_name"      => $name						
					);
					$citiesArray[$count] = $json;
					$count++;
				}
			}else{
				$response = array(
					'msg_code' 	=> 404,
					'msg'		=> 'There is no data'
				);			
			}
		}else{
			$response = array(
				'msg_code' 	=> 404,
				'msg'		=> 'There is no data'
			);
		}
		if(isset( $citiesArray )){
			$dataArray   =  array('city'=>$citiesArray);
			$cityArray1  =	json_encode($dataArray);			
			header('Content-type: application/json;charset=utf-8'); 
			echo $cityArray1; exit;			
		}else{
			$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
		}
	}

    /*
	It will search asset id form master data inspection against among uin, barcode, rfid.
    if any searched asset id assined in to storemanger or any user in tbl as_assign_work_management ( through API add_assign_work_post ) , then it will not search list.

'msg_code'	=> 404,
'msg'		=> 'Already asset is assiged to user with group.'

	
	mdata_item_series
	*/
	function searchCompWithUidBCodeRid_get(){  
		$temp = array();
		$temp['mdata_uin'] = !empty($_REQUEST['uin'])?$_REQUEST['uin']:'';
		$temp['mdata_barcode'] = !empty($_REQUEST['barcode'])?$_REQUEST['barcode']:'';
		$temp['mdata_rfid'] = !empty($_REQUEST['rfid'])?$_REQUEST['rfid']:'';

		$this->load->model('m_api_model');
		$this->load->model('api_model');
                $this->load->model('asm_model');
	
		$siteData1 = $this->m_api_model->get_assetseries_masterid($temp);

		#echo "<pre>";
		#print_r($siteData1);

		// $siteData1['msg_code'] = "";
		// $siteData1['msg'] = "";

		//echo "<pre>";
		//print_r($siteData1);  exit;
		$siteData = $siteData1;				
		 // print_r($siteData);die;	  inspected_status
                
                
		if(!empty($siteData[0]['master_id']) && is_array($siteData)){

			$this->load->model('Api_model');			
			$this->load->model('Siteid_model');
			$this->load->model('Form_model');
			$this->load->model('Base_model');
			// $sdata = $siteData;

			$sdata=array();

			if($temp['mdata_uin']!=''){
				$sdata['mdata_uin']=$temp['mdata_uin'];
			}
			if($temp['mdata_barcode']!=''){
				$sdata['mdata_barcode']=$temp['mdata_barcode'];
			}
			if($temp['mdata_rfid']!=''){
				$sdata['mdata_rfid']=$temp['mdata_rfid'];
			}

                        $sdata['mdata_id']=$siteData[0]['master_id'];
                        $mdata=$productUin='';

			foreach($siteData as $sKey=>$sVal){  

					 $mdata_item_seriesArry = json_decode($sVal['mdata_item_series'], true) ;
					 $mdata_assetArry = json_decode($sVal['mdata_asset'], true) ;					
                     
					 if(isset($mdata_assetArry) && ( is_array($mdata_assetArry)) ) {

						 $countt = 0;
						 foreach($mdata_assetArry AS $keyy1=>$vall1){
							 /*
							 if($this->api_model->field_exists_check('asset_id', $vall1 , 'as_assign_work_management') > 0) {
                                 // 					
							 }else{
								 $componentData = $this->Base_model->all_value_fetch('components', 'component_code', $vall1 );
								 if( is_array($componentData) && (sizeof($componentData) > 0 ) ){
									  $sdata[$sKey]['component'][$countt] 	= $componentData[0];
									  $countt++;  
								 }
							 }
							 */
								 $componentData = $this->Base_model->all_value_fetch('components', 'component_code', $vall1 );
								 if( is_array($componentData) && (sizeof($componentData) > 0 ) ){

								 	$componentArr=$componentData[0];
								 	$componentArr['mdata_id']=$siteData[0]['master_id'];
                                                                        
                                                                        $mid=$siteData[0]['master_id'];
                                                                        $mdata=$this->asm_model->get_data_row('master_data',array('mdata_id'=>$mid));
                                                                        if($mdata){
                                                                            $productUin=$mdata['mdata_uin'];
                                                                        }
                                                                        $componentArr['mdata_uin']=$mdata['mdata_uin']; 
									#$sdata['data'][$countt] 	= $componentData[0];
									$sdata['data'][$countt] 	= $componentArr;
									$countt++;  
								 }
							 
						 }

							 $sdata['status_code'] = 200;
							 $sdata['message'] = "Records found ";

						 if( $countt == 0){
							
						     $sdata['data'] 	= "";
							 $sdata['status_code'] = 404;
							 $sdata['message'] = "Already assigned.";
						 }
					 }else{
					    $sdata['data'] 	= "";
						$sdata['status_code'] = 404;
						$sdata['message'] = "No data found !";
					 }

			}
			
		}else{
			
			$sdata['status_code'] = 404;
			$sdata['message'] = "No data found !";

		} 

       #print_r($sdata); die;



		$this->response($sdata,200);
	}

        function add_assign_work_post() {      
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_POST['user_id']) || empty($_POST['asset_id'])  || empty($_POST['group_id'])) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields can not be blank'
			);
		} else { 
			$user_id  = trim($_POST['user_id']);
			$post_asset_id = trim($_POST['asset_id']);
			$group_id = trim($_POST['group_id']);
			$data = array(
				'user_id' 		=> $user_id,				
				'group_id'		=> $group_id
			);  
			
		    $asset_id  = '';
			if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts') > 0) {
					$user_request = $this->api_model->get_data("*", 'uacc_id', $user_id, 'user_accounts');
					$data['status'] = 'P';
					$currentTime = date("Y-m-d h:i:s");
					$data['assigned_date'] = $currentTime;
                    
					$INSSQLPROJECT = '';
					if( !empty( $_POST['project_id'] ) ){
					    $project_id   =  $_POST['project_id'];
						$projectDataArray = $this->api_model->get_and_data('*', 'id', $project_id, 'as_project_user');
						if(sizeof($projectDataArray) > 0 )
						{
                            $INSSQLPROJECT  = ", project_id = '$project_id' ";
						}else{						
							$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No project found'
							);
							$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
                            exit;  						
						}
					}
					
					
					if(!empty($user_request)) {

						$asset_idArrayZ  =    explode(",", $post_asset_id );							
						unset($arrayInsAssetZ);
						$arrayInsAsset  = array();
						foreach($asset_idArrayZ AS $valw){								
							 $arrayInsAssetZ[] = $valw;
						}

						// Start: validation checking
						if( $_POST['group_id'] != 13 )
						{
							// Start to user user validation 																					
										  unset($assignedStoreArray); 
										  unset($assignedStoreAssetArray);
										  $assignedStoreArray  = array(); 
										  $assignedStoreAssetArray  = array(); 
										  $notWithStore  = '';
										  $notWithCheckin  = '';
										  $reason          = '';
										  $alreadyAssigned = '';
										  if( sizeof( $arrayInsAssetZ ) > 0 ){  
											  foreach($arrayInsAssetZ AS $key=>$val){   
												    $val  = trim($val);
													$check_status = '';
													
													
													$db_m_user_id = '';
													$db_m_group_id = '';
													$status = '';
													$assigned_date = 0;

													$SQLM = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."'  ORDER BY assigned_date DESC LIMIT 0, 1 ";
													$SQLM_result = $this->Base_model->get_dbquery($SQLM);
													if($SQLM_result)
													{   $db_m_user_id = $SQLM_result[0]['user_id'];  
														$db_m_group_id = $SQLM_result[0]['group_id'];
														$status		  = $SQLM_result[0]['status'];
														$assigned_date   = $SQLM_result[0]['assigned_date'];
														$assigned_date  = strtotime($assigned_date);
														$assignedStoreArray[$val] = $SQLM_result[0]['store_id'];
													}
													if( $db_m_user_id == $user_id)
													{
														$alreadyAssigned  .= ','.$val; 
													}else
													{
													    														
														if( ($db_m_group_id == 13) || ($status == 'R'))
														{

																// Start to check status R (removed) 																	
																$SQL5 = "SELECT * FROM  as_assign_work_check_checkout WHERE asset_id = '".$val."'  ORDER BY added_time DESC LIMIT 0, 1 ";
																$SQL5_result = $this->Base_model->get_dbquery($SQL5);
																if($SQL5_result)
																{   
																	$check_status = $SQL5_result[0]['check_status'];
																	$reason		  = $SQL5_result[0]['reason'];
																	$added_time   = $SQL5_result[0]['added_time'];
																	$added_time1  = strtotime($added_time);											
																}													
																
																if( $check_status == 'in' ){ 
																	  $notWithCheckin  = ','.$val;
																}else if( ($check_status == 'out') && ( $reason == 'ST' ||  $reason == 'DA' ||  $reason == 'RC'||  $reason == 'OT' ) ){  
																	  $notWithCheckin  = ','.$val;
																}else if( ($check_status == 'out') && ( $reason == 'NA') ){  
																	  $asset_id    .= $val.',';  
																}else{													        
																		$SQL = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."' AND group_id = '13' ORDER BY assigned_date DESC LIMIT 0, 1 ";
																		$asset_history = $this->Base_model->get_dbquery($SQL);
																		if($asset_history)
																		{  
																			
																			$assignedStoreArray[$val] = $asset_history[0]['store_id'];
																			$assignedStoreAssetArray[$val] = $val;
																			$asset_id    .= $val.',';
																		}else{								
																			$notWithStore  .= ', '.$val; 
																		}
																}	
																// End to check status R (removed)  
														}else
														{
															$alreadyAssigned  .= ','.$val; 
														}														
														
													
													}

											  }
										  }else{
												$notWithStore  .= 'There is no asset assigned'; 
										  
										  }
						 // End to user user validation 
						}else
						{  
						/// Start to store manager checking
										  unset($assignedStoreArray); 
										  unset($assignedStoreAssetArray);
										  $assignedStoreArray  = array(); 
										  $assignedStoreAssetArray  = array(); 
										  $notWithStore  = '';
										  $notWithCheckin  = '';
										  $reason			= '';
										  if( sizeof( $arrayInsAssetZ ) > 0 ){     
											  foreach($arrayInsAssetZ AS $key=>$val){ 
												    $val  = trim($val);
													$check_status     = '';
													
													$db_m_user_id     = '';
													$db_m_group_id     = '';
													$status     = '';
													$assigned_date     = 0;


													$SQLM = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."'  ORDER BY assigned_date DESC LIMIT 0, 1 "; 
													$SQLM_result = $this->Base_model->get_dbquery($SQLM);
													if($SQLM_result)
													{   $db_m_user_id = $SQLM_result[0]['user_id'];  
														$db_m_group_id = $SQLM_result[0]['group_id'];
														$status		  = $SQLM_result[0]['status'];
														$assigned_date   = $SQLM_result[0]['assigned_date'];
														$assigned_date  = strtotime($assigned_date);											
													}

													if( $db_m_user_id == $user_id)
													{  
														$alreadyAssigned  .= ','.$val; 
													}else
													{  

													/*	
													echo "<BR/>---val---$val  ||--db_m_user_id---$db_m_user_id  || --status---$status || --db_m_group_id---$db_m_group_id || --db_m_user_id---$db_m_user_id || -<BR/>";
													*/
														if( $status == ''  &&  $db_m_user_id == ''  )
														{    // echo "<BR/>----1---------<BR/>";
															////////////////////////////sxxxxxxxxx
															$chechCurrentVal  = 0;
															$added_time1      = 0;
															$assigned_date1   = 0;
															$SQL55 = "SELECT * FROM  as_assign_work_check_checkout WHERE asset_id = '".$val."'  ORDER BY added_time DESC LIMIT 0, 1 ";
															$SQL55_result = $this->Base_model->get_dbquery($SQL55);
															if($SQL55_result)
															{   
																$check_status = $SQL55_result[0]['check_status'];
																$reason		  = $SQL55_result[0]['reason'];
																$added_time   = $SQL55_result[0]['added_time'];
																$added_time1  = strtotime($added_time);											
															}
															
															$SQL5 = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."' AND group_id = '13' ORDER BY assigned_date DESC LIMIT 0, 1 ";
															$SQL_result2 = $this->Base_model->get_dbquery($SQL5);
															if($SQL_result2)
															{  											
																$assigned_date   = $SQL_result2[0]['assigned_date'];
																$db_user_id      = $SQL_result2[0]['user_id'];
																$assigned_date1  = strtotime($assigned_date);											
															}
															 
															 if($check_status == 'in'){  
																 $notWithCheckin  .= ', '.$val; 	
															 }else if( ($check_status == 'out') && ( $reason == 'ST' ||  $reason == 'DA' ||  $reason == 'RC'||  $reason == 'OT' ) ){  
																  $notWithCheckin  = ','.$val;
															}else if( ($check_status == 'out') && ( $reason == 'NA') ){  
																  $asset_id    .= $val.',';  
															}else{	 
																if( $added_time1 > $assigned_date1 ){
																	$notWithStore  .= ', '.$val; 											
																}else if( $added_time1 < $assigned_date1 ){
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.',';
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}else if( $added_time1 ==  $assigned_date1 ){										
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.','; 
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}
															}															
															
															////////////////////////////sxxxxxxxxx
														
														}else if ( ($db_m_group_id != 13) && ($status == 'R') )
														{
															$chechCurrentVal  = 0;
															$added_time1      = 0;
															$assigned_date1   = 0;
															$SQL55 = "SELECT * FROM  as_assign_work_check_checkout WHERE asset_id = '".$val."'  ORDER BY added_time DESC LIMIT 0, 1 ";
															$SQL55_result = $this->Base_model->get_dbquery($SQL55);
															if($SQL55_result)
															{   
																$check_status = $SQL55_result[0]['check_status'];
																$reason		  = $SQL55_result[0]['reason'];
																$added_time   = $SQL55_result[0]['added_time'];
																$added_time1  = strtotime($added_time);											
															}
															
															$SQL5 = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."' AND group_id = '13' ORDER BY assigned_date DESC LIMIT 0, 1 ";
															$SQL_result2 = $this->Base_model->get_dbquery($SQL5);
															if($SQL_result2)
															{  											
																$assigned_date   = $SQL_result2[0]['assigned_date'];
																$db_user_id      = $SQL_result2[0]['user_id'];
																$assigned_date1  = strtotime($assigned_date);											
															}
															 
															 if($check_status == 'in'){  
																 $notWithCheckin  .= ', '.$val; 	
															 }else if( ($check_status == 'out') && ( $reason == 'ST' ||  $reason == 'DA' ||  $reason == 'RC'||  $reason == 'OT' ) ){  
																  $notWithCheckin  = ','.$val;
															}else if( ($check_status == 'out') && ( $reason == 'NA') ){  
																  $asset_id    .= $val.',';  
															}else{	 
																if( $added_time1 > $assigned_date1 ){
																	$notWithStore  .= ', '.$val; 											
																}else if( $added_time1 < $assigned_date1 ){
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.',';
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}else if( $added_time1 ==  $assigned_date1 ){										
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.','; 
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}
															}															
															
														}else if ( ($db_m_group_id == 13))
														{
															//echo "<BR/>----3---------<BR/>";															
															$chechCurrentVal  = 0;
															$added_time1      = 0;
															$assigned_date1   = 0;
															$SQL55 = "SELECT * FROM  as_assign_work_check_checkout WHERE asset_id = '".$val."'  ORDER BY added_time DESC LIMIT 0, 1 ";
															$SQL55_result = $this->Base_model->get_dbquery($SQL55);
															if($SQL55_result)
															{   
																$check_status = $SQL55_result[0]['check_status'];
																$reason		  = $SQL55_result[0]['reason'];
																$added_time   = $SQL55_result[0]['added_time'];
																$added_time1  = strtotime($added_time);											
															}
															
															$SQL5 = "SELECT * FROM  as_assign_work_management WHERE asset_id = '".$val."' AND group_id = '13' ORDER BY assigned_date DESC LIMIT 0, 1 ";
															$SQL_result2 = $this->Base_model->get_dbquery($SQL5);
															if($SQL_result2)
															{  											
																$assigned_date   = $SQL_result2[0]['assigned_date'];
																$db_user_id      = $SQL_result2[0]['user_id'];
																$assigned_date1  = strtotime($assigned_date);											
															}
															 
															 if($check_status == 'in'){  
																 $notWithCheckin  .= ', '.$val; 	
															 }else if( ($check_status == 'out') && ( $reason == 'ST' ||  $reason == 'DA' ||  $reason == 'RC'||  $reason == 'OT' ) ){  
																  $notWithCheckin  = ','.$val;
															}else if( ($check_status == 'out') && ( $reason == 'NA') ){  
																  $asset_id    .= $val.',';  
															}else{	 
																if( $added_time1 > $assigned_date1 ){
																	$notWithStore  .= ', '.$val; 											
																}else if( $added_time1 < $assigned_date1 ){
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.',';
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}else if( $added_time1 ==  $assigned_date1 ){										
																	$chechCurrentVal  = $val;
																	if( $db_user_id != $user_id )
																	{
																		$asset_id    .= $val.','; 
																	}else{
																		$notWithStore  .= ', '.$val; 	
																	}
																}
															}															
															
														}

													}

											  }
											  
										  }else{
												$notWithStore  .= 'There is no asset assigned'; 
										  
									  }
						/// End to store manager checking
						} 
						// End : validation checking

                    
						$asset_id  = rtrim($asset_id,',');
                       // echo $asset_id;   echo "ZZZ";  exit;
						if( $alreadyAssigned != '' ){
							  $alreadyAssigned_str = ', either already assigned or could not store'.$alreadyAssigned;
						
						}
						
						if( $asset_id == '' )
						{ 
							if($notWithStore == 'There is no asset assigned' ){
								 $notWithStore_str  = 'There is no asset assigned:   '.$notWithStore;
							}else if( $notWithStore != '' ){
							    
								if( $group_id == 13 ){
									 $notWithStore_str  = ', assigned with same my store:   '.$notWithStore;
								}else{
								    $notWithStore_str  = ', not assign with store:   '.$notWithStore;
								}
								
								
							}
                            $notWithCheckin_str  = '';
							if( $notWithCheckin != '' ){
							     $notWithCheckin_str = ', due to checkin'.$notWithCheckin;
							}
							$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'Could not assign'.$notWithStore_str.$notWithCheckin_str.$alreadyAssigned_str
							);
							$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
                            exit;   
						}
						
						$asset_idArray  =    explode(",", $asset_id );  // To make						
						unset($arrayInsAsset);
						$arrayInsAsset  = array();
						foreach($asset_idArray AS $valw){
						     $assestSTR   .= "'".$valw."',";
							 $arrayInsAsset[] = $valw;  
						}
						 			 						
                       
                          
						 $insertAssetArray	= array(); //  user to send notification
						 $insCount			= 0;	
						 $existCount		= 0;
						 $ins_exist_checkout_asset	= '';
						 $exist_checkout_reason		= '';
						 $checking_asset_message    = '';
						 $checking_same_group_message    = '';
						 $checking_with_store_checkin_message    = '';	
						 
						 foreach($arrayInsAsset AS $val)
						 {	  						
							 if(isset($storeDataArray)){ unset($storeDataArray); }
							 $val  = trim($val);  
							 $storeDataArray = $this->api_model->get_and_data('*', 'asset_id', $val, 'as_assign_work_management', 'group_id', $data['group_id']);
							
							 if(sizeof($storeDataArray) > 0 ){   
								 $asset_iddb  = $storeDataArray[0]['asset_id'];
								 $user_iddb   = $storeDataArray[0]['user_id'];
								 $group_iddb  = $storeDataArray[0]['group_id'];
								 
									
								
								 if( $data['group_id'] == 13 ){

									 /* Start to checking store , if assest is checked out with NA reason in checking checkout history then it will again assign to store,  otherwise it will given message to store with reason */
									
										$SQL =  "SELECT * FROM as_assign_work_check_checkout WHERE asset_id = '".$val."' ORDER BY added_time DESC LIMIT 0, 1";
										$asset_history = $this->Base_model->get_dbquery($SQL);
										if($asset_history)
										{      
												
																								
												
												$reasonArray = $this->config->item('conf_checkcheckout_reason');
												$checkin_time		  = $asset_history[0]['checkin_time'];
												$checkout_time		  = $asset_history[0]['checkout_time'];
												if( $checkin_time != '0000-00-00 00:00:00'  ||  $checkin_time != '' ){
												   $checkin_time  = strtotime($asset_history[0]['checkin_time']);
												}else{
												   $checkin_time  = 0;
												}

												if( $checkout_time != '0000-00-00 00:00:00'  ||  $checkout_time != '' ){
												   $checkout_time  = strtotime($asset_history[0]['checkout_time']);
												}else{
												   $checkout_time  = 0;
												}

												if( $checkout_time >  $checkin_time  ){
												
														$insFlag = 0;
														$reason  = '';
														$reason = $asset_history[0]['reason'];
														if( $reason == ''){
															$ins_exist_checkout_asset .= $val.',';
															$insFlag = 1;
															
														}else if( strtolower($reason) == 'na'){
															$ins_exist_checkout_asset .= $val.',';
															$insFlag = 1;
															
														}else{
														  $exist_checkout_reason .=	 $val. ' => '. $reasonArray[$reason];
														}
														

												}else if($checkout_time ==  $checkin_time){
														$insFlag = 0;
														$reason  = '';
														$reason = $asset_history[0]['reason'];
														if( $reason == ''){
															$ins_exist_checkout_asset .= $val.',';
															$insFlag = 1;
															
														}else if( strtolower($reason) == 'na'){
															$ins_exist_checkout_asset .= $val.',';
															$insFlag = 1;
															
														}else{
														  $exist_checkout_reason .=	 $val. ' => '. $reasonArray[$reason];
														}														
												}else if( $checkin_time > $checkout_time ){
													$checking_asset_message .= ' Due to checked in';
												} else{
												   //					
												 
												}


												if( $insFlag == 1){
												 $insertAssetArray[] = $val;
												
												$INSSQL = "INSERT INTO as_assign_work_management SET asset_id = '".$val."', user_id = '".$data['user_id']."', group_id = '".$data['group_id']."',	status = '".$data['status']."', assigned_date = '".$data['assigned_date']."', store_id = '".$user_id."' ".$INSSQLPROJECT;                                            
												 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
												 $insCount++;
												}


										}else{
										
										    $insertAssetArray[] = $val;
											$INSSQL = "INSERT INTO as_assign_work_management SET asset_id = '".$val."', user_id = '".$data['user_id']."', group_id = '".$data['group_id']."',	status = '".$data['status']."', assigned_date = '".$data['assigned_date']."', store_id = '".$user_id."' ".$INSSQLPROJECT;                                          
											 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
											 $insCount++;
										
										}
									
									 /*  Start to checking store , if assest is checked out with NA reason in checking checkout history then it will again assign to store,  otherwise it will given message to store with reason */
								 }else{	  
										
										$db_assigned_date  = 0;
										$db_added_time     = 0;
										$db_chk_assigned_date = 0;
										$store_id = $assignedStoreArray[$val];
										$SQL6 =  "SELECT * FROM as_assign_work_management WHERE asset_id = '".$val."'  ORDER BY assigned_date DESC LIMIT 0, 1";
										$SQL6_reult = $this->Base_model->get_dbquery($SQL6);												
										if($SQL6_reult)
										{ 
											 $db_user_id   = $SQL6_reult[0]['user_id'];
											 $db_store_id   = $SQL6_reult[0]['store_id'];
											 $db_status     = $SQL6_reult[0]['status'];
											 $db_assigned_date   = strtotime($SQL6_reult[0]['assigned_date']);
										}
                         
										if($db_store_id == 13 ){
										   // MMMM      $checking_same_store_message    .= $val.', ';
											 $store_id = $assignedStoreArray[$val];
											 $insertAssetArray[] = $val;
											 $INSSQL = "INSERT INTO as_assign_work_management SET asset_id = '".$val."', user_id = '".$data['user_id']."', group_id = '".$data['group_id']."',	status = '".$data['status']."', assigned_date = '".$data['assigned_date']."', store_id = '".$store_id."' ".$INSSQLPROJECT;                                            													 
											 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
											 $insCount++;

										   
										}else if( $db_user_id != $data['user_id']  ){

											 $store_id = $assignedStoreArray[$val];
											 $insertAssetArray[] = $val;
											 $INSSQL = "INSERT INTO as_assign_work_management SET asset_id = '".$val."', user_id = '".$data['user_id']."', group_id = '".$data['group_id']."',	status = '".$data['status']."', assigned_date = '".$data['assigned_date']."', store_id = '".$store_id."' ".$INSSQLPROJECT;                                            													 
											 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
											 $insCount++;
										
										}else{
										     
											$checking_same_store_message    .= $val.', ';
												
										}	
								 }
							 }else{     
																				
										if( $group_id == 13 ){
										   $store_id = $user_id;
										   $SQL6 =  "SELECT * FROM as_assign_work_management WHERE asset_id = '".$val."' AND user_id = '".$user_id."'  ORDER BY assigned_date DESC LIMIT 0, 1";
										}else{
										   $store_id = $assignedStoreArray[$val];
										   $SQL6 =  "SELECT * FROM as_assign_work_management WHERE asset_id = '".$val."' AND user_id = '".$user_id."'  ORDER BY assigned_date DESC LIMIT 0, 1";
										}
										
										$SQL6_reult = $this->Base_model->get_dbquery($SQL6);
										
										if($SQL6_reult)
										{ 
											 $db_store_id   = $SQL6_reult[0]['store_id'];
										}

										if( $db_store_id == $store_id ){
										    $checking_same_store_message    .= $val.', ';
										}else{																							
											 $insertAssetArray[] = $val;
											 $INSSQL = "INSERT INTO as_assign_work_management SET asset_id = '".$val."', user_id = '".$data['user_id']."', group_id = '".$data['group_id']."',	status = '".$data['status']."', assigned_date = '".$data['assigned_date']."', store_id = '".$store_id."' ".$INSSQLPROJECT;                                            													 
											 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
											 $insCount++;												
										}	
						    }
							 
						}

						
						if( $insCount > 0 ){
							$messResponce = '';
							$notWithCheckin_str  = '';
							if( $notWithCheckin != '' ){
								 $notWithCheckin_str = ',but these not due to checkin'.$notWithCheckin;
							}

							if( $existCount > 0 ){
								$messResponce = 'Assign successfully, but some assest is already assigned.'; 
								$messResponce .= $ins_exist_checkout_asset.$notWithCheckin_str; 	
							}else{
								$messResponce = 'Assign successfully.'.$notWithCheckin_str; 
							}

							if( $exist_checkout_reason != '' ){
								$messResponce .= ', also there is some asset could not assign due to: '.$exist_checkout_reason; 
							}
							//  Start to send notification to all previous users										
								foreach( $insertAssetArray AS $ky=>$vl )
								{
									$sqlSelect = "SELECT DISTINCT(AWM.user_id), UA.uacc_activation_token, AWM.asset_id FROM as_assign_work_management AS AWM LEFT JOIN  user_accounts AS UA ON ( AWM.user_id = UA.uacc_id AND AWM.asset_id = '$vl' )  WHERE AWM.asset_id = '$vl' AND AWM.assigned_date <= '$currentTime' AND AWM.user_id != '$user_id' GROUP BY AWM.user_id "; 
									$user_tokenArray = $this->Base_model->get_dbquery( $sqlSelect);  											
									if( $user_tokenArray){													
										if( !empty( $_POST['device_type'] )){   
											$device_type  = trim($_POST['device_type']);
											if( ( $device_type == 'android' ) || ( $device_type == 'ios' ) ){ 
												// $this->_get_asset_user_notification($user_tokenArray, $device_type);
											}else{
												//$messResponce .= ' But notification could not due to invalid parameter of device type.';				
											}													
										}
									}
								}
							//  End to send notification to all previous users

							$response = array(
								'msg_code'	=> 200,
								'msg'		=> $messResponce
							);									 
						}else{
							$checking_with_store_checkin_message_str  = '';
							if( $checking_with_store_checkin_message != ''){
							   $checking_with_store_checkin_message_str  = ', these are not assign, due to not store or checkin '.$checking_with_store_checkin_message;
							}

							if( $exist_checkout_reason != '' ){
							   $mess = 'Could not assign, due to :'.$exist_checkout_reason;
							}else{
                               if( $checking_same_store_message != '' ){
								   $checking_same_store_message_str = 'Due to same store: '.$checking_same_store_message;	
							   }
							   $mess = 'Could not assign.'.$checking_asset_message.$checking_same_group_message.$checking_same_store_message_str.$checking_with_store_checkin_message_str;
							}
							$response = array(
								'msg_code'	=> 404,
								'msg'		=> $mess
							);										 
						}

						

					}else{
							$response = array(
								'msg_code'	=> 404,
								'msg'		=> 'No user found.'
							);			
					}				
			} else {
				$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'No user found.'
			    );
			}
		}
		
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
	}
	function _get_asset_user_notification($user_tokenArray, $device_type ){
	   
		$this->load->model('Base_model');
        if(strtolower($device_type) == 'android') { 
			 foreach($user_tokenArray AS $key=>$val){
			    $user_id				= $val['user_id'];
				$device_token			= $val['uacc_activation_token'];
				$asset_id				= $val['asset_id'];
				$msg					= 'Assigned to product '.$asset_id;
				$title					= 'Asset Assign'; 
		        $this->Base_model->android_notification($device_token, $title, $msg);

			 }
          
        } else {           
			 foreach($user_tokenArray AS $key=>$val){
			    $user_id				= $val['user_id'];
				$device_token			= $val['uacc_activation_token'];
				$asset_id				= $val['asset_id'];
				$msg					= 'Assigned to product '.$asset_id;
				$title					= 'Asset Assign'; 
		        $this->Base_model->ios_notification($device_token, $title, $msg);

			 }
        }

	}

	function add_project_user_post() {
		$this->load->model('api_model');
		if(empty($_POST['user_id']) || empty($_POST['project_name']) ) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else {
			$user_id		= trim($_POST['user_id']);
			$project_name	= trim($_POST['project_name']);			
			$currentDate    = date("Y-m-d H:i:s");
			$data = array(
				'user_id' 		=> $_POST['user_id'],
				'project_name'	=> $project_name,
				'added_date'	=> $currentDate,
				'status'	    => 'Open'

			);

			if($this->api_model->field_exists_check('user_id', $user_id , 'as_project_user', 'project_name', $project_name) > 0) {
					$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'Already there is project assign with user'
					);				
			} else {
				$add_projectuser = $this->api_model->insert_data($data, 'as_project_user');
				if($add_projectuser['msg_code'] == 200 ) {
				   $response = array(
						'msg_code'	=> 200,
						'msg'		=> 'Project added to user successfully.'
					);	

				} else {
					$response = array(
						'msg_code'	=> 200,
						'msg'		=> 'Project could not add  with user'
					);
				}
			}
		}
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
	}

    /*
	In reference to asset  management 
    The asset id which assign with storemanger, are counted with public user
    count_in_use : All asset id with public user is counted with status => A (Approve) , and all these asset id also exist with storemanger
    total_count  : Total asset id assigned with storemanager
	*/

 	function count_asset_in_use_get() {  
		$this->load->model('api_model');
		$this->load->model('Base_model');
		
		
		if(empty($_GET['user_id'])) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields can not be blank'
			);
		} else {
			$user_id  = $_GET['user_id'];
			// Note group Id of store manger is 13
			$stor_man_groupid = 13;
			if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts', 'uacc_group_fk',$stor_man_groupid ) > 0) {
				
					 $SQL  = "SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE user_id = '".$user_id."' ORDER BY assigned_date DESC ";
					
					 $asset_data = $this->Base_model->get_dbquery($SQL);	
                     
					 if($asset_data){
                         if(isset($StoreAssetArray)){ unset($assetArray); }
						 if(isset($StoreAssetKeyArray)){ unset($StoreAssetKeyArray); }
						 $StoreAssetArray  = array();
						 $StoreAssetKeyArray  = array();
						 foreach($asset_data AS $key=>$val){
						    $StoreAssetArray[] = $val['asset_id'];
							$StoreAssetKeyArray[$val['asset_id']] = $val['assigned_date'];
						 } 
						 $total_storemanger_assest  = sizeof($StoreAssetArray);
						 $total_storemanger_assest  = (string)$total_storemanger_assest;
                         
						 $assetArrayImp  = implode("','",$StoreAssetArray);
						 $SQL1  = " SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE asset_id IN ('".$assetArrayImp."') AND user_id != '".$user_id."' AND group_id = 13  " ; 

						 $otherStoreResult = $this->Base_model->get_dbquery( $SQL1);   
						 if( $otherStoreResult){
							 if(isset($otherStoreAsset)){ unset($otherStoreAsset); }
							 if(isset($otherStoreKeyAsset)){ unset($otherStoreKeyAsset); }	

							 $otherStoreAsset	 = array();
							 $otherStoreKeyAsset = array();
							 foreach($otherStoreResult AS $key=>$val){
								$otherStoreAsset[]  = $val['asset_id'];
								$otherStoreKeyAsset[$val['asset_id']] = $val['assigned_date'];
							 }

						 }
                        
						 if( is_array($otherStoreKeyAsset)){
							     if(isset($storeAssetF)){ unset($storeAssetF); }
								 $storeAssetF = array();
								 if(is_array($StoreAssetKeyArray)){
									 foreach($StoreAssetKeyArray AS $kky=>$vvl  ){
										$storeAssignDate =   strtotime($vvl);
										if( isset($otherStoreKeyAsset[$kky])){
										   $otherStoreAssignDate  = strtotime($otherStoreKeyAsset[$kky]);
										   if( $storeAssignDate > $otherStoreAssignDate ){
										        $storeAssetF[] = $kky;
										   }
										}else{
											$storeAssetF[] = $kky;
										}
									 }
								 }
						 }
                         
						 if( $storeAssetF ){						   
							 $total_storemanger_assest  = sizeof($storeAssetF);
							 $total_storemanger_assest  = (string)$total_storemanger_assest;
							 $assetArrayImp  = implode("','",$storeAssetF);
						 }

						 $SQL  = " SELECT COUNT( DISTINCT( asset_id) ) AS asset_in_use FROM as_assign_work_management WHERE asset_id IN ('".$assetArrayImp."') AND user_id != '".$user_id."' AND store_id = '".$user_id."' AND group_id != 13  " ; 
						 $asset_appr_array = $this->Base_model->get_dbquery( $SQL);   
						 if( $asset_appr_array){
								$assetUse = $asset_appr_array[0]['asset_in_use'];
								$assetUse = (string)$assetUse;
								$response = array(
										'msg_code'	  => 200,
										'msg'		  => 'There are asset found in use ',
										'count_in_use' => $assetUse,
									    'total_count' => $total_storemanger_assest
								);
						 }else{						 
							$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No asset found in use.'
							);
						 }						 		
					  }else{
							$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No asset assigned to storemanager.'
							);				  
					  }

			} else {
				$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'Invalid storemanger'
			    );
			}
		}
		
		
		if( isset($response['count_in_use'])){
               $this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'count_in_use'=>$response['count_in_use'], 'total_count'=>$response['total_count']));
		}else{
		     $this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
		}
		
	}

	function asset_use_detail_get(){
		
			if(empty($_GET['user_id']) ||  empty($_GET['filter']) ) {				
				$response = array(
					'msg_code'	=> 404,
					'msg'		=> 'Fields can not be blank'
				);
			} else { 
				if( ( $_GET['filter'] == 'use' ) || ( $_GET['filter'] == 'total' ) ){					
					    $user_id    =  trim($_GET['user_id']);
						$filter		=  trim($_GET['filter']);
						$stor_man_groupid = 13;	
						$this->load->Model('api_model');
						$this->load->Model('Base_model');
						if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts', 'uacc_group_fk', $stor_man_groupid ) > 0) {
								$selectArray  = array('asset_id');
								$WherFiled    = 'user_id';
								$whereArry    = array($user_id);
								$whereArry    = array($user_id);
								$table        = 'as_assign_work_management';
								$inType       = 'int';
								$asset_data = $this->Base_model->get_selete_in_field( $selectArray , $WherFiled, $whereArry, $table, $inType);								
								if( $filter == 'total' ){  
									//  Start to total asset ids 

									 $SQL  = "SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE user_id = '".$user_id."' ORDER BY assigned_date DESC ";
									
									 $asset_data = $this->Base_model->get_dbquery($SQL);	
									 
									 if($asset_data){
										 if(isset($StoreAssetArray)){ unset($assetArray); }
										 if(isset($StoreAssetKeyArray)){ unset($StoreAssetKeyArray); }
										 $StoreAssetArray  = array();
										 $StoreAssetKeyArray  = array();
										 foreach($asset_data AS $key=>$val){
											$StoreAssetArray[] = $val['asset_id'];
											$StoreAssetKeyArray[$val['asset_id']] = $val['assigned_date'];
										 } 
										 $total_storemanger_assest  = sizeof($StoreAssetArray);
										 $total_storemanger_assest  = (string)$total_storemanger_assest;
										 
										 $assetArrayImp  = implode("','",$StoreAssetArray);
										 $SQL1  = " SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE asset_id IN ('".$assetArrayImp."') AND user_id != '".$user_id."' AND group_id = 13  " ; 

										 $otherStoreResult = $this->Base_model->get_dbquery( $SQL1);   
										 if( $otherStoreResult){
											 if(isset($otherStoreAsset)){ unset($otherStoreAsset); }
											 if(isset($otherStoreKeyAsset)){ unset($otherStoreKeyAsset); }	

											 $otherStoreAsset	 = array();
											 $otherStoreKeyAsset = array();
											 foreach($otherStoreResult AS $key=>$val){
												$otherStoreAsset[]  = $val['asset_id'];
												$otherStoreKeyAsset[$val['asset_id']] = $val['assigned_date'];
											 }

										 }
										
										 if( is_array($otherStoreKeyAsset)){
												 if(isset($storeAssetF)){ unset($storeAssetF); }
												 $storeAssetF = array();
												 if(is_array($StoreAssetKeyArray)){
													 foreach($StoreAssetKeyArray AS $kky=>$vvl  ){
														$storeAssignDate =   strtotime($vvl);
														if( isset($otherStoreKeyAsset[$kky])){
														   $otherStoreAssignDate  = strtotime($otherStoreKeyAsset[$kky]);
														   if( $storeAssignDate > $otherStoreAssignDate ){
																$storeAssetF[] = $kky;
														   }
														}else{
															$storeAssetF[] = $kky;
														}
													 }
												 }
										 }
										 
										 if( $storeAssetF ){						   
											 $total_storemanger_assest  = sizeof($storeAssetF);
											 $total_storemanger_assest  = (string)$total_storemanger_assest;
											 $assetArrayImp  = implode("','",$storeAssetF);
										 }
                                       

										  $SQL  = " SELECT * FROM components WHERE component_code IN ('".$assetArrayImp."') " ; 
										  $asset_inuse_array = $this->Base_model->get_dbquery( $SQL);
										  if($asset_inuse_array){
											  $response = array(
												'msg_code'	=> 200,
												'msg'		=> 'Total assest found.',
												'component' => $asset_inuse_array
											 );
										  }else{
											  $response = array(
												'msg_code'	=> 404,
												'msg'		=> 'Not detail to assest in use.',
												'component' => ""
											 );
										   
										  }


									 }else{									 
										$response = array(
											'msg_code'	=> 404,
											'msg'		=> 'No record found.'
										);										 
									 }
								
								}else if($filter == 'use'){								
								    //  Start in use asset ids	//////////////////////////////////////////

									 $SQL  = "SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE user_id = '".$user_id."' ORDER BY assigned_date DESC ";
									
									 $asset_data = $this->Base_model->get_dbquery($SQL);	
									 
									 if($asset_data){
										 if(isset($StoreAssetArray)){ unset($assetArray); }
										 if(isset($StoreAssetKeyArray)){ unset($StoreAssetKeyArray); }
										 $StoreAssetArray  = array();
										 $StoreAssetKeyArray  = array();
										 foreach($asset_data AS $key=>$val){
											$StoreAssetArray[] = $val['asset_id'];
											$StoreAssetKeyArray[$val['asset_id']] = $val['assigned_date'];
										 } 
										 $total_storemanger_assest  = sizeof($StoreAssetArray);
										 $total_storemanger_assest  = (string)$total_storemanger_assest;
										 
										 $assetArrayImp  = implode("','",$StoreAssetArray);
										 $SQL1  = " SELECT DISTINCT(asset_id), assigned_date FROM as_assign_work_management WHERE asset_id IN ('".$assetArrayImp."') AND user_id != '".$user_id."' AND group_id = 13  " ; 

										 $otherStoreResult = $this->Base_model->get_dbquery( $SQL1);   
										 if( $otherStoreResult){
											 if(isset($otherStoreAsset)){ unset($otherStoreAsset); }
											 if(isset($otherStoreKeyAsset)){ unset($otherStoreKeyAsset); }	

											 $otherStoreAsset	 = array();
											 $otherStoreKeyAsset = array();
											 foreach($otherStoreResult AS $key=>$val){
												$otherStoreAsset[]  = $val['asset_id'];
												$otherStoreKeyAsset[$val['asset_id']] = $val['assigned_date'];
											 }

										 }
										
										 if( is_array($otherStoreKeyAsset)){
												 if(isset($storeAssetF)){ unset($storeAssetF); }
												 $storeAssetF = array();
												 if(is_array($StoreAssetKeyArray)){
													 foreach($StoreAssetKeyArray AS $kky=>$vvl  ){
														$storeAssignDate =   strtotime($vvl);
														if( isset($otherStoreKeyAsset[$kky])){
														   $otherStoreAssignDate  = strtotime($otherStoreKeyAsset[$kky]);
														   if( $storeAssignDate > $otherStoreAssignDate ){
																$storeAssetF[] = $kky;
														   }
														}else{
															$storeAssetF[] = $kky;
														}
													 }
												 }
										 }
										 
										 if( $storeAssetF ){						   
											 $total_storemanger_assest  = sizeof($storeAssetF);
											 $total_storemanger_assest  = (string)$total_storemanger_assest;
											 $assetArrayImp  = implode("','",$storeAssetF);
										 }										 
										////////////////////////////////////////////////
										 $SQL  = " SELECT DISTINCT( asset_id) FROM as_assign_work_management WHERE asset_id IN ('".$assetArrayImp."') AND user_id != '".$user_id."' AND store_id = '".$user_id."' AND group_id != 13  " ; 
										 $asset_appr_array = $this->Base_model->get_dbquery( $SQL);   
										 if( $asset_appr_array){
													if(isset($StoreAssetArray)){ unset($StoreAssetArray); }
													
													$StoreAssetArray  = array();
													foreach($asset_appr_array AS $key=>$val){
														$StoreAssetArray[] = $val['asset_id'];
														
													}

													$assetUseArrayImp  = implode("','",$StoreAssetArray);													
													$SQL  = " SELECT * FROM components WHERE component_code IN ('".$assetUseArrayImp."') " ; 
													$asset_inuse_array = $this->Base_model->get_dbquery( $SQL);
													if($asset_inuse_array){
													  $response = array(
														'msg_code'	=> 200,
														'msg'		=> 'Total assest in use.',
														'component' => $asset_inuse_array
													 );
													}else{
													  $response = array(
														'msg_code'	=> 404,
														'msg'		=> 'Not detail to assest in use.',
														'component' => ""
													 );

													}
													 
										 }else{						 
											$response = array(
													'msg_code'	=> 404,
													'msg'		=> 'No asset found in use.'
											);
										 }


										///////////////////////////////////////////////


									}else{
									
										$response = array(
											'msg_code'	=> 404,
											'msg'		=> 'No record found with store manager to calculate in use with general user.'
										);									
									}

									//  End in use asset ids	///////////////////////////////////////////
								}																
						} else {
							$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'Invalid storemanger'
							);
						}					
				}else{
				    $response = array(
						'msg_code'	=> 404,
						'msg'		=> 'Filter should be either use or total'
					);
				}				
			}
			if(isset( $response['component'] )){
				 $this->response(array(0=>array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'component'=>$response['component'])));
			}else{
				 $this->response(array(0=>array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'])));
			}

	}

	function user_project_list_get(){				
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_GET['user_id']) ) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else {
			$user_id		= trim($_GET['user_id']);
			$projectData = $this->Base_model->all_value_fetch('as_project_user', 'user_id', $user_id );			
			if($projectData){				
				if(isset($proArray)){
				unset($proArray);
				}

				$proArray  = array();
				foreach($projectData AS $val ){
					$assetCount = 0;
					$project_id  = $val['id'];
					$SQLCOUNT		   = "SELECT count(id) AS total_count FROM as_assign_work_management WHERE  project_id = '".$project_id."' AND user_id = '".$user_id."' AND status != 'R' ";
					$proAssCount = $this->Base_model->get_dbquery($SQLCOUNT);
					if( $proAssCount){
						$assetCount = $proAssCount[0]['total_count'];
					}

					$proArray[] = array('id'=> $val['id'], 'name'=>$val['project_name'], 'count' => $assetCount);
				
				}
				
				$response = array(
					'msg_code'	=> 200,
					'msg'		=> 'Project found.',
					'project'   => $proArray 
				);			
			}else{
				$response = array(
					'msg_code'	=> 404,
					'msg'		=> 'No records found'
				);			
			}			
		}

		if( isset($response['project'])){
			$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'project'=>$response['project']  ));
		}else{
		  $this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));
		}
		
	}

    /*
	Work Process : Provide list of all assest detail , which was insert through API (add_assign_work_post into tbl=> as_assign_work_management)
	Ref Tbl => as_assign_work_management  
	*/
	function user_project_asset_list_get(){				
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_GET['user_id']) || empty($_GET['project_id']) ) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else {
				$user_id		= trim($_GET['user_id']);
				$project_id		= trim($_GET['project_id']);
				if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts') > 0) {								
						$projectDataArray = $this->api_model->get_and_data('*', 'id', $project_id, 'as_project_user');

						if(sizeof($projectDataArray) > 0 )
						{  
							$projectAssetData = $this->api_model->get_and_data('*', 'project_id', $project_id, 'as_assign_work_management', 'user_id', $user_id , 'status !=','R' );			
							if($projectAssetData){				
								if(isset($proArray)){
								unset($proArray);
								}
								$proArray  = array();

								if(isset($FproArray)){
								unset($FproArray);
								}
								$FproArray  = array();
								$notInCheckInArray  = array();
								foreach($projectAssetData AS $val ){
									
									$component_code  = $val['asset_id'];
									$SQLCHIN = "SELECT * FROM as_assign_work_check_checkout WHERE asset_id = '".$component_code."' AND user_id = '".$user_id."' AND checkin_time != '0000-00-00 00:00:00'    ORDER BY checkin_time DESC LIMIT 0,1 ";
									$checkinData = $this->Base_model->get_dbquery($SQLCHIN);
									

									$SQLCHOUT = "SELECT * FROM as_assign_work_check_checkout WHERE asset_id = '".$component_code."' AND user_id = '".$user_id."'  AND checkout_time != '0000-00-00 00:00:00'     ORDER BY checkout_time DESC LIMIT 0,1 ";
									$checkoutData = $this->Base_model->get_dbquery($SQLCHOUT);
									
									$AssetCodeCheckingStatus  = "";
									$check_time					= '';
                                    if(  (isset($checkinData[0]['checkin_time'])) &&  (isset($checkinData[0]['checkout_time'])) ){
										  $checkin_time  =  strtotime($checkinData[0]['checkin_time']);
										  $checkout_time  =  strtotime($checkoutData[0]['checkout_time']);
										  if($checkin_time > $checkout_time ){
                                              $AssetCodeCheckingStatus  = 'checkin';
											  $check_time					= $checkin_time;
										  }else if($checkin_time < $checkout_time ){
                                              $AssetCodeCheckingStatus  = 'checkout';
											   $check_time					= $checkout_time;
										  }
									
									}elseif (isset($checkinData[0]['checkin_time'])){
									          $AssetCodeCheckingStatus  = 'checkin';
											  $check_time					= strtotime($checkinData[0]['checkin_time']);
									}elseif (isset($checkoutData[0]['checkout_time'])){
									          $AssetCodeCheckingStatus  = 'checkout';
											  $check_time					= strtotime($checkoutData[0]['checkout_time']);
									}
									
                                     
									$componentData = $this->Base_model->all_value_fetch('components', 'component_code', $component_code );
									if($componentData){
										$componentData[0]['check_status'] = $AssetCodeCheckingStatus;
										$componentData[0]['check_time'] = $check_time;
										
										$proArray[] = $componentData[0];
										if($check_time > 0 ){
										   $FproArray[$check_time] = $componentData[0];
										}else{
										  $FproArray[] = $componentData[0];
										}
									}
								}
								
								if( sizeof($FproArray) > 0 )
								{	
										unset($FArray);
										$FArray = array();
										$numSortArray = array();
										foreach($FproArray AS $key=>$val){
											$FArray[$val['check_time']]  = $val;
											$numSortArray[$val['check_time']] = $val['check_time'];
										
										}
										arsort($numSortArray);									
										$componentArray  = array();
										foreach($numSortArray AS $val1){
											$componentArray[] = $FArray[$val1];
										
										}
										if( isset($checkinCheckout)) { unset($checkinCheckout); } 
										$checkinCheckout = array();
										foreach($componentArray AS $key3=>$val3){
											if($val3['check_status'] == 'checkin'){
											   $checkinCheckout[] = $val3;
											}
										}
										foreach($componentArray AS $key3=>$val3){
											if($val3['check_status'] == 'checkout'){
											   $checkinCheckout[] = $val3;
											}
										}

										foreach($FproArray AS $key3=>$val3){
											if($val3['check_status'] == ''){
											   $checkinCheckout[] = $val3;
											}
										}

										$response = array(
												'msg_code'	=> 200,
												'msg'		=> 'Project Asset found.',
												'component'   => $checkinCheckout
										);	

								}else
								{
											$response = array(
											'msg_code'	=> 404,
											'msg'		=> 'No Asset found.'
											);	

								}


							}else{
								$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No asset records found'
								);			
							}			
						}else{
							if($this->api_model->field_exists_check('user_id', $user_id , 'as_project_user') > 0) {
								$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No project found correspondence to user.'
								);
							}else{
								$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No user found correspondence to project.'
								);
							}							
					   }							
				}else{
					$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'No user found'
					);						
				}										
		}

        
		if( isset($response['component'])){
			$this->response(array('0'=>array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'component'=>$response['component'])));			
		}else{
          $this->response(array('0'=>array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'])));		  
		}
		
	}


	function insert_history_assign_work_post() {  
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_POST['user_id']) || empty($_POST['asset_id'])  || empty($_POST['group_id'])  || empty($_POST['project_id'])) {
				$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields can not be blank'
			);
		} else { 
			if(isset($data)) { unset($data); }
			$data     = array();
			$user_id  = trim($_POST['user_id']);
			$asset_id = trim($_POST['asset_id']);
			$group_id = trim($_POST['group_id']);
			$project_id = trim($_POST['project_id']);
			$data = array(
				'user_id' 		=> $user_id,				
				'group_id'		=> $group_id
			); 
			
			if( !empty($_POST['project_id']) ){
			   $data['project_id']  = $project_id;
			}

			if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts') > 0) {
					$data['status'] = 'P';
					$currentTime = date("Y-m-d h:i:s");
					$data['added_date'] = $currentTime;
						$asset_idArray  =    explode(",", $asset_id );
						unset($arrayInsAsset);
						$arrayInsAsset  = array();
						foreach($asset_idArray AS $valw){
							 $arrayInsAsset[] = $valw;
						} 
								 $insCount = 0;						 
								 foreach($arrayInsAsset AS $val)
								 {		
									 if(isset($storeDataArray)){ unset($storeDataArray); }
									 $final_asset_id  = trim($val);
									 if($final_asset_id != '' ){										
										 $CUSTSQL  = '';
										 if( isset( $data['project_id']) ){
											  $CUSTSQL  .= ", project_id = '$project_id' " ;
										 }
										 if( isset( $data['group_id']) ){
											  $CUSTSQL  .= ", group_id = '$group_id' " ;
										 }									 

										 $INSSQL = "INSERT INTO as_assign_work_history SET asset_id = '".$final_asset_id."', user_id = '".$data['user_id']."', added_date = '".$data['added_date']."' ".$CUSTSQL;  
									
										 $asset_appr_array = $this->Base_model->get_db_multins_query( $INSSQL); 
										 $insCount++;
								   }
								}
								if( $insCount > 0 ){
									$response = array(
										'msg_code'	=> 200,
										'msg'		=> 'Added successfully.'
									);									 
								}else{
									$response = array(
										'msg_code'	=> 404,
										'msg'		=> 'Could not add.'
									);										 
								}
								
			} else {
				$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'No user found.'
			    );
			}
		}
		$this->response(array('0'=>array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']) ));		  
	}

	function checkin_checkout_work_post() {  
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_POST['asset_id']) || empty($_POST['user_id'])  || empty($_POST['check_type'])  || empty($_POST['time'])) {
				$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields can not be blank'
			);
		}else{
			$user_id	= trim($_POST['user_id']);
			$asset_id	= trim($_POST['asset_id']);
			$time		= trim($_POST['time']);
			$current_time  = date("Y-m-d H:i:s");
			if($this->api_model->field_exists_check('uacc_id', $user_id , 'user_accounts' ) > 0) {

				   if( ($_POST['check_type'] == 'checkin') || ($_POST['check_type'] == 'checkout')){
						if($_POST['check_type'] == 'checkin'){
							$check_tim   = ($time/1000);
							$checkin_time  = date("Y-m-d H:i:s", $check_tim);							
							$data  = array(
								'user_id' => $user_id,
								'checkin_time' => $checkin_time,
								'asset_id' => $asset_id,
								'added_time' => $current_time,
								'check_status' => 'in'
							);

							$SQL =  "SELECT * FROM as_assign_work_check_checkout WHERE asset_id = '".$asset_id."' ORDER BY added_time DESC LIMIT 0, 1";		
							$asset_history = $this->Base_model->get_dbquery($SQL);							 
							if(sizeof($asset_history) > 0 )
							{      
									$reasonArray = $this->config->item('conf_checkcheckout_reason');
									$insFlag = 0;
									$reason  = $asset_history[0]['reason'];	
									if( isset($reason)){
										 $reasonl = strtolower($reason);
									}else{
										$reasonl = '';
									}
									
									if( ($reasonl == 'na') || ( $reasonl == '')  ){
										$add_checkin = $this->api_model->insert_data($data, 'as_assign_work_check_checkout');							
										if($add_checkin) {
											$response = array(
												'msg_code'	=> 200,
												'msg'		=> 'Checked in successfully'
											);
										} else {
											$response = array(
												'msg_code'	=> 404,
												'msg'		=> 'Checked in failed'
											);
										}	
									}else{
										if($reasonl == 'ot'){
										  $mess  = 'Checked in failed due to '.$reasonArray[$reason].' :'.$asset_history[0]['reason_detail'];										  
										}else{
										   $mess  = 'Checked in failed due to '.$reasonArray[$reason];
										 }
										$response = array(
											'msg_code'	=> 404,
											'msg'		=> $mess
										);									
									}
									
							}else{
                                  
								$add_checkin = $this->api_model->insert_data($data, 'as_assign_work_check_checkout');							
								if($add_checkin) {
									$response = array(
										'msg_code'	=> 200,
										'msg'		=> 'Checked in successfully'
									);
								} else {
									$response = array(
										'msg_code'	=> 404,
										'msg'		=> 'Checked in failed'
									);
								}							
							
							}

						}else if($_POST['check_type'] == 'checkout') {
                            if( ($_POST['reason'] == 'ST' ) || ($_POST['reason'] == 'DA' ) || ($_POST['reason'] == 'RC' ) || ($_POST['reason'] == 'OT' ) || ($_POST['reason'] == 'NA' ) )
							{									
									$check_tim   = ($time/1000);
									$checkout_time  = date("Y-m-d H:i:s", $check_tim);
									$reason			= trim($_POST['reason']);
									$data  = array(
										'user_id' => $user_id,
										'checkout_time' => $checkout_time,
										'asset_id' => $asset_id,
										'reason' => $reason,
										'added_time' => $current_time,
										'check_status' => 'out'
									);

									if( !empty($_POST['reason_detail'])){
										$data['reason_detail'] = trim($_POST['reason_detail']);
									}
						  			$add_checkout = $this->api_model->insert_data($data, 'as_assign_work_check_checkout');
						            // Start Update the status so that it would be assign to other user 
									  if($_POST['reason'] == 'NA'){
									    $SQL5 = "UPDATE  as_assign_work_management SET status = 'R'  WHERE asset_id = '".$asset_id."' AND user_id = '".$user_id."' ";
										$SQL5_result = $this->Base_model->get_db_multins_query($SQL5);
									  }
									// End Update the status so that it would be assign to other user 
									if($add_checkout) {
										$response = array(
											'msg_code'	=> 200,
											'msg'		=> 'Checked out successfully'
										);
									} else {
										$response = array(
											'msg_code'	=> 404,
											'msg'		=> 'Checked out  failed'
										);
									}							
							
							}else{
								$response = array(
								'msg_code'	=> 404,
								'msg'		=> 'Reason parameter should one  among the ( ST=> Stolen,DA=> Damaged,RC=> Return To Client,OT=> Others,NA=> None of Above). '
								 );								
							}

						
						}else{
							$response = array(
							'msg_code'	=> 404,
							'msg'		=> 'Checking type parameter should be either checkin or checkout. '
							 );				
						}
						   

				   }else{
				   
						$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'Checking type parameter should be either checkin or checkout. '
					   );
				   
				   } 

            }else{
						$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'No user found. '
					   );			
			
			}
		
		} 		
		$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));		  		  
	}

	function asset_check_history_get(){				
		$this->load->model('api_model');
		$this->load->model('Base_model');
		if(empty($_GET['asset_id']) ) {
			$response = array(
				'msg_code'	=> 404,
				'msg'		=> 'Fields cannot be blank'
			);
		} else { 
				$asset_id		= trim($_GET['asset_id']);
				
				if($this->api_model->field_exists_check('asset_id', $asset_id , 'as_assign_work_check_checkout') > 0) {								
						 $SQLCHIN = "SELECT CHK.*,  DUP.upro_first_name, DUP.upro_last_name 
									FROM as_assign_work_check_checkout AS CHK 
									LEFT JOIN demo_user_profiles AS DUP ON ( CHK.user_id = DUP.upro_uacc_fk AND CHK.asset_id = '".$asset_id."' )
									WHERE CHK.asset_id = '".$asset_id."'  ORDER BY added_time DESC , user_id"; 
						$checkinData = $this->Base_model->get_dbquery($SQLCHIN);
						if( isset($userDataArray)){ unset($userDataArray); }
						$userDataArray  = array();
						$userNameArray  = array();
						if($checkinData){
							
							foreach($checkinData AS $key=>$val){  
								$valFArray  = array();
								foreach($val AS $ky=>$vy){
									  if( $vy == '' || $vy == '0000-00-00 00:00:00'){										 
										  $val[$ky] = "";										 
									  }									 
								}								
							   $user_id  = $val['user_id'];
							   if( $user_id > 0 ){
							       if(array_key_exists($user_id, $userDataArray)){
								      $userDataArray[$user_id][] = $val;
									  $userNameArray[$user_id]  = trim($val['upro_first_name']).' '.trim($val['upro_last_name']);
								   }else{								     
									  $userDataArray[$user_id][] = $val;
									  $userNameArray[$user_id]  = trim($val['upro_first_name']).' '. trim($val['upro_last_name']);
								   }
							   }else{
							      $userDataArray['other'][] = $val;
							   }
							}
							$fArray  =array();
							foreach($userDataArray AS $key=>$val ){
								if(is_array($val)){  
									 $fArray[] = array('user_id'=>$key, 'name'=>$userNameArray[$key],'data'=>$val);
								}
							}

							$response = array(
								'msg_code'	=> 200,
								'msg'		=> 'Asset history',
								'asset_history'		=> $fArray
							);						    						
						}else{
								$response = array(
									'msg_code'	=> 404,
									'msg'		=> 'No asset history'
								);							
						}
													
				}else{
					$response = array(
						'msg_code'	=> 404,
						'msg'		=> 'No asset history'
					);						
				}										
		}        
		if( isset($response['asset_history'])){
			$this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg'], 'asset_history'=>$response['asset_history']));			
		}else{
          $this->response(array('msg_code'=>$response['msg_code'], 'msg'=>$response['msg']));		  
		}
		
	}


    public function dummy_notification_post() {
		$this->load->model('Base_model');
        if(strtolower($_POST['device_type']) == 'android') { 
            $this->Base_model->android_notification($_POST['device_token'], $_POST['title'], $_POST['msg']);
        } else {
            $this->Base_model->ios_notification($_POST['device_token'], $_POST['title'], $_POST['msg']);
        }
    }



	
}


