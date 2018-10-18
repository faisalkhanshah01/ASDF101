<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Date 	: 15-May-2016
*	Email 	: shakti.singh@flashonmind.com
*/
class M_infonet_left_menu extends CI_Model{
	
	function __construct(){
	    parent::__construct();
            $this->load->database();
            $this->table_name = 'manage_categories';
            $this->table_products = 'manage_products';
	}

	function get_menus_categories(){
		$this->db->from($this->table_name);
		$this->db->order_by("cat_parentid");
		$res = $this->db->get(); 
		if ($res->num_rows() > 0){
				$res = $res->result_array();
				$return = $res;
				//print_r($return);die;
		} else {
				$return = -2;
		} 
		
		return $return;
	}
	
	/*function get_product_image($speci_file_id = '',$product_code = ''){
		if(!empty($speci_file_id) && !empty($product_code)){
                    $query_component = "SELECT component_code as product_code ,speci_file_id, component_imagepath as imagepath FROM components WHERE speci_file_id = '$speci_file_id' AND component_code = '$product_code' LIMIT 1";
                    $query_product = "SELECT product_code as product_code ,speci_file_id, product_imagepath as imagepath FROM products WHERE speci_file_id = '$speci_file_id' AND product_code = '$product_code' LIMIT 1";
                    $result_component = $this->db->query($query_component);
                    $result_product = $this->db->query($query_product);
                    if (($result_component->num_rows() > 0) || ($result_product->num_rows() > 0)) {
                        if(!empty($result_component->result_array())){
                            $result = $result_component->result_array();
                            $return = $result;
                        }else{
                            $result = $result_product->result_array();
                            $return = $result;
                        }
                    }else{
                            $return = -4;
                    }
                }else{
			$return = -1; 
		}
                
                //print_r($result);die;
                return $return;
	}*/
	
	function get_product_image($table_name = '',$product_code = ''){
		if(!empty($table_name) && !empty($product_code)){
			if($table_name == 'components'){
				 $query = "SELECT component_code as product_code ,speci_file_id, component_imagepath as imagepath FROM components WHERE  component_code = '$product_code' LIMIT 1";
			}else if($table_name == 'products'){
				$query = "SELECT product_code as product_code ,speci_file_id, product_imagepath as imagepath FROM products WHERE  product_code = '$product_code' LIMIT 1";
			}    
			
			$result = $this->db->query($query);
			if (($result->num_rows() > 0)) {
				$result = $result->result_array();
				$return = $result;
			}else{
				$return = -2;
			}
		}else{
			$return = -1; 
		}
		//print_r($result);die;
		return $return;
	}
	
	function fetch_category_image($id = ''){
	   //$where = "id=".addslashes($id)." OR cat_parentid=".addslashes($cat_parentid)." ORDER BY  id DESC";
		 
			$param = array();
			$result = array();
			$where = "id='%".addslashes($id)."%' ORDER BY  id DESC";
			if(!empty($id)){}
				 $query = "SELECT id,category,cat_parentid FROM $this->table_name where category LIKE $where";
			$res = $this->db->query($query);
			if ($res->num_rows() > 0) {
					$param = $res->result_array();
					$arrayCid = $this->_categoryList($param,$id);
					if(!empty($id)){
						$arrayCid[] .= $id;
					}    
					$ids = join("','",$arrayCid); 
				   // print_r($ids);print '<br/>';
					//SELECT `id`, `category_id`, `assets`, `sub_assets`, `assets_series` FROM `manage_products` WHERE category_id IN ('14','13','11','10','9','8','7','6','4','1','2')
					$productQuery = "SELECT `id`, `category_id`, `assets`, `sub_assets`, `assets_series` FROM $this->table_products where category_id IN ('$ids')";
					$result = $this->db->query($productQuery);
					if ($result->num_rows() > 0) {
						$result = $result->result_array();
						$return = $this->_manage_products($result);
					}else{
						$return = -4;
					}
				   
			} else {
					$return = -1;
			}
		// print_r($return);die;
		return $return;        
	}
        
