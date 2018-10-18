
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Excelsheet extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function subAssetView($param){
		if(is_array($param) && ($param['defaultId'] == 6)){
			$params= array();
			 $params['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
			 $params['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
			
			$this->load->model('Subassets_model');
			$inspection		=	$this->Subassets_model->get_type_category('inspection');
			$uom			=	$this->Subassets_model->get_type_category('uom');
			$result			=	$this->Subassets_model->get_type_category('result');
			$observations	=	$this->Subassets_model->get_type_category('observations');
			
			$this->load->model('SiteId_model');
			$subAsset = $this->SiteId_model->get_subAsset_list($params); 
			
			$c = 0;
			foreach($subAsset as $cKey=>$value){
				$data[$c]['S.no'] = $c;
				$data[$c]['Sub Assets Code'] = $value['sub_assets_code'];
				$data[$c]['Description'] = $value['sub_assets_description'];
				
				if(is_array($uom) && !empty($value['sub_assets_uom'])){
					if(array_key_exists($value['sub_assets_uom'],$uom)){
						$uom_value = $uom[$value['sub_assets_uom']];
					}else{
						$uom_value = $value['sub_assets_uom'];
					}
				}else{
					$uom_value =  $value['sub_assets_uom'];
				}
				$data[$c]['UOM'] = $uom_value;
				
				if(is_array($inspection) && !empty($value['sub_assets_inspection'])){
					if(array_key_exists($value['sub_assets_inspection'],$inspection)){
						$inspection_value = $inspection[$value['sub_assets_inspection']];
					}else{
						$inspection_value = $value['sub_assets_inspection'];
					}
				}else{
					$inspection_value = $value['sub_assets_inspection'];
				}
				$data[$c]['Inspection Type'] = $inspection_value;
				
				if(is_array($result) && !empty($value['sub_assets_result'])){
					$excpected_result = json_decode($value['sub_assets_result'],true);
					$result_value = '';
					foreach($excpected_result as $expResult){
						if(array_key_exists($expResult,$result)){
							$result_value .=  $result[$expResult];
						}else{
							$result_value .= $value['sub_assets_result'];
						}
					}
				}else{
					$result_value = $value['sub_assets_result'];
				}
				$data[$c]['Expected Result'] = $result_value;
				
				if(is_array($observations) && !empty($value['sub_assets_observation'])){
						$observations_array = json_decode($value['sub_assets_observation'],true);
						$observation_value = '';
						foreach($observations_array as $obsResult){
							if(array_key_exists($obsResult,$observations)){
								$observation_value .=  $observations[$obsResult];
							}else{
								$observation_value .= $value['sub_assets_observation'];
							}
						}
				}else{
					$observation_value = $value['sub_assets_observation'];
				}
				
				$data[$c]['Observation'] = $observation_value;
				$data[$c]['Repair'] = strtoupper($value['sub_assets_repair']);
				$status =  ($value['status'] =='Active')? 'Active':'Inactive';
				$data[$c]['status'] = $status;
				$data[$c]['Date'] = date("M jS, Y", strtotime($value['time']));
				$c++;
			}
			
			$this->exportToCsv($data, 'Sub_Asset_View');	
		}else{
			return -2;
		}	
	}
	
	 function assets_list($param){
			$params= array();
			 $params['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
			 $params['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
			
			$this->load->model('Subassets_model');
			$inspection		=	$this->Subassets_model->get_type_category('inspection');
			$uom			=	$this->Subassets_model->get_type_category('uom');
			$result			=	$this->Subassets_model->get_type_category('result');
			$observations	=	$this->Subassets_model->get_type_category('observations');
			
			$this->load->model('SiteId_model');
			$asset = $this->SiteId_model->get_asset_list($params);
			
			if(is_array($asset)){
				
				$c = 0;
				foreach($asset as $cKey=>$value){
					$data[$c]['S.no'] = $c;
					$data[$c]['Assets Code'] = $value['component_code'];
					$data[$c]['Description'] = $value['component_description'];
					
					if(is_array($uom) && !empty($value['component_uom'])){
						if(array_key_exists($value['component_uom'],$uom)){
							$uom_value = $uom[$value['component_uom']];
						}else{
							$uom_value = $value['component_uom'];
						}
					}else{
						$uom_value =  $value['component_uom'];
					}
					$data[$c]['UOM'] = $uom_value;
					
					if(is_array($inspection) && !empty($value['component_inspectiontype'])){
						if(array_key_exists($value['component_inspectiontype'],$inspection)){
							$inspection_value = $inspection[$value['component_inspectiontype']];
						}else{
							$inspection_value = $value['component_inspectiontype'];
						}
					}else{
						$inspection_value = $value['component_inspectiontype'];
					}
					$data[$c]['Inspection Type'] = $inspection_value;
					
					if(is_array($result) && !empty($value['component_expectedresult'])){
						$excpected_result = json_decode($value['component_expectedresult'],true);
						$result_value = '';
						foreach($excpected_result as $expResult){
							if(array_key_exists($expResult,$result)){
								$result_value .=  $result[$expResult];
							}else{
								$result_value .= $value['component_expectedresult'];
							}
						}
					}else{
						$result_value = $value['component_expectedresult'];
					}
					$data[$c]['Expected Result'] = $result_value;
					
					if(is_array($observations) && !empty($value['component_observation'])){
							$observations_array = json_decode($value['component_observation'],true);
							$observation_value = '';
							foreach($observations_array as $obsResult){
								if(array_key_exists($obsResult,$observations)){
									$observation_value .=  $observations[$obsResult];
								}else{
									$observation_value .= $value['component_observation'];
								}
							}
					}else{
						$observation_value = $value['component_observation'];
					}
					
					$data[$c]['Observation'] = $observation_value;
					$data[$c]['Repair'] = strtoupper($value['component_repair']);
					$data[$c]['infonet_status'] = ($value['infonet_status'] ==1)? 'Active':'Inactive';
					$status =  ($value['status'] =='Active')? 'Active':'Inactive';
					$data[$c]['status'] = $status;
					$data[$c]['Date'] = date("M jS, Y", strtotime($value['component_created_date']));
					$c++;
				}
				
			} else{
				print 'no data';die;
			}
			
			$this->exportToCsv($data, 'Assets_List');
		
	}
	
	function assets_series_list($param){
			$params= array();
			 $params['startTime'] = (!empty($param['startTime'])) ? $param['startTime']: date( 'Y-m-d', strtotime("-30 days",time()) );
			 $params['endTime'] = (!empty($param['endTime']))? $param['endTime']: '';
			
			$data = array();
			$this->load->model('kare_model');
			$components = $this->kare_model->get_components_list();
			if(!empty($components) && is_array($components)){
				foreach($components as $component){
					$components_list[]=$component['component_code'];
				}
			}else{
				$components_list = '';
			}
			
			$this->load->model('Subassets_model');
			$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
			if(!empty($subAssets)){
				foreach($subAssets as $sAssets){
					$sub_assets_list[]=$sAssets['sub_assets_code'];
				}
			}else{
				$sub_assets_list = '';
			}
			
			if(is_array($components_list) && is_array($sub_assets_list)){
				$asset_array = array_merge($components_list,$sub_assets_list);
			}else if(is_array($components_list) && !is_array($sub_assets_list)){
				$asset_array = $components_list;
			}else if(is_array($sub_assets_list) && !is_array($components_list)){
				$asset_array = $sub_assets_list;
			}else if(!is_array($sub_assets_list) && !is_array($components_list)){
				$asset_array = '';
			}
				
			$inspection	=	$this->Subassets_model->get_type_category('inspection');
			
			$this->load->model('SiteId_model');
			$asset = $this->SiteId_model->get_assets_series_list($params);
			
			if(is_array($asset)){
				
				$c = 0;
				foreach($asset as $cKey=>$value){
					$data[$c]['S.no'] = $c;
					$data[$c]['Assets Code'] = $value['product_code'];
					$data[$c]['Description'] = $value['product_description'];
					
					$components = ($value['product_components']!='')?json_decode($value['product_components'],true):$value['product_components'];
					if(is_array($components)){
						$assetList ='';
						foreach($components as $code){
							$tCode = trim($code);
							$value = strtolower($tCode);
							if(is_array($asset_array)){
								if(in_array(strtolower($value),$asset_array)){
									$assetList .=  $tCode;
								}else{
									$assetList .= $tCode;
								}
							}else{
								$assetList = $tCode;
							}
						}	
					}
					$data[$c]['Asset List'] = $assetList;
					
					if(is_array($inspection) && !empty($value['product_inspectiontype'])){
						if(array_key_exists($value['product_inspectiontype'],$inspection)){
							$inspection_value = $inspection[$value['product_inspectiontype']];
						}else{
							$inspection_value = $value['product_inspectiontype'];
						}
					}else{
						$inspection_value =  $value['product_inspectiontype'];
					}
					$data[$c]['Inspection Type'] = $inspection_value;
					
					$data[$c]['infonet_status'] = ($value['infonet_status'] ==1)? 'Active':'Inactive';
					$status =  ($value['status'] =='Active')? 'Active':'Inactive';
					$data[$c]['status'] = $status;
					$data[$c]['Date'] = date("M jS, Y", strtotime($value['component_created_date']));
					$c++;
				}
				
			} else{
				print 'no data';die;
			}
			
			$this->exportToCsv($data, 'Assets_Series_List');
		
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