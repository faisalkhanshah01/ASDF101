<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Form_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
	    $this->auth = new stdClass;
		$this->load->library('flexi_auth');
		
		if (! $this->flexi_auth->is_logged_in_via_password()){
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		$this->data = null;
		$data = null;

	}
	/* Public Functions Start*/
	
	function index(){
		$this->Form_model->delete_session_values();
		$this->inspector();
	}
	
	
	function work_permit_form_inspection($id=''){
		if(!empty($id)){
			$this->load->model('Form_model');
			$work_no = $this->Form_model->check_work_permit_number($id);
				
			$work_number = '';
			if(!$work_no){
				$work_number = 'WORK-000001';
			}else{
				$workNo_array 	= explode('-',$work_no['workPermit_number']);
				$newWorkNo		= $workNo_array[1] + 1;
				/* To Get the string the specific format */
				$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
			}
			
			
			$site_array = $this->Form_model->get_siteID_data($id);
			$job_card 	= $site_array['site_jobcard'];
			$sms_no 	= $site_array['site_sms'];
			$master_table_details = $this->data['master_table_details']=$this->Form_model->master_table_details($job_card,$sms_no);
			if(!empty($master_table_details)){
				$asset_series 	= (!empty($master_table_details['mdata_item_series']))?json_decode($master_table_details['mdata_item_series']) : '';
				$this->data['list'] = array(
										'siteID_id' 		=> $id,
										'site_id' 			=> $site_array['site_id'],
										'job_card' 			=> $site_array['site_jobcard'],
										'sms_no' 			=> $site_array['site_sms'],
										'site_lattitude' 	=> $site_array['site_lattitude'],
										'site_longitude' 	=> $site_array['site_longitude'],
										'site_location' 	=> $site_array['site_location'],
										'site_address' 		=> $site_array['site_address'],
										'site_city'			=> $site_array['site_city'],
										'batch_no' 			=> $master_table_details['mdata_batch'],
										'serial_no' 		=> $master_table_details['mdata_serial'],
										'client_name' 		=> $master_table_details['client_name'],
										'client_id'	 		=> $master_table_details['mdata_client'],
										'asset_series_code' => $asset_series[0],
										'work_number' 		=> trim($work_number)
									);
			}
			//print_r($this->data);die('123');
			$this->load->view('inspector/work_permit_form_inspection',$this->data);
	    }			
		
	}
	
	function work_permit_form1($id=''){
		//print_r($id);die('123');
		if(isset($_POST['inspection_submit'])){
			$this->inspection_first_form_submit();
		}else{
			if(isset($_POST['work_permit'])){
				$this->form_validation->set_rules('permitDate_from','Permit Date From','required',  array('required' => '<p class="alert alert-danger capital">You must provide a %s.</p>'));
				$this->form_validation->set_rules('permitValid_upto','Permit Valid UPTO','required', array('required' => '<p class="alert alert-danger capital">You must provide a %s.</p>'));
				
				if(!$this->form_validation->run()==false){
					
					$result = $this->insert_workPermit_report();
					if($result){
						/* When Work Permit forms Inserted successfully. OPen the New Form of Taking RFID/UIN etc. */
						$_SESSION['inspector']['wpermit_id'] = $result;
						$this->inspection_form_rfid_uin($id);
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>All Dates Cannot be Empty. Please Provide all Dates.</p>');
						redirect('assign_siteid/assign_list/'.$id, 'refresh');
					}
				}else{
					$this->display_work_permit_form($id);
				}
			}else{
				$this->security();
				$this->display_work_permit_form($id);
			}
		}
	}
	
	function inspector_assign(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		$data = array();
		$this->load->model('SiteId_model');	
		$this->load->model('Form_model');	
		$assign_list = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$groupId,$userId);
		//print_r($assign_list);die;
		if(is_array($assign_list)){
			foreach($assign_list as $key=>$val){
				$data['assign_list'][$key] =  $val;
				$data['assign_list'][$key]['approved_status'] 	= $this->Form_model->get_approved_status($val['siteID_id']);
				$data['assign_list'][$key]['approved_admin_id'] = $this->Form_model->get_admin_approved_status($val['siteID_id']);
			}
		}
		//print_r($data);die("123");
		$this->load->view('inspector/inspector_inspection_list',$data);
	}
	
	
	function manage_inspector(){
		$userID = $_SESSION['flexi_auth']['id'];
		$group_id = key($_SESSION['flexi_auth']['group']);
		if(!empty($userID) && !empty($group_id)){
			$temp = array();
			$this->load->model('Inspector_inspection_model');
			if($group_id == 9){
				$assign_inspector_data =  $this->Inspector_inspection_model->get_assign_inspector_data($group_id);
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
				if($userID == $value){
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
					

					
					$temp[$key]['totalAsset'] 			= 	"$totalComponents";
					$temp[$key]['totalSubAsset']			=	"$totalsubComponent";
					$temp[$key]['totalAction_proposed']	=	"$countAction";
					$client_res = $this->Siteid_model->get_clientName_siteID(trim($value['site_jobcard']),trim($value['site_sms']));
					if(is_object($client_res)){
							$clientid = $client_res->mdata_client;
							$clientName = $client_res->client_name;
							$temp[$key]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
					}



					
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
					$temp1['manage_inspector'][$key]['id'] = $value['assgin_id'];
					$temp1['manage_inspector'][$key]['report_no'] = $value['report_no'];
					$temp1['manage_inspector'][$key]['client_name'] = $value['client_name'];
					$temp1['manage_inspector'][$key]['site_id'] = $value['site_id'];
					$temp1['manage_inspector'][$key]['job_card'] = $value['site_jobcard'];
					$temp1['manage_inspector'][$key]['sms'] = $value['site_sms'];
					$temp1['manage_inspector'][$key]['asset_series'] = $value['asset_series'];
					$temp1['manage_inspector'][$key]['approved_status'] = $value['approved_status'];
					$temp1['manage_inspector'][$key]['upro_first_name'] = $value['upro_first_name'];
					$temp1['manage_inspector'][$key]['upro_last_name'] = $value['upro_last_name'];
					$temp1['manage_inspector'][$key]['report'] = '<a target="_blank" title="" href="'.base_url().'clientuser_dashboard/inspectorInspectionListbyID?id='.$value['assgin_id'].'&userID='.$userID.'">'.
						'<span style="font-size: 30px;-webkit-align-content: center;align-content: center;" class="glyphicon glyphicon-eye-open"></span></a>';
				}
			}
			
		}
		//print_r($temp1);die;
		$this->load->view('assign_client/manage_inspector',$temp1);
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
	
	function inspector_inspection(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		$data = array();
		//print_r($userId);die;
		$dataInspector = $this->_inspectorID($userId,$groupId);
		
		foreach($dataInspector as $key => $value){
			//if($value['inspected_status'] == 'No'){
				$data['all_data'][$key] =  $value;
				$site_address = explode(',',$value['site_address']);
				$site_address = implode('<br/>',$site_address);
				$data['all_data'][$key]['site_address'] = 'Site Address : '.$site_address.'.<br/>'.'Site Location => '.$value['site_location'].'<br/>'.'Site City => '.$value['site_city'];
			//}	
		}
		//print_r($data['all_data']);die;
		$this->load->view('assign_client/inspector_all_view', $data);
	}
	
	function inspector_upcoming_data(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		$data = array();
		$dealerData = $this->_inspectorID($userId,$groupId);
		//print_r($dealerData);die;
		foreach($dealerData as $key => $value){
			if($value['inspected_status'] == 'No'){
				$data['upcoming_data'][$key] =  $value;
				$site_address = explode(',',$value['site_address']);
				$site_address = implode('<br/>',$site_address);
				$data['upcoming_data'][$key]['site_address'] = 'Site Address : '.$site_address.'.<br/>'.'Site Location => '.$value['site_location'].'<br/>'.'Site City => '.$value['site_city'];
				$data['upcoming_data'][$key]['inspector_details'] = 'Name : '.$value['inspector_details']['upro_first_name'].' '.$value['inspector_details']['upro_last_name'].'<br/>'.'Mobile No. :	 '.$value['inspector_details']['upro_phone'];
			}	
		}
		//print_r($data);
		$this->load->view('assign_client/inspector_upcoming_view',$data);
	}
	
	function _inspectorInspectionList($userId,$groupId){
		if(!empty($userId) && !empty($groupId)){
			$this->load->model('Inspector_inspection_model');
			if($groupId == 9){
				//print_r($userId);die(' 123');
				$result =  $this->Inspector_inspection_model->get_inspectionData_for_inspector($userId,'');
		    }else{
				$result =  $this->Inspector_inspection_model->get_client_siteID($userId);
			}
			return $result;
		}	
		
	}
	
	function inspector_past_data(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		//print_r($userId);print_r($groupId);die('  123');
		$data = array();
		$this->load->model('Siteid_model');
		$this->load->model('Assign_client_model');
		
		$inspecteList = $this->_inspectorInspectionList($userId,$groupId);
		//print_r($inspecteList);die;
		if(!empty($inspecteList) && is_array($inspecteList)){
			foreach($inspecteList as $key => $v){
				$data['past_data'][$key] =  $v;
				$client_res = $this->Siteid_model->get_clientName_siteID(trim($v['job_card']),trim($v['sms']));

				if(is_object($client_res)){
					$clientid = $client_res->mdata_client;
					$clientName = $client_res->client_name;
					$data['past_data'][$key]['client_name'] = (!is_numeric($clientid))? $clientid : $clientName;
				}
				
				$data['past_data'][$key]['id'] = '<a target="_blank" title="" href="'.base_url().'clientuser_dashboard/inspectorInspectionListbyID?id='.$v['id'].'&userID='.$userId.'">'.
						'<span style="font-size: 30px;-webkit-align-content: center;align-content: center;" class="glyphicon glyphicon-eye-open"></span></a>';
			}
		}	
		//print_r($data['past_data']);die;
		$this->load->view('assign_client/inspector_part_view',$data);
	} 
	
	function _inspectorID($userID,$groupID){
            if(!empty($userID) && ($groupID == 9)){
                $userID 		= $userID;
				$userGroupID 	= $groupID;
				
				$this->load->model('Form_model');
				$checkResult = $this->Form_model->check_data_by_user_in_tmp_table($userID, 'api');
				
				
				$this->load->model('Siteid_model');
				if($userGroupID == 9){  /* Inspector Site Id in database is 9 */
					if($site_id==''){
						$siteData = $this->Siteid_model->get_siteid_list_of_inspector('',$userGroupID,$userID);
						if(is_array($siteData)){
							$sdata = $siteData;
						}
					}else{
						$sitedata=$this->Siteid_model->get_siteid_item($site_id);
						if($sitedata){
							$sdata[] = $sitedata;
						}
					}
				}else{
					if($site_id==''){
						$siteData = $this->Siteid_model->get_siteid_list();
						if(is_array($siteData)){
							$sdata = $siteData;
						}
					}else{
						$sitedata[]=$this->Siteid_model->get_siteid_item($site_id);
						if($sitedata){
							$sdata[] = $sitedata;
						}
					}
				}
				
                
				$temp = array();
				if(!empty($sdata) && is_array($sdata)){
					$this->load->model('Assign_client_model');
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
				}   
            }
			//print_r($temp);die;
            return $sdata;
	}
	
	function security(){
		
		/* SECURITY : START OF LOGGED IN USER IF INSPECTOR */
		$group_array = $this->session->flexi_auth['group'];
		foreach($group_array as $key=>$val){
			$group_id = $key;
		}
		if($group_id == '9'){
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>You are Not Authorised to Enter. Please Contact Administration Department.</p>');
			redirect('auth/logout', 'refresh');
		}else{
			return true;
		}
		/* SECURITY : END OF LOGGED IN USER IF INSPECTOR */
	}
	
	function inspector(){
		// When A user login it will check the date of today's date with the date stored in today_date table
		// If date is different then truncate temp tables and update todats date in table today_date
		// This function is used so that when a user logged in for first time today 
		// then we should empty the temp tables.
		$this->Form_model->truncate_tables();
                $this->data['lang']  = $this->sma->get_lang_level('first');		
		$this->security();
		$this->Form_model->delete_session_values();
		$inspector_list = $this->Form_model->get_assigned_inspectorNames();
		
		$this->data['inspector_list'] = $inspector_list;
		//print_r($inspector_list);die(' 123');
		$_SESSION['inspector']['inspector_list'] = $inspector_list;
		if(isset($_POST['inspector_name'])){
			
			$selected_ins_id = $this->input->post('selected_inspector');
			
			//$inspector_list = $_SESSION['inspector']['inspector_list'];
			$_SESSION['inspector']['selected_inspector_name'] = $inspector_list[$selected_ins_id];
			$_SESSION['inspector']['selected_inspector_id'] = $selected_ins_id;
			$this->assign_list($selected_ins_id);
		}else{
			$this->load->view('inspector/select_inspector',$this->data);
		}
	}
	
	function assign_list_update($selected_ins_id =''){
		$this->security();
		$selected_ins_id = $_SESSION['inspector']['selected_inspector_id'];
		/* START : EMPTY DATA FROM TEMPORARY TABLES BEFORE LOADING THE ASSIGN PAGE */
		$checkResult = $this->Form_model->check_data_by_user_in_tmp_table($selected_ins_id, 'api');
		/* END : SESSION EMPTY*/

		$data['group_id'] = $group_id = '9';
		
			$this->load->model('SiteId_model');				
			$assign_list = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$group_id,$selected_ins_id);
			
			if(is_array($assign_list)){
				foreach($assign_list as $key=>$val){
					$assign_list[$key]['approved_status'] 	= $this->Form_model->get_approved_status($val['siteID_id']);
					$assign_list[$key]['approved_admin_id'] = $this->Form_model->get_admin_approved_status($val['siteID_id']);
				}
			}else{
				if($assign_list=='0001'){
					$err = "ERROR 0001 : No Site ID is assign to you";
				}else if($assign_list=='0002'){
					$err = "ERROR 0002 : Site IDs assigned to the Inspector are not present in siteID_data table.";
				}
				setMessages($err, 'error');
				$assign_list = '';
			}
			$data['assign_list'] = $assign_list;
			$this->load->view('inspector/assign_list',$data);
	}

	function assign_list($selected_ins_id =''){
		$this->security();
		$selected_ins_id = !empty($_SESSION['inspector']['selected_inspector_id'])?$_SESSION['inspector']['selected_inspector_id']:$_SESSION['flexi_auth']['id'];
		/* START : EMPTY DATA FROM TEMPORARY TABLES BEFORE LOADING THE ASSIGN PAGE */
		$checkResult = $this->Form_model->check_data_by_user_in_tmp_table($selected_ins_id, 'api');
		/* END : SESSION EMPTY*/

		$data['group_id'] = $group_id = '9';
		
			$this->load->model('SiteId_model');				
			$assign_list = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$group_id,$selected_ins_id);
			
			if(is_array($assign_list)){
				foreach($assign_list as $key=>$val){
					$assign_list[$key]['approved_status'] 	= $this->Form_model->get_approved_status($val['siteID_id']);
					$assign_list[$key]['approved_admin_id'] = $this->Form_model->get_admin_approved_status($val['siteID_id']);
				}
			}else{
				if($assign_list=='0001'){
					$err = "ERROR 0001 : No Site ID is assign to you";
				}else if($assign_list=='0002'){
					$err = "ERROR 0002 : Site IDs assigned to the Inspector are not present in siteID_data table.";
				}
				setMessages($err, 'error');
				$assign_list = '';
			}
			$data['assign_list'] = $assign_list;
			$this->load->view('inspector/assign_list',$data);
	}
	
	/* First Form : Inspector Assign Site ID */
	function work_permit_form($id=''){
		
		if(isset($_POST['inspection_submit'])){
			$this->inspection_first_form_submit();
		}else{
			if(isset($_POST['work_permit'])){
				$this->form_validation->set_rules('permitDate_from','Permit Date From','required',  array('required' => '<p class="alert alert-danger capital">You must provide a %s.</p>'));
				$this->form_validation->set_rules('permitValid_upto','Permit Valid UPTO','required', array('required' => '<p class="alert alert-danger capital">You must provide a %s.</p>'));
				
				if(!$this->form_validation->run()==false){
					$result = $this->insert_workPermit_report();
					if($result){
						/* When Work Permit forms Inserted successfully. OPen the New Form of Taking RFID/UIN etc. */
						$_SESSION['inspector']['wpermit_id'] = $result;
						$this->inspection_form_rfid_uin($id);
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>All Dates Cannot be Empty. Please Provide all Dates.</p>');
						redirect('assign_siteid/assign_list/'.$id, 'refresh');
					}
				}else{
					$this->display_work_permit_form($id);
				}
			}else{
				$this->security();
				$this->display_work_permit_form($id);
			}
		}
	}
	
	function display_work_permit_form($id){
		
		/* Check for any work Permit Number, If exist then increment the last work permit no. */
		
			if(isset($_SESSION['inspector']['workPermit'])){
				// if Session WorkPermit exists then form should be updated else inserted
				$work_number = $_SESSION['inspector']['workPermit']['workPermit_number'];
				$_SESSION['inspector']['form'] ='update';
			}else{
				$work_no = $this->Form_model->check_work_permit_number($id);

				$work_number = '';
				if(!$work_no){
					$work_number = 'WORK-000001';
				}else{
					$workNo_array 	= explode('-',$work_no['workPermit_number']);
					$newWorkNo		= $workNo_array[1] + 1;
					/* To Get the string the specific format */
					$work_number = $workNo_array[0].'-'.sprintf("%'.06d\n", $newWorkNo);
				}
			}
		/* End of Work Permit Number */
			
			$site_array = $this->Form_model->get_siteID_data($id);
			$job_card 	= $site_array['site_jobcard'];
			$sms_no 	= $site_array['site_sms'];
			$master_table_details = $this->data['master_table_details']=$this->Form_model->master_table_details($job_card,$sms_no);
			if(!empty($master_table_details)){
				$asset_series 	= (!empty($master_table_details['mdata_item_series']))?json_decode($master_table_details['mdata_item_series']) : '';
				$this->data['list'] = array(
										'siteID_id' 		=> $id,
										'site_id' 			=> $site_array['site_id'],
										'job_card' 			=> $site_array['site_jobcard'],
										'sms_no' 			=> $site_array['site_sms'],
										'site_lattitude' 	=> $site_array['site_lattitude'],
										'site_longitude' 	=> $site_array['site_longitude'],
										'site_location' 	=> $site_array['site_location'],
										'site_address' 		=> $site_array['site_address'],
										'site_city'			=> $site_array['site_city'],
										'batch_no' 			=> $master_table_details['mdata_batch'],
										'serial_no' 		=> $master_table_details['mdata_serial'],
										'client_name' 		=> $master_table_details['client_name'],
										'client_id'	 		=> $master_table_details['mdata_client'],
										'asset_series_code' => $asset_series[0],
										'work_number' 		=> trim($work_number)
									);
			}
			$this->load->view('inspector/work_permit_form',$this->data);
	}
	
	function insert_workPermit_report(){
		
		$_SESSION['inspector']['form_submitting_date'] = $this->input->post('today_date');
		
		$returnID = $this->insert_inspection_list_1_temp();
		
		$data= array(
					'client_name'					=>	$_SESSION['inspector']['work_permit_client_name'],
					'today_date'					=>	$this->input->post('today_date'),
					'workPermit_number'				=>	$_SESSION['inspector']['work_permit_workPermit_number'],
				//	'siteId_name'					=>	$_SESSION['inspector']['work_permit_siteID_name'],
					'siteId_name'					=>	$returnID,
					'permitDate_from'				=>	$this->input->post('permitDate_from'),
					'permitValid_upto'				=>	$this->input->post('permitValid_upto'),
					'siteID_address'				=>	$_SESSION['inspector']['work_permit_siteID_address'],
					'gprs_lattitude'				=>	$_SESSION['inspector']['workPermit_lattitude'],
					'gprs_longitude'				=>	$_SESSION['inspector']['workPermit_longitude'],
					'asset_series'					=>	$_SESSION['inspector']['workPermit_asset_series'],
					'batch_no'						=>	$_SESSION['inspector']['workPermit_batch_no'],
					'serial_no'						=>	$_SESSION['inspector']['workPermit_serial_no'],
					'harness'						=>	$this->input->post('harness'),
					'helmet'						=>	$this->input->post('helmet'),
					'shoes'							=>	$this->input->post('shoes'),
					'gloves'						=>	$this->input->post('gloves'),
					'goggle'						=>	$this->input->post('goggle'),
					'work_position'					=>	$this->input->post('work_position'),
					'equipment_use'					=>	$this->input->post('equipment_use'),
					'worker_trained'				=>	$this->input->post('worker_trained'),
					'worker_safety'					=>	$this->input->post('worker_safety'),
					'worker_height'					=>	$this->input->post('worker_height'),
					'worker_defensive'				=>	$this->input->post('worker_defensive'),
					'weather_good'					=>	$this->input->post('weather_good'),
					'enough_light'					=>	$this->input->post('enough_light'),
					'site_accessable'				=>	$this->input->post('site_accessable'),
					'equipment_available'			=>	$this->input->post('equipment_available'),
					'unguarded_edges'				=>	$this->input->post('unguarded_edges'),
					'equipment_condition'			=>	$this->input->post('equipment_condition'),
					'wiring_condition'				=>	$this->input->post('wiring_condition'),
					'equipment_calibrated'			=>	$this->input->post('equipment_calibrated'),
					'physically_fitness'			=>	$this->input->post('physically_fitness'),
					'work_at_height'				=>	$this->input->post('work_at_height'),
					'alcohol_influence'				=>	$this->input->post('alcohol_influence'),
					'mediclaim_insured'				=>	$this->input->post('mediclaim_insured'),
					'carry_firstAid'				=>	$this->input->post('carry_firstAid'),
					'client_approval'				=>	$this->input->post('client_approval'),
					'documentation_with_client'		=>	$this->input->post('documentation_with_client')
					);
		$_SESSION['inspector']['workPermit'] = $data;
		if(isset($_SESSION['inspector']['form'])){
			return $this->Form_model->update_work_permit($_SESSION['inspector']['workPermit']['workPermit_number'], $data);
		}else{
			$data['worker_trained'] = 'no';
			$data['worker_safety'] = 'no';
			$data['worker_defensive'] = 'no';
			$data['enough_light'] = 'no';
			$data['site_accessable'] = 'no';
			$data['equipment_available'] = 'no';
			$data['equipment_condition'] = 'no';
			$data['wiring_condition'] = 'no';
			$data['work_at_height'] = 'no';
			$data['carry_firstAid'] = 'no';
			return $this->Form_model->insert_work_permit($data);
		}
	}
	
	/* 	On Inserting the first form. Enter the Site id in Inspection temp table first.
		Then save it's id in workpermit form.
	*/
	function insert_inspection_list_1_temp(){
		if(isset($_SESSION['inspector']['temp1_return_id'])){
			$dbdata=array(
						'siteID_id'		=>$_SESSION['inspector']['work_permit_siteID_name'],
						'created_date'	=>$_SESSION['inspector']['form_submitting_date'],
						'asset_series'	=>$_SESSION['inspector']['workPermit_asset_series'],
						//'inspected_by'	=>!empty($_SESSION['flexi_auth']['id'])?$_SESSION['flexi_auth']['id']:$_SESSION['inspector']['selected_inspector_id'],
						'inspected_by'	=>!empty($_SESSION['inspector']['selected_inspector_id'])?$_SESSION['inspector']['selected_inspector_id']:$_SESSION['flexi_auth']['id'],
					);
			return $this->Form_model->first_slot_data_update($_SESSION['inspector']['temp1_return_id'],$dbdata);
		}else{
			
			$dbdata=array(
				'wpermit_id'	=>'',
				'siteID_id'		=>$_SESSION['inspector']['work_permit_siteID_name'],
				'job_card'		=>'',
				'sms'			=>'', 
				'created_date'	=>$_SESSION['inspector']['form_submitting_date'],
				'site_id'		=>'',
				'asset_series'	=>$_SESSION['inspector']['workPermit_asset_series'],
				'lattitude'		=>'', 
				'longitude'		=>'',
				'inspected_by'	=>!empty($_SESSION['flexi_auth']['id'])?$_SESSION['flexi_auth']['id']:$_SESSION['inspector']['selected_inspector_id'], 
				//'inspected_by'	=>$_SESSION['inspector']['selected_inspector_id'], 
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
			//print_r($dbdata);die(' 123');
			return $_SESSION['inspector']['temp1_return_id'] = $this->Form_model->first_slot_data_insert($dbdata);
		}
	}
	
	function inspection_form_rfid_uin($id=''){
		
			/* Get Admin Data From Session */
				$userdata 		= $this->session->userdata();
				$admin_name 	= $userdata['firstName'].' '.$userdata['lastName'];
				$flexi_auth 	= $userdata['flexi_auth'];
				$admin_id 		= $flexi_auth['id'];
				$_SESSION['inspector']['admin_id']		= $admin_id;
				$_SESSION['inspector']['admin_name']	= $admin_name;
			/* End of Admin Data from Session */
			
			$site_array = $this->Form_model->get_siteID_data($id);

			$_SESSION['inspector']['siteID_id'] 	= $siteID_id 		= $id;
			$_SESSION['inspector']['siteid'] 		= $site_id 			= $site_array['site_id'];
			$_SESSION['inspector']['job_card'] 		= $job_card 		= $site_array['site_jobcard'];
			$_SESSION['inspector']['sms_no'] 		= $sms_no 			= $site_array['site_sms'];
			$_SESSION['inspector']['lattitude'] 	= $site_lattitude 	= $site_array['site_lattitude'];
			$_SESSION['inspector']['longitude'] 	= $site_longitude 	= $site_array['site_longitude'];
			$site_location 				= $site_array['site_location'];
			$site_address 				= $site_array['site_address'];
			$site_city					= $site_array['site_city'];
					
			/* Start : Check if Report No. exist for this SiteID, Job Card And SMS in database table or not */
				$this->load->model('Form_model');
				$reportNo = $this->Form_model->check_report_numbers($siteID_id);
				if(is_array($reportNo)){
					$report_array 	= explode('-',$reportNo['report_no']);
					$newReportvalue = $report_array[1] + 1;
					/* To Get the string in the specific format */
					$new_reportNo = $report_array[0].'-'.sprintf("%'.04d\n", $newReportvalue);
					$_SESSION['inspector']['report_no'] = trim($new_reportNo);
					
				}else{
					$year									= date("Y");
					$_SESSION['inspector']['report_no']		= $id.'_'.$year."-0001";
				}
				$_SESSION['inspector']['inspected_status'] 	= 'No';
				$_SESSION['inspector']['approved_status'] 	= 'Pending';
			/* End : Of Checking to Report No existence */
			
			$master_table_details = $this->data['master_table_details']=$this->Form_model->master_table_details($job_card,$sms_no);
			if(!empty($master_table_details)){
					$_SESSION['inspector']['pono'] 			= $po_no 			= $master_table_details['mdata_po'];
					$_SESSION['inspector']['batch_no'] 		= $batch_no 		= $master_table_details['mdata_batch'];
					$_SESSION['inspector']['serial_no'] 	= $serial_no 		= $master_table_details['mdata_serial'];
					$_SESSION['inspector']['client_name'] 	= $client_name 		= $master_table_details['client_name'];
					$_SESSION['inspector']['mdata_rfid'] 	= $mdata_rfid 	= $master_table_details['mdata_rfid'];
					$_SESSION['inspector']['mdata_barcode'] = $mdata_barcode 	= $master_table_details['mdata_barcode'];
					$_SESSION['inspector']['mdata_uin'] 	= $mdata_uin 		= $master_table_details['mdata_uin'];
					$_SESSION['inspector']['client_id']		= $client_id	 	= $master_table_details['mdata_client'];
					$asset_series 	= (!empty($master_table_details['mdata_item_series']))? json_decode($master_table_details['mdata_item_series']) : '';
				
				if($asset_series!=''){
					/* Assuming Only One Asset Series is Assigned for The Job Card and SMS Number. */
					$_SESSION['inspector']['asset_series'] = $asset_series[0];
					$get_assets = $this->data['get_assets']=$this->Form_model->get_assets($asset_series[0]);
					
					$assetSeries_asset_val	= json_decode($get_assets['product_components']);
					
					$final_asset = array();
					foreach($assetSeries_asset_val as $aKey=>$assets){
						$asset_code = array();
						$get_asset_sms = $this->Form_model->get_sms_component($job_card,$sms_no,$asset_series[0],$assets);
						
						// Get Qunatity of Asset, If Asset Quantity is 0, do not display it.
						if(($get_asset_sms) && $get_asset_sms['item_quantity']!='0'){
							
							// Get Asset UOM : 
							$fetch_asset_uom 	= $this->Form_model->get_asset_values($assets);
							if($fetch_asset_uom){
								//$asset_code['name']	= $assets;
								$asset_code['item_quantity']	= $get_asset_sms['item_quantity'];
								
								if(isset($fetch_asset_uom['component_sub_assets'])){
									// It is Asset
									$asset_code['asset_uom']		= $fetch_asset_uom['uom'];
									
									if($fetch_asset_uom['component_sub_assets']!='' || $fetch_asset_uom['component_sub_assets'] !=NULL){
										// Asest have Subasset
										$asset_code['contain_subasset']	= 'Yes';
										$asset_code['subassets']	= $fetch_asset_uom['component_sub_assets'];
									}
								}else{
									// it is Subasset
									$asset_code['asset_uom']		= $fetch_asset_uom['uom'];
								}
							}else{
								$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Asset Quantity isnot Assigned in SMS Component. Please First Assign the Asset quantity and Try Again.</p>');
								redirect('form_controller/inspector', 'refresh');
							}
							$final_asset[$assets] = $asset_code;
							
						}
					}
					
					$_SESSION['inspector']['assets_values'] 	= (isset($final_asset))? $final_asset : '';
					$array = $this->data['details'] = array(
													'site_id'		 =>		$site_id,
													'job_card' 		 =>		$job_card,
													'site_address' 	 =>		$site_address,
													'po_no' 		 =>		$po_no,
													'batch_no' 		 =>		$batch_no,
													'serial_no' 	 =>		$serial_no,
													'asset_series' 	 =>		$asset_series[0],
													'sms_no' 		 =>		$sms_no,
													'client_name' 	 =>		$client_name,
													'inspector_name' 	 =>	$_SESSION['inspector']['selected_inspector_name'],
													'site_location'  =>		$site_location,
													'report_no'  	 =>		$_SESSION['inspector']['report_no'],
													'site_city' 	 =>		$site_city
					);
				
					$this->load->view('inspector/inspection_first_form', $this->data);
				}else{
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>No Asset Series is assigned on this Job Card and SMS Number. Please First Assign the Asset Series and Try Again.</p>');
					redirect('form_controller/inspector', 'refresh');
				}
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Required data is not in the database</p>');
				redirect('assign_siteid/inspector', 'refresh');
			}
	}

	function inspection_first_form_submit(){
		if (isset($_POST['inspection_submit'])){
			
			/* When Data is Submitted from 1st Form */
			$approvedby		=	'';
			$reportno		=	$_SESSION['inspector']['report_no'];
			$siteid			=	$_SESSION['inspector']['siteid'];
			$date			=	$_SESSION['inspector']['form_submitting_date'];
			$jobcardno		=	$_SESSION['inspector']['job_card'];
			$smsno			=	$_SESSION['inspector']['sms_no'];
			$asset_series	=	$_SESSION['inspector']['asset_series'];
			$pono			=	$_SESSION['inspector']['pono'];
			
			$_SESSION['inspector']['inspectedby']	=	$inspectedby	=	$this->input->post('inspectedby');
			$_SESSION['inspector']['lattitude']		=	$lattitude		=	$this->input->post('lattitude');
			$_SESSION['inspector']['longitude']		=	$longitude		=	$this->input->post('longitude');
			$_SESSION['inspector']['inputmethod']	=	$inputmethod	=	$this->input->post('inputmethod');
			$_SESSION['inspector']['inputvalue']	=	$Inputvalue		=	$this->input->post('Inputvalue');
			$dbdata=array(
				'wpermit_id'	=>$_SESSION['inspector']['wpermit_id'],
				'siteID_id'		=>$_SESSION['inspector']['siteID_id'],
				'job_card'		=>$jobcardno,
				'sms'			=>$smsno, 
				'created_date'	=>$date,
				'site_id'		=>$siteid,
				'asset_series'	=>$asset_series, 
				'lattitude'		=>$lattitude, 
				'longitude'		=>$longitude,
//'inspected_by'	=>!empty($_SESSION['flexi_auth']['id'])?$_SESSION['flexi_auth']['id']:$_SESSION['inspector']['selected_inspector_id'], 
				'inspected_by'	=>!empty($_SESSION['inspector']['selected_inspector_id'])?$_SESSION['inspector']['selected_inspector_id']:$_SESSION['flexi_auth']['id'], 
				'approved_by'	=>$approvedby,
				'input_method'	=>$inputmethod,
				'input_value'	=>$Inputvalue,
				'report_no'		=>$reportno,
				'inspector_signature_image'	=>  '',
				'client_name'				=>	'',
				'client_designation'		=>	'',
				'client_signature_image'	=>	'',
				'client_remark'				=>	''
			);
			
	
			/* Insert Google Map Location Image */			
			if($lattitude =='' || $longitude==''){
				$dbdata['map_image'] = $image_path = "FCPATH/uploads/images/users/default.jpg";
			}else{
				$this->load->model('Form_model');
				$dbdata['map_image'] = $this->Form_model->get_google_map_image($lattitude,$longitude);
			}
			
			
			$_SESSION['inspector']['rfidForm'] = $dbdata;
			
			$result= $this->Form_model->first_slot_data_update($_SESSION['inspector']['temp1_return_id'],$dbdata);
			
			if($result){
				$_SESSION['inspector']['form_one_inserted_id'] =	$result;
				redirect('form_controller/inspection_second_form');
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital">Inspection Form Not Completed</p>');
				redirect('form_controller/inspection_form_rfid_uin', 'refresh');
			}
		}
	}

	/*	Second Form : Inspector Assign Site ID */
	function inspection_second_form(){

		/* When Final CLick button is Clicked*/
		if(isset($_POST['inspection_second_form'])){
			redirect('form_controller/inspector_information_form');
		}
		// echo "<pre>";
		// print_r($_SESSION['inspector']);
		// echo "</pre>";
		$this->load->view('inspector/inspection_second_form');
	}
	
	/* Third Form : Inspector Assign Site ID */
	function inspect_asset(){
		$current_asset_code	= (isset($_REQUEST['asset_code']) && $_REQUEST['asset_code'] !='')? $_REQUEST['asset_code']: $_SESSION['inspector']['current_asset_code'];
			$_SESSION['inspector']['current_asset_code'] = $current_asset_code;
			
			$current_asset_id	= (isset($_REQUEST['count_asset_id']) && $_REQUEST['count_asset_id'] !='')? $_REQUEST['count_asset_id']: $_SESSION['inspector']['current_asset_id'];
			$_SESSION['inspector']['current_asset_id'] = $current_asset_id;
		
		if(!isset($_POST['get_inspect_asset'])){
			/* Display of form three when Values are not submitted */
			$_SESSION['inspector']['asset_code']	=	$asset_code		=	$_GET['asset_code'];

			/* get_type_category START */
				$get_values 		= $this->Form_model->get_asset_values($asset_code);
				$inspectionType		= (is_array($get_values))? $get_values['insType'] : '';
				$expectedresult		= (is_array($get_values))? $get_values['result'] : '';
				$observation		= (is_array($get_values))? $get_values['observation'] : '';
			/* get_type_category END */
			
			$_SESSION['inspector']['inspection'] = ($inspectionType !='' && !empty($this->Form_model->get_inspection_value($inspectionType)))? $this->Form_model->get_inspection_value($inspectionType) : '';
				
				if($expectedresult!=''){
					$res_array = json_decode($expectedresult);
					$expRes = array();
					foreach($res_array as $resKey=>$resVal){
						$expRes[$resVal] = $this->Form_model->get_inspection_value($resVal);
					}
					$_SESSION['inspector']['result'] = $expRes;
				}else{
					$_SESSION['inspector']['result'] = '';
				}
				if($observation!=''){
					$obs_array = json_decode($observation);
					$obsRes = array();
					foreach($obs_array as $obsVal){
						$obsRes[$obsVal] = $this->Form_model->get_inspection_value($obsVal);
					}
					$_SESSION['inspector']['observation'] = $obsRes;
				}else{
					$_SESSION['inspector']['observation'] = '';
				}
			$this->load->view('inspector/inspect_asset_form');
		}
		else if(isset($_POST['get_inspect_asset'])){
			/* Insert or Update of form three when Values are submitted */
			if($this->insert_inspect_asset_or_subAsset()){
				if(!in_array($_SESSION['inspector']['current_asset_id'], $_SESSION['inspector']['completed_assets'])){
					$_SESSION['inspector']['completed_assets'][] = $_SESSION['inspector']['current_asset_id'];
				}
				//$_SESSION['inspector']['completed_assets'][] = $_SESSION['inspector']['current_asset_id'];

				redirect('form_controller/inspection_second_form');
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Asset not submitted</p>');
				redirect('form_controller/inspection_second_form');
			}
		}	
	}
	
	/* Third Form : If Asset has Sub Assets then Open this Form */
	function inspect_form_third(){
		
		/* When Final Click button is Clicked */
		if(isset($_POST['inspection_third_form'])){
			if(!in_array($_SESSION['inspector']['current_asset_id'], $_SESSION['inspector']['completed_assets'])){
				$_SESSION['inspector']['completed_assets'][] = $_SESSION['inspector']['current_asset_id'];
			}
			unset($_SESSION['inspector']['total_subAsset_id']);
			unset($_SESSION['inspector']['completed_sub_assets']);
			unset($_SESSION['inspector']['current_subAsset_code']);
			unset($_SESSION['inspector']['current_subAsset_id']);
			unset($_SESSION['inspector']['current_asset_code']);
			unset($_SESSION['inspector']['current_asset_id']);
			
			redirect('form_controller/inspection_second_form');
		}else{
			$current_asset_code	= (isset($_REQUEST['asset_code']) && $_REQUEST['asset_code'] !='')? $_REQUEST['asset_code']: $_SESSION['inspector']['current_asset_code'];
			$_SESSION['inspector']['current_asset_code'] = $current_asset_code;
			
			$current_asset_id	= (isset($_REQUEST['asset_id']) && $_REQUEST['asset_id'] !='')? $_REQUEST['asset_id']: $_SESSION['inspector']['current_asset_id'];
			$_SESSION['inspector']['current_asset_id'] = $current_asset_id;
			
			/* Get Asset Code on which behalf we will save subassets and assign it in session */
			$_SESSION['inspector']['asset_containing_subasset'] = $_SESSION['inspector']['assets_values'][$current_asset_code]['subassets'];
			$this->load->view('inspector/inspection_third_form');
		}
	}
	
	function get_actionProposed(){
		$id = $_POST['id'];
		$type = $_POST['type'];
		return $this->Form_model->get_action_proposed($id,$type);
	}
	
	function inspect_subAsset(){
			$current_subAsset_code	= (isset($_REQUEST['subAsset_code']) && $_REQUEST['subAsset_code'] !='')? $_REQUEST['subAsset_code']: $_SESSION['inspector']['current_subAsset_code'];
			$_SESSION['inspector']['current_subAsset_code'] = $current_subAsset_code;
			
			$current_subAsset_id	= (isset($_REQUEST['count_subAsset_id']) && $_REQUEST['count_subAsset_id'] !='')? $_REQUEST['count_subAsset_id']: $_SESSION['inspector']['current_subAsset_id'];
			$_SESSION['inspector']['current_subAsset_id'] = $current_subAsset_id;
			
		if(!isset($_POST['get_inspect_subAsset'])){
			/* Display subasset form when Values are not submitted */
			$_SESSION['inspector']['subAsset_code']	=	$subAsset_code	=	$_GET['subAsset_code'];

			/* get_type_category START */
			$get_values 		= $this->Form_model->fetch_subAsset_records($subAsset_code);
			$inspectionType		= (is_array($get_values))? $get_values['insType'] : '';
			$expectedresult		= (is_array($get_values))? $get_values['result'] : '';
			$observation		= (is_array($get_values))? $get_values['observation'] : '';
			/* get_type_category END */
			$_SESSION['inspector']['subAsset_inspection'] = ($inspectionType !='' && !empty($this->Form_model->get_inspection_value($inspectionType)))? $this->Form_model->get_inspection_value($inspectionType) : '';
				
				if($expectedresult	!=	''){
					$res_array 	= json_decode($expectedresult);
					$expRes 	= array();
					foreach($res_array as $resKey=>$resVal){
						$expRes[$resVal] = $this->Form_model->get_inspection_value($resVal);
					}
					$_SESSION['inspector']['subAsset_result'] = $expRes;
				}else{
					$_SESSION['inspector']['subAsset_result'] = '';
				}
				if($observation	!=	''){
					$obs_array 	= json_decode($observation);
					$obsRes 	= array();
					foreach($obs_array as $obsVal){
						$obsRes[$obsVal] = $this->Form_model->get_inspection_value($obsVal);
					}
					$_SESSION['inspector']['subAsset_observation'] = $obsRes;
				}else{
					$_SESSION['inspector']['subAsset_observation'] = '';
				}
			$this->load->view('inspector/inspect_subAsset_form');
		}else if(isset($_POST['get_inspect_subAsset'])){
			if($this->insert_inspect_asset_or_subAsset()){
				
				if(!in_array($_SESSION['inspector']['current_subAsset_id'], $_SESSION['inspector']['completed_sub_assets'])){
					$_SESSION['inspector']['completed_sub_assets'][] = $_SESSION['inspector']['current_subAsset_id'];
				}
				redirect('form_controller/inspect_form_third');
			}else{
				//$_SESSION['inspector']['subAsset_count_left'] -=1;
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Sub Asset not submitted</p>');
				redirect('form_controller/inspect_form_third?asset_code="'.$_SESSION['inspector']['current_asset_code'].'"');
			}
		}
	}
	
	function insert_inspect_asset_or_subAsset(){
	
		/* Insert or Update of form three when Values are submitted */
		/* Start of Upload Before and After Image */
		if($_FILES["before_repair_image"]["name"][0] != ''){
			$before_image_result = $this->before_image_upload();
		}
		$before_repair_image = ($_FILES["before_repair_image"]["name"][0] != '')? json_encode($before_image_result) : '';
		
		if($_FILES["after_repair_image"]["name"][0] != ''){
			$after_image_result = $this->after_image_upload();
		}
		$after_repair_image = ($_FILES["after_repair_image"]["name"][0] != '')? json_encode($after_image_result) : '';
		/* End of Upload Before and After Image */
		$inspection_type	=	$this->input->post('inspection_type');
		$observation_type	=	$this->input->post('obs');
		if(!isset($_SESSION['inspector']['current_asset_code'])){
			$_SESSION['inspector']['before_repair_image'] 	= $before_image_result;
			$_SESSION['inspector']['after_repair_image'] 	= $after_image_result;
			$_SESSION['inspector']['inspection_type']		= $inspection_type;
			$_SESSION['inspector']['observation_type']		= $observation_type;
		}else{
			$_SESSION['inspector']['subAsset_before_repair_image']	= $before_image_result;
			$_SESSION['inspector']['subAsset_after_repair_image'] 	= $after_image_result;
			$_SESSION['inspector']['subAsset_inspection_type']		= $inspection_type;
			$_SESSION['inspector']['subAsset_observation_type']		= $observation_type;
		}
		
		// save into db
		$dbdata	=	array(
						'inspection_list_id'		=>	$_SESSION['inspector']['form_one_inserted_id'],
						'asset_name'				=>	(isset($_SESSION['inspector']['current_asset_code']))? $_SESSION['inspector']['current_asset_code']: $this->input->post('asset_name') ,
						'subAsset_name'				=>	(isset($_SESSION['inspector']['current_asset_code']))? $this->input->post('asset_name'): '0',
						'before_repair_image'		=>	$before_repair_image,
						'after_repair_image'		=>	$after_repair_image,
						'observation_type'			=>	json_encode($observation_type),
						'action_proposed'			=>	'',
						'result'					=>	''						
					);
		
		$result	= $this->Form_model->second_slot_data_inserted($dbdata);
		if($result > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function before_image_upload(){
		$before_array			= array();
		$before_images_array 	= $_FILES["before_repair_image"];
		$count = count($before_images_array['name']);
		
		for($i= 0; $i < $count; $i++){
			$confirm_code	=	md5(uniqid(rand()));
			$allowedExts 	= 	array("jpg", "jpeg", "gif", "png");
			$ext 			= 	explode(".", $before_images_array["name"][$i]);
			$extension 		= 	strtolower($ext[1]);
			$beforefile		=	$confirm_code.'.'.$extension;
			$new_path 		= 	"./uploads/inspected_img/".date('d-m-Y')."/";
			$this->load->model('Demo_auth_model');
			$this->Demo_auth_model->check_directory($new_path);
			
			$before_array[] = 'FCPATH/uploads/inspected_img/'.date('d-m-Y').'/'.$beforefile;
			if ((in_array($extension, $allowedExts))){
				if ($before_images_array["error"][$i] > 0){
					//$_SESSION['inspector']['subAsset_count_left'] -= 1;		/* Decement the count If Image upload fails */
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid Before Image file</p>');
					redirect('form_controller/inspection_second_form', 'refresh');
				}
				else{
					$dir = FCPATH."uploads/inspected_img/".date('d-m-Y')."/";
					move_uploaded_file($before_images_array["tmp_name"][$i], $dir.$beforefile);
					$file_path	= $dir.$beforefile;
					return $before_array;
				}
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid Image file extension.</p>');
				redirect('form_controller/inspection_second_form', 'refresh');
			}
		}
	}
	
	function after_image_upload(){
		$after_array		= 	array();
		$after_images_array = 	$_FILES["after_repair_image"];
		$count1 			= 	count($after_images_array['name']);
		
		for($j= 0; $j < $count1; $j++){
			$confirm_code	= md5(uniqid(rand()));
			$allowedExts 	= array("jpg", "jpeg", "gif", "png");
			$ext 			= explode(".", $after_images_array["name"][$j]);
			$extension 		= strtolower($ext[1]);
			$afterfile		= $confirm_code.'.'.$extension;
			$new_path 		= "./uploads/inspected_img/".date('d-m-Y')."/";
			$this->load->model('Demo_auth_model');
			$this->Demo_auth_model->check_directory($new_path);
			
			$after_array[] = 'FCPATH/uploads/inspected_img/'.date('d-m-Y').'/'.$afterfile;
			if ((in_array($extension, $allowedExts))){
				if ($after_images_array["error"][$j] > 0){
					//$_SESSION['inspector']['subAsset_count_left'] -=	1;    /* Decement the count If Image upload fails */
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid After Repair Image. Only .jpeg, .gif and .png type files allowed</p>');
					redirect('form_controller/inspection_second_form');
				}else{
					$dir = FCPATH."uploads/inspected_img/".date('d-m-Y')."/";
					move_uploaded_file($after_images_array["tmp_name"][$j], $dir.$afterfile);
					$file_path	= $dir.$afterfile;
					return $after_array;
				}
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid After Repair Image. Only .jpeg, .gif and .png type files allowed</p>');
				redirect('form_controller/inspection_second_form');
			}
		}
	}
	
	function inspector_information_form(){
		
		$_SESSION['inspector']['user_designation'] = 'Inspector';
		
		if(isset($_POST['inspector_information_form'])){
			if($_FILES["signature_image"]["name"] != ''){
					$confirm_code=md5(uniqid(rand()));
					$_SESSION['inspector']['inspector_signature_image'] = $signature	=	$confirm_code.$_FILES["signature_image"]["name"];
					$allowedExts = array("jpg", "jpeg", "gif", "png");
					$ext = explode(".", $_FILES["signature_image"]["name"]);
					$extension = $ext[1];
					
					if ((in_array($extension, $allowedExts))){
						if ($_FILES["signature_image"]["error"] > 0){
							$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid file type</p>');
							redirect('form_controller/inspector_information_form', 'refresh');
						}else{
							$dir = FCPATH."uploads/inspector_sign/";
							move_uploaded_file($_FILES["signature_image"]["tmp_name"], $dir.$signature);
							$file_path= "FCPATH/uploads/inspector_sign/".$signature;
						}
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid File extension of inspector image</p>');
						redirect('form_controller/inspection_second_form', 'refresh');
					}
			}else{
				$file_path ='';
			}
			
			$id = $_SESSION['inspector']['form_one_inserted_id'];
			$dbdata = array(
							'inspector_signature_image' => $file_path
							);
			$result	= $this->Form_model->third_slot_data_insert($id,$dbdata);
			if($result){
				redirect('form_controller/client_information_form');
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b> Error In Inserting Data In Database</p>');
				redirect('form_controller/inspector_information_form', 'refresh');
			}
		}
		$this->load->view('inspector/inspector_information_form');
	}
	
	function client_information_form(){
		$group = $_SESSION['flexi_auth']['group'];
		foreach($group as $val){
			$group_name = $val;
		}
		$_SESSION['inspector']['user_designation'] = $group_name;
		
		if(isset($_POST['client_information_form'])){
			
			$client_name 		= 	ucwords(strtolower(trim($this->input->post('client_name'))));
			$client_designation = 	ucwords(strtolower(trim($this->input->post('client_designation'))));
			$client_remarks 	= 	trim($this->input->post('client_remark'));
			
			if($_FILES["signature_image"]["name"] != ''){
					$confirm_code=md5(uniqid(rand()));
					$_SESSION['inspector']['client_signature_image'] = $signature	=	$confirm_code.$_FILES["signature_image"]["name"];
					$allowedExts = array("jpg", "jpeg", "gif", "png");
					$ext = explode(".", $_FILES["signature_image"]["name"]);
					$extension = $ext[1];
					
					if ((in_array($extension, $allowedExts))){
						if ($_FILES["signature_image"]["error"] > 0){
							$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Invalid file extension</p>');
							redirect('form_controller/client_information_form', 'refresh');
						}else{
							$dir = FCPATH."uploads/client_sign/";
							move_uploaded_file($_FILES["signature_image"]["tmp_name"], $dir.$signature);
							$file_path= "FCPATH/uploads/client_sign/".$signature;
						}
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b> Invalid Client Signature File.</p>');
						redirect('form_controller/inspection_second_form', 'refresh');
					}
			}else{
				$file_path ='';
			}
			
			$id = $_SESSION['inspector']['form_one_inserted_id'];
			
			$dbdata = array(
							'client_name' 				=> $client_name,
							'client_designation' 		=> $client_designation,
							'client_signature_image' 	=> $file_path,
							'client_remark' 			=> $client_remarks,
							'inspected_status' 			=> 'Yes',
							'approved_status' 			=> 'Pending'
							);
			$result	= $this->Form_model->fourth_slot_data_insert($id,$dbdata);
			if($result){
				/* 	Once Data Insertd 
					Now Insert the data from Temporary tables into the main tables
				*/
				$results = $this->Form_model->insert_main_table_data();
				if(!$results){
					setMessages('Error In Inserting data in Main Table', 'error');
					//return false;
					redirect('form_controller/client_information_form');
				}else{
					$this->Form_model->delete_session_values();
					$this->session->set_flashdata('msg','<p class="alert alert-success capital">Thankyou For Inspecting! Report has been send to the Administration Department. </p>');
					redirect('form_controller/inspector');
				}
			}else{
				//$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR !! : </b> In inserting data in database</p>');
				setMessages('Error In inserting data in database', 'error');
				redirect('form_controller/client_information_form', 'refresh');
			}
		}
		$this->load->view('inspector/client_information_form');
	}
}// end of controller class
?>