<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Specification_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	function get_all_specification($id='', $type=''){
		$this->db->select('*');
		if($id!='' && $type!=''){
			$this->db->where('codeID', $id);
			$this->db->where('tableName', $type);
		}
		$query= $this->db->get('specification');
		if($query->num_rows() >0){
			return ($id !='')? $query->row_array() : $query->result_array();
		}else{
			return false;
		}
	}

	function get_type_details_testing($type_name){
		if($type_name =='components'){
			$select = 'components.component_id as typeID, components.component_code as code, components.component_description as description, components.component_imagepath as imagePath, specification.*';
			$code_id = 'component_id';
				
		}elseif($type_name =='sub_assets'){
			$select = 'sub_assets.sub_assets_id as typeID, sub_assets.sub_assets_code as code, sub_assets.sub_assets_description as description, specification.*';
			$code_id = 'sub_assets_id';
			
		}elseif($type_name =='products'){
			$select = 'products.product_id as typeID, products.product_code as code, products.product_description as description, products.product_imagepath  as imagePath, specification.*';
			$code_id = 'product_id';
		}
		
		$query = $this->db->query("SELECT ".$select."
							FROM ".$type_name."
							LEFT JOIN specification
							ON ".$type_name.".".$code_id." = specification.codeID
							AND specification.tableName = '".$type_name."' WHERE ".$type_name.".status = 'Active'");
		if($query->num_rows()>0){
			return $query->result_array(); 
		}else{
			
			return false;
		}
	}
	
	function delete_uploaded_file($id, $title, $fileName){
		$this->db->select('file');
		$this->db->from('specification');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() >0){
			$res = $query->row_array();
			$file_arr = json_decode($res['file'],true);
			foreach($file_arr as $key=>$val){
				if(strtolower($val['title']) == strtolower($title)){
					$file_arr[$key]['url']='';
					$file_arr[$key]['file_name']='';
				}
			}
			
			$dbdata= array('file'=>json_encode($file_arr));
			$result = $this->update_specfication_value($id,$dbdata);
			return ($result)? true : false;
		}else{
			return false;
		}
	}
	
	function update_specfication_value($id,$dbdata){
		$this->db->where('id',$id);
		return $this->db->update('specification',$dbdata);
	}
	
}// end of Model class 
?>