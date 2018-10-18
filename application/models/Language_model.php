<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Date 	: 30-May-2016
*	Email 	: shakti.singh@flashonmind.com
*/
class Language_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	function  get_language_list($level=NULL, $group=NULL){
		$this->db->select('id, level, group_name, en_description, en_long_description, fr_description, fr_long_description, arabic_description,arabic_long_description');
		if($level!=NULL){ 
			$this->db->where('level',$level);
		}
		if($group!=NULL){ 
			$this->db->where('group_name',$group);
		}
		$result=$this->db->get('language_level');
		if($result->num_rows()>0){
			$result->result_array();			
			return $result->result_array();
		}else{
			return false;
		}
	}	
	
	function get_language_list_via_ajax($sWhere,$sOrder,$sLimit){
		$query = $this->db->query("SELECT * FROM `language_level` {$sWhere} {$sOrder} {$sLimit}");
		$row = $query->result_array();		
		return $row = $query->result_array();
	}

	function get_total_count_language(){
		return $table_row_count = $this->db->count_all('language_level');
	}


	public function edit_lang($userid)
	{
		$this->db->select("id,level, group_name, en_description, en_long_description, fr_description, fr_long_description, arabic_description,arabic_long_description");
		$this->db->from('language_level');
		$this->db->where('id',$userid);
		$query = $this->db->get();		
		return $query->result();
	
	}

	public function update_lang($dbdata,$id)
	{	// echo "<pre>$id==="; print_r($dbdata); echo "</pre>";  exit;	
		$this->db->where('id', $id);
		return $this->db->update('language_level', $dbdata);
	}


		function get_register_data_rows($fildArry){
			$this->db->select('id, user_id, group_id, site_id, master_data_id');
			if(!empty($fildArry['user_id'])){
			 $this->db->where('user_id',$fildArry['user_id']);
			}
			if(!empty($fildArry['site_id'])){
			 $this->db->where('site_id',$fildArry['site_id']);
			}
			if(!empty($fildArry['master_data_id'])){
			 $this->db->where('master_data_id',$fildArry['master_data_id']);
			}
			$result=$this->db->get('register_data');
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

	function insert_lang($dbdata){
		return  $this->db->insert('language_level',$dbdata);
	}

	function  chk_exclude_language_id($fildArry){
		$this->db->select('id, level, group_name, en_description, en_long_description, fr_description, fr_long_description, arabic_description,arabic_long_description');
		
		if(!empty($fildArry['id'])){
		 $this->db->where('id !=',$fildArry['id']);
		}		
		if(!empty($fildArry['level'])){
		 $this->db->where('level',$fildArry['level']);
		}	
		if(!empty($fildArry['group_name'])){
		 $this->db->where('group_name',$fildArry['group_name']);
		}			
		
		$result=$this->db->get('language_level');
		if($result->num_rows()>0){
			return $result->result_array();			
		}else{
			return false;
		}
	}

	function  get_language_filt_arry_list($fildArry){
		$this->db->select('id, level, group_name, en_description, en_long_description, fr_description, fr_long_description, arabic_description,arabic_long_description');
		
		if(!empty($fildArry['id'])){
		 $this->db->where('id',$fildArry['id']);
		}		
		if(!empty($fildArry['level'])){
		 $this->db->where('level',$fildArry['level']);
		}	
		if(!empty($fildArry['group_name'])){
		 $this->db->where('group_name',$fildArry['group_name']);
		}			
		
		$result=$this->db->get('language_level');
		if($result->num_rows()>0){
			return $result->result_array();			
		}else{
			return false;
		}
	}

	function import_language_level_xls($dbdata){
		return $this->db->insert_batch('language_level',$dbdata);  
	} 


}



?>