<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Date 	: 21-May-2016
*	Email 	: shakti.singh@flashonmind.com
*/


class Kare_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_fileData($inspector_id){  
		$this->db->select('file');
		$this->db->where('id',$inspector_id);
		$result=$this->db->get('inspector_data');
		if($result->num_rows()>0){
			$return = $result->row_array();
		}else{
			$return = -1;
		}
		return $return;
	}
	
	function manageInspector_doc_update($inspector_id,$data){
		if(!empty($inspector_id)){
			$this->db->set('file',$data);
			$this->db->where('id',$inspector_id);
			$return = $this->db->update('inspector_data');
			if($return){
				return 1;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
	}
	
	function get_imei_email_id($id){
		$this->db->select('uacc_email,uacc_id');
		$this->db->from('user_accounts');
		 $this->db->where('uacc_id =',$id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$return = $query->row_array();
			return $return['uacc_email'];
		}else{
			return -1;
		}
	}
	
	public function store_logs($data) {
		$this->db->insert('logs_data', $data);
                $insert_id = $this->db->insert_id();
                if($insert_id > 0){
                    return 1;
		}else{
		   return -1;
		}
	}
	
	function fetch_imei_email_id($id){
		$this->db->select('*');
		$this->db->from('demo_user_profiles');
		 $this->db->where('upro_uacc_fk =',$id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$return = $query->row_array();
			return $return;
		}else{
			return -1;
		}
	}
	
	function update_imei_email_id($id,$dbdata,$upro_id = ''){
	    if(!empty($id)){
			$this->db->where('upro_uacc_fk',$id);
			if(!empty($upro_id)){
				$this->db->where('upro_id',$upro_id);
			}
			$return = $this->db->update('demo_user_profiles', $dbdata);
			if($return){
				return 1;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
	}
	
	function get_imei_no(){
		$query = 'SELECT * FROM demo_user_profiles WHERE upro_mob_imei != "" ORDER BY (IF(upro_mob_imei != upro_mob_new_imei, "upro_mob_new_imei", "upro_mob_imei")) DESC';
		$result = $this->db->query($query);
		if($result->num_rows()>0){
				$return = $result->result_array();
				return $return;
		}else{
				return -1;
		}
	}
	
	function get_imei_no_old(){
		$this->db->select('*');
		$this->db->from('demo_user_profiles');
		 $this->db->where('upro_mob_imei !=','');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result_array();
		}else{
			return -1;
		}
	}
	
	function check_product($tableName,$productName,$check=''){
		if(!empty($tableName) && !empty($productName)){
			$this->db->select('*');
			$this->db->from($tableName);
			if(!empty($check)){
				$this->db->where('product_code',$productName);
			}else{
				$this->db->where('component_code',$productName);
			}	
			$query = $this->db->get();
			if($query->num_rows()>0){
				$return = $query->row_array();
				return count($return);
			}else{
				return -2;
			}
		}else{
			return -1;
		}
	}
	
	function save_components_list($dbdata){
		if($this->db->insert('components',$dbdata)){
			//print_r($this->db->last_query());die;
		   return 1;
		}
		else{
		   return -1;
		}
	  //echo $this->db->last_query();
	}
  
 function  get_components_list($field=NULL){
        $client_id=$_SESSION['client']['client_id'];
	if($field==NULL){ 
	$this->db->select('*');
	}else{
	$this->db->select($field);	
	}
        $this->db->where('component_client_fk',$client_id);
	$result=$this->db->get('components');
	if($result->num_rows()>0){
		return $result->result_array();
	}else{
		return false;
		} 
	 
  }	
  
	function get_sub_assets_image($sub_assets_code){
		if(!empty($sub_assets_code)){
			$this->db->select('*');
			$this->db->where('sub_assets_code',$sub_assets_code);
			$result=$this->db->get('sub_assets');
			if($result->num_rows()>0){
				$return = $result->row_array();
				return $return['sub_assets_imagepath'];
			}else{
				return false;
			}
		}else{
			return -1;
		}
	}
  
	function get_component($component_id){
		$this->db->select('*');
		$this->db->where('component_id',$component_id);
		$this->db->or_where('component_code',$component_id);
		$result=$this->db->get('components');
		if($result->num_rows()>0){
			return $result->row_array();
		}else{
			return false;
		}
	} 
	
	function get_emailID($userID){
		if(!empty($userID)){
			$this->db->select('uacc_id,uacc_email');
			$this->db->where('uacc_id', $userID);
			$result=$this->db->get('user_accounts');
			if($result->num_rows()>0){
				$return = $result->result_array();
							return $return[0];
			}else{
				return -1;
			}
		}else{
			return -2;
		}    
    }
  
	function update_component($dbdata,$component_id){
		$this->db->where('component_id', $component_id);
		$return	= $this->db->update('components', $dbdata);
		//print_r($this->db->last_query());die;
		return $return;
	} 
	
	function delete_component($component_id){
		$this->db->where('component_id',$component_id);	
		return $this->db->delete('components');	 
	}
	
	function delete_sub_assets($component_id){
		$this->db->where('sub_assets_id',$component_id);	
		return $this->db->delete('sub_assets');	 
	}
	
	function delete_featured_images($id){
		$this->db->where('id',$id);	
		return $this->db->delete('featured_images');	 
	}
	
	function add_featured_images($dbdata){
		if($this->db->insert('featured_images',$dbdata)){
			return $insert_id = $this->db->insert_id();
		}else{
		   return $this->db->error();
		}
	}
	
	function update_featured_images($dbdata,$id){
		$this->db->where('id', $id);
		return	$this->db->update('featured_images', $dbdata);
	}
	
	function get_featured_images_list($image_id,$type){
		$this->db->select('*');
		$this->db->where('image_id',$image_id);
		$this->db->where('type',$type);
		$result=$this->db->get('featured_images');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $data=$result->row_array();
			//return $data['component_fimages'];
		}else{
			return false;
		} 
	}
	
	function get_featured_images_list_by_id($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$result=$this->db->get('featured_images');
		if($result->num_rows()>0){
			return $data=$result->row_array();
		}else{
			return false;
		} 
	}
	/*
	function add_component_fimges($dbdata,$component_id){
	 
		if($fimage_json=$this->get_component_fimges($component_id)){
			// get fimages already saved 
			$fimages=json_decode($fimage_json,true);
			$new_fimages=json_decode($dbdata['component_fimages'],true);
			
			$db_fimages=array_merge($fimages,$new_fimages);
			//print_r($db_fimages);
			$db_data['component_fimages']=json_encode($db_fimages);
			
			$this->db->where('component_id', $component_id);
			return	$this->db->update('components', $db_data);
				
				
		}else{

		$this->db->where('component_id', $component_id);
		return	$this->db->update('components', $dbdata);	
		}
	 
	}
	*/
	
	function get_component_fimges($component_id){
		 
		$this->db->select('component_fimages');
		$this->db->where('component_id',$component_id);
		$result=$this->db->get('components');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			$data=$result->row_array();
			return $data['component_fimages'];
		}else{
			return false;
			} 
	}
 
 
 
	function get_products_list($field=NULL){
                $client_id=$_SESSION['client']['client_id'];
            
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
                $this->db->where('product_client_fk',$client_id);
		$result=$this->db->get('products');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}	 
	 
	
	
	function save_product($dbdata){
		$result = $this->db->insert('products',$dbdata);
		if($result){
			return 1;
		}else{
			return -1;
		}  
	}	
	
	function get_product($product_id){

		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->or_where('product_code',$product_id);
		$result=$this->db->get('products');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}	 
	
	/*function get_asset_from_assetSeriesCode($product_id){
		$this->db->select('product_components');
		$this->db->where('product_id',$product_id);
		$this->db->or_where('product_code',$product_id);
		$result=$this->db->get('products');
		if($result->num_rows()>0){
			
			return $result->row_array();
		}else{
			return false;
		}
	}*/
	
	function get_asset_from_assetSeriesCode($product_id){
		//$this->db->select('product_components');
		//$this->db->select('product_components,product_geo_fancing,product_work_permit');
		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->or_where('product_code',$product_id);
		$result=$this->db->get('products');
		return ($result->num_rows()>0)? $result->row_array() : FALSE;
	}

	function get_component_from_componentCode($component_code){
		$this->db->select('*');
		$this->db->where('component_id',$component_code);
		$this->db->or_where('component_code',$component_code);
		$result=$this->db->get('components');
	
		return ($result->num_rows()>0)? $result->row_array() : FALSE;
	}
	
	
	
	function get_asset_quantity($asset,$assetSeries,$jobCard,$sms){
		$this->db->select('item_quantity');
		$this->db->where('jc_number',$jobCard);
		$this->db->where('sms_number',$sms);
		$this->db->where('series',$assetSeries);
		$this->db->where('item_code',$asset);
		$this->db->where('status','Active');
		$result=$this->db->get('sms_component');
		if($result->num_rows()>0){
			return $result->row_array();
		
		}else{
		
			return false;
		}
	}
 
	function update_product($dbdata,$product_id){
			//print_r($dbdata);
		$this->db->where('product_id',$product_id);
		$return = $this->db->update('products',$dbdata);
		//print_r($this->db->last_query());die;	
		return $return;

	} 
 
 
	function delete_prodcut($product_id){
		$this->db->where('product_id',$product_id);	
		return $this->db->delete('products');	 
	}
  
  
  
	function import_assets_list($dbdata){
		if(!empty($dbdata) && is_array($dbdata)){
			$c = 0;
			foreach($dbdata as $key => $value){
				$data[$c] = $value;
				if(is_array($value['component_sub_assets'])){
					$data[$c]['component_sub_assets'] = json_encode($value['component_sub_assets']);
				}else{
					$data[$c]['component_sub_assets'] = $value['component_sub_assets'];
				}
				$c++;
			}
		}
		
		 return $this->db->insert_batch('components',$data);
		#echo $this->db->last_query();
	} 
	
	function import_products_list($dbdata){
		return $this->db->insert_batch('products',$dbdata);
		//echo $this->db->last_query();
	 }	 
  
         
         
	function import_inspection_xls($dbdata){
		return $this->db->insert_batch('master_data',$dbdata);  
	}    
  
	function get_mdata_list($field=NULL){
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
		$result=$this->db->get('master_data');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_mdata_list_forDownload($rel){
		
		$query = "SELECT A.mdata_id AS 'ID', A.mdata_jobcard AS 'Job Card', A.mdata_sms AS 'SMS Number', A.mdata_batch AS 'Batch Number',
					A.mdata_serial AS 'Serial Number', A.mdata_rfid AS 'RFID Number', A.mdata_barcode AS 'Barcode Number', A.mdata_uin AS 'UIN',
					A.mdata_client as 'Master', B.client_name AS 'Client Name', A.mdata_po AS 'PO Number', A.mdata_item_series AS 'Asset Series', A.mdata_material_invoice AS 'Material Invoice Number',
					A.mdata_material_invoice_date AS 'Material Invoice Date', A.mdata_qty AS 'Quantity'
					FROM master_data AS A
					LEFT JOIN clients AS B
					ON A.mdata_client = B.client_id
					WHERE 1
					";
		if($rel =="CSV Format"){
			return $result = $this->db->query($query);
		}else if($rel =="XLS Format"){
			$result = $this->db->query($query);
			return $result->result_array();
		}
		
	}
	
	
	function get_mdata_lists_sms($jobCard=NULL){

		$this->db->select('mdata_jobcard,mdata_sms,mdata_item_series');
		$this->db->where('mdata_jobcard',$jobCard);
		$result=$this->db->get('master_data');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_mdata_list_of_inspector($field=NULL,$group_id="",$userID=''){

		if($userID == ''){
			$id = $this->session->flexi_auth['id'];
		}else{
			$id = $userID;
		}

		$this->db->select('id,inspector_jobCard as jobCard_id,inspector_ids');
		$query=$this->db->get('inspector_data');
		if($query->num_rows()>0){
			$res = $query->result();
			$job_id = array();
			foreach($res as $val){
				$array_ins_id = json_decode($val->inspector_ids);
				if(in_array($id,$array_ins_id)){
					$job_ids[] = json_decode($val->jobCard_id);
				}
			}

			if(!isset($job_ids)){
				return false;
			}
			
			if(is_array($job_ids)){
				$result_single = $this->array_flatten($job_ids);
				$unique_jobIds = array_unique($result_single);

				$row = array();
				foreach($unique_jobIds as $jobVal ){
					$row[] = $this->get_mdata_item($jobVal);
				}
				
				if(!isset($row)){
						return false;
				}else{
					return $row;
				}
			}
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
	
	
	
	
	function get_mdata_item($itemid){ 

		$this->db->select('*');
		$this->db->where('mdata_id',$itemid);
		$result=$this->db->get('master_data');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
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
	
	function duplicate_jobSMS_list(){
		
		$res = $this->db->query('select * 
							from master_data
							group by mdata_jobcard ,mdata_sms
							having count(*)>=2' );
		if($res->num_rows()>0){
			$result = $res->result();
			$jobCard = array();
			foreach($result as $val){
				$jobCard[] = $val->mdata_jobcard;
			}
			return $jobCard;
		}else{
			return $jobCard = '';
		}
	}
	
	
	function insert_mdata($dbdata){
		return  $this->db->insert('master_data',$dbdata);
	}	

	function update_mdata($dbdata,$itemid){
		$this->db->where('mdata_id',$itemid);
	return $this->db->update('master_data',$dbdata);

	}	
	
	
	
	
	
	function delete_jobId_inspector_form($mdata_id){
		$this->db->where('inspector_jobCard',$mdata_id);
		return $this->db->delete('inspector_data');
	}
	
	function delete_madata($mdata_id){
		$this->db->where('mdata_id',$mdata_id);
		return $this->db->delete('master_data');
	}  
  
  
	function get_client_list($field=NULL){ 
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
		//$this->db->where('client_type',10);
		//$this->db->where('client_type',10);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	} 
	
	function get_clientName_list_block_bysachin_31_08_2018(){ 
		$this->db->select('A.client_id,A.client_name,B.type_name as client_type');
		$this->db->from('clients AS A');
		$this->db->join('type_category AS B', 'A.client_type = B.id', 'INNER');
		$this->db->where('A.client_status','Active');
		$this->db->where('B.status',1);
		$result=$this->db->get();
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
        
        function get_clientName_list(){ 
		$this->db->select('*');
		$this->db->from('clients AS A');
		//$this->db->join('type_category AS B', 'A.client_type = B.id', 'INNER');
                $this->db->where('A.client_client_fk',$_SESSION['client']['client_id']);
		$this->db->where('A.client_status','Active');
		//$this->db->where('B.status',1);
		$result=$this->db->get();
                
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	
	
	/*
	
	function get_dealer_list($field=NULL){
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
		$this->db->where('client_type',11);
		$result=$this->db->get('clients');
		return $result->result_array();
		//if($result->num_rows()>0){
		//	return $result->result_array();
		//}else{
		//	return false;
		//}
	}	
	*/
	
	function get_inspector_name($field=''){
		$que = ($field =='')? '': "AND (A.upro_first_name like '%$field%' OR A.upro_last_name like '%$field%')";
		
		$query = $this->db->query("	
									SELECT CONCAT(A.upro_first_name,' ',A.upro_last_name) as full_name, B.uacc_id as id
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_id = B.uacc_id
									INNER JOIN user_groups AS C
									ON B.uacc_group_fk = C.ugrp_id
									WHERE C.ugrp_id = 9 AND B.uacc_active=1 AND B.uacc_suspend=0 ".$que."
									ORDER BY A.upro_first_name ASC
								") or die(mysql_error());
								
		return  array_column($query->result_array(),'full_name','id');
	}
	
	// function get_inspector_name_search($query=''){
		// $query = $this->db->query("	
									// SELECT CONCAT( A.upro_first_name ,' ', A.upro_last_name) as full_name, B.uacc_id as id
									// FROM demo_user_profiles as A 
									// INNER JOIN user_accounts as B
									// ON A.upro_id = B.uacc_id
									// INNER JOIN user_groups AS C
									// ON B.uacc_group_fk = C.ugrp_id
									// WHERE C.ugrp_id = 9 AND B.uacc_active=1 AND B.uacc_suspend=0
									// AND (A.upro_first_name like '%$query%' OR A.upro_last_name like '%$query%')
								// ") or die(mysql_error());
								
		// return  array_column($query->result_array(),'full_name','id');
	// }	
  
	function insert_inspector_data($dbdata){
		return  $this->db->insert('inspector_data',$dbdata);
	}
	
	function get_inspector_list($field=NULL){
		if($field==NULL){
			$this->db->select('*');
		}else{
			$this->db->select($field);	
		}
			$result=$this->db->get('inspector_data');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_inspector_item($inspector_id){  
		$this->db->select('*');
		$this->db->where('id',$inspector_id);
		$result=$this->db->get('inspector_data');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function update_inspector_data($dbdata,$inspector_id){
		$this->db->where('id',$inspector_id);
		return $this->db->update('inspector_data',$dbdata);	
	}
	
	function update_inspector_jobID_column($newData){
		foreach($newData as $newID=>$newValue){
			$this->db->set('inspector_jobCard', $newValue, FALSE);
			$this->db->where('id',$newID);
			return $this->db->update('inspector_data',$dbdata);	
		}
		
	}
	
	function delete_inspector($inspector_id){
		$this->db->where('id',$inspector_id);
		return $this->db->delete('inspector_data');
	}
	
	function get_assigned_siteId_from_inspector($jobCards,$sms_number){
		$this->db->select('site_id');
		$this->db->where('inspector_jobCard',$jobCards);
		$this->db->where('inspector_sms',$sms_number);
		$result=$this->db->get('inspector_data');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function get_assetSeries_image($assetSeries){
		$this->db->select('product_imagepath');
		$this->db->where('product_code',$assetSeries);
		$result=$this->db->get('products');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->row();
		}else{
			return false;
		}
	}

	function get_component_image($componentCode){
		$this->db->select('component_image');
		$this->db->where('component_code',$componentCode);
		$result=$this->db->get('components');
		if($result->num_rows()>0){
			//echo $this->db->last_query();die();
			return $result->row();
		}else{
			return false;
		}
	}
	
	function reset_table_data($tableName){
		/* SQL Transation Start */
		$this->db->trans_start();
			$query = $this->db->query("	TRUNCATE TABLE ".$tableName);
		/* SQL Transation Ends */
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function get_client_id($client_name){ 
		$this->db->select('client_id');
		$this->db->where('client_type',10);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return false;
		}
	} 
	
	
	function get_dealer_id($client_name){ 
		$this->db->select('client_id');
		$this->db->where('client_type',11);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return false;
		}
	}
	
	function get_client_ids($client_name){
		$this->db->select('client_id');
		$this->db->where('client_name',$client_name);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return false;
		}
	}
	
	
	
	/* Need to remove code */
	
	function get_client_master(){
		$this->db->select('mdata_id,mdata_client');
		$result=$this->db->get('master_data');
		if($result->num_rows()>0){
			$res = $result->result_array();
			foreach($res as $val){
				$id = $val['mdata_id'];
				$client_name = $val['mdata_client'];
				if(!is_numeric($client_name)){
					$client_id = $this->get_client_id_client($client_name);
					if($client_id){
						$this->update_client_id_in_master($id,$client_id);
					}
				}
			}
		}else{
			return false;
		}
	}
	
	function get_client_id_client($client_name){
		$this->db->select('client_id');
		$this->db->where('client_name', $client_name);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			$results = $result->row_array();
			return $results['client_id'];
		}else{
			return false;
		}
	}
	
	function update_client_id_in_master($id,$client_id){
		$dbdata = array('mdata_client'=>$client_id);
		$this->db->where('mdata_id', $id);
		return	$this->db->update('master_data', $dbdata);
	}
	
	function check_site_id_in_inspectorData($jobCards, $sms_number, $id){
		$query = $this->db->query("SELECT * FROM inspector_data WHERE inspector_jobCard='".$jobCards."' AND inspector_sms='".$sms_number."' AND site_id like '%".$id."%' ");
		if($query->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}
	
	function get_logs_view($params){
		if(!empty($params['startTime'])){
				$where ="timestamp >= ".$this->db->escape($params['startTime']);
		}
		if(!empty($params['endTime'])){
				$params['endTime'] = (strpos($params['endTime'],':') !== false) ? $params['endTime'] : $params['endTime'].' 23:59:59';
				$where .=" AND timestamp <= ".$this->db->escape($params['endTime']);
		}
	   
		/*$this->db->select('*');
		 $this->db->order_by("log_id","desc");
		$result=$this->db->get('logs_data');*/
		$query = "Select * FROM logs_data where $where ORDER BY log_id desc";
	
		$result =  $this->db->query($query);
		if($result->num_rows()>0){
				return $result->result_array();
		}else{
				return false;
		}
    }
	
	function get_logs_profileData($userID){
		if(!empty($userID)){
			$query = "SELECT A.`uacc_email`,A.`uacc_group_fk`,B.upro_first_name,B.upro_last_name FROM user_accounts as A INNER JOIN demo_user_profiles as B ON A.uacc_id=B.upro_uacc_fk WHERE A.uacc_id=".$userID;
			$result = $this->db->query($query);
			if($result->num_rows()>0){
					$return = $result->result_array();
					return $return[0];
			}else{
					return -1;
			}
		}else{
			return -2;
		}    
	}
	
	function get_productData_for_report($fildArry){
		$chkFlag = 0;
		$this->db->select('p.product_id, p.product_code, p.product_description, p.product_components, p.standard_certificate_id, p.notified_body_certificate_id, p.article_11b_certificate_id,p.ec_type_certificate_text, mc1.name as standardName, mc1.type as standardType,  mc2.name as notifiedName, mc2.type as notifiedType,  mc3.name  as articleName, mc3.type AS articletype');
		if(!empty($fildArry['product_id'])){
		 $chkFlag = 1;
		 $this->db->where('product_id',$fildArry['product_id']);
		}		
		if(!empty($fildArry['product_code'])){
         $chkFlag = 1;
		 $this->db->where('product_code',$fildArry['product_code']);		
		}
		$this->db->from('products p');
		$this->db->join('manage_certificate mc1', 'mc1.id = p.standard_certificate_id', 'left');
		$this->db->join('manage_certificate mc2', 'mc2.id = p.notified_body_certificate_id', 'left');
		$this->db->join('manage_certificate mc3', 'mc3.id = p.article_11b_certificate_id', 'left');
		if( $chkFlag  == 0) return false;
		$result=$this->db->get();
		if($result->num_rows()>0){
			$inspection_type=$result->result_array();
			$insdatas = array();
			foreach($inspection_type as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;
		}else{
			return false;
		}
	}
	


	function get_componentData_for_report($fildArry){		
		$chkFlag = 0;
		$this->db->select('c.component_id, c.component_code, c.component_description,  c.standard_certificate_id, c.notified_body_certificate_id, c.article_11b_certificate_id,c.ec_type_certificate_text, mc1.name as standardName, mc1.type as standardType,  mc2.name as notifiedName, mc2.type as notifiedType,  mc3.name  as articleName, mc3.type AS articletype');
		if(!empty($fildArry['component_id'])){
		 $chkFlag = 1;
		 $this->db->where('component_id',$fildArry['component_id']);
		}		
		if(!empty($fildArry['component_code'])){
         $chkFlag = 1;
		 $this->db->where('component_code',$fildArry['component_code']);		
		}
		$this->db->from('components c');
		$this->db->join('manage_certificate mc1', 'mc1.id = c.standard_certificate_id', 'left');
		$this->db->join('manage_certificate mc2', 'mc2.id = c.notified_body_certificate_id', 'left');
		$this->db->join('manage_certificate mc3', 'mc3.id = c.article_11b_certificate_id', 'left');
		if( $chkFlag  == 0) return false;
		$result=$this->db->get();		
		if($result->num_rows()>0){
			$inspection_type=$result->result_array();
			$insdatas = array();
			foreach($inspection_type as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;
		}else{
			return false;
		}
	}	

	function  get_manage_country_filt_result_list( $fildArry = array()){
		$this->db->select('*');
		if(!empty($fildArry['id'])){
		 $this->db->where('id',$fildArry['id']);
		}
		if(!empty($fildArry['shortname'])){
		 $this->db->where('shortname',$fildArry['shortname']);
		}
		if(!empty($fildArry['abb'])){
		 $this->db->where('abb',$fildArry['abb']);
		}
		if(!empty($fildArry['name'])){
		 $this->db->where('name',$fildArry['name']);
		}
		if(!empty($fildArry['phonecode'])){
		 $this->db->where('phonecode',$fildArry['phonecode']);
		}
		if(!empty($fildArry['status'])){
		 $this->db->where('status',$fildArry['status']);
		}
		$result=$this->db->get('countries');
		
		if($result->num_rows()>0){
			$country_data =$result->result_array();
			$insdatas = array();
			foreach($country_data as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;
		}else{
			return false;
		}
		
	}

	function  get_manage_zone_filt_result_list($fildArry = array()){
		$this->db->select('*');
		if(!empty($fildArry['id'])){
		 $this->db->where('id',$fildArry['id']);
		}
		if(!empty($fildArry['name'])){ 
		 $this->db->where('name',$fildArry['name']);
		}
		if(!empty($fildArry['country_id'])){
		 $this->db->where('country_id',$fildArry['country_id']);
		}
		if(!empty($fildArry['status'])){
		 $this->db->where('status',$fildArry['status']);
		}
		$result=$this->db->get('province');
		
		if($result->num_rows()>0){
			$province_data = $result->result_array();
			
			$insdatas = array();
			foreach($province_data as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;
		}else{
			return false;
		}
	}

	function  get_manage_city_filt_result_list($fildArry = array()){
		$this->db->select('*');
		if(!empty($fildArry['city_id'])){
		 $this->db->where('city_id',$fildArry['city_id']);
		}
		if(!empty($fildArry['city_name'])){ 
		 $this->db->where('city_name',$fildArry['city_name']);
		}
		if(!empty($fildArry['state_id'])){
		 $this->db->where('state_id',$fildArry['state_id']);
		}
		if(!empty($fildArry['city_status'])){
		 $this->db->where('city_status',$fildArry['city_status']);
		}
		$result=$this->db->get('cities');		
		if($result->num_rows()>0){
			$citydata_type=$result->result_array();			
			$insdatas = array();
			foreach($citydata_type as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;
		}else{
			return false;
		}
	}

	/* Need to Remove Code */
	
 }// end of class 



?>