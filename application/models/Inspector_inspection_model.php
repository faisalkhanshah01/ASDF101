<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh/Ravindra
*	Date 	: 17-Aug-2016
*	Email 	: shakti.singh@flashonmind.com
*/

class Inspector_inspection_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
		$this->load->library('email');
	}

	function get_inspectionData_for_admin($id='')
	{
		if($id != ''){
			$this->db->select(" A.*, B.upro_first_name, B.upro_last_name,C.uacc_group_fk,D.ugrp_name as inspector_group");
			$this->db->from('inspection_list_1 AS A');
			$this->db->join('demo_user_profiles AS B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$this->db->join('user_accounts AS C', 'A.inspected_by = C.uacc_id', 'inner');
			$this->db->join('user_groups AS D', 'C.uacc_group_fk = D.ugrp_id', 'inner');
			$this->db->where('A.id',$id);
			$query = $this->db->get();
			return $query->row_array();
		}
		else{
			$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.approved_status,B.upro_first_name,B.upro_last_name");
			$this->db->from('inspection_list_1 as A');
			$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$query = $this->db->get();		
			return $query->result_array();
		}
	}
	
	function get_assign_inspector_data($client_type){
		if(!empty($client_type)){
			$this->db->select("*");
			$this->db->from('assign_client_data');
			$this->db->where('client_type',$client_type);
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return -3;
			}
		}else{
			-2;
		}
	}
	
	function get_inspector_data(){
		$this->db->select("*");
		$this->db->from('inspector_data');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function get_inspector_list($siteid_marge){
		$this->db->select("*");
		$this->db->from('inspection_list_1');
		$this->db->where_in('siteID_id',$siteid_marge);
		$this->db->where('approved_status','Pending');
		$query = $this->db->get();
		return $query->result_array();
    }
	
	function get_inspecte_name($userID){
		$this->db->select("upro_first_name,upro_last_name");
		$this->db->from('demo_user_profiles');
		$this->db->where('upro_uacc_fk',$userID);
		//$this->db->where('A.approved_status','Pending');
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_inspectionData_for_inspector_old($userID='',$id='')
	{
		if($id != ''){
			$this->db->select(" A.*, B.upro_first_name, B.upro_last_name,C.uacc_group_fk,D.ugrp_name as inspector_group");
			$this->db->from('inspection_list_1 AS A');
			$this->db->join('demo_user_profiles AS B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$this->db->join('user_accounts AS C', 'A.inspected_by = C.uacc_id', 'inner');
			$this->db->join('user_groups AS D', 'C.uacc_group_fk = D.ugrp_id', 'inner');
			$this->db->where('A.id',$id);
			$query = $this->db->get();
			return $query->row_array();
		}
		else{
			$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.asset_series,A.approved_status,B.upro_first_name,B.upro_last_name");
			$this->db->from('inspection_list_1 as A');
			$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$this->db->where('A.inspected_by',$userID);
			$this->db->where('A.approved_status','Pending');
			$query = $this->db->get();		
			return $query->result_array();
		}
	}
	
	function _client_inspection($siteID){
		if(!empty($siteID) && is_array($siteID)){
			$this->db->select("id,report_no,siteID_id,site_id,job_card,asset_series,approved_status");
			$this->db->from('inspection_list_1');
			$this->db->where_in('siteID_id',$siteID);
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return -3;
			}
		}else{
			return -2;
		}
	}
	
	function get_client_siteID($userID){
		if(!empty($userID)){
			$this->db->select("client_ids,site_id");
			$this->db->from('assign_client_data');
			$this->db->where("status",'Active');
			$query = $this->db->get();
			if($query->num_rows()>0){
				$client_id = $query->result_array();
				if(!empty($client_id)){
					foreach ($client_id as $key => $value) {
						$clientID = json_decode($value['client_ids'],true);
						if(in_array($userID,$clientID)){
							$siteid[] = json_decode($value['site_id'],true);
						}
					}
				}
				$siteID = array_unique(call_user_func_array('array_merge', $siteid));
				$client_inspection = $this->_client_inspection($siteID);
				
				return $client_inspection;
			}else{
				return -1;
			}
		}
	}
	
	function get_inspectionData_for_inspector($userID='',$id=''){
		if($id != ''){
			$this->db->select(" A.*, B.upro_first_name, B.upro_last_name,C.uacc_group_fk,D.ugrp_name as inspector_group");
			$this->db->from('inspection_list_1 AS A');
			$this->db->join('demo_user_profiles AS B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$this->db->join('user_accounts AS C', 'A.inspected_by = C.uacc_id', 'inner');
			$this->db->join('user_groups AS D', 'C.uacc_group_fk = D.ugrp_id', 'inner');
			$this->db->where('A.id',$id);
			$query = $this->db->get();
			return $query->row_array();
		}else{
			$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.asset_series,A.approved_status,B.upro_first_name,B.upro_last_name");
			$this->db->from('inspection_list_1 as A');
			$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
			$this->db->where('A.inspected_by',$userID);
			$this->db->where('A.approved_status','Pending');
			$query = $this->db->get();
			//print_r($this->db->last_query());die(' 123');
			return $query->result_array();
		}
	}
	function get_inspectionData_by_product($product=''){
		if($product != ''){
			$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.asset_series,A.approved_status,B.upro_first_name,B.upro_last_name");
			$this->db->from('inspection_list_1 as A');
			$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
		    $this->db->where('A.asset_series',$product);
			$query = $this->db->get();
			return $query->result_array();
		}
	}
	
	
	function isJson($string) {
		 return is_array(json_decode($string,true));
	}
	
	function get_inspectionDetails_for_admin($id='',$jobcard,$sms,$series)
	{
		if($id != ''){
		/*
			$this->db->select("A.*,B.type_name as observation_name");
			$this->db->from('inspection_list_2 as A');
			$this->db->join('type_category AS B', 'A.observation_type = B.id', 'left');
			$this->db->where('A.inspection_list_id',$id);
			$query = $this->db->get();
			$result = $query->result_array(); 
		*/
			$this->db->select("A.*");
			$this->db->from('inspection_list_2 as A');
			$this->db->where('A.inspection_list_id',$id);
			$query = $this->db->get();
			$result = $query->result_array();
				
			foreach($result as $key=>$val){
				// Check whether observation type is json data or old data of single value
				$obs_check = $this->isJson($val['observation_type']);
				if($obs_check){
					$array = json_decode($val['observation_type'], true);
					$observation = $actionProposed = $array_result =  array();
					foreach($array as $aVal){
						$observation[] 		= $this->get_inspection_name($aVal['observation_type']);
						$actionProposed[] 	= $this->get_inspection_name($aVal['action_proposed']);
						$array_result[] 			= $aVal['result'];
					}
					$result[$key]['observation_name'] 		= $observation;
					$result[$key]['action_proposed_name'] 	= $actionProposed;
					$result[$key]['result_array'] 			= $array_result;
				}else{
					$result[$key]['observation_name'] = $this->get_inspection_name($val['observation_type']);
					$result[$key]['action_proposed_name'] = $this->get_inspection_name($val['action_proposed']);
				}
				
				$this->load->model('Form_model');
				if($val['subAsset_name']!= '0'){
					$asset = $this->Form_model->get_subasset_from_asset($val['subAsset_name']);
					$asset_image = $this->Form_model->get_asset_image($val['asset_name']);
					$result[$key]['asset_image'] = $asset_image;
					$asset_ins = $this->get_inspection_name($asset['insType']);
					$asset_qty = $this->get_asset_qty($jobcard,$sms,$series,$val['asset_name']);
					
					$result[$key]['inspection_type_name'] = ($asset_ins)? $asset_ins : '';
					$result[$key]['asset_qty'] = ($asset_qty)? $asset_qty : '';
				}else{
					/* Else There is No Sub Asset Avaliable */
						//// $asset = $this->Form_model->get_asset_values($val['asset_name']);
					$asset = $this->Form_model->get_subasset_from_asset($val['asset_name']);
					
					$result[$key]['asset_image'] = (isset($asset['image']))? $asset['image'] : '';
					$asset_ins = $this->get_inspection_name($asset['insType']);
					$asset_qty = $this->get_asset_qty($jobcard,$sms,$series,$val['asset_name']);
					
					$result[$key]['inspection_type_name'] = ($asset_ins)? $asset_ins : '';
					$result[$key]['asset_qty'] = ($asset_qty)? $asset_qty : '';
				}
			}
			return $result;
		}else{
			return false;
		}
	}
	
	function get_asset_qty($jobcard,$sms,$series,$asset){
		$this->db->select("item_quantity");
		$this->db->from('sms_component');
		$this->db->where('jc_number',$jobcard);
		$this->db->where('sms_number',$sms);
		$this->db->where('series',$series);
		$this->db->where('item_code',$asset);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$result = $query->row();
			return $result->item_quantity;
		}else{
			return false;
		}
	}
	
	function get_inspection_name($id){
		$this->db->select("type_name");
		$this->db->from('type_category');
		$this->db->where('id',$id);
		$query = $this->db->get();		
		$result = $query->row();
		return $result->type_name;
	}
	
	function get_client_masterData_details($jobCard,$sms){
		$que = "SELECT A.mdata_client,B.client_name,A.mdata_batch,A.mdata_serial,A.mdata_rfid,A.mdata_barcode,A.mdata_uin,A.mdata_po
					FROM master_data as A
					LEFT JOIN clients as B
					ON A.mdata_client = B.client_id OR A.mdata_client = B.client_name
					WHERE A.mdata_jobcard ='".$jobCard."'
					AND A.mdata_sms ='".$sms."'";
		 $queries = $this->db->query($que);
		
		if($queries->num_rows() > 0){
			return $result = $queries->row_array();
		}else{
			return $result = '';
		}
	}
	
	function get_client_address($id){
		$this->db->select("site_address");
		$this->db->from('siteID_data');
		$this->db->where('siteID_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$result = $query->row();
			return $result->site_address;
		}else{
			return $result = '';
		}
	}
	
	function get_inspected_user($id){
		$this->db->select("inspected_by");
		$this->db->from('inspection_list_1');
		$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$result = $query->row();
			return $result->inspected_by;
		}else{
			return $result = '';
		}
	}
	
	function update_approved_values($dbdata,$id){
		$this->db->where('id',$id);
		return $this->db->update('inspection_list_1',$dbdata);
	}
	
	function update_latitude_longitude_in_siteid($id,$dbdata){
		$this->db->where('siteID_id',$id);
		return $this->db->update('siteID_data',$dbdata);
	}
	
	function update_uin_barcode_rfid_in_masterData($jobCard,$sms, $asset_seriesSTR, $created_date, $dbdata){		
       if( $asset_seriesSTR != '' ){		    
			$asset_series	 = '["'.$asset_seriesSTR.'"]';	
			if(isset($dbdata['mdata_uin']) ){
				if( !empty($dbdata['mdata_uin']) ){
					 $SQL   = "UPDATE master_data SET mdata_uin = '".$dbdata['mdata_uin']."', date_of_inspection = '".$created_date."'  WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}else{
				    $SQL   = "UPDATE master_data SET  date_of_inspection = '".$created_date."'  WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}				
			}else if(isset($dbdata['mdata_rfid'])){				
				if( !empty($dbdata['mdata_rfid']) ){					
					$SQL   = "UPDATE master_data SET mdata_rfid = '".$dbdata['mdata_rfid']."' , date_of_inspection = '".$created_date."' WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}else{
					$SQL   = "UPDATE master_data SET  date_of_inspection = '".$created_date."' WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}								
			}else if(isset ($dbdata['mdata_barcode'])){				
				if( !empty($dbdata['mdata_barcode'])){				
					$SQL   = "UPDATE master_data SET mdata_barcode = '".$dbdata['mdata_barcode']."' , date_of_inspection = '".$created_date."' WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}else{
					$SQL   = "UPDATE master_data SET  date_of_inspection = '".$created_date."' WHERE  mdata_jobcard = '".$jobCard."' AND  mdata_sms = '".$sms."' AND ( mdata_item_series = '".$asset_series."'  ||  mdata_asset =  '".$asset_series."') ";
				}				
			}			
			if($this->db->query($SQL)){
				return true;
			}else{
				return false;
			}
	   }else{   
	     return false;
	   }
	}
	
	function get_lattitude_longitude($id){
		$this->db->select("siteID_id,lattitude,longitude,job_card,sms, asset_series, input_method,input_value, created_date");
		$this->db->from('inspection_list_1');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_sms_jobCard($id){
		$this->db->select(" job_card,sms");
		$this->db->from('inspection_list_1');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function delete_siteID_from_inspectorAssigned($jobCard,$sms,$site_id){
		$query = $this->db->query("SELECT id,site_id FROM inspector_data WHERE site_id like '%".$site_id."%' ");
		if($query->num_rows()>0){
			//$resVal = $query->row_array();
			$res = $query->result_array();
			// it can contain multiple site id's which have value containing $site_id
			$final_res = array();
			$error = array();
			foreach($res as $resVal){
				$siteID_array = json_decode($resVal['site_id'],true);
				// search for $site_id in $siteID_array, If present then proceed further
				if(in_array($site_id,$siteID_array)){
					// count the values present in $siteID_array
					$count = count($siteID_array);
					// if count == 1, means only one site id is present and that is selected one.
					// Then delete the whole row from Inspector table.
					if($count == 1){
						$this->load->model('Kare_model');
						$final =  $this->Kare_model->delete_inspector($resVal['id']);
						if(!$final){
							$error[] = $resVal['id'];
						}
						//$final_res[] = (){}
					}else{
						// if count > 1, then search the value of $site_id in $siteID_array and delete it.
						$key = array_search($site_id, $siteID_array);
						unset($siteID_array[$key]);
						
						// after deleting the key, reform the array of $siteID_array
						$array = array();
						foreach($siteID_array as $siteVal){
							$array[] = $siteVal;
						}
						// Now update the value in Inspector data table.
						$dbdata = array('site_id'=>json_encode($array));
						$final_arr = $this->update_siteID_of_inspector($resVal['id'],$dbdata);
						if(!$final_arr){
							$error[] = $resVal['id'];
						}
					}
				}
			}
			if(!empty($error)){
				$error['error'] = 'Error In Deleting Data';
				return $error;
			}else{
				return true;
			}
		}else{
			return $errors = array('error' => 'No Data Exist with site ID : '.$site_id.' in Inspector Table');
		}
	}
	
	function update_siteID_of_inspector($id,$dbdata){
		$this->db->where('id',$id);
		return $this->db->update('inspector_data',$dbdata);
	}
	
	function get_approved_rejected_pending_list($type=''){
		$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.approved_status,B.upro_first_name,B.upro_last_name,C.upro_first_name as admin_fname,C.upro_last_name as admin_lname");
		$this->db->from('inspection_list_1 as A');
		$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
		$this->db->join('demo_user_profiles as C', 'A.approved_by = C.upro_uacc_fk', 'left');
		$this->db->where('approved_status',$type);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function delete_report($id){
		$this->load->model('common_model');
		$this->db->select("A.id,A.inspector_signature_image,A.client_signature_image,A.map_image,B.id as bid, B.before_repair_image, B.after_repair_image");
		$this->db->from('inspection_list_1 as A');
		$this->db->join('inspection_list_2 as B', 'A.id = B.inspection_list_id', 'inner');
		$this->db->where('A.id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$result = $query->result_array();
			
			foreach($result as $res){
				$insPic = str_replace('FCPATH/', './', $res['inspector_signature_image']);
				$this->delete_picture($insPic);
				$cliPic = str_replace('FCPATH/', './', $res['client_signature_image']);
				$this->delete_picture($cliPic);
				$mapPic = str_replace('FCPATH/', './', $res['map_image']);
				$this->delete_picture($mapPic);
				$befPic = str_replace('FCPATH/', './', $res['before_repair_image']);
				$this->delete_picture($befPic);
				$aftPic = str_replace('FCPATH/', './', $res['after_repair_image']);
				$this->delete_picture($aftPic);
				
				$this->common_model->delete_table_data($res['bid'],'inspection_list_2');
			}
			
			$delRes = $this->common_model->delete_table_data($id,'inspection_list_1');
			if($delRes){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	function delete_picture($pic){
		if(file_exists($pic)){
			if(!unlink($pic)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;;
		}
	}
	
	function get_workpermit_id($id){
		$this->db->select('id, wpermit_id');
		$this->db->from('inspection_list_1');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			return $res['wpermit_id'];
		}
	}
	
	function get_workpermit_data($id){
		$this->db->select('*');
		$this->db->from('workPermit_report');
		$this->db->where('wpermit_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			return $res;
		}
	}
	
	// New Function which is Incomplete
	function get_user_defined_array($ins_array){
		// Get all the Wire Rope Values if present in 	
		$ar1 = array('PN7000(05)(R)', 'PN6000(01)', 'PN7000(12)', 'PN2001(04)(R)', 'PN9XXXS',
			'PN7000(03)', 'PN7000(02)(R)', 'PN7000(04)(R)');
		
		$arrE = array();
		foreach($ar1 as $a1){
			foreach($ins_array as $insV){
				if(in_array($a1, $insV)){
					$arrE[] = $insV;
				}
				if(in_array('PN9000(TA)(30)',$insV)){
					$list_of_30mtr = 'Yes';
				}
			}	
		}
		if(isset($list_of_30mtr)){
			// It's a 30 meter ALU so have to go this arr30
			$res30 = $this->_get_arr30_list($ins_array);
			return $final = (!empty($arrE))? $arrE + $res30 : $res30;
		}else{
			// It's a 25 mtr ALU so have to go with arr25
			$res25 = $this->_get_arr25_list($ins_array);
			return $final = (!empty($arrE))? $arrE + $res25 : $res25;
		}
	}
	
	function _get_arr25_list($ins_array){
		$arr25 = array('PN9000(BA)', 'PN9000(IA)(P)(A)', 'PN9000(IA)(R)',
					'PN9000(IA)(P)(B)', 'PN9000(IA)(L)', 'PN9000(TA)');
		
		$arr = $arr1 = $arr2 = $arr3 = $arr4 = array();
		foreach($arr25 as $aVal){
			$arr1 = $arr2 = $arr3 = $arr4 = array();
			
			foreach($ins_array as $insK=>$insVal){
				if($insVal['asset_name'] == $aVal){
					if($aVal=='PN9000(BA)'){
						if($insVal['subAsset_name'] == 'PN-9000(06)(R)'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr2[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr3[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else if($aVal=='PN9000(IA)(P)(A)'){
						if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr2[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else  if($aVal=='PN9000(IA)(P)(B)' || $aVal=='PN9000(IA)(R)' || $aVal=='PN9000(IA)(L)'){
						if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr2[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else  if($aVal=='PN9000(TA)'){
						if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN5000(03)'){
							$arr2[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}
				}
			}
			
			if(!empty($arr1)){
				foreach($arr1 as $a1V){
					$arr[] = $a1V;
				}
			}
			if(!empty($arr2)){
				foreach($arr2 as $a2V){
					$arr[] = $a2V;
				}
			}
			if(!empty($arr3)){
				foreach($arr3 as $a3V){
					$arr[] = $a3V;
				}
			}
			if($aVal=='PN9000(BA)'){
				if(!empty($arr4)){
					foreach($arr4 as $a4V){
						$arr[] = $a4V;
					}
				}
			}
		}
		return $arr;
	}
	
	
	function _get_arr30_list($ins_array){
		$arr30 = array('PN9000(BA)', 'PN9000(IA)(L)', 'PN9000(IA)(P)(C)',
				'PN9000(IA)(P)(B)', 'PN9000(IA)(R)', 'PN9000(TA)(30)');
		
		$arr = $arr1 = $arr2 = $arr3 = $arr4 = array();
		foreach($arr30 as $aVal){
			$arr1 = $arr2 = $arr3 = $arr4 = array();
			
			foreach($ins_array as $insK=>$insVal){
				if($insVal['asset_name'] == $aVal){
					if($aVal=='PN9000(BA)'){
						if($insVal['subAsset_name'] == 'PN-9000(06)(R)'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr2[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr3[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else if($aVal=='PN9000(IA)(P)(A)'){
						if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr2[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else  if($aVal=='PN9000(IA)(P)(C)' || $aVal=='PN9000(IA)(B)' || $aVal=='PN9000(IA)(L)' || $aVal=='PN9000(IA)(R)'){
						if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr1[$insK] = $insVal;
						}else if($insVal['subAsset_name'] == 'PN5000(08)'){
							$arr2[$insK] = $insVal;
						}else{
							$arr4[$insK] = $insVal;
						}
					}else  if($aVal=='PN9000(TA)(30)'){
						if($insVal['subAsset_name'] == 'PN-5000(06)R'){
							$arr1[$insK] = $insVal;
						}
					}
				}
			}
			
			if(!empty($arr1)){
				foreach($arr1 as $a1V){
					$arr[] = $a1V;
				}
			}
			if(!empty($arr2)){
				foreach($arr2 as $a2V){
					$arr[] = $a2V;
				}
			}
			if(!empty($arr3)){
				foreach($arr3 as $a3V){
					$arr[] = $a3V;
				}
			}
			if($aVal=='PN9000(BA)'){
				if(!empty($arr4)){
					foreach($arr4 as $a4V){
						$arr[] = $a4V;
					}
				}
			}
		}
		return $arr;
	}
	
}
?>