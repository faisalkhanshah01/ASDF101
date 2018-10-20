<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 class Build_model extends CI_Model{
     
     function __construct(){
         parent::__construct();
         //$this->db=$this->karedb;
         //print_r($this->db);
     }
     
    function insert_data($table,$data){
        $id=$this->db->insert($table,$data);
        #echo $this->db->last_query();
        if($id){
            return $id;
        }else{
             echo $this->db->_error_message();
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
            'data'=>$data,
        );

        $fcmFields = array(
            'to'            => $token,
            'priority'      => 'high',
            'notification'  => $fcmMsg,
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