        function _manage_products($result = ''){
            $return = array();
            $temp = array();
            if(!empty($result) && is_array($result)){
                foreach($result as $key => $value){
                    $temp['assets'][] = json_decode($value['assets'], true);
                    $temp['sub_assets'][] = json_decode($value['sub_assets'], true);
                    $temp['assets_series'][] = json_decode($value['assets_series'], true);
                }
            }
            
            if(!empty($temp['assets'][0]) && is_array($temp['assets'])){
                $c = 0;
               foreach($temp['assets'] as $key => $value){
                 
                    if(!empty($value)){
                        foreach($value as $k => $v){
                            if(!empty($v)){
                                $asset[$c] = $v;
                                $c++;
                            }    
                        }
                    }    
               }
               $return['assets'] = $this->_specification($asset,'components');
            }
          
           /*if(!empty($temp['sub_assets']) && is_array($temp['sub_assets'])){
                $c = 0;
               foreach($temp['sub_assets'] as $key => $value){
                    if(!empty($value)){
                        foreach($value as $k => $v){
                             if(!empty($v)){
                                $sub_assets[$c] = $v;
                                $c++;
                             }    
                        }
                    }    
               }
               
               $return['sub_assets'] = $this->_specification($sub_assets,'sub_assets');
            }*/
           
            if(!empty($temp['assets_series'][0]) && is_array($temp['assets_series'])){
                $c = 0;
               foreach($temp['assets_series'] as $key => $value){
                    if(!empty($value)){
                        foreach($value as $k => $v){
                            if(!empty($v)){
                                $assets_series[$c] = $v;
                                $c++;
                            }    
                        }
                    } 
               }
               $return['assets_series'] = $this->_specification($assets_series,'products');
               //print_r($return['assets_series']);die;
            }
            
            //print_r($return);die;
            return $return;
        }
        
        function _specification($param,$tableName){
            if(!empty($param) && is_array($param) && !empty($tableName)){
                $return = array();
                if($tableName == 'products'){
                    $id = 'product_id as id';
		    $pCode = 'product_code';
                    $code = 'product_code as product_code';
                    $imagepath = 'product_imagepath as imagePath';
                    //$image = 'product_image as image';
                }
                if($tableName == 'components'){
                    $id = 'component_id as id';
		    $pCode = 'component_code';
                    $code = 'component_code as product_code';
                    $imagepath = 'component_imagepath as imagePath';
                    //$image = 'component_image as image';
                }
                
               /* if($tableName == 'sub_assets'){
                    $id = 'sub_assets_id as id';
		    $pCode = 'sub_assets_code';
                    $code = 'sub_assets_code as product_code';
                    $imagepath = 'sub_assets_imagepath as imagePath';
                    //$image = 'component_image as image';
                }*/
                //$c = 0;
                foreach ($param as $key =>$value){
                   // $componentID = "SELECT $id, $code,$imagepath,$image,speci_file_id FROM $tableName WHERE $pCode = '$value' AND speci_file_id!=''";
                    $componentID = "SELECT $id, $code,$imagepath,speci_file_id FROM $tableName WHERE $pCode = '$value' AND speci_file_id!=''"; 
                    //print_r($componentID);die;
                    $res = $this->db->query($componentID);
                    if ($res->num_rows() > 0) {
                            $return[$key] = $res->row_array();
                    }
                    //$c++;
                }
                
            }else{
                $return = -5;
            }
           // print_r($return);die("_specification");
           return $return;
        }
        
        /*******************************************************/
        /***********************all data********************************/
        function fetch_all_category_image(){
           /* $query = "SELECT product_id as id, product_code as product_code,"
                    . "product_imagepath as imagePath,product_image as image,"
                    . "speci_file_id FROM products UNION ALL SELECT component_id as id,"
                    . " component_code as product_code,component_imagepath as imagePath,"
                    . "component_image as image,speci_file_id FROM components";*/
            $query = "SELECT product_id as id, product_code as product_code, infonet_status,"
                    . "product_imagepath as imagePath,product_image as image,"
                    . "speci_file_id FROM products where speci_file_id !='' UNION ALL SELECT component_id as id,"
                    . " component_code as product_code,infonet_status,component_imagepath as imagePath,"
                    . "component_image as image,speci_file_id FROM components where speci_file_id !=''";
            $res = $this->db->query($query);
            if ($res->num_rows() > 0) {
                $res = $res->result_array();
                $return = $res;
            } else {
                $return = -2;
            } 
            return $return;
        }
		
