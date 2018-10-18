<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Clientuser_dashboard extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data = null;
	}
		
	function index(){
		$this->dashboard();
	}
	
	function dashboard_old(){
		
		$userID = $this->session->flexi_auth['id'];
		// Get All the Clients Assigned to this User 
		// Get Assigned Client Id and their Names
		$this->load->model('Assign_client_model');
		$client_data = $this->Assign_client_model->get_client_data_by_user($userID, 'client_dashboard');	
		
		// echo "<pre>";
		// print_r($client_data);
		// echo "</pre>";
		
		//$client_datas = $site_datas = $inspected_site_data = array();
		// if(is_array($client_data)){
			// foreach($client_data as $cKey=>$CVal){
				// $client_user = json_decode($CVal['client_users'],true);
				// foreach($client_user as $clientVal){
					// $names	=$this->Assign_client_model->get_client_user_name_by_id($clientVal);
					// $client_datas[$clientVal] = $names['client_name'];
					// Get JOB Card and SMS Numbers Assigned to clients
					// $site_data = $this->Assign_client_model->getjobcardSmsSiteID_from_clients($clientVal,$names['client_name']);
					// if($site_data){

						// $site_datas[] = array('client_id'=>$clientVal, 'client_name'=>$names['client_name'], 'site_data'=>$site_data);

						// foreach($site_data as $siteVal){
							// Get Inspected Site Id's Data
							// $res = $this->all_inspected_siteids($siteVal['siteID_id']);

							// if($res){
								// $inspected_site_data[] = $res;
							// }
							// Get Site Id's Due for Inspection
						// }

					// }else{
						// $site_data = '';
						// $inspected_site_data = '';
					// }
				// }
			// }
		// }
		
		if(is_array($client_data)){
			$site_datas			 = $client_data['site_data'];
			$inspected_site_data = $client_data['inspected_site_data'];
			$total_site = $client_data['total_site'];
			$total_inspected = $client_data['total_inspected'];
		}else{
			$site_datas = $inspected_site_data = array();
			$total_site = $total_inspected = '0';
		}
		
		$this->data['site_datas'] 			= $site_datas;
		$this->data['inspected_site_data'] 	= $inspected_site_data;
		$this->data['total_site'] 			= $total_site;
		$this->data['total_notinspected'] 	= ($total_site - $total_inspected);
		$this->data['total_inspected'] 		= $total_inspected;
		
		$this->load->view('clientuser_dashboard',$this->data);
	}
	
	
	
	function _clientID($userID,$groupID){
            if(!empty($userID) && ($groupID == 8)){
                $data = array();
                $userID = $userID;
               
                $this->load->model('Siteid_model');
                $this->load->model('Form_model');
                
                $this->load->model('Assign_client_model');
                //$client_data = $this->Assign_client_model->get_client_data();
				$this->load->model('M_client_user_dashboard');
		        $client_data = $this->M_client_user_dashboard->get_client_data_by_user($userID,$groupID);
				//print_r($client_data);
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
              
                $siteid_marge = explode('/',implode('/',array_unique($siteid_marge)));
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



                                /* Get Report Number from inspection_list_1 table */
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
                                        $temp[$sKey]['workPermit_number'] 	= trim($work_number);

                                /* End of Work Permit Number */
                        }
                    }
                }   
            }
            return $temp;
	}
	
	function _inspectorInspectionList($userId,$groupId){
		if(!empty($userId) && !empty($groupId)){
			$this->load->model('Inspector_inspection_model');
			if($groupId == 9){
				$result =  $this->Inspector_inspection_model->get_inspectionData_for_inspector($userId,'');
		    }else{
				$result =  $this->Inspector_inspection_model->get_client_siteID($userId);
			}
			return $result;
		}	
		
	}
	
	function client_upcoming_data(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		$data = array();
		$clientData = $this->_clientID($userId,$groupId);
		foreach($clientData as $key => $value){
			if($value['inspected_status'] == 'No'){
				$data['upcoming_data'][$key] =  $value;
				$site_address = explode(',',$value['site_address']);
				$site_address = implode('<br/>',$site_address);
				$data['upcoming_data'][$key]['site_address'] = 'Site Address : '.$site_address.'.<br/>'.'Site Location => '.$value['site_location'].'<br/>'.'Site City => '.$value['site_city'];
				$data['upcoming_data'][$key]['inspector_details'] = 'Name : '.$value['inspector_details']['upro_first_name'].' '.$value['inspector_details']['upro_last_name'].'<br/>'.'Mobile No. :	 '.$value['inspector_details']['upro_phone'];
			}	
		}
		//print_r($data);
		$this->load->view('assign_client/client_upcoming',$data);
	}
	
	function client_past_data(){
		$userId = $_SESSION['flexi_auth']['id'];
		$groupId = key($_SESSION['flexi_auth']['group']);
		$data = array();
		$clientData = $this->_clientID($userId,$groupId);
		$inspecteList = $this->_inspectorInspectionList($userId,$groupId);
		
		foreach($clientData as $key => $value){
			if($value['inspected_status'] == 'Yes'){
				$data['past_data'][$key] =  $value;
				if(!empty($inspecteList)){
					foreach($inspecteList as $k => $v){
						if(($v['siteID_id'] == $value['siteID_id']) && ($v['site_id'] == $value['site_id'])){
							//$data['past_data'][$key]['id'] = $v['id'];
							//$data['past_data'][$key]['userId'] = $userId;
							$data['past_data'][$key]['id'] = '<a target="_blank" title="" href="'.base_url().'clientuser_dashboard/inspectorInspectionListbyID?id='.$v['id'].'&userID='.$userId.'">'.
													'<span style="font-size: 30px;-webkit-align-content: center;align-content: center;" class="glyphicon glyphicon-eye-open"></span></a>';
						}	
					}
				}
				$site_address = explode(',',$value['site_address']);
				$site_address = implode('<br/>',$site_address);
				$data['past_data'][$key]['site_address'] = 'Site Address : '.$site_address.'.<br/>'.'Site Location : '.$value['site_location'].'<br/>'.'Site City : '.$value['site_city'];
				$data['past_data'][$key]['inspector_details'] = 'Name : '.$value['inspector_details']['upro_first_name'].' '.$value['inspector_details']['upro_last_name'].'<br/>'.'Mobile No. : '.$value['inspector_details']['upro_phone'];
			}	
		}
		//print_r($data['past_data']);die;
		$this->load->view('assign_client/client_past',$data);
	}
	
	function inspectorInspectionListbyID(){
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
	
	function dashboard(){
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



						/* Get Report Number from inspection_list_1 table */
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
								$temp[$sKey]['workPermit_number'] 	= trim($work_number);

						/* End of Work Permit Number */
				}
			}
		}
		
		$data['client_site_data'] = $temp;
		
		$this->load->view('clientuser_dashboard',$data);
		
	}
	
	function assign_siteID(){
		$data = array();
		
		$this->load->model("m_client_user_dashboard");
		$this->load->model("form_model");
		$this->load->model("assign_client_model");
		
		$flexi_auth = $_SESSION['flexi_auth']['group'];
		foreach($flexi_auth as $key =>$value){
			$name = $value;
			$group_id = $key;
		}
		
		$client_data = $this->m_client_user_dashboard->get_client_data($group_id);
		
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
		
		
		$this->load->view("assign_client/client_dashboard_assign_site",$data);
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
				$html .= '<td><a class="btn btn-success" href="'.base_url().'clientuser_dashboard/view_site_detail?assginClientId='.$val['id'].'" > Site Detail </a></td>';
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
			
			$this->load->view("assign_client/viewSiteDetail_dashboard",$data);
			
		}
	}
	
	
	function all_inspected_siteids($id){
		$inspected_data = $this->Assign_client_model->get_inspectedSite_ids($id);
		if($inspected_data){
			return $inspected_data;
		}else{
			return false;
		}
	}
	
}// end of controller class 




?>