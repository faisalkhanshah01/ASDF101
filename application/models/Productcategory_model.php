<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class ProductCategory_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	 
	function get_categories($parent_id=null){

		$id = ($parent_id !='')? $parent_id : '0';

		$sql="select category.*,parent.cat_name as cat_parentname 
		from manage_categories as category 
		left join manage_categories as parent 
		on category.cat_parentid=parent.id 
		where category.cat_parentid=$id";

		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			return $data=$query->result_array();
		}
	}
	
	function get_product_barcode($code,$type){
		if(!empty($code) && !empty($type)){
			if($type == 'rfid'){
				$sql="select category.*,parent.cat_name as cat_parentname from manage_categories as category
						left join manage_categories as parent on category.cat_parentid=parent.id
						JOIN manage_products as products on category.id = products.category_id
						JOIN master_data as master on (master.mdata_item_series = products.assets OR master.mdata_item_series = products.assets_series) 
						WHERE master.mdata_rfid = '".$code."'";
			}else if($type == 'uin'){
				$sql="select category.*,parent.cat_name as cat_parentname from manage_categories as category
						left join manage_categories as parent on category.cat_parentid=parent.id
						JOIN manage_products as products on category.id = products.category_id
						JOIN master_data as master on (master.mdata_item_series = products.assets OR master.mdata_item_series = products.assets_series) 
						WHERE master.mdata_uin = '".$code."'";
						
			}else if($type == 'barcode'){
					$sql="select category.*,parent.cat_name as cat_parentname from manage_categories as category
						left join manage_categories as parent on category.cat_parentid=parent.id
						JOIN manage_products as products on category.id = products.category_id
						JOIN master_data as master on (master.mdata_item_series = products.assets OR master.mdata_item_series = products.assets_series) 
						WHERE master.mdata_barcode = '".$code."'";
			}
			//print_r($sql);die;
			$query=$this->db->query($sql);
			return ($query->num_rows()>0)? $query->result_array() : FALSE;
		}else{
			return false;
		}	
	}
	
	function has_child($id){
		$this->db->select('*');
		$this->db->from('manage_categories');
		$this->db->where('cat_parentid', $id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function get_all_categories_old($parent_id=null,$id=null){
	   
	    if($parent_id!=''){
			$sql="select category.*,parent.cat_name as cat_parentname, parent.cat_parentid as cat_parentnameID
			from manage_categories as category 
			left join manage_categories as parent on category.cat_parentid=parent.id where category.cat_parentid=$parent_id"; 
		}else if($id!=''){
			$sql="select category.*,parent.cat_name as cat_parentname, parent.cat_parentid as cat_parentnameID
			from manage_categories as category 
			left join manage_categories as parent on category.cat_parentid=parent.id where category.id=$id"; 
		}else{
			$sql="select category.*,parent.cat_name as cat_parentname 
			from manage_categories as category 
			left join manage_categories as parent 
			on category.cat_parentid=parent.id";
		}
        $query=$this->db->query($sql);
           
		if($query->num_rows()>0){  
			return $data=$query->result_array();
		}else{
			return false;
		}
	  // print_r($data);
			
	}
	
	function get_all_categories($parent_id=null,$id=null){
	   
	    if($parent_id!=''){
			$sql="select category.*,parent.cat_name as cat_parentname, parent.cat_parentid as cat_parentnameID
			from manage_categories as category 
			left join manage_categories as parent on category.cat_parentid=parent.id where category.cat_parentid=$parent_id"; 
		}else if($id!=''){
			$sql="select category.*,parent.cat_name as cat_parentname, parent.cat_parentid as cat_parentnameID
			from manage_categories as category 
			left join manage_categories as parent on category.cat_parentid=parent.id where category.id=$id"; 
		}else{
			$sql="select category.*,parent.cat_name as cat_parentname 
			from manage_categories as category 
			left join manage_categories as parent 
			on category.cat_parentid=parent.id";
		}
        $query=$this->db->query($sql);
        return ($query->num_rows()>0)? $query->result_array() : FALSE;
	}
	
	function get_category_for_id($cat_id){
		$this->db->select('*');
		$this->db->where('id',$cat_id);
		$query= $this->db->get('manage_categories');
		return $query->row_array();
	}
	
	function get_category_list(){
		$this->db->select('id, cat_name, cat_parentid');
		$query= $this->db->get('manage_categories');
		return $query->result_array();
	}
	
	
	function get_all_products($catID='', $parentID=''){
		if($catID !='' && $parentID != ''){
			$sql="select A.*,B.cat_name as category_name, C.cat_name as parent_name 
					from manage_products as A
					left join manage_categories as B 
					on A.category_id = B.id
					left join manage_categories as C
					on A.catParent_id = C.id
					WHERE A.category_id =".$catID." AND A.catParent_id =".$parentID;
			$query=$this->db->query($sql);
			if($query->num_rows() >0){
				return $query->row_array();
			}else{
				return false;
			}
		}else{
			$sql="select A.*,B.cat_name as category_name, C.cat_name as parent_name 
				from manage_products as A
				left join manage_categories as B 
				on A.category_id = B.id
				left join manage_categories as C
				on A.catParent_id = C.id";
			$query=$this->db->query($sql);
			if($query->num_rows() >0){
				return $query->result_array();
			}else{
				return false;
			}
		}
	}
	
	function get_category_for_productId($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$query= $this->db->get('manage_products');
		return $query->row_array();
	}
	function get_product_list(){
		$this->db->select('id,category_name');
		$query= $this->db->get('manage_products');
		return $query->result_array();
	}
	
	/* changes for product view 27-02-2017 */
	
	function get_all_parent_products_byID($id){
		
		$this->db->select('*');
		$this->db->where('category_id',$id);
		$query= $this->db->get('manage_products');
		return $query->row_array();
	}
	
	/* changes for product view 27-02-2017 END */
	
	
}// end of Model class 
?>