		 /*******************************************************/
		function get_all_components_id(){
            $query =  "SELECT `component_id` as id,`component_code` as code FROM `components` WHERE `speci_file_id` != ''";
            $res = $this->db->query($query);
            if ($res->num_rows() > 0) {
                $res = $res->result_array();
                $return = $res;
            } else {
                $return = -2;
            } 
            return $return;
        }
         function get_all_products_id(){
            $query =  "SELECT `product_id` as id,`product_code` as code FROM `products` WHERE `speci_file_id` != ''";
            $res = $this->db->query($query);
            if ($res->num_rows() > 0) {
                $res = $res->result_array();
                $return = $res;
            } else {
                $return = -2;
            } 
            return $return;
        }
        /***********************all data********************************/
        function search_product(){
            $query = "SELECT * FROM `search_category` WHERE 1";
            $res = $this->db->query($query);
            $result = $res->row_array();
            return $result;
        }
        function insert_category_id($temp){
            $query = "SELECT `id`,'search_product' FROM `search_category`";
            $res = $this->db->query($query);
            $result = $res->row_array();
            if (empty($result)) {
               $this->db->insert('search_category',$temp);
            }else{
               $this->db->where('id', $result['id']);
               $this->db->update('search_category',$temp); 
            }
        }
        
        function searchProductall($product){
            $query = "SELECT product_id as id, product_code as product_code, infonet_status,"
                    . "product_imagepath as imagePath,product_image as image,"
                    . "speci_file_id FROM products where speci_file_id !='' AND  product_code LIKE  '%".$product."%' UNION ALL SELECT component_id as id,"
                    . " component_code as product_code,infonet_status,component_imagepath as imagePath,"
                    . "component_image as image,speci_file_id FROM components where speci_file_id !='' AND  component_code LIKE  '%".$product."%'";
            
            $res = $this->db->query($query);
            if ($res->num_rows() > 0) {
                $res = $res->result_array();
                $return = $res;
            } else {
                $return = -2;
            } 
            return $return;
        }
        
        function searchProduct($product){
            $query = "SELECT product_id as id, product_code as product,"
                    . "speci_file_id FROM products where speci_file_id !='' AND  product_code = '".$product."' UNION ALL SELECT component_id as id,"
                    . " component_code as product,"
                    . "speci_file_id FROM components where speci_file_id !='' AND  component_code = '".$product."'";
            $res = $this->db->query($query);
            if ($res->num_rows() > 0) {
                $res = $res->result_array();
                $return = $res[0];
            } else {
                $return = -2;
            } 
            return $return;
        }
        
        function fetch_category_image_search($id = '',$searchProduct=''){
	   //$where = "id=".addslashes($id)." OR cat_parentid=".addslashes($cat_parentid)." ORDER BY  id DESC";
			//print_r($id);print_r($searchProduct);die;
			$param = array();
			$result = array();
			$where = "id='%".addslashes($id)."%' ORDER BY  id DESC";
			if(!empty($id)){}
				 $query = "SELECT id,category,cat_parentid FROM $this->table_name where category LIKE $where";
			$res = $this->db->query($query);
			if ($res->num_rows() > 0) {
					$param = $res->result_array();
					$arrayCid = $this->_categoryList($param,$id);
					if(!empty($id)){
						$arrayCid[] .= $id;
					}    
					$ids = join("','",$arrayCid); 
				   // print_r($ids);print '<br/>';
					//SELECT `id`, `category_id`, `assets`, `sub_assets`, `assets_series` FROM `manage_products` WHERE category_id IN ('14','13','11','10','9','8','7','6','4','1','2')
                                        $productQuery = "SELECT `id`, `category_id`, `assets`, `sub_assets`, `assets_series` FROM $this->table_products where category_id IN ('$ids')";
					$result = $this->db->query($productQuery);
					if ($result->num_rows() > 0) {
						$result = $result->result_array();
                                 $return = $this->_manage_products_serach($result,$searchProduct);
					}else{
						$return = -4;
					}
				   
			} else {
					$return = -1;
			}
		// print_r($return);die;
		return $return;        
	}
        
