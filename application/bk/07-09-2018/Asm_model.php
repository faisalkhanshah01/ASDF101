<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 class Asm_model extends CI_Model{
     
     function __construct(){
         parent::__construct();
            $this->load->database();
            #print_r($this->db);
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
    
    function get_data($table,$where=1,$fields='*'){
        /*if(!is_array()){
        }*/
        #print_r($table); die;
        
        $this->db->select($fields);
        $this->db->where($where);
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
    
    
     
     
 }
