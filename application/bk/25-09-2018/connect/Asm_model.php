<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 class Asm_model extends CI_Model{
     
    function __construct(){
        parent::__construct();
            //$db = $this->load->database('karedb',true);
            #print_r($this->dbkare);
        $this->db=$this->karedb;
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
                return true;
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
    
    
     
     
 }
