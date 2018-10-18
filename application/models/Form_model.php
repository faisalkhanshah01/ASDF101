<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh/Ravindra Tiwari 
*	Email 	: shakti.singh@flashonmind.com/ravindra.tiwari@flashonmind.com
*/
class Form_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	
	function get_inspector_name($id){
		$query = $this->db->query("	
									SELECT CONCAT( A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_id = B.uacc_id
									INNER JOIN user_groups AS C
									ON B.uacc_group_fk = C.ugrp_id
									WHERE C.ugrp_id = 9 AND B.uacc_active=1 AND B.uacc_suspend=0 AND B.uacc_id =".$id
								) or die(mysql_error());
								
		//return  array_column($query->result_array(),'full_name','id');
		return  $query->result_array();
	}
	
	
	function get_assigned_inspectorNames(){
		$this->db->select('inspector_ids');
		$this->db->from('inspector_data');
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			$res = $query->result_array();
			
			$arr = array();
			$array = array();
			foreach($res as $val){
				$id_arrays = json_decode($val['inspector_ids']);
				$array = array_unique(array_merge($array,$id_arrays));
			}
			foreach($array as $key=>$nVal){
				$names = $this->get_inspector_name($nVal);
				$arr = array_merge($arr,$names);
			}
			
			$new = array();
			foreach($arr as $aVal){
				$new[$aVal['id']] = ucwords($aVal['full_name']);
			}
			asort($new);
			return $new;
		}else{
			return false;
		}
	}
	
	function master_table_details($site_jobcard,$mdata_sms){
		$this->db->select("A.mdata_client, A.mdata_po, A.mdata_item_series,A.mdata_rfid,A.mdata_barcode,A.mdata_uin, A.status, A.mdata_batch, A.mdata_serial, B.client_name");
		$this->db->from('master_data as A');
		$this->db->join('clients as B', 'A.mdata_client = B.client_id', 'left');
		$this->db->where('A.mdata_jobcard',$site_jobcard);
		$this->db->where('A.mdata_sms',$mdata_sms);
		$this->db->where('A.status','Active');
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_assets($asset_val){
		$this->db->select("product_components");
		$this->db->from('products');
		$this->db->where('product_code',$asset_val);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_asset_image($asset_code){
		$this->db->select("component_imagepath");
		$this->db->from('components');
		$this->db->where('component_code',$asset_code);
		$query = $this->db->get();
		$result = $query->row_array();
		if($result){
			return ($result['component_imagepath']!='' && $result['component_imagepath']!='0')? $result['component_imagepath'] : 'FCPATH/uploads/images/users/default.jpg';
		}else{
			return 'FCPATH/uploads/images/users/default.jpg';
		}
	}
	
	function get_inspection_value($value){
		$this->db->select("type_name");
		$this->db->from('type_category');
		$this->db->where('id',$value);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			return $res['type_name'];
		}else{
			return $res ='';
		} 
	}
	
	

	function get_asset_values($asset){
		$this->db->select("component_sub_assets,component_imagepath as image,component_uom as uom,component_inspectiontype as insType,component_expectedresult as result,component_observation as observation,component_repair as repair");
		$this->db->from('components');
		$this->db->where('component_code',$asset);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$return = $result = $query->row_array();
			//print_r($return);die;
			return $return;
		}else{
			$this->db->select("sub_assets_uom as uom,sub_assets_inspection as insType,sub_assets_result as result,sub_assets_observation as observation,sub_assets_repair as repair");
			$this->db->from('sub_assets');
			$this->db->where('sub_assets_code',$asset);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->row_array();
			}else{
				return false;
			}
		}
	}
	
	function get_subasset_from_asset($asset){
		$result_subAsset = array();
		$res = $this->fetch_asset_values($asset);
		if($res){
			// Now check is the present asset contains any sub-asset or not. It Yes then fetch the sub_asset codes. //
			$result = $res;
			$subasset_code = ($result['asset'] !='' && $result['asset'] !='0')? json_decode($result['asset']) : '';
			
			if(is_array($subasset_code)){
				foreach($subasset_code as $code){
					$return_subasset = $this->fetch_subAsset_records($code);
					if($return_subasset){
						$result_subAsset[] = $return_subasset;
					}
				}
				return $result_subAsset;
			}else{
				// Component Code does not contain subassets. So we need values of Components. 
				// Components value fetched here will be single dimension but it will be converted into multidimensional. 
				return $result_subAsset = $result;
			}
		}else{
			// This code is not present in component table so not search in subasset table
			return $result_subAsset = $this->fetch_subAsset_records($asset);
		}
	}

	function fetch_asset_values($asset){
		$this->db->select("component_sub_assets as asset,component_imagepath as image,component_uom as uom,component_inspectiontype as insType,component_expectedresult as result,component_observation as observation,component_repair as repair");
		$this->db->from('components');
		$this->db->where('component_code',$asset);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}
	//sub_assets_excerpt as actionProposed,
	
	function fetch_subAsset_records($sub_asset){
		$this->db->select("sub_assets_code as asset,sub_assets_uom as uom,sub_assets_inspection as insType,sub_assets_result as result,sub_assets_observation as observation,sub_assets_repair as repair");
		$this->db->from('sub_assets');
		$this->db->where('sub_assets_code',$sub_asset);
		$query = $this->db->get();
		if($query->num_rows() > 0){
				return $query->row_array();
		}else{
			return false;
		}
	}
	
	/* Get Action Proposed On */
	function get_action_proposed($id,$type =''){
		$this->db->select('type_name as actionProposed,id');
		$this->db->from('type_category');
		$this->db->where('type_category',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			if($type !='view'){
				return $query->result_array();
			}else{
				$res = $query->result_array();
					echo "<option value=''> - Select Action Proposed - </option>";
				foreach($res as $val){
					echo "<option value='".$val['id']."'>".$val['actionProposed']."</option>";
				}
			}
		}else{
			return false;
		}
	}
	
	
	
	function get_sms_component($job_card,$sms_no,$asset_series,$assets){
		$this->db->select("item_quantity, no_of_lines");
		$this->db->from('sms_component');
		$this->db->where('jc_number',$job_card);
		$this->db->where('sms_number',$sms_no);
		$this->db->where('series',$asset_series);
		$this->db->where('item_code',$assets);
		$this->db->where('status','Active');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}
	
	function get_siteID_data($siteID_id){
		$this->db->select("*");
		$this->db->from('siteID_data');
		$this->db->where('siteID_id',$siteID_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}
	
	
	function first_slot_data_insert_old($dbdata){
		if($this->db->insert('inspection_list_1_temp',$dbdata)){
			$insert_id = $this->db->insert_id();
			return  $insert_id;
		}else{
			$this->session->set_flashdata('msg','<p>Error In Submitting First Slot Data</p>');
			return false;
		}
	}
	
	function first_slot_data_insert($dbdata){
		$dbdata['admin_approved_date'] = strtotime(date('Y-m-d H:i:s'));
		$dbdata['inspected_by'] = ($dbdata['inspected_by'] == NULL)?'':$dbdata['inspected_by'];
	
		if($this->db->insert('inspection_list_1_temp',$dbdata)){
			$insert_id = $this->db->insert_id();
			return  $insert_id;
		}else{
			$this->session->set_flashdata('msg','<p>Error In Submitting First Slot Data</p>');
			return false;
		}
	}
	
	function first_slot_data_update($id,$dbdata){
		//$this->db->where('report_no', $reportno);
		$this->db->where('id', $id);
		$result = $this->db->update('inspection_list_1_temp', $dbdata);
		if($result){
			return $id;
		}else{
			return false;
		}
	}
	
	function second_slot_data_inserted($dbdata){
		if($this->db->insert('inspection_list_2_temp',$dbdata)){
			$insert_id = $this->db->insert_id();
				return  $insert_id;
		}else{
			return false;
		}		
	}
	
	
	function second_slot_data_inserted_api($dbdata){
		if($this->db->insert('inspection_list_2_temp',$dbdata)){
			return true;
		}else{
			return false;
		}
	}
	
	function third_slot_data_insert($id,$dbdata){
		$this->db->where('id', $id);
		$result = $this->db->update('inspection_list_1_temp', $dbdata);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function fourth_slot_data_insert($id,$dbdata){
		$this->db->where('id', $id);
		//print_r($dbdata);die("123");
		$result = $this->db->update('inspection_list_1_temp', $dbdata);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/* Insert Data from First Temporary Table to First Main Table */
	/*function insert_main_table_data($id=''){
		
		$report_no = (isset($_SESSION['inspector']['report_no']))? $_SESSION['inspector']['report_no'] : '';

		$this->db->select("*");
		$this->db->from('inspection_list_1_temp');
		if(isset($_SESSION['inspector']['report_no']) && $id==''){
			$this->db->where('report_no',$report_no);
		}else{
			$this->db->where('id',$id);
		}
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			
			// Inspector ID 
			$user_id = $res['inspected_by'];
			// Insert Data In main Table 
			$db1 = array(
						'wpermit_id' 				=> $res['wpermit_id'],
						'siteID_id' 				=> $res['siteID_id'],
						'report_no' 				=> $res['report_no'],
						'site_id'					=> $res['site_id'],
						'job_card' 					=> $res['job_card'],
						'sms' 						=> $res['sms'],
						'created_date' 				=> $res['created_date'],
						'asset_series' 				=> $res['asset_series'],
						'lattitude' 				=> $res['lattitude'],
						'longitude' 				=> $res['longitude'],
						'map_image' 				=> $res['map_image'],
						'inspected_by' 				=> $res['inspected_by'],
						'approved_by' 				=> $res['approved_by'],
						'input_method' 				=> $res['input_method'],
						'input_value' 				=> $res['input_value'],
						'inspector_signature_image'	=>  $res['inspector_signature_image'],
						'client_name'				=>	$res['client_name'],
						'client_designation'		=>	$res['client_designation'],
						'client_signature_image'	=>	$res['client_signature_image'],
						'client_remark'				=>	$res['client_remark'],
						'inspected_status'			=>	$res['inspected_status'],
						'approved_status'			=>	$res['approved_status'],
						'approved_admin_id'			=>	$res['approved_admin_id'],
						'pdf_status'				=>	$res['pdf_status'],
						'email_status'				=>	$res['email_status'],
						'admin_approved_date'		=>	$res['admin_approved_date']
						);

				if($this->db->insert('inspection_list_1',$db1)){
					
					$insert_id = $this->db->insert_id();
					// Now Insert Data into second table
					$ids = ($id =='')? $res['id'] : $id;
					
					if($this->insert_second_main_table_data($ids,$insert_id,$user_id)){
						return true;
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Error in inserting Data in Main Table 2');
						return false;
					}
				}else{
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Error in inserting data in Main Table 1');
					return false;
				}
		}else{
			return false;
		}
	}*/
	
	function insert_main_table_data($id=''){
		$report_no = (isset($_SESSION['inspector']['report_no']))? $_SESSION['inspector']['report_no'] : '';

		$this->db->select("*");
		$this->db->from('inspection_list_1_temp');
		if(isset($_SESSION['inspector']['report_no']) && $id==''){
			$this->db->where('report_no',$report_no);
		}else{
			$this->db->where('id',$id);
		}
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			
			/* Inspector ID */
			$user_id = $res['inspected_by'];
			/* Insert Data In main Table */
			$db1 = array(
						'wpermit_id' 				=> $res['wpermit_id'],
						'siteID_id' 				=> $res['siteID_id'],
						'report_no' 				=> $res['report_no'],
						'site_id'					=> $res['site_id'],
						'job_card' 					=> $res['job_card'],
						'sms' 						=> $res['sms'],
						'created_date' 				=> $res['created_date'],
						'asset_series' 				=> $res['asset_series'],
						'lattitude' 				=> $res['lattitude'],
						'longitude' 				=> $res['longitude'],
						'map_image' 				=> $res['map_image'],
						'inspected_by' 				=> $res['inspected_by'],
						'approved_by' 				=> $res['approved_by'],
						'input_method' 				=> $res['input_method'],
						'input_value' 				=> $res['input_value'],
						'inspector_signature_image'	=>  $res['inspector_signature_image'],
						'client_name'				=>	$res['client_name'],
						'client_designation'		=>	$res['client_designation'],
						'client_signature_image'	=>	$res['client_signature_image'],
						'client_remark'				=>	$res['client_remark'],
						'inspected_status'			=>	$res['inspected_status'],
						'approved_status'			=>	$res['approved_status'],
						'approved_admin_id'			=>	$res['approved_admin_id'],
						'pdf_status'				=>	$res['pdf_status'],
						'email_status'				=>	$res['email_status'],
						'admin_approved_date'		=>	$res['admin_approved_date']
						);
						
				$client_id = $this->_clientID($res['siteID_id']);
				if(!empty($client_id)){
					$db1['client_id'] = $client_id;		
				}	
			
				if($this->db->insert('inspection_list_1',$db1)){
					
					$insert_id = $this->db->insert_id();
					// Now Insert Data into second table
					$ids = ($id =='')? $res['id'] : $id;
					
					if($this->insert_second_main_table_data($ids,$insert_id,$user_id)){
						return true;
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Error in inserting Data in Main Table 2');
						return false;
					}
				}else{
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b>Error in inserting data in Main Table 1');
					return false;
				}
		}else{
			return false;
		}
	}
	
	function _clientID($siteID_id){
        if(!empty($siteID_id)){
			$this->db->select('*');
			$this->db->from('assign_client_data');
			$query = $this->db->get();
			$result = $query->result_array();
			foreach($result as $cKey=>$CVal){
				$site_id = json_decode($CVal['site_id'],true);
				if(in_array($siteID_id,$site_id)){
					 $client_id[] = json_decode($CVal['client_ids'],true);
					 if(!empty($client_id[0]) && is_array($client_id)){
						$clientid_marge = $client_id[0];
						$count = count($client_id)-1;
						for($i=1;$i<=$count;$i++){
							$clientid_marge = array_unique(array_merge($clientid_marge,$client_id[$i]));
						}
					 }
				}
			}
			return json_encode($clientid_marge);
        }
    }
	
	/*
		Insert Data from Second Temporary Table to Second Main Table
		DELETE FROM `inspection_list_2` WHERE `inspection_list_id`='2740';
		DELETE FROM `workPermit_report` WHERE `wpermit_id`='7311';
		DELETE FROM `inspection_list_1` WHERE `id`='2740';
		
	*/
	function insert_second_main_table_data($inspection_list_id,$insert_id,$user_id){
		$this->db->select("*");
		$this->db->from('inspection_list_2_temp');
		$this->db->where('inspection_list_id',$inspection_list_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			
			$res = $query->result_array();
			$count = count($res);
			foreach($res as $val){
				
				$temp_2_id = $val['inspection_list_id'];
				$db2 = array(
							'inspection_list_id'	=> $insert_id,
							'asset_name' 			=> $val['asset_name'],
							'subAsset_name' 		=> $val['subAsset_name'],
							'before_repair_image' 	=> $val['before_repair_image'],
							'after_repair_image' 	=> $val['after_repair_image'],
							'observation_type' 		=> $val['observation_type'],
							'action_proposed' 		=> '',
							'result' 				=> ''
				);
				if(!$this->db->insert('inspection_list_2',$db2)){
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR!! : </b> Error In Inserting data in Second Main Table</p>');
					return false;
				}
			}
			
			/*
				Once All data got transfered,
				Now Delete values from Temorary Tables of the Inserted Client
			*/
			return $this->check_data_by_user_in_tmp_table($user_id);
		}else{
			return false;
		}
	}
	
	/* 
		IN API/WEB When User Logins, 
		Find if any data is peresnt in temporary table regarding the logged in user.
		If Yes then delete that data. Else do Nothing.
	*/
	function check_data_by_user_in_tmp_table($user_id, $from=''){
		$this->db->select('id,wpermit_id,');
		$this->db->where('inspected_by',$user_id);
		$query = $this->db->get('inspection_list_1_temp');
		if($query->num_rows() > 0){
			$res = $query->result_array();
			foreach($res as $val){
				$id_temp 		= $val['id'];
				$wpermitID_temp = $val['wpermit_id'];
				$this->empty_temporary_tables('inspection_list_1_temp',$user_id);
				$this->empty_temporary_tables('inspection_list_2_temp',$id_temp);
				if($from =='api'){
					$this->delete_id_from_workPermit($wpermitID_temp);
				}
			}
			return true;
		}else{
			return false;
		}
	}
	
	function empty_temporary_tables($tableName,$id){
		if($tableName == 'inspection_list_1_temp'){
			$this->db->where('inspected_by',$id);
			return $this->db->delete($tableName);
		}else if($tableName == 'inspection_list_2_temp'){
			$this->db->where('inspection_list_id',$id);
			return $this->db->delete($tableName);
		}
	}
	
	function delete_session_values(){
		if(isset($_SESSION['inspector'])){
			unset($_SESSION['inspector']);
		}
		return true;
	}

	/* 
		Function to check is any Report No already exist or not in Inspection Table for particular Site ID.
	*/
	function check_report_numbers($id){
		$this->db->select("report_no,inspected_status,approved_status");
		$this->db->from('inspection_list_1');
		$this->db->where('siteID_id',$id);
		$this->db->order_by('siteID_id','desc');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->result_array();
			$arr   = end($res);
			return $arr;
		}else{
			return false;
		}
	}
	
	function get_approved_status($id){
		$this->db->select("approved_status");
		$this->db->from('inspection_list_1');
		$this->db->where('siteID_id',$id);
		$this->db->where('approved_status','Pending');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			return $res['approved_status'];
		}else{
			return $res ='';
		}
	}
	
	function get_admin_approved_status($id){
		$this->db->select("approved_admin_id");
		$this->db->from('inspection_list_1');
		$this->db->where('siteID_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->row_array();
			return $res['approved_admin_id'];
		}else{
			return $res ='';
		}
	}
	
	function insert_work_permit($dbdata){
		//print_r($dbdata);die("123");
		if($this->db->insert('workPermit_report',$dbdata)){
			
			$insert_id = $this->db->insert_id();
			return  $insert_id;
		}else{
			return false;
		}
	}
	
	/* 
		Function to get Last Work Permit Number, 
		If Data is present in Inspection List 1 table.
	*/
	
	
	function check_work_permit_number($id){
		 $this->db->select('A.workPermit_number')
						->from('workPermit_report as A')
						->join('inspection_list_1 as B', 'A.siteId_name = B.id')
						->where('B.siteID_id',$id)
						->order_by('A.wpermit_id','desc')
						->limit(1);
						//->get('workPermit_report');
			$query =	$this->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		}else{
			return false;
		}
	}
	
	function get_pdf(){
		$this->db->select("id,lattitude,longitude");
		$this->db->from('inspection_list_1');
		$this->db->where('lattitude !=', '');
		$this->db->where('longitude !=', '');
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			$res = $query->result_array();
			//return $res['approved_admin_id'];
			
			foreach($res as $val){
				$id			= $val['id'];
				$lattitude	= $val['lattitude'];
				$longitude	= $val['longitude'];
				
				$path = $this->get_google_map_image($lattitude,$longitude);
				
				$dbdata = array('map_image'=> $path);
				$this->db->where('id', $id);
				$this->db->update('inspection_list_1', $dbdata);
			}
			return true;
		}else{
			return $res ='';
		}
	}
	
	
	function get_google_map_image($latt,$long){
		$map_loc = "https://maps.googleapis.com/maps/api/staticmap?center=".$latt.",".$long."&zoom=13&size=300x200&maptype=roadmap&markers=color:red%7Clabel:A%7C".$latt.",".$long."&key=AIzaSyBpSn0LXbq1wyv2bnZ9EQsqBMzpcxl6DEE";	
		$time = time();
		$desFolder = './uploads/google_map/';
		$imageName = 'google-map_'.$time.rand().'.jpg';
		$imagePath = $desFolder.$imageName;
		$db_imagePath = "FCPATH/uploads/google_map/";
		file_put_contents($imagePath,file_get_contents($map_loc));
		return $db_imagePath.$imageName;
	}

	function check_wpermitID_in_inspection_list_1($id){
		$this->db->from('inspection_list_1');
		$this->db->where('wpermit_id',$id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}
	
	function delete_id_from_workPermit($id){
		$this->db->where('wpermit_id',$id);
		return $this->db->delete('workPermit_report');
	}
	
	function delete_ins($id){
		$this->db->where('id',$id);
		return $this->db->delete('inspection_list_1');
	}
	
	function update_work_permit($id,$dbdata){
		$this->db->where('workPermit_number', $id);
		$result = $this->db->update('workPermit_report', $dbdata);
		if($result){
			$query = $this->db->select('wpermit_id')
					->where('workPermit_number',$id)
					->get('workPermit_report');
			$res = $query->row_array();
			return $res['wpermit_id'];
		}else{
			return false;
		}
	}
	
	function get_temp_1_inserted_id($id){
		$this->db->select('siteId_name');
		$this->db->from('workPermit_report');
		$this->db->where('wpermit_id', $id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row_array();
			return $temp_id = $res['siteId_name'];
		}else{
			return false;
		}
	}
	
	function get_today_date(){
		$this->db->select('today_date');
		$this->db->from('today_date');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row_array();
			return $temp_id = $res['today_date'];
		}else{
			return false;
		}
	}
	
	
	function truncate_tables(){
		$date = $this->get_today_date();
		$loginDate = date("d-m-Y", now());
		if($loginDate != $date){
			$this->db->select('id,wpermit_id,');
			$query = $this->db->get('inspection_list_1_temp');
			if($query->num_rows() > 0){
				$res = $query->result_array();
				foreach($res as $val){
					$wpermitID_temp = $val['wpermit_id'];
						$this->delete_id_from_workPermit($wpermitID_temp);
				}
			}
			
			$res1 = $this->db->query("TRUNCATE TABLE inspection_list_1_temp");
			$res2 = $this->db->query("TRUNCATE TABLE inspection_list_2_temp");
			if($res1 && $res2){
				$arr = array('today_date'=>$loginDate);
				$this->db->where('id', 1);
				$result = $this->db->update('today_date', $arr);
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}

	function check_report_asset_seriesnumbers($asset_series){
		$this->db->select("report_no,inspected_status,approved_status");
		$this->db->from('inspection_list_1');
		$this->db->where('asset_series',$asset_series);
		$this->db->order_by('siteID_id','desc');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$res = $query->result_array();
			$arr   = end($res);
			return $arr;
		}else{
			return false;
		}
	}
	
}// end of Model class 
?>