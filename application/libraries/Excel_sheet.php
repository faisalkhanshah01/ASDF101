<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_sheet {
	function __construct() {
		$this->CI =& get_instance();
	}	
	
		function subAssetView($param){
			print_r($param);die;
			if(is_array($param) && ($param['defaultId'] == 6)){
				$params= array();
				 $params['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
				 $params['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
				
				$this->CI->load->model('Subassets_model');
				$inspection		=	$this->CI->Subassets_model->get_type_category('inspection');
				$uom			=	$this->CI->Subassets_model->get_type_category('uom');
				$result			=	$this->CI->Subassets_model->get_type_category('result');
				$observations	=	$this->CI->Subassets_model->get_type_category('observations');
				
				$this->CI->load->model('SiteId_model');
				$subAsset = $this->CI->SiteId_model->get_subAsset_list($params); 
				$c = 1;
				foreach($subAsset as $cKey=>$value){
					$data[$c]['S.no'] = $c;
					$data[$c]['Sub Assets Code'] = $value['sub_assets_code'];
					$data[$c]['Description'] = $value['sub_assets_description'];
					if($value['sub_assets_imagepath']!=''){
						$imagePath = '<img src="'.base_url().$value['sub_assets_imagepath'].'" width="60" height="60" />';
					}else{
						$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
					}
					$data[$c]['Image'] = $imagePath;
					if(is_array($uom) && !empty($value['sub_assets_uom'])){
						if(array_key_exists($value['sub_assets_uom'],$uom)){
							$uom_value = "<p>".$uom[$value['sub_assets_uom']]."</p>";
						}else{
							$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
						}
					}else{
						$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
					}
					$data[$c]['UOM'] = $uom_value;
					if(is_array($inspection) && !empty($value['sub_assets_inspection'])){
						if(array_key_exists($value['sub_assets_inspection'],$inspection)){
							$inspection_value = "<p>".$inspection[$value['sub_assets_inspection']]."</p>";
						}else{
							$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
						}
					}else{
						$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
					}
					$data[$c]['Inspection Type'] = $inspection_value;
					if(is_array($result) && !empty($value['sub_assets_result'])){
						$excpected_result = json_decode($value['sub_assets_result'],true);
						$result_value = '';
						foreach($excpected_result as $expResult){
							if(array_key_exists($expResult,$result)){
								$result_value .=  "<p>".$result[$expResult]."</p><hr>";
							}else{
								$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
							}
						}
					}else{
						$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
					}
					$data[$c]['Expected Result'] = $result_value;
					if(is_array($observations) && !empty($value['sub_assets_observation'])){
							$observations_array = json_decode($value['sub_assets_observation'],true);
							$observation_value = '';
							foreach($observations_array as $obsResult){
								if(array_key_exists($obsResult,$observations)){
									$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
								}else{
									$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_observation']."</p>";
								}
							}
					}else{
						$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_observation']."</p>";
					}
					$data[$c]['Observation'] = $observation_value;
					$data[$c]['Repair'] = strtoupper($value['sub_assets_repair']);
					$status =  ($value['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$data[$c]['status'] = $status;
					$data[$c]['Date'] = date("M jS, Y", strtotime($value['time']));
					$c++;
				}
				
				$this->exportToCsv($data, 'Sub_Asset_View');	
			}else{
                return -2;
            }	
		}
		
		function assign_siteID($param){
			if(is_array($param) && ($param['defaultId'] == 4)){
				$data = array();
				$temp = array();
				$this->CI->load->model("assign_client_model");
				$this->CI->load->model("form_model");
				
				 $params= array();
				 $params['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
				 $params['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
				if(!empty($param['clientType'])){
					$params['clientType'] =  $param['clientType'];
				}else{
					$params['clientType'] =  '';
				}
		
				$client_data = $this->CI->assign_client_model->fetch_client_data($params);
				$c = 1;
				foreach($client_data as $cKey=>$CVal){
					if(!empty($CVal['site_id'])){
						$site_id = json_decode($CVal['site_id'],true);
						$siteID = array();
						if(!empty($site_id)){
							foreach($site_id as $siteKey => $siteVal){
								$site = $this->CI->assign_client_model->ajax_get_siteID($siteVal);
								$clientname  = $this->CI->assign_client_model->get_clientName_siteID(trim($site['site_jobcard']),trim($site['site_sms']));
								//$siteID[] = $clientname['client_name'].' / '.$site['site_id'];
								$siteID[] = $clientname['client_name'];
							}
						}
					}
					
					$client_name_list = json_decode($CVal['client_ids'],true);
					$userName= array();
					if(!empty($client_name_list) && !empty($CVal['client_type'])){
						foreach($client_name_list as $clientNameVal){
							$users = $this->CI->assign_client_model->get_client_list_name_id($clientNameVal,$CVal['client_type']);
							$userName[] = $users['upro_first_name'].' '.$users['upro_last_name'];
						}
					}
			
					$data[$c] = array(
							'S.No.'        => $c,
							'Client Name' 	=> implode('<br/>', $siteID),
							'User name' 	=> implode('<br/>', $userName),
					);
					if($CVal['client_type'] == 7){
						$data[$c]['Client Type'] = 'Dealer';
					}else if($CVal['client_type'] == 8){
						$data[$c]['Client Type'] = 'Client';
					}else if($CVal['client_type'] == 9){
						$data[$c]['Client Type'] = 'Inspector';
					}
					$data[$c]['Status'] = $CVal['status'];
					$data[$c]['Date'] = date("M jS, Y", strtotime($CVal['assign_data']));
					$c++;
				}
				
			$this->exportToCsv($data, 'Assign_SiteID_List');	
			} else{
                return -2;
            }
		}
		
		
        
        function assets_list($param){
            if(is_array($param) && !empty($param['defaultId']) && ($param['assets'] == 'assets')){
                $data = array();
                $this->CI->load->model("kare_model");
                $result = $this->CI->kare_model->get_component_excel();
                $c = 0;
                if(is_array($result)){
                    foreach($result as $key => $value){
                            $data[$c]['Component Id'] = $value['component_id'];
                            $data[$c]['Component Code'] = $value['component_code'];
                            $data[$c]['Component Sub Assets'] = !empty($value['component_sub_assets'])?implode(",",json_decode($value['component_sub_assets'],true)):'';
                            $data[$c]['Component Uom'] = $value['component_uom'];
                            $data[$c]['Component Repair'] = $value['component_repair'];
                            $data[$c]['Component Work Permit'] = $value['component_work_permit'];
                            $data[$c]['Component Geo Fancing'] = $value['component_geo_fancing'];
                       $c++;
                    }
                } else{
                    print 'no data';die;
                }
                
                $this->exportToCsv($data, 'Assets_List');
            } else{
                return -2;
            }
        }
        
        function asset_series_list($param){
            if(is_array($param) && !empty($param['defaultId']) && ($param['assets_series'] == 'assets_series')){
                $data = array();
                $this->CI->load->model("kare_model");
                $result = $this->CI->kare_model->get_products_excel();
              
                if(is_array($result)){
                    foreach($result as $key => $value){
                            $data[$key]['Product Id'] = $value['product_id'];
                            $data[$key]['Product Code'] = $value['product_code'];
                            $data[$key]['Product Sub Assets'] = !empty($value['product_components'])?implode(",",json_decode($value['product_components'],true)):'';
                            $data[$key]['Product Work Permit'] = $value['product_work_permit'];
                            $data[$key]['Product Geo Fancing'] = $value['product_geo_fancing'];
                      
                    }
                } else{
                    print 'no data';die;
                }
                //print_r($data);die;
                $this->exportToCsv($data, 'Assets_Series_List');
            } else{
                return -2;
            }
        }
		
		function search_data($param){
            if(is_array($param) && !empty($param)){
                $data = array();
				
                $this->CI->load->library('email/search');
				$result = $this->CI->search->search_dashboard($param);
				//print_r($result);die;
				
				if(is_array($result)){
					  foreach($result as $key => $value){
						$data[$key]['SNo'] = $key+1;
						$data[$key]['Client Name'] = $value['client_name'];
						$data[$key]['Asset'] = $value['asset'];
						$data[$key]['Asset Series'] = $value['asset_series'];
						$data[$key]['Report No'] = $value['report_no'];
						$data[$key]['Site Id'] = $value['site_id'];
						$data[$key]['Job Card'] = $value['job_card'];
						$data[$key]['SMS'] = $value['sms'];
						$data[$key]['Status'] = $value['status'];
						$data[$key]['Date'] = date("M jS, Y", strtotime($value['time']));
					 }
                } else{
                    print 'no data';die;
                }
               // print_r($data);die;
                $this->exportToCsv($data, 'kare Data');
            } else{
                return -2;
            }
        }
		
		function logsView($param){
             if(is_array($param) && !empty($param['defaultId']) && ($param['logsView'] == 'logs_view')){
                 $temp= array();
                 $temp['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
                 $temp['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
                 
                $this->CI->load->model('kare_model');
                $logsData = $this->CI->kare_model->get_logs_view($temp);
                if(!empty($logsData) && is_array($logsData)){
                    $c = 1;
                    foreach($logsData as $key => $value){
                        $profileData = $this->_profileData($value['user_id']);
                        $data[$key]['S.No'] = $c;
                        $data[$key]['name'] = !empty($profileData['name'])?$profileData['name']:'';
                        $data[$key]['email'] = !empty($profileData['email'])?$profileData['email']:'';
                        $data[$key]['description_id'] = !empty($profileData['description_id'])?$profileData['description_id']:'';
                        $data[$key]['description'] = !empty($profileData['description'])?$profileData['description']:'';
                        $data[$key]['ip_address'] = $value['ip_address'];
                        $data[$key]['process'] = $value['process'];
                        $data[$key]['time'] = $value['time'];
                        $data[$key]['date'] = date("M jS, Y", strtotime($value['timestamp']));
                        
                        $c++;
                    }
                }else{
                    print 'no data';die;
                }
                $this->exportToCsv($data, 'Logs_View');
             }else{
                return -2;
            }
        }
        
        function _profileData($userID){
            $param = array();
            if(!empty($userID)){
                $logsData = $this->CI->kare_model->get_logs_profileData($userID);
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
                }else {
                    $param['description'] = $logsData['uacc_group_fk'];
                }
                $param['name'] = (!empty($logsData['upro_first_name']) || !empty($logsData['upro_last_name']))?$logsData['upro_first_name'].' '.$logsData['upro_last_name']:'';
            }
            return $param;
        }
        
      
                	
        function exportToCsv($data = '', $filename = ''){
			// file name for download
			$filename .= ".xls";
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			
			if(is_array($data) && count($data)>0){
				//column name
							echo implode("\t", array_keys($data[0])) . "\r\n";
				//values in columns
				foreach($data as $key => $value){
					if($value < 0){
						continue;
					}
					echo implode("\t", array_values($value)) . "\r\n";
				}
			} else{
				echo 'no entries';
			}
		}
	
	
}
