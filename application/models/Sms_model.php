<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Date 	: 15-May-2016
*	Email 	: shakti.singh@flashonmind.com
*/
class Sms_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();

	}

	function add_sms_component($dbdata){
		return $this->db->insert_batch('sms_component',$dbdata);
	}
	
	public function get_jobcard_from_masterData()
	{
		$this->db->select("mdata_id, mdata_jobcard");
		$this->db->from('master_data');
		$this->db->group_by("mdata_jobcard");
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_sms_from_masterData($jc_number)
	{
		$this->db->select("mdata_sms");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $jc_number);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_assetSeries_from_masterData($jc_number,$sms_number)
	{
		$this->db->select("mdata_item_series");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $jc_number);
		$this->db->where('mdata_sms', $sms_number);
		$query = $this->db->get();
		return $query->row();
	}
	
	public function get_asset_from_product($assetSeries)
	{
		$this->db->select("product_components");
		$this->db->from('products');
		$this->db->where('product_code', $assetSeries);
		$query = $this->db->get();
		return $query->row();
	}
	
	/*
	public function add_sms_component($dbdata)
	{
		
			if($this->db->insert('sms_component',$dbdata))
			{
				return true;
			}
			else
			{
			   return $this->db->error();
			 //return false;   
			}
		
	}
	*/
	function add_excelcomponents($components)
	{
		
		$this->db->select("*");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $components['jc_number']);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result=$query->result_array();
			
			foreach($result as $val){
				
				$mdata_id = $val['mdata_id'];
				$sms_number= $val['mdata_sms'];
				
				if($sms_number == $components['sms_number'])
				{
					
					$componentitem= $val['mdata_item_series'];
					$result=json_decode($componentitem,true);
					
					foreach($result as $value){
						$component = $value['component'];
					
						foreach($component as $cValue){
							$name = $cValue['name'];
							if($name == $components['component_name'] )
							{
								$sql= "INSERT INTO sms_component 	(jc_number,sms_number,component_name,component_no,component_lines,component_result,status) VALUES ( '$mdata_id','$components[sms_number]','$components[component_name]' ,'$components[component_no]','$components[component_lines]','$components[component_result]','$components[status]')";
						
								$this->db->query($sql);
								$insert_id = $this->db->insert_id();
								return  $insert_id;
							}	
						}
					}
					
					if(!isset($insert_id)){
						return false;
					}
				}
				else
				{
					return false;
				}	
			}
		}
		else
		{
			return false;
		}
	}
	
	public function sms_component_view($excel='')
	{
		error_reporting(E_ALL);
		ini_set("memory_limit","1024M");
		ini_set('max_execution_time',600);
		ini_set('mysql.connect_timeout', 600);
		ini_set('default_socket_timeout', 600); 
		$this->db->select("jc_number, sms_number, series, item_code, item_quantity, no_of_lines, status");
		$this->db->from('sms_component');
		$this->db->limit(200);
		$query = $this->db->get();		
		if($excel != ''){
			return $query->result_array();
		}else{
			return $query->result();	
		}
				
	}
	
	public function edit_sms_component($id)
	{
		$this->db->select("id, jc_number, sms_number, series, item_code, item_quantity, no_of_lines, status");
		$this->db->from('sms_component');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
	
	}
	
	public function update_sms_component($dbdata,$userid)
	{
		//var_dump($dbdata);
		//die();
		if($userid!=""){
		$this->db->where('id', $userid);
		return $this->db->update('sms_component', $dbdata);
		}else{
			return false;
		}
	}
	
	public function component_delete($userid)
	{
		//print_r($userid);die();
		$query= $this->db->query("DELETE FROM sms_component WHERE id='$userid'");
		return true;
	}
	 
	public function get_jobcard()
	{
		$this->db->select("id, jc_number");
		$this->db->from('sms_component');
		$this->db->group_by("jc_number");
		$query = $this->db->get();
		return $query->result();
	}
	
	
	/*
	public function ajax_get_sms($jobcard) {
		
        $this->db->select("id, sms_number, jc_number");
		$this->db->from('sms_component');
		$this->db->where('jc_number', $jobcard);
		$this->db->group_by("sms_number");
		$query = $this->db->get();
        return $query->result();
    }
	*/
	/*
	public function ajax_get_series_from_sms($sms_number,$jobCards) {
        $this->db->select("series");
		$this->db->from('sms_component');
		$this->db->where('jc_number', $jobCards);
		$this->db->where('sms_number', $sms_number);
		$this->db->group_by("series");
		$query = $this->db->get();
		if($query->num_rows()>0){
			
			$res = $query->result_array();
			foreach($res as $seriesVal){
				$result[] = $seriesVal['series'];
			}
			return $result;
		}else{
			return false;
		}
    }
	*/
	/*
	public function ajax_get_series_item_from_sms($series) {

        $this->db->select("item_code");
		$this->db->from('sms_component');
		$this->db->where('series', $series);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->row();
		}else{
			return false;
		}
    }
	*/
	
	public function get_itemname_jobcard($jobcard) {
		//echo $jobcard;
		//die();
        $this->db->select("mdata_item_series,mdata_id, mdata_sms, mdata_jobcard");
		$this->db->from('master_data');
		$this->db->where('mdata_jobcard', $jobcard);
		$query = $this->db->get();
        $result=$query->row_array();
		//print_r($result);
		$result=json_decode($result['mdata_item_series'],true);
		$result= array_column($result[0]['component'], 'name');
		return $result;
		
    }
	
	function duplicate_jobSMS_list(){
		
		$res = $this->db->query('select * 
							from sms_component
							group by jc_number ,sms_number
							having count(*)>=2' );
		if($res->num_rows()>0){
			$result = $res->result();
			$jobCards = array();
			foreach($result as $val){
				$jobCard = $val->jc_number;
				$sms = $val->sms_number;
				$jobCards[] = array($jobCard,$sms);
			}
			return $jobCards;
		}else{
			return '';
		}
	}
	
	function duplicate_jobSMScomp_list(){
		
		$res = $this->db->query('select * 
							from sms_component
							group by jc_number ,sms_number,component_name
							having count(*)>=2' );
		if($res->num_rows()>0){
			$result = $res->result();
			$jobCards = array();
			foreach($result as $val){
				$jobCard = $val->jc_number;
				$sms = $val->sms_number;
				$component_name = $val->component_name;
				$jobCards[] = array($jobCard,$sms,$component_name);
			}
			return $jobCards;
		}else{
			return '';
		}
	}
	
	/* Search Box for SMS Table */
	
	public function ajax_get_result_from_search($array) {

        $this->db->select("*");
		$this->db->from('sms_component');
		$this->db->where($array);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->result_array();
		}else{
			return false;
		}
    }
	
	function get_jobCard_from_db(){

		$this->db->select('mdata_jobcard');
		$query=$this->db->get('master_data');
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $value){
				$dbJobCard[] = $value['mdata_jobcard'];
			}
			return $result = array('jobCard'=>$dbJobCard);
			
			
		}else{
			//echo "No data";
			return $a = '';
		}
	}
	
	function get_sms_from_db($jobCard){

		$this->db->select('mdata_sms');
		$this->db->where('mdata_jobcard', $jobCard);
		$query=$this->db->get('master_data');
		if($query->num_rows()>0){
			$res = $query->result_array();
			foreach($res as $value){
				$dbSMS[] = $value['mdata_sms'];
			}
			return $result = array('sms'=>$dbSMS);
		}else{
			//echo "No data";
			return $a = '';
		}
	}
	
	function get_assetSeries_from_db($jobCard,$sms){

		$this->db->select('mdata_item_series');
		$this->db->where('mdata_jobcard', $jobCard);
		$this->db->where('mdata_sms', $sms);
		$query=$this->db->get('master_data');
		if($query->num_rows()>0){
			$res = $query->row_array();
				return $dbseries = json_decode($res['mdata_item_series']);
		}else{
			//echo "No data";
			return $a = '';
		}
	}
	
	function get_job_masterData($array){
		
		$this->db->select("mdata_jobcard");
		$this->db->from('master_data');
		$this->db->where($array);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->result_array();
		}else{
			return false;
		}
	}
	
	function get_job_masterData_for_smsComponent($array){
		
		$this->db->select("mdata_jobcard");
		$this->db->from('master_data');
		$this->db->where($array);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->result_array();
		}else{
			return false;
		}
	}
	
	function get_jobSMS_masterData($array){
		
		$this->db->select("mdata_item_series");
		$this->db->from('master_data');
		$this->db->where($array);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->row_array();
		}else{
			return false;
		}
	}


	function get_asset_components($asset){
		
		$this->db->select("component_code");
		$this->db->from('components');
		$this->db->where('component_code', $asset);
		$query = $this->db->get();
		//$a = $res = $query->row();
		if($query->num_rows()>0){
			return $res = $query->row_array();
		}else{
			$this->db->select("sub_assets_code");
			$this->db->from('sub_assets');
			$this->db->where('sub_assets_code', $asset);
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $res = $query->row_array();
			}else{
				return false;
			}
		}
	}
	
}
?>