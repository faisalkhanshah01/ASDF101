<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Assign_client_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	function get_all_data(){
		$this->db->select('*');
		$this->db->from('client_manual_data');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function get_data_from_id($id){		
		$this->db->select('*');
		$this->db->from('client_manual_data');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();	
	}
	
	function get_client_data_by_id($client_id){		
		$this->db->select('*');
		$this->db->from('assign_client_data');
		$this->db->where('id',$client_id);
		$query = $this->db->get();
		return $query->row_array();	
	}
	
	function get_site_list($siteid){
		if(!empty($siteid)){
			$this->db->select("*");
			$this->db->from('inspection_list_1');
			$this->db->where('siteID_id',$siteid);
			$this->db->where('approved_status','Pending');
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return -1;
			}    
		}
	}
	
	/*************************api client**************************************/
	    function masterData($client_id){
            $this->db->select('mdata_id');
            $this->db->from('master_data');
            $this->db->where_in('mdata_client',$client_id);
            $query = $this->db->get();
            if($query->num_rows()>0){
                $master_ID = $query->result_array();
                $master_ID = array_column($master_ID, 'mdata_id');
                $return = $this->_siteData($master_ID);
           }else{
                $return -1;
            }
            ///print_r($result);die;
           //print_r($inspecterData);die;
            return $return;
        }
        
       
        
      function inspecterData(){
            $this->db->select('site_id,inspector_ids');
            $this->db->from('inspector_data');
            $query = $this->db->get();
            if($query->num_rows()>0){
                $result =  $query->result_array();
                $res = array();
                foreach ($result as $key=>$value){
                    $siteid = json_decode($value['site_id'],true);
                    if(!empty($siteid)){
                        $inspecterid =   json_decode($value['inspector_ids'],true);
                        $inspecterid = $inspecterid[0];
                        $res[$siteid[0]]['siteid'] = $siteid[0];
                        $res[$siteid[0]]['inspector_address'] = $this->_inspecterImage($inspecterid);
                    }
                } 
                return $res;
            }else{
                return -3;
            }
        }
        
        function inspecterDataClient($site_id){
             $this->db->select('site_id,inspector_ids');
            $this->db->from('inspector_data');
            $query = $this->db->get();
            if($query->num_rows()>0){
                $result =  $query->result_array();
                $res = array();
                foreach ($result as $key=>$value){
                    $siteid = json_decode($value['site_id'],true);
                    if(in_array($site_id, $siteid)){
                        $inspecterid =   json_decode($value['inspector_ids'],true);
                        $inspecterid = $inspecterid[0];
                        $res = $this->_inspecterImage($inspecterid);
                    }
                } 
                return $res;
            }else{
                return -3;
            }
        }
        
        function _inspecterImage($inspecterid){
            $this->db->select('upro_first_name,upro_last_name,upro_image,upro_phone');
            $this->db->from('demo_user_profiles');
             $this->db->where('upro_id',$inspecterid);
            $query = $this->db->get();
            return  $query->row_array();
        }
        
        function _siteData($master_ID){
            if(!empty($master_ID) && is_array($master_ID)){
                $this->db->select('*');
                $this->db->from('siteID_data');
                $this->db->where_in('master_id',$master_ID);
                $query = $this->db->get();
                
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return -2;
                }    
            }
        }
        
    function get_clientName_list($client_name){ 
		$this->db->select('A.client_id,A.client_name,B.type_name as client_type');
		$this->db->from('clients AS A');
		$this->db->join('type_category AS B', 'A.client_type = B.id', 'INNER');
		$this->db->where('A.client_status','Active');
                $this->db->where('A.client_name',$client_name);
		$this->db->where('B.status',1);
		$result=$this->db->get();
		return ($result->num_rows()>0)? $result->result_array() : FALSE;
	} 
	/***************************************************************/
	
	function get_client_data(){
		$this->db->select('*');
		$this->db->from('assign_client_data');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function get_site_data($siteid){
		if(!empty($siteid) && is_array($siteid)){
			$this->db->select('*');
			$this->db->from('siteID_data');
			$this->db->where_in('siteID_id',$siteid);
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return -2;
			}    
		}
	}
	
	function ajax_get_siteID($siteID_id = ''){
		if(!empty($siteID_id)){
			$this->db->select("siteID_id, site_id,site_location,site_city,site_address,site_sms,site_jobcard");
			$this->db->from('siteID_data');
			$this->db->where('status', 1);
			$this->db->where('siteID_id', $siteID_id);
		}else{
			//$this->db->select("siteID_id, site_id,site_sms,site_jobcard");
			$this->db->select("siteID_id, site_id,site_location,site_city,site_address,site_sms,site_jobcard");
			$this->db->from('siteID_data');
			$this->db->where('status', 1);
		}    
		$query = $this->db->get();
		if($query->num_rows()>0){
			if(!empty($siteID_id)){
				return $query->row_array();
			}else{
				return $query->result_array();
			}
		}else{
			return -1;
		}            
	}
	
	
	function get_clientName_siteID_Data($jobCard,$sms){
		$que = "SELECT A.mdata_client,B.client_name,B.client_district,B.client_circle
					FROM master_data as A
					LEFT JOIN clients as B
					ON A.mdata_client = B.client_id OR A.mdata_client = B.client_name
					WHERE A.mdata_jobcard ='".$jobCard."'
					AND A.mdata_sms ='".$sms."'";
		$queries = $this->db->query($que);
		return ($queries->num_rows()>0)? $queries->row_array() : $result = '0';
	}
	
	
	function fetch_client_data($params){
		$where = '';
		if(!empty($params['clientType'])){
			$where =" client_type = ".addslashes($params['clientType']);
		}else{
			$where =" client_type IN ('7', '8', '9') ";
		}
		
		if(!empty($params['startTime'])){
				$where .=" AND assign_data >= ".$this->db->escape($params['startTime']);
		}
		if(!empty($params['endTime'])){
				$params['endTime'] = (strpos($params['endTime'],':') !== false) ? $params['endTime'] : $params['endTime'].' 23:59:59';
				$where .=" AND assign_data <= ".$this->db->escape($params['endTime']);
		}
		
		
		$query = "Select * FROM assign_client_data where $where ORDER BY id ASC";
	
		$result =  $this->db->query($query);
		if($result->num_rows()>0){
				return $result->result_array();
		}else{
				return false;
		}
    }
	
	function get_clientName_siteID($jobCard,$sms){
		$que = "SELECT A.mdata_client,B.client_name
					FROM master_data as A
					LEFT JOIN clients as B
					ON A.mdata_client = B.client_id OR A.mdata_client = B.client_name
					WHERE A.mdata_jobcard ='".$jobCard."'
					AND A.mdata_sms ='".$sms."'";
		$queries = $this->db->query($que);
		return ($queries->num_rows()>0)? $queries->row_array() : $result = '0';
	}
	
	function get_client_list_name_id($clientNameVal,$client_type){
		$this->db->select('A.upro_id,A.upro_uacc_fk,A.upro_first_name,A.upro_last_name');
		$this->db->from('demo_user_profiles AS A');
		$this->db->join('user_accounts AS B', 'A.upro_uacc_fk = B.uacc_id', 'INNER');
		$this->db->where('B.uacc_group_fk',$client_type);
        $this->db->where('A.upro_uacc_fk',$clientNameVal);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_client_name_list($client_type){
		$query = $this->db->query("	
									SELECT CONCAT( A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_id = B.uacc_id
									INNER JOIN user_groups AS C
									ON B.uacc_group_fk = C.ugrp_id
									WHERE C.ugrp_id = ".$client_type." AND B.uacc_active=1 AND B.uacc_suspend=0
								") or die(mysql_error());
								
		return  array_column($query->result_array(),'full_name','id');
	}	
	
	function delete_client($client_id){
		$this->db->where('id',$client_id);
		return $this->db->delete('assign_client_data');
	}
	
	function get_client_list($field=NULL){
		if($field==NULL){
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
			$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_client_name($field=NULL){
		$query = $this->db->query("	
									SELECT CONCAT( A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_id = B.uacc_id
									INNER JOIN user_groups AS C
									ON B.uacc_group_fk = C.ugrp_id
									WHERE C.ugrp_id = 8 AND B.uacc_active=1 AND B.uacc_suspend=0
								") or die(mysql_error());
								
		return  array_column($query->result_array(),'full_name','id');
	}
	
	function search_ajax_client_list($query){
		$this->load->database();
		if($query != 'blank'){
			$sql = "SELECT CONCAT(A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
						FROM demo_user_profiles as A 
						INNER JOIN user_accounts as B
						ON A.upro_id = B.uacc_id
						INNER JOIN user_groups AS C
						ON B.uacc_group_fk = C.ugrp_id
						WHERE C.ugrp_id = 8 AND B.uacc_active=1 AND B.uacc_suspend=0 AND concat_ws(' ',A.upro_first_name,A.upro_last_name) 
						like '%$query%'";
		}else{
			$sql = "SELECT CONCAT(A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
						FROM demo_user_profiles as A 
						INNER JOIN user_accounts as B
						ON A.upro_id = B.uacc_id
						INNER JOIN user_groups AS C
						ON B.uacc_group_fk = C.ugrp_id
						WHERE C.ugrp_id = 8 AND B.uacc_active=1 AND B.uacc_suspend=0";
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
			return  array_column($query->result_array(),'full_name','id');
		}	
	}

	function search_ajax_client_name($query,$clientType){
		$this->load->database();
		if($query != 'blank'){
			$sql = "SELECT CONCAT(A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
						FROM demo_user_profiles as A 
						INNER JOIN user_accounts as B
						ON A.upro_id = B.uacc_id
						INNER JOIN user_groups AS C
						ON B.uacc_group_fk = C.ugrp_id
						WHERE C.ugrp_id = ".$clientType." AND B.uacc_active=1 AND B.uacc_suspend=0 AND concat_ws(' ',A.upro_first_name,A.upro_last_name) 
						like '%".$query."%'";
		}else{
			$sql = "SELECT CONCAT(A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
						FROM demo_user_profiles as A 
						INNER JOIN user_accounts as B
						ON A.upro_id = B.uacc_id
						INNER JOIN user_groups AS C
						ON B.uacc_group_fk = C.ugrp_id
						WHERE C.ugrp_id = ".$clientType." AND B.uacc_active=1 AND B.uacc_suspend=0";
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
			return  array_column($query->result_array(),'full_name','id');
		}	
	}
	
	
	/*function insert_client_data($dbdata){
		return  $this->db->insert('assign_client_data',$dbdata);
	}*/
	
	function insert_client_data($dbdata){
		if(!empty($dbdata['site_id']) && !empty($dbdata['client_ids'])){
			$this->db->insert('assign_client_data',$dbdata);
			$result = $this->db->insert_id();
			if($result > 0){
				return $result;
			}else{
				 return -1;
			}
		}else{
			return -2;
		}	
	}
	
	function update_client_data($dbdata,$client_id){
		$this->db->where('id',$client_id);
		return $this->db->update('assign_client_data',$dbdata);	
	}
	
	function get_client_user_name_by_id($clientVal){
		$this->db->select('client_id,client_name,client_district,client_circle');
		$this->db->from('clients');
		$this->db->where('client_id',$clientVal);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_client_user_name_by_id_new($clientVal){
		$this->db->select('client_id,client_name,client_district,client_circle');
		$this->db->from('clients');
		$this->db->where('client_id',$clientVal);
		$query = $this->db->get();
		$return = $query->row_array();
		return $return;
		//print_r($return);die;
	}
	
	function get_client_list_name($clientType){
		$query = $this->db->query("	
							SELECT CONCAT( A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
							FROM demo_user_profiles as A 
							INNER JOIN user_accounts as B
							ON A.upro_uacc_fk = B.uacc_id
							INNER JOIN user_groups AS C
							ON B.uacc_group_fk = C.ugrp_id
							WHERE C.ugrp_id = ".$clientType." AND B.uacc_active=1 AND B.uacc_suspend=0
						") or die(mysql_error());
							
		return  array_column($query->result_array(),'full_name','id');
	}
	
	
	function get_client_list_name_by_id($clientNameVal){
		$this->db->select('upro_id,upro_uacc_fk,upro_first_name,upro_last_name');
		$this->db->from('demo_user_profiles');
		$this->db->where('upro_uacc_fk',$clientNameVal);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function get_client_data_by_user($id,$from=''){
		
		$query = $this->db->query("SELECT client_users, client_ids FROM assign_client_data WHERE client_ids like '%".$id."%'  AND status ='Active '");
		if($query->num_rows()>0){
			$res = $query->result();
			$client_ids = array();
			foreach($res as $key=>$val){
				$array_client_id = json_decode($val->client_ids,true);
				// if client id is present in $array_client_id then proceed further.
				if(in_array($id,$array_client_id)){
					$company_names_arr = json_decode($val->client_users,true);
					foreach($company_names_arr as $sVal){
						$company_ids[] =  $sVal;
					}
				}
			}
			
			if(empty($company_ids)){
				// ERROR C0001 : "No Company is assign to you";
				return ($from != '')? FALSE : "C0001";
			}else{
				$all_companyID = array();
				$missing_company_ids = array();
				$row = array();
				
				$all_companyID = array_unique($company_ids);
				
				$site_datas = $inspected_site_data =  array();
				$total_sites = 0;
				foreach($all_companyID as $companyVal ){
					$names = $this->get_client_user_name_by_id_new($companyVal);
					$client_datas[$companyVal] = $names['client_name'];
					// Get JOB Card and SMS Numbers Assigned to clients
					$site_data = $this->getjobcardSmsSiteID_from_clients($companyVal,$names['client_name']);
					
					if($site_data){
						$total_sites = $total_sites + count($site_data);
						
						$site_datas[] = array('client_id'=>$companyVal, 'client_name'=>$names['client_name'], 'site_data'=>$site_data);
						
						// Get Inspected Site IDs
						foreach($site_data as $siteVal){
							// Get Inspected Site Id's Data
							$res = $this->all_inspected_siteids($siteVal['siteID_id']);

							if($res){
								$inspected_site_data[] = $res;
							}
							// Get Site Id's Due for Inspection
						}
					}
				}
				
				if(empty($site_datas)){
					// ERROR C0002 : "Company's assigned to the Client are not present in clients or master table or site_id table.";
					return ($from != '')? FALSE : "C0002";
				}else{
					$total_site = $total_sites;
					$total_inspected = (!empty($inspected_site_data))? count($inspected_site_data) : '0';
					return array('site_data'=>$site_datas, 'inspected_site_data'=>$inspected_site_data, 'total_site'=>$total_site, 'total_inspected'=>$total_inspected);
				}
			}
		}else{
			// ERROR C0001 : "No Company is assign to you";
			return ($from != '')? FALSE : "C0001";
		}
		
	}
	
	function get_client_data_by_user_old($id){
		
		$query = $this->db->query("SELECT client_users, client_ids FROM assign_client_data WHERE client_ids like '%".$id."%'  AND status ='Active '");
		if($query->num_rows()>0){
			$res = $query->result();
			$client_ids = array();
			foreach($res as $key=>$val){
				$array_client_id = json_decode($val->client_ids,true);
				// if client id is present in $array_client_id then proceed further.
				if(in_array($id,$array_client_id)){
					$company_names_arr = json_decode($val->client_users,true);
					foreach($company_names_arr as $sVal){
						$company_ids[] =  $sVal;
					}
				}
			}
			
			if(empty($company_ids)){
				// ERROR C0001 : "No Company is assign to you";
				return "C0001";
			}else{
				$all_companyID = array();
				$missing_company_ids = array();
				$row = array();
				
				$all_companyID = array_unique($company_ids);
				
				// echo "<pre>";
				// print_r($company_ids);
				// print_r($all_companyID);
				// die;
				
				$site_datas = $inspected_site_data =  array();
				foreach($all_companyID as $companyVal ){
					$names = $this->get_client_user_name_by_id($companyVal);
					// echo "<pre>";
					// print_r($names);
					// print_r($company_result);
					// die;
					$client_datas[$companyVal] = $names['client_name'];
					// Get JOB Card and SMS Numbers Assigned to clients
					$site_data = $this->getjobcardSmsSiteID_from_clients($companyVal,$names['client_name']);
					
					if($site_data){
						
						$site_datas[] = array('client_id'=>$companyVal, 'client_name'=>$names['client_name'], 'site_data'=>$site_data);
						
						// Get Inspected Site IDs
						foreach($site_data as $siteVal){
							// Get Inspected Site Id's Data
							$res = $this->all_inspected_siteids($siteVal['siteID_id']);

							if($res){
								$inspected_site_data[] = $res;
							}
							// Get Site Id's Due for Inspection
						}

					}
				}
				
				
					// echo "<pre>";
					// print_r($inspected_site_data);
					// die;
				
				if(empty($site_datas)){
					// ERROR C0002 : "Company's assigned to the Client are not present in clients or master table or site_id table.";
					return "C0002";
				}else{
					return array('site_data'=>$site_datas, 'inspected_site_data'=>$inspected_site_data);
				}
			}
		}else{
			// ERROR C0001 : "No Company is assign to you";
			return "C0001";
		}
		
	}
	
	function all_inspected_siteids($id){
		$inspected_data = $this->get_inspectedSite_ids($id);
		if($inspected_data){
			return $inspected_data;
		}else{
			return false;
		}
	}
	
	// function get_companyID_item($itemid){ 
		// $this->db->select('*');
		// $this->db->where('client_id',$itemid);
		// $this->db->where('client_status','Active');
		// $result=$this->db->get('clients');
		// if($result->num_rows()>0){
			// return $result->row_array();
		// }else{
			// return false;
		// }
	// }
	
	
	function getjobcardSmsSiteID_from_clients($clientID, $clientName){
		$query = $this->db->query("SELECT A.mdata_jobcard, A.mdata_sms, B.site_id AS siteName, B.siteID_id
								FROM master_data as A
								INNER JOIN siteID_data as B
 								ON A.mdata_id = B.master_id
								WHERE A.mdata_client=".$clientID." OR A.mdata_client='".$clientName."'
								AND A.status ='Active' AND B.status ='Active'");
		if($query->num_rows()>0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	function get_inspectedSite_ids($siteID){
		$query = $this->db->query("SELECT A.*, CONCAT( B.upro_first_name ,' ', B.upro_last_name) as inspector_name
								FROM inspection_list_1 as A
								INNER JOIN demo_user_profiles as B
 								ON A.inspected_by = B.upro_uacc_fk
								WHERE A.siteID_id=".$siteID."
								AND A.approved_status ='Approved' 
								ORDER BY A.id DESC
								");
		if($query->num_rows()>0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
}// end of Model class 
?>