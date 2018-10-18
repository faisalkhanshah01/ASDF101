<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 class Asm_model extends CI_Model{
     
     function __construct(){
         parent::__construct();
         //$this->db=$this->karedb;
         //print_r($this->db);
     }
     
 function asm_init(){
         // this function initialize the asm configuration 
         // table asm_products, asm_product_transactions, asm_prooduct_usecase,asm_user_projects,
    $client_id=$_SESSION['client']['client_id'];      

     $sql =<<<EOSQL
     CREATE TABLE IF NOT EXISTS `asm_{$client_id}_products` (
     `ps_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
     `ps_mdata_id` int(11) NOT NULL COMMENT 'foreign key , ref the master table id ',
     `ps_product_id` varchar(100) NOT NULL,
     `ps_user_id` int(11) NOT NULL,
     `ps_project_id` int(11) NOT NULL,
     `ps_store_id` int(11) NOT NULL,
     `ps_smanager_id` int(11) NOT NULL COMMENT 'Store Manager ID refer as  Store ID ',
     `ps_checkedin` int(11) NOT NULL,
     `ps_checkedout` int(11) NOT NULL,
     `ps_isused` int(11) NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ASM Products Base/Main Table';
EOSQL;
     
  if($this->db->query($sql)){
      $log[]="asm_products_{$client_id} SUCCESS";
  }else{
    $log[]="asm_products_{$client_id} FAIL";  
  }  
    
 /* $sql = <<< EOSQL
  CREATE TABLE IF NOT EXISTS `asm_{$client_id}_store_products` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `sp_product_id` int(11) NOT NULL,
  `sp_smanage_id` int(11) NOT NULL,
  `sp_store_id` int(11) NOT NULL,
  `sp_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOSQL;
 
  if($this->db->query($sql)){
      $log[]="asm_store_products_{$client_id} SUCCESS";
  }else{
    $log[]="asm_store_products_{$client_id} FAIL";  
  }*/  
  
  
 $sql = <<< EOSQL
  CREATE TABLE IF NOT EXISTS `asm_{$client_id}_user_projects` (
  `up_id` int(11) NOT NULL AUTO_INCREMENT,
  `up_user_id` int(11) NOT NULL,
  `up_project_name` varchar(255) NOT NULL,
  `up_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`up_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOSQL;
  
  if($this->db->query($sql)){
      $log[]="asm_user_projects_{$client_id} SUCCESS";
  }else{
    $log[]="asm_user_projects_{$client_id} FAIL";  
  }
  
  
 $sql = <<< EOSQL
  CREATE TABLE IF NOT EXISTS `asm_{$client_id}_user_project_products` (
  `upp_id` int(11) NOT NULL AUTO_INCREMENT,
  `upp_user_id` int(11) NOT NULL,
  `upp_project_id` int(11) NOT NULL,
  `upp_mdata_id` int(11) NOT NULL COMMENT 'ref mdata table ',
  `upp_product_id` varchar(100) NOT NULL,
  `upp_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`upp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User Project Products';
EOSQL;
  
  if($this->db->query($sql)){
      $log[]="user_projects_product SUCCESS";
  }else{
    $log[]="user_projects_product FAIL";  
  }
  
  
  
  
 $sql = <<< EOSQL
 CREATE TABLE IF NOT EXISTS `asm_{$client_id}_product_transactions` (
  `pt_id` int(11) NOT NULL AUTO_INCREMENT,
  `pt_product_id` int(11) NOT NULL,
  `pt_user_id` int(11) NOT NULL,
  `pt_smanager_id` int(11) NOT NULL,
  `pt_store_id` int(11) NOT NULL,
  `pt_checkedin` int(11) NOT NULL,
  `pt_checkedout` int(11) NOT NULL,
  `pt_comment` varchar(255) NOT NULL,
  `pt_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ASM PRODUCT TRANSATIONS HISTORY';
EOSQL;
  
  if($this->db->query($sql)){
      $log[]="product_transactions SUCCESS";
  }else{
    $log[]="product_transactions FAIL";  
  }
  

  print_r($log); 

}
     
     
    function get_project_list(){
     
        $this->db->select('*');
        $this->db->from('asm_user_projects');
        $query=$this->db->get();
        if($query->num_rows()>1){
           return $query->result_array();
        }else{
           return false; 
        }
        
    }
    
    function get_project_products($project_id){
        $this->db->select('*');
        $this->db->where('project_id',$project_id);
        $this->db->from('asm_project_products');
        $query=$this->db->get();
        if($query->num_rows()>1){
           return $query->result_array();
        }else{
           return false; 
        } 
        
    }
    
    function insert_project($dbdata){
       $id= $this->db->insert('asm_user_projects',$dbdata);
        if($id){
            return true;
        }else{
            echo $this->db->_error_message();
            return false;
        }
    }
    
    
    
    function insert_data($table,$data){
        $id=$this->db->insert($table,$data);
        #echo $this->db->last_query();
        if($id){
            return true;
        }else{
            return false;
            
        }
    }
    
    function update_table($table,$data,$where){
        
        #print_r($where); //die;
        if(!count($where)){
            return false;
        }else{
            $this->db->where($where);
            $this->db->set($data);
            $result=$this->db->update($table);

            //$result=$this->db->update($table,$data);
            #echo $this->db->last_query();
            if($result){
                return $result;
            }else{
             return false;   
            }
        }
    }
    
    function get_data($table,$where=1,$order_str=null,$fields='*'){
        /*if(!is_array()){
        }*/
        #print_r($table); die;
        $this->db->select($fields);
        $this->db->where($where);
        if($order_str){
           $this->db->order_by($order_str); 
        }
        
        $query  = $this->db->get($table); 
        #echo $this->db->last_query();
        
        if($query->num_rows()>1){
         return $query->result_array();
        } if($query->num_rows == 1){
              return $query->result_array();
            #return $query->row_array();
        }else{
           
            return false;
        }
       
    }
    
    function get_data_row($table,$where=1,$order_str=null,$fields='*'){
          $data= $this->get_data($table,$where,$order_str,$fields);
          #echo $this->db->last_query();
          if(count($data)==1){
              return $data[0];
          }else{
              return $data;
          } 
    }
    
    
    function filter_data($table,$filter,$where=null){
        
        if(isset($filter['distinct'])){
            $this->db->distinct(); 
            $this->db->select($filter['distinct']);
        }
        
        if(is_array($where)){
           $this->db->where($where); 
        }
        
        #$field = "DISTINCT({$filter['distinct']})";
        #$this->db->select($field);
        #$this->db->from($table);
        
        $query=$this->db->get($table);
        #echo $this->db->last_query();
        
        if($query->num_rows()){
            return $query->result_array();
        }else{
            
            return false;
        }
        
    }
    
    
    
    function delete_record($table,$where){
        if(!count($where)){
            
            return false;
            
        } else{
            
            $this->db->where($where);
            $result=$this->db->delete($table);
            #echo $this->db->last_query();
            if($result){
                return true;
            }else{
             return false;   
            }    
        } 
    }
    
   function row_count($table,$where){
       $this->db->select('*');
       $this->db->where($where);
       $query=$this->db->get($table);
       #echo $this->db->last_query();
       return $query->num_rows();
   } 
   
   function get_prodcut_history($product_id){
       $sql="select * FROM asm_product_usecase where pu_product_id='".$product_id."' group by pu_user_id";
       $query = $this->db->query($sql);
       if($query->num_rows()){
           return $query->result_array();
       }else{
           return false;
       }
   } 
   
    function query_data($table,$where=null,$filter=null,$field=null){
        
        if(isset($filter['distinct'])){
            $this->db->distinct(); 
            $this->db->select($filter['distinct']);
        }
        
        if(is_array($where)){
           $this->db->where($where); 
        }
        $query=$this->db->get($table);
        //echo $this->db->last_query();
        
        if($query->num_rows()){
            return $query->result_array();
        }else{
            return false;
        }
        
    }
    
   
      public function android_notification($token,$title,$msg,$data=null) {
          
        $fields = array (
            'to' => $token,
            'notification' => array (
                "body" => $msg,
                "title" => $title,
                "timestamp" => time()
            ),
            'data'=>$data
        );
        $fields = json_encode ( $fields );
        //print_r($fields);die();
        $headers = array (
                'Authorization: key=' . FIREBASE_API_KEY_ANDROID,
                'Content-Type: application/json'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, FIREBASE_URL);
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        return $result = curl_exec ( $ch );
        curl_close ( $ch );
    }
    
    
    public function ios_notification($token, $title, $msg,$data=null) {
        $fcmMsg = array(
            'body'  => $msg,
            'title' => $title,
            'sound' => "default",
            'color' => "#203E78" 
        );

        $fcmFields = array(
            'to'            => $token,
            'priority'      => 'high',
            'notification'  => $fcmMsg,
            'data'=>$data
        );

        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY_IOS,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, FIREBASE_URL);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        echo $result . "\n\n";
    }
    
    
   
 }
