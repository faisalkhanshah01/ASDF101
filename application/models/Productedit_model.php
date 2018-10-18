<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh/Ravindra
*	Date 	: 17-Aug-2016
*	Email 	: shakti.singh@flashonmind.com
*/

class Productedit_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
		$this->load->library('email');
	}

	function update_subAssets($id,$tableName,$dbdata){		
		$this->db->where('sub_assets_id',$id);
		if($this->db->update($tableName,$dbdata)){
		   return true;
		}
		else{
		   return $this->db->error();
		}
	}
	
	function update_assets($id,$tableName,$dbdata){
		$this->db->where('component_id',$id);
		if($this->db->update($tableName,$dbdata)){
		   return true;
		}
		else{
		   return $this->db->error();
		}	
	}
	
	function update_asset_series($id,$tableName,$dbdata){
		
		$this->db->where('product_id',$id);	
		if($this->db->update($tableName,$dbdata)){
		   return true;
		}
		else{
		   return $this->db->error();
		}
	}
}
?>