        function _manage_products_serach($result = '',$searchProduct=''){
            $return = array();
            $temp = array();
            if(!empty($result) && is_array($result)){
                foreach($result as $key => $value){
                    $temp['assets'][] = json_decode($value['assets'], true);
                    $temp['sub_assets'][] = json_decode($value['sub_assets'], true);
                    $temp['assets_series'][] = json_decode($value['assets_series'], true);
                }
            }
            
            if(!empty($temp['assets'][0]) && is_array($temp['assets'])){
                $c = 0;
               foreach($temp['assets'] as $key => $value){
                 
                    if(!empty($value)){
                        foreach($value as $k => $v){
                            if(!empty($v)){
                                $asset[$c] = $v;
                                $c++;
                            }    
                        }
                    }    
               }
              
                $return['assets'] = $this->_specification_search($asset,'components',$searchProduct);
            }
          
           /*if(!empty($temp['sub_assets']) && is_array($temp['sub_assets'])){
                $c = 0;
               foreach($temp['sub_assets'] as $key => $value){
                    if(!empty($value)){
                        foreach($value as $k => $v){
                             if(!empty($v)){
                                $sub_assets[$c] = $v;
                                $c++;
                             }    
                        }
                    }    
               }
               
               $return['sub_assets'] = $this->_specification($sub_assets,'sub_assets');
            }*/
           
            if(!empty($temp['assets_series'][0]) && is_array($temp['assets_series'])){
                $c = 0;
               foreach($temp['assets_series'] as $key => $value){
                    if(!empty($value)){
                        foreach($value as $k => $v){
                            if(!empty($v)){
                                $assets_series[$c] = $v;
                                $c++;
                            }    
                        }
                    } 
               }
               //$return['assets_series'] = $this->_specification($assets_series,'products');
               $return['assets'] = $this->_specification_search($assets_series,'products',$searchProduct);
            }
            
            //print_r($return);die;
            return $return;
        }
        
        function _specification_search($param='',$tableName='',$searchProduct=''){
            if(!empty($param) && is_array($param) && !empty($tableName)){
                $return = array();
                if($tableName == 'products'){
                    $id = 'product_id as id';
					$pCode = 'product_code';
                    $code = 'product_code as product_code';
                    $imagepath = 'product_imagepath as imagePath';
                    $search_product = "product_code LIKE  '%".$searchProduct."%'";
                    //$image = 'product_image as image';
                }
                if($tableName == 'components'){
                    $id = 'component_id as id';
					$pCode = 'component_code';
                    $code = 'component_code as product_code';
                    $imagepath = 'component_imagepath as imagePath';
                    $search_product = "component_code LIKE  '%".$searchProduct."%'";
                    //$image = 'component_image as image';
                }
                
               /* if($tableName == 'sub_assets'){
                    $id = 'sub_assets_id as id';
		    $pCode = 'sub_assets_code';
                    $code = 'sub_assets_code as product_code';
                    $imagepath = 'sub_assets_imagepath as imagePath';
                * $search_product = 'component_code LIKE  '%".$searchProduct."%'';
                    //$image = 'component_image as image';
                }*/
                //$c = 0;
                foreach ($param as $key =>$value){
                   // $componentID = "SELECT $id, $code,$imagepath,$image,speci_file_id FROM $tableName WHERE $pCode = '$value' AND speci_file_id!=''";
                    $componentID = "SELECT $id, $code,$imagepath,speci_file_id FROM $tableName WHERE ".$pCode ."= '".$value."' AND speci_file_id!='' AND ".$search_product;
                       
                    $res = $this->db->query($componentID);
                    if ($res->num_rows() > 0) {
                            $return[$key] = $res->row_array();
                    }
                    //$c++;
                }
                
            }else{
                $return = -5;
            }
           // print_r($return);die("_specification");
           return $return;
        }
        
        /*******************************************************************/
        /***************************specification (product data)******************************************/
        /* *****************************************************************/
        function get_data_specification($id){
            if(!empty($id)){
                //$this->db->select('id,codeID,file');
                $this->db->select('file');
                $this->db->from('specification');
                $this->db->where('id',$id);
                $query = $this->db->get();
                if($query->num_rows()>0){
                    $query = $query->result_array();
                    $return = $query[0];
                }else{
                    $return = -1;
                }
            }else{
                $return = -2;
            }    
            return $return;
	}
      
        /************************end all data********************************/
        /*******************************************************/
        
        function _categoryList($param = '',$id = ''){
            $temp = array();
            if(!empty($param) && is_array($param) && !empty($id)){
                foreach($param as $key => $value){
                    $explodData = explode(',', $value['category']);
                    if(in_array($id, $explodData)){
                        $temp[]= $value['id'];
                    }
                }
                $return = $temp;
            }else{
                $return = -3;
            }
            return $return;
        }
        
}
?>