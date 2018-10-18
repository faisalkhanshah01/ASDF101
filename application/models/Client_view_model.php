<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Client_view_model extends CI_Model{
	
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
	
	
	// Function for creating Breadcrumb in Client View.
	
	function get_breadCrum($subcategory){
		$current_selected_categoryName 		= $subcategory[0]['cat_parentname'];
		$current_selected_categoryID 		= $subcategory[0]['cat_parentid'];
		$current_selected_categoryParentID 	= $subcategory[0]['cat_parentnameID'];
		$html='';
		if($current_selected_categoryParentID =='0'){
		
			$mainLink = base_url('Client_view/');
			$html ='<li><a href="'.$mainLink.'">Knowledge Database</a></li>
					<li class="active">'.$current_selected_categoryName.'</li>';
			
			$_SESSION['infornetView']['breadCrum']['mainLink'] 	= $html;
			
			$_SESSION['infornetView']['pageTitle'] = $current_selected_categoryName;
		
			return true;
		}else{
			$new = $arr = array();
			$mainLink = base_url('Client_view/');
			$co =0;
			$arr[] = array('url'=>$mainLink, 'name'=>'Knowledge Database');
			$ress 	= $this->get_cat_values($current_selected_categoryID,$arr,$co, $new);
		}
	}
	
	function get_cat_values($id,$arr,$co,$new){
		// echo "<pre>";
		// print_r($id);
		// die;
		$this->load->model('ProductCategory_model');
		$subcategories = $this->ProductCategory_model->get_all_categories($id);
		if($subcategories){
			$res = $subcategories[0];
			if($res['cat_parentnameID']!=0){
				$url = base_url('Client_view/client_view_subCategory/'.$res['cat_parentid']);
				$new[$co] = array('url'=>$url , 'name'=>$res['cat_parentname']);
				$co++;
				$this->get_cat_values($res['cat_parentnameID'], $arr, $co, $new);
			}else{
				if($co=='0'){
					$html='';
					$html	='<li><a href="'.$arr[0]['url'].'">'.$arr[0]['name'].'</a></li>';
					$html 	.= '<li class="active">'.$res['cat_parentname'].'</li>';
					$_SESSION['infornetView']['breadCrum']['mainLink'] 	= $html;
				}else{
					$url = base_url('Client_view/client_view_subCategory/'.$res['cat_parentid']);
					$new[$co] = array('url'=>$url , 'name'=>$res['cat_parentname']);
					
					$count = count($new);
					$htm='';
					$htm='<li><a href="'.$arr[0]['url'].'">'.$arr[0]['name'].'</a></li>';
					for($i= $count-1 ; $i >= 0; $i--){
						if($i==0){
							$htm .= '<li class="active">'.$new[$i]['name'].'</li>';
						}else{
							$htm .= '<li><a href="'.$new[$i]['url'].'">'.$new[$i]['name'].'</a></li>';
						}
					}

 					$_SESSION['infornetView']['pageTitle'] = $new[0]['name'];
					$_SESSION['infornetView']['breadCrum']['mainLink'] 	= $htm;
				}	
			}
		}else{
			return false;
		}
	}
	
	function get_data_id($data_type, $data){
		
		// echo $data_type."<br />";
		// echo $data."<br />";
		// die();
		
		if($data_type =='components'){
			$select	= 'component_id as id';
			$where	= 'component_code';
		}elseif($data_type =='sub_assets'){
			$select	= 'sub_assets_id as id';			
			$where	= 'sub_assets_code';
		}elseif($data_type =='products'){
			$select	= 'product_id as id';
			$where	= 'product_code';
		}
		
		$this->db->select($select);
		$this->db->from($data_type);
		$this->db->where($where,$data);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row_array(); 
		}else{	
			return false;
		}
	}
	
	function get_data_specification($data_type, $id){
		$this->db->select('*');
		$this->db->from('specification');
		$this->db->where('codeID',$id);
		$this->db->where('tableName',$data_type);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row_array(); 
		}else{	
			return false;
		}
	}

	
}// end of Model class 
?>