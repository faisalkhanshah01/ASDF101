<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Date 	: 30-May-2016
*	Email 	: shakti.singh@flashonmind.com
*/
class Subassets_model extends CI_Model{
        private $client="";
	
	function __construct(){
	    parent::__construct();
		$this->load->database(); 
              
//                print_r($this);
//                $this->domain = $_SESSION['client'];     
//                print_r($_SESSION['client']);
                #die;
                
	}
	
	function  get_sub_assets_list($field=NULL){
            
                $client_id=$_SESSION['client']['client_id'];
            
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);
			$this->db->where('status','Active');
		} 
                $this->db->where('sub_assets_client_fk',$client_id);
		$result=$this->db->get('sub_assets');
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}	
	
	function save_sub_assets($dbdata){
		if($this->db->insert('sub_assets',$dbdata)){
		   return true;
		}
		else{
		   return $this->db->error();
		}
	  //echo $this->db->last_query();
	}
	
	function update_sub_assets($dbdata,$sub_assets_id){
		$this->db->where('sub_assets_id', $sub_assets_id);
		return	$this->db->update('sub_assets', $dbdata);
	}
	
	function get_sub_assets($sub_assets_id){
		$this->db->select('*');
		$this->db->where('sub_assets_id',$sub_assets_id);
		$this->db->or_where('sub_assets_code',$sub_assets_id);
		$result=$this->db->get('sub_assets');
		if($result->num_rows()>0){
		
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function import_sub_assets_list($dbdata){
		return $this->db->insert_batch('sub_assets',$dbdata);
		//echo $this->db->last_query();
	}
	
	
	function delete_sub_assets($id){
		$this->db->where('sub_assets_id',$id);	
		return $this->db->delete('sub_assets');
	}
	
	/* *************************************************************************************************** */
	/* *************************************************************************************************** */
	//						Inspection / Result / Observations / Action Proposed Form
	/* *************************************************************************************************** */
	/* *************************************************************************************************** */
	
	
	function  get_type_list($field=NULL){
		if($field==NULL){ 
			$this->db->select('*');
		}else{
			$this->db->select($field);
			$this->db->where('status',1);
			$this->db->where('type_category',1);
			$this->db->or_where('type_category',0);
		} 
		$this->db->order_by('id','desc');
		$result=$this->db->get('type_category');
		
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->result_array();
		}else{
			return false;
		}
	}

	function save_inspection_result($dbdata){
		if($this->db->insert('type_category',$dbdata)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
        
        /* Exclude the Observation Type and New Type  */
        
        function  get_inspection_result_list_old($field=NULL){
		
		
		$result = $this->db->query("SELECT A.type_name as type_name, A.id as id, A.status as status, B.type_name as parent_name
									FROM type_category AS A , type_category AS B
									WHERE A.type_category = B.id AND B.type_category != 17 AND B.type_category != 0
							");
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
        
        
        
	
	function  get_inspection_result_list($field=NULL){
            
            $sql="SELECT A.type_name as type_name, A.id as id, A.status as status, B.type_name as parent_name
									FROM type_category AS A , type_category AS B
									WHERE A.type_category = B.id AND B.type_category != 17 AND B.type_category != 0";
            
            $results=$this->get_typecategory_id_byname(array('Observations'));
            $observ_id=$results['Observations'];
            
            # 'New Type' category id not listed in the list 
            $newtype_id=0;  //'new type parent id'
            
            $client_id=$_SESSION['client']['client_id'];
            
            $sql="SELECT  A.id as id, A.type_name as type_name,A.type_category, A.status as status, B.type_name as parent_name
									FROM type_category AS A , type_category AS B
									WHERE A.type_category = B.id  AND  B.type_category != '$observ_id' AND B.type_category != '$newtype_id' AND A.type_client_fk=$client_id ";
		$result = $this->db->query($sql);
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
        
        
     function get_typecategory_id_byname($typecat){
          
           $this->db->select('*');
           if(is_array($typecat)){
                 $this->db->where_in('type_name',$typecat);  
           }else if(is_string($typecat)){
               $this->db->where('type_name',$typecat);
           }
          
           $query=$this->db->get('type_category');
           $results=$query->result_array();
           #echo $this->db->last_query();
           foreach($results as $cat){
               $rtn_arr[$cat['type_name']]=$cat['id'];
           }
            /*echo "<pre>";     
           print_r($results);
           die;*/
           
           return $rtn_arr; 
      }  
        
        
        
        
   function  get_inspection_observation_list($field=NULL){
       
                // get Observations category id 
                $rs=$this->get_typecategory_id_byname(array('Observations'));
                $obser_id=$rs['Observations'];
                #$client_id=$this->domain['client_id'];
                $client_id=$_SESSION['client']['clinet_id'];
                $client_id=$this->client_id;
		
		$result = $this->db->query("SELECT A.type_name as type_name, A.id as id, A.status as status,B.id as parent_id, B.type_name as parent_name
									FROM type_category AS A , type_category AS B
                                                                        WHERE A.type_category = B.id AND B.type_category =".$obser_id." and A.type_client_fk={$client_id} order by B.id Asc ");
		$row = $result->result_array();
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}     
        
        
	
	function  get_inspection_observation_list_new($field=NULL){

            /*$sql="SELECT A.type_name as type_name, A.id as id, A.status as status,B.id as parent_id, B.type_name as parent_name
                            FROM type_category AS A , type_category AS B
                            WHERE A.type_category = B.id AND B.type_category = 17";*/
//            print_r($_SESSION);
//            print_r($this->domain); die;
            
             $domain_client_id = $this->client_id;
             
             $sql="SELECT A.type_name as type_name, A.id as id, A.status as status,B.id as parent_id, B.type_name as parent_name
                           FROM type_category AS A , type_category AS B
                           WHERE A.type_category = B.id AND A.type_client_fk='".$domain_client_id."' AND  B.type_name ='Observations'";
            
                $result = $this->db->query($sql);
                
		$row = $result->result_array();
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function  get_observation_type_list($field=NULL){
		
		$this->db->select('*');		
		$this->db->where('type_category',17);
		$result=$this->db->get('type_category');
		if($result->num_rows()>0){
			//echo $this->db->last_query();
			return $result->result_array();
		}else{
			return false;
		}
	}

	
	function get_inspection_result($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$result=$this->db->get('type_category');
		if($result->num_rows()>0){
		//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function get_related_result($id){
		$res_id = array();
		$this->db->select('id');
		$this->db->where('id',$id);
		$this->db->or_where('type_category',$id);
		$results=$this->db->get('type_category');
		if($results->num_rows()>0){
		//echo $this->db->last_query();
			$res =  $results->result_array();
			foreach($res as $val){
				$res_id[] = $val['id'];
			}
			return $res_id;
		}else{
			return false;
		}
	}
	
	function update_inspection_result($dbdata,$id){
		$this->db->where('id', $id);
		return	$this->db->update('type_category', $dbdata);
	}
	
	function delete_inspection_result($idArray){
		foreach($idArray as $id){
			$this->db->where('id',$id);
			$res = $this->db->delete('type_category');
		}
		if($res){
			return $res;
		}
	}
	
	function delete_action_proposed_result($id){
		$this->db->where('id',$id);
		if($this->db->delete('type_category')){
			return true;
		}else{
			return false;
		}
	}
	
	function get_inspection_list($type='',$inspectiontype=''){
		
		if($type=='inspection'){
			$id = 2;
		}else if($type == 'result'){
			$id = 3;	
		}else if($type == 'client'){
			$id = 9;
		}else if($type == 'observations'){
			$id = 15;
		}else if($type == 'uom'){
			$id = 22;
		}
		if($inspectiontype!=''){
			$this->db->select('id');
			$this->db->where('type_category',$id);
			$this->db->where('type_name',$inspectiontype);
			$this->db->where('status',1);
			$result=$this->db->get('type_category');
			if($result->num_rows()>0){
			//echo $this->db->last_query();
				$res = $result->row_array();
				return $res['id'];
			}else{
				return false;
			}
		}else{
			$this->db->select('id,type_name,type_category');
			$this->db->where('type_category',$id);
			$this->db->where('status',1);
			$result=$this->db->get('type_category');
			if($result->num_rows()>0){
			//echo $this->db->last_query();
				return $result->result_array();
			}else{
				return false;
			}
		}
	}
	
	/*
		* Get The type of Inspection we want from type_category table and return it.
	*/
	function get_type_category($type=''){
            
            if($type=="inspection"){ $type="Inspection Type";}
            if($type=="uom"){ $type="Uom";}
            if($type=="result"){ $type="Expected Result";}
            
            if($type=="observations"){ $type="Observations";}
            
            $typeArr=$this->get_typecategory_id_byname($type);
            
            //print_r($typeArr);
            
            $id=$typeArr[$type];
            $client_id=$_SESSION['client']['client_id'];
            //print_r($id);  
            
		/*if($type=='inspection'){
			$id = 2;
		}else if($type == 'result'){
			$id = 3;	
		}else if($type == 'client'){
			$id = 9;
		}else if($type == 'observations'){
			$id = 17;
		}else if($type == 'uom'){
			$id = 24;
		}*/
		
		$this->db->select('id,type_name,type_category');
			$this->db->where('type_category',$id);
                        
                        #$this->db->where('type_client_fk',$client_id);
                        #$this->db->or_where('type_client_fk',0);
                        
			$this->db->where('status',1);
			$result=$this->db->get('type_category');
                        #echo $this->db->last_query();
			if($result->num_rows()>0){
				$inspection_type=$result->result_array();
					$insdatas = array();
					foreach($inspection_type as $resVal){
						$insdatas[$resVal['id']] = $resVal['type_name'];
					}
					return $insdatas;
			}else{
				return $ins='';
			}
	}
	
	function get_inspection_value($id){
		$this->db->select('id,type_name');
		$this->db->where('id',$id);
		$this->db->where('status',1);
		$result=$this->db->get('type_category');
		if($result->num_rows()>0){
			$inspection_type=$result->row_array();
			$array = array();
			return $array = array($inspection_type['id']=>$inspection_type['type_name']);
		}else{
			return false;
		}
	}
	
	function get_inspection_observation_list_api($id){
		
		$this->db->select('id,type_name');
		$this->db->where('type_category',$id);
		$this->db->where('status',1);
		$result=$this->db->get('type_category');
		$row = $result->result_array();
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}
	
	function get_unique_observation_list_api($field=NULL){
		
		$result = $this->db->query("
									SELECT B.id as parent_id
									FROM type_category AS A , type_category AS B
									WHERE A.type_category = B.id AND B.type_category = 17
									GROUP BY B.id
								");
		$row = $result->result_array();
		if($result->num_rows()>0){
			$res = $result->result_array();
			$arr = array();
			foreach($res as $val){
				$arr[] = $val['parent_id'];
			}
			return $arr;
		}else{
			return false;
		}
	}
	
	
	function get_standard_result($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$result=$this->db->get('manage_certificate');
		if($result->num_rows()>0){
		//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function  get_manage_standard_result_list($field=NULL){
		$result = $this->db->query("SELECT id, type,name, status FROM manage_certificate ");
		if($result->num_rows()>0){
			return $result->result_array();
		}else{
			return false;
		}
	}	
	
	function save_manage_certificate_result($dbdata){
		if($this->db->insert('manage_certificate',$dbdata)){
			return true;
		}
		else{ 
			return $this->db->error();
		}
	}



	function delete_manage_stand_certificate_result($idArray){
		foreach($idArray as $id){
			$this->db->where('id',$id);
			$res = $this->db->delete('manage_certificate');
		}
		if($res){
			return $res;
		}
	}


	function  get_manage_cert_filt_result_list($fildArry){
		
		$this->db->select('*');
		if(!empty($fildArry['type'])){
		 $this->db->where('type',$fildArry['type']);
		}
		if(!empty($fildArry['name'])){
		 $this->db->where('name',$fildArry['name']);
		}
		if(!empty($fildArry['status'])){
		 $this->db->where('status',$fildArry['status']);
		}
		$result=$this->db->get('manage_certificate');
		if($result->num_rows()>0){
			$inspection_type=$result->result_array();
			$insdatas = array();
			foreach($inspection_type as $resVal){
				$insdatas[] = $resVal;
			}
			return $insdatas;

			return $result->row_array();
		}else{
			return false;
		}
	}

	function update_manage_certificate_result($dbdata,$id){
		$this->db->where('id', $id);
		return	$this->db->update('manage_certificate', $dbdata);
	}	
	
	function get_manage_certificate_result($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$result=$this->db->get('manage_certificate');
		if($result->num_rows()>0){
		//echo $this->db->last_query();
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	function  get_manage_cert_filt_chk_result($fildArry){
		
		$this->db->select('id');
		if(!empty($fildArry['type'])){
		 $this->db->where('type',$fildArry['type']);
		}
		if(!empty($fildArry['name'])){
		 $this->db->where('name',$fildArry['name']);
		}
		if(!empty($fildArry['status'])){
		 $this->db->where('status',$fildArry['status']);
		}
		$result=$this->db->get('manage_certificate');
		if($result->num_rows()>0){
			return $result->row_array();
		}else{
			return false;
		}
	}

}



?>