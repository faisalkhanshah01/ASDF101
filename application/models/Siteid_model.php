<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/


class Siteid_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	function get_site_data_by_id($siteID){
		if(!empty($siteID) && is_array($siteID)){
			$this->db->select('*');
			$this->db->where_in('siteID_id',$siteID);
			$result=$this->db->get('siteID_data');
			if($result->num_rows()>0){
				return $result->result_array();
			}else{
				return -1;
			}
		}else{
			return -2;
		}	
	}
    
	function get_siteID_list_via_ajax($sWhere,$sOrder,$sLimit){
                if($sWhere){                                
                    $sWhere .=" AND site_client_fk=".$_SESSION['client']['client_id'];
                } 
		$query = $this->db->query("SELECT * FROM `siteID_data` {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
	
	function get_Asset_list_via_ajax($sWhere,$sOrder,$sLimit){
            
                /*if($sWhere){                                
                    $sWhere .=" AND component_client_fk=".$_SESSION['client']['client_id'];
                }*/
		$query = $this->db->query("SELECT * FROM `components` {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
	
	function get_asset_list($params = ''){
            
                $client_id=$_SESSION['client']['client_id'];
            
		if(!empty($params['startTime'])){
			$where ="component_created_date >= ".$this->db->escape($params['startTime']);
		}
		if(!empty($params['endTime'])){
			$params['endTime'] = (strpos($params['endTime'],':') !== false) ? $params['endTime'] : $params['endTime'].' 23:59:59';
			$where .=" AND component_created_date <= ".$this->db->escape($params['endTime']);
		}
		$this->db->select('*');
		if(!empty($where)){
			$this->db->where($where);
		}
                $this->db->where('component_client_fk',$client_id);
                
		$this->db->order_by("component_id", "desc");
		$result=$this->db->get('components');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	
	
	function get_AssetSeries_list_via_ajax($sWhere,$sOrder,$sLimit){
		$query = $this->db->query("SELECT * FROM `products` {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
	
	function get_assets_series_list($params = ''){
            
            $client_id=$_SESSION['client']['client_id'];
            
		if(!empty($params['startTime'])){
			$where ="product_created_date >= ".$this->db->escape($params['startTime']);
		}
		if(!empty($params['endTime'])){
			$params['endTime'] = (strpos($params['endTime'],':') !== false) ? $params['endTime'] : $params['endTime'].' 23:59:59';
			$where .=" AND product_created_date <= ".$this->db->escape($params['endTime']);
		}
		$this->db->select('*');
		if(!empty($where)){
			$this->db->where($where);
		}
                $this->db->where('product_client_fk',$client_id);
                
		$this->db->order_by("product_id", "desc");
		$result=$this->db->get('products');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_subAsset_list_via_ajax($sWhere,$sOrder,$sLimit){
		$query = $this->db->query("SELECT * FROM `sub_assets` {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
	
	function get_subAsset_list($params = ''){
                $client_id=$_SESSION['client']['client_id'];
		if(!empty($params['startTime'])){
			$where ="time >= ".$this->db->escape($params['startTime']);
		}
		if(!empty($params['endTime'])){
			$params['endTime'] = (strpos($params['endTime'],':') !== false) ? $params['endTime'] : $params['endTime'].' 23:59:59';
			$where .=" AND time <= ".$this->db->escape($params['endTime']);
		}
		$this->db->select('*');
                $this->db->where('sub_assets_client_fk',$client_id);
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by("sub_assets_id", "desc");
		$result=$this->db->get('sub_assets');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_masterData_list_via_ajax_sachin_31_08_2018($sWhere,$sOrder,$sLimit){
		$query = $this->db->query("SELECT A.mdata_id,A.mdata_jobcard, A.mdata_sms, A.mdata_batch, A.mdata_serial, A.mdata_rfid,
							A.mdata_barcode,A.mdata_uin,A.mdata_client,
							B.client_name,A.status as Status,C.type_name
									FROM master_data AS A
									LEFT JOIN clients AS B
									ON A.mdata_client = B.client_id
									LEFT JOIN type_category as C
									ON B.client_type = C.id
									{$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
        
        function get_masterData_list_via_ajax($sWhere,$sOrder,$sLimit){
            
                if($sWhere){
                    $sWhere .=" And mdata_client_fk=".$_SESSION['client']['client_id'];
                }
		$query = $this->db->query("SELECT A.mdata_id,A.mdata_jobcard, A.mdata_sms,A.mdata_asset,A.mdata_item_series,A.mdata_batch, A.mdata_serial, A.mdata_rfid,
							A.mdata_barcode,A.mdata_uin,A.mdata_client,A.mdata_error_flag,A.mdata_error_summery,
							A.status as Status
							FROM master_data AS A
                                                        {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
        
	
	function get_smsComponent_list_via_ajax($sWhere,$sOrder,$sLimit){
                
                $sWhere .=" AND client_fk=".$_SESSION['client']['client_id'];
                
		$query = $this->db->query("SELECT * FROM `sms_component` {$sWhere} {$sOrder} {$sLimit} ");
		//echo $this->db->last_query();
		return $row = $query->result_array();
	}
	
	function get_client_list_via_ajax($sWhere,$sOrder,$sLimit){
                if($sWhere){ 
                  $sWhere .=" AND client_client_fk=".$_SESSION['client']['client_id'];  
                }
		$query = $this->db->query("SELECT * FROM `clients` {$sWhere} {$sOrder} {$sLimit}");
		return $row = $query->result_array();
	}
	
	function get_total_count_siteid(){
		return $table_row_count = $this->db->count_all('siteID_data');
	}
	
	function get_total_count_client(){
		return $table_row_count = $this->db->count_all('clients');
	}
	
	function get_total_count_masterData(){
                 $this->db->where('mdata_client_fk',$_SESSION['client']['client_id']);
                 $this->db->get('master_data');
		return $table_row_count = $this->db->count_all();
	}
	
	function get_total_count_Asset(){
		return $table_row_count = $this->db->count_all('components');
	}
	
	function get_total_count_AssetSeries(){
		return $table_row_count = $this->db->count_all('products');
	}
	
	function get_total_count_subAsset(){
		return $table_row_count = $this->db->count_all('sub_assets');
	}
	
	function get_total_count_SmsComponent(){
                    $this->db->where('client_fk',$_SESSION['client']['client_id']);
                    $this->db->get('sms_component');
                   return  $table_row_count = $this->db->count_all();
                    //$this->db->last_query();
	}
	
	
	function import_siteID_xls($dbdata){
		return $this->db->insert_batch('siteID_data',$dbdata);  
	}    

	function get_siteID_list($field=NULL){
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
		//$this->db->select('*');
		$result=$this->db->get('siteID_data');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	


	
	// recursive function
	function array_flatten($array) { 
		if (!is_array($array)) { 
			return FALSE; 
		} 
		$result = array(); 
		foreach ($array as $key => $value) { 
			if (is_array($value)) {
				$result = array_merge($result, $this->array_flatten($value)); 
			} 
			else { 
				$result[$key] = $value; 
			}
		}
		return $result; 
	} 
	
  

	
	function get_siteID_item($itemid){ 
		$this->db->select('*');
		$this->db->where('siteID_id',$itemid);
		$result=$this->db->get('siteID_data');
		if($result->num_rows()>0){
			return $result->row_array();
		}else{
			return false;
		}
	}

	function jobcardSMS_unique_identity($jobcard,$sms){
		$this->db->select('mdata_sms');
		$this->db->where('mdata_jobcard',$jobcard);
		$result=$this->db->get('master_data');
		if($result->num_rows()>0){
			$query = $result->result();
			foreach($query as $val){
				$dbSMS = $val->mdata_sms;
				if($sms == $dbSMS){
				$sms_found = $sms;
				}
			}
			if(isset($sms_found)){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		} 
	}
	

	function duplicate_jobSMSSite_list(){
		
		$res = $this->db->query('select * 
							from siteID_data
							group by site_jobcard ,site_sms, site_id
							having count(*)>=2' );
		if($res->num_rows()>0){
			$result = $res->result();
			$jobCard = array();
			
			foreach($result as $key=>$val){
				$jobCard[$key]['job'] = $val->site_jobcard;
				$jobCard[$key]['sms'] = $val->site_sms;
				$jobCard[$key]['site'] = $val->site_id;
			}
			return $jobCard;
		}else{
			return $jobCard = '';
		}
	}
	
	
	function check_siteID_unique($job,$sms,$site){
		$res = $this->db->query('SELECT * 
								FROM siteID_data
								WHERE site_jobcard = "'.$job.'" AND site_sms ="'.$sms.'" AND site_id = "'.$site.'"'
								);
		if($res->num_rows()>0){
			return $site;
		}else{
			return 'Yes';
		}
	}
	
	function insert_siteID($dbdata){
		return  $this->db->insert('siteID_data',$dbdata);
	}	

	function update_siteID($dbdata,$itemid){
		$this->db->where('siteID_id',$itemid);
		return $this->db->update('siteID_data',$dbdata);
	}
	
	
	
	function delete_jobId_inspector_form($mdata_id){
		$this->db->select('id,jobCard_id');
		//$this->db->where('mdata_id',$mdata_id);
		$result = $this->db->get('inspector_data');
		if($result->num_rows()>0){
			$row = $result->result_array();
			$newRow = array();
			foreach($row as $rowVal){
				$rowID		= $rowVal['id'];
				$rowArray 	= json_decode($rowVal['jobCard_id']);
				if(in_array($mdata_id,$rowArray)){
					$rowKey = array_search($mdata_id,$rowArray);
					array_splice($rowArray,$rowKey,1);
					$newRow[$rowID] = $rowArray;
				}
			}
			if(!empty($newRow)){
				$this->update_inspector_jobID_column($newRow);
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	} 
	
	function delete_siteID_data($siteID_id){
		$this->db->where('siteID_id',$siteID_id);
		return $this->db->delete('siteID_data');
	}  
	
	function delete_siteId_inspector_form($siteID_id){
		$this->db->select('id,inspector_ids');
		$result = $this->db->get('inspector_data');
		if($result->num_rows()>0){
			$row = $result->result_array();
			$newRow = array();
			foreach($row as $rowVal){
				$rowID		= $rowVal['id'];
				$rowArray 	= json_decode($rowVal['inspector_ids']);
				if(in_array($siteID_id,$rowArray)){
					$rowKey = array_search($siteID_id,$rowArray);
					array_splice($rowArray,$rowKey,1);
					$newRow[$rowID] = $rowArray;
				}
			}
			if(!empty($newRow)){
				$this->update_inspector_jobID_column($newRow);
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	
	public function ajax_get_siteID_from_sms($sms_number,$jobCards){
		
		$this->db->select("siteID_id, site_id");
		$this->db->from('siteID_data');
		$this->db->where('site_jobcard', $jobCards);
		$this->db->where('site_sms', $sms_number);
		$this->db->where('status', 1);
		$query = $this->db->get();
        
		if($query->num_rows()){
			return $query->result_array();
		}else{
			return false;
        }
	}
	
	
	/* For API */
	function get_siteid_list_of_inspector($field=NULL,$group_id="",$userID=''){
		
		if($userID == ''){
			$id = $this->session->flexi_auth['id'];
		}else{
			$id = $userID;
		}
		
		$query = $this->db->query("SELECT inspector_ids, site_id FROM inspector_data WHERE inspector_ids like '%".$id."%' ");
		if($query->num_rows()>0){
			$res = $query->result();
			$site_ids = array();
			foreach($res as $key=>$val){
				$array_ins_id = json_decode($val->inspector_ids,true);
				// if inspector id is present in $array_ins_id then proceed further.
				if(in_array($id,$array_ins_id)){
					$site_ids_arr = json_decode($val->site_id,true);
					foreach($site_ids_arr as $sVal){
						$site_ids[] =  $sVal;
					}
				}
			}

			if(empty($site_ids)){
				// ERROR 0001 : "No Site ID is assign to you";
				return "0001";
			}else{
				$all_siteID = array();
				$missing_site_ids = array();
				$row = array();
				
				$all_siteID = array_unique($site_ids);
				foreach($all_siteID as $siteVal ){
					$site_result = $this->get_siteID_item($siteVal);
					if($site_result){
						$row[] = $site_result;
					}else{
						$missing_site_ids[] = $siteVal;
					}
				}
				if(empty($row) && !empty($missing_site_ids)){
					// ERROR 0002 : "Site IDs assigned to the Inspector are not present in siteID_data table.";
					return "0002";
				}else{
					return $row;
				}
			}
		}else{
			// ERROR 0001 : "No Site ID is assign to you";
			return "0001";
		}
	}

	
	/*
	function get_siteid_list_of_inspector($field=NULL,$group_id="",$userID=''){
		
		if($userID == ''){
			$id = $this->session->flexi_auth['id'];
		}else{
			$id = $userID;
		}
		
		
		//$query = $this->db->query("SELECT site_id FROM inspector_data WHERE inspector_ids like '%".$id."%' ");
		// echo $this->db->last_query();
		// die;
		if($query->num_rows()>0){
			$res = $query->result();
			$all_siteID = array();
			foreach($res as $rVal){
				if(!empty($rVal->site_id)){
					$siteIDs = json_decode($rVal->site_id);
					foreach($siteIDs as $sVal){
						if(!empty($sVal)){
							$all_siteID[] = $sVal;
						}
					}
				}
			}

			if(empty($all_siteID)){
				return "Site ID's assigned is blank, check Site ID Data in Web Form or contact Administrator";
			}else if(is_array($all_siteID)){
				$row = array();
				$missing_site_ids = array();
				$all_siteID = array_unique($all_siteID);
				foreach($all_siteID as $siteVal ){
					$site_result = $this->get_siteID_item($siteVal);
					if($site_result){
						$row[] = $site_result;
					}else{
						$missing_site_ids[] = $siteVal;
					}
				}
				if(!isset($row)){
						return "Site IDs assigned to the Inspector does not present in Site ID Web Form. Please contact Administration Department for it";
				}else{
					return $row;
				}
			}
		}else{
			return "No Site ID is assign to you";
		}
	}
	
	*/
	
	public function get_jobcard_from_siteID()
	{
		$this->db->select("site_jobcard,site_sms");
		$this->db->from('siteID_data');
	//	$this->db->group_by('site_jobcard');
		$this->db->group_by('site_sms');
		$this->db->order_by('site_jobcard','ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_total_siteID_count($job,$sms)
	{
		$this->db->select("COUNT(site_id) AS totalSiteID");
		$this->db->from('siteID_data');
		$this->db->where('site_jobcard',$job );
		$this->db->where('site_sms',$sms);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();
			
		}else{
			return $a = '';
		}
	}
	
	public function get_lines_from_smsController($job,$sms)
	{
		$this->db->select("no_of_lines");
		$this->db->from('sms_component');
		$this->db->where('jc_number',$job );
		$this->db->where('sms_number',$sms);
		$this->db->group_by('sms_number');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();	
		}else{
			return $a = '';
		}
	}
	
	public function get_jobcard()
	{
		$this->db->select("mdata_id, mdata_jobcard");
		$this->db->from('master_data');
		$this->db->group_by('mdata_jobcard');
		$this->db->order_by('mdata_jobcard','ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return $a = '';
		}
		
	}
        
        public function get_jobcard_list()
	{
            
            $client_id=$_SESSION['client']['client_id']; 
            $this->db->select('DISTINCT(mdata_jobcard), mdata_id');
            $this->db->from('master_data');
            $this->db->where('mdata_client_fk',$client_id);
                
                $this->db->group_by('mdata_jobcard');
		$this->db->order_by('mdata_jobcard','ASC');
                
		$query = $this->db->get();
                #echo $this->db->last_query();
                
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return '';
		}
		
	}

        public function get_sms_list()
	{
		$this->db->select("mdata_id, mdata_sms");
		$this->db->from('master_data');
		$this->db->group_by('mdata_jobcard');
		$this->db->order_by('mdata_sms','ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return $a = '';
		}
		
	}
	
	public function ajax_get_sms($jobcard) {	
        $this->db->select("mdata_id, mdata_sms, mdata_jobcard");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $jobcard);
		$query = $this->db->get();
        if($query->num_rows() > 0){
			return $query->result();
		}else{
			return $a = '';
		}
    }
	
	function get_masterData_id($jobCard,$sms){
		$this->db->select("mdata_id");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $jobCard);
		$this->db->where('mdata_sms', $sms);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$result = $query->row();
			return $result->mdata_id;
		}else{
			return $result = '0';
		}
	}
	
	/*function get_clientName_siteID($jobCard,$sms){

		$que = "SELECT A.mdata_client,B.client_name
					FROM master_data as A
					LEFT JOIN clients as B
					ON A.mdata_client = B.client_id OR A.mdata_client = B.client_name
					WHERE A.mdata_jobcard ='".$jobCard."'
					AND A.mdata_sms ='".$sms."'";
		 $queries = $this->db->query($que);
		
		if($queries->num_rows() > 0){
			return $result = $queries->row();
			//return $result->mdata_client;
		}else{
			return $result = '0';
		}
	}*/
	
	function get_clientName_siteID($jobCard,$sms){
		$que = "SELECT A.mdata_client,B.client_name
					FROM master_data as A
					LEFT JOIN clients as B
					ON A.mdata_client = B.client_id OR A.mdata_client = B.client_name
					WHERE A.mdata_jobcard ='".$jobCard."'
					AND A.mdata_sms ='".$sms."'";
		$queries = $this->db->query($que);
		return ($queries->num_rows()>0)? $queries->row() : $result = '0';
	}
	
	function get_siteID_list_forDownload($rel,$jobCard,$sms){
		
		$query = "	SELECT B.mdata_jobcard as 'Job Card', B.mdata_sms as 'SMS Number', A.site_id as 'Site ID', A.site_location as 'Site Location', 
					A.site_city  as 'Site City', A.site_address as 'Site Address', A.site_lattitude as 'Site Lattitude', 
					A.site_longitude as 'Site Longitude', A.site_contact_name as 'Site Contact Person Name', A.site_contact_number as 'Contact Person Number', 
					A.site_contact_email as 'Contact Person Email', A.status as 'Site Status'
					FROM siteID_data as A
					INNER JOIN master_data as B
					ON A.master_id = B.mdata_id
					WHERE A.site_jobcard ='".$jobCard."'
					AND A.site_sms ='".$sms."'";
		if($rel =="CSV Format"){
			return $result = $this->db->query($query);
		}else if($rel =="XLS Format"){
			$result = $this->db->query($query);
			return $result->result_array();
		}
	}
	
	function isRowNotEmpty($row)
	{
		foreach ($row->getCellIterator() as $cell) {
			if ($cell->getValue()) {
				return true;
			}
		}
		return false;
	}

	
 }// end of class 



?>