<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arresto_model extends Flexi_auth_model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('date');
		$this->load->helper('file');
	}
        
        

function getRootAdmin(){
    $sql="select ugrp_id from user_groups where ugrp_id=1 and ugrp_type='arresto_ADMIN'";
    $query=$this->db->query($sql);
    $result=array();
     if($query->num_rows>0){
     	$result=$query->result_array();
     } 
     return $result;
}


function get_clients($client_id){

   // echo  $sql="select * from ar_clients_2018 join user_accounts on ar_clients_2018.user_acc_fk=user_accounts.uacc_id";
   $sql="select clients.*,users.uacc_id,users.uacc_email,users.uacc_username,users.uacc_active,profiles.* from ar_clients_2018 as clients
            join user_accounts as users  on clients.customer_uacc_fk=users.uacc_id
            join demo_user_profiles as profiles on users.uacc_id= profiles.upro_uacc_fk"; 
    if($client_id){
      $sql .= " where clients.customer_id=".$client_id;
    }

     $query=$this->db->query($sql);
     $result =array();
     
     if($query->num_rows()>0){
         
         if($client_id){
          $result=$query->row_array();     	
            }else{
                $result=$query->result_array();  
            }
         
     }
     
     
     
     return $result;
}


function delete_client($client_id){
    
      // empty the group table 
       $this->db->select('customer_uacc_fk');
       $this->db->where('customer_id',$client_id);
       $query=$this->db->get('ar_clients_2018');
       if($query->num_rows()>0){
           $row=$query->row_array();
           $uacc_id=$row['customer_uacc_fk'];
       }
       
       // empty the refrence from   
       if($uacc_id){
           $this->db->where('ugrp_client_fk',$uacc_id);
           $this->db->delete('user_groups');
       }
      // empty the refrence user_accounts table  
       if($this->flexi_auth->delete_user($client_id)){
           
            $this->db->where('customer_id',$client_id);
            $this->db->delete('ar_clients_2018');
          
            redirect("/admin/auth_admin/");
            
       }
          
    
}

function language_add(){
    
   $language= $this->input->post('lang_name');
   $status= $this->input->post('lang_status');
   
   $data=array('lang_name'=>$language,
                'lang_status'=>$status
       );
   return $this->db->insert('language_list',$data);     
}


function import_language_list($lang_data){
    if(count($lang_data)){
        return $this->db->insert_batch('language',$lang_data);
    }
    
}


function get_lang_list(){
    $this->db->select('*');
    $query=$this->db->get('language_list');
    if($query->num_rows()){
        return $query->result_array();
    }
}


function getClients(){
     echo $sql="select ugrp_id from user_groups where ugrp_pid=1 and ugrp_type='ARRESTO_CLIENT'";
     $query=$this->db->query($sql);
     $result =array();
     if($query->num_rows()>0){

     	$rs=$query->result_array();
       	$group_ids= implode(",",array_column($rs,'ugrp_id'));

       echo  $sql="select * from user_accounts where uacc_group_fk in({$group_ids})";
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
          $result=$query->result_array();
        } 

     }
    return $result;
} 



public function generate_token($length = 8) {
        $characters = '23456789BbCcDdFfGgHhJjKkMmNnPpQqRrSsTtVvWwXxYyZz';
        $count = mb_strlen($characters);
        for ($i = 0, $token = ''; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $token .= mb_substr($characters, $index, 1);
        }
        return $token;
}

public function generate_num_token($length = 8) {
        $characters = '0123456789';
        $count = mb_strlen($characters);
        for ($i = 0, $token = ''; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $token .= mb_substr($characters, $index, 1);
        }
        return $token;
}


function encrypt_decrypt_string( $string, $action = 'e' ) {
        $secret_key 		= ENCRYPTION_SECRET_KEY;
        $secret_iv 			= ENCRYPTION_SECRET_IV;		 
        $output 			= false;
        $encrypt_method 	= "AES-256-CBC";
        $key 				= hash( 'sha256', $secret_key );
        $iv 				= substr( hash( 'sha256', $secret_iv ), 0, 16 );		 
        if( $action == 'e' ) {
            $output 		= base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output 		= openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }		 
        return $output;
    }



public function insert_data($data, $table_name) {
        $result = $this->db->insert($table_name, $data);
        if($result) {
            $result = array(
                        'id' =>$this->db->insert_id(), 
                        'msg_code'  => 200
                       );
        } else {
                $result = array(
                            'id'	=> '',
                            'msg_code'  => 404
                );
}
return $result;
}

public function update_data($data, $table_name, $where_field1, $where_value1, $where_field2=NULL, $where_value2=NULL, $where_field3=NULL, $where_value3=NULL) {
        $this->db->where($where_field1, $where_value1);
        if(!is_null($where_field2)) {
                $this->db->where($where_field2, $where_value2);
        }
        if(!is_null($where_field3)) {
                $this->db->where($where_field3, $where_value3);
        }
        $result = $this->db->update($table_name, $data);
        //print_r($this->db->last_query());die();
return $result;
}


public function field_exists_check($where_field1, $where_value1, $table_name, $where_field2 = NULL, $where_value2 = NULL, $where_field3 = NULL, $where_value3 = NULL) {
                $this->db->where($where_field1, $where_value1);
                if(!is_null($where_field2)) {
                        $this->db->where($where_field2, $where_value2);
                }
                if(!is_null($where_field3)) {
                        $this->db->where($where_field3, $where_value3);
                }
                $query = $this->db->get($table_name);
                return $query->num_rows();
        }

public function get_data($select, $where_field1, $where_value1, $table_name, $where_field2 = NULL, $where_value2 = NULL, $where_field3 = NULL, $where_value3 = NULL) {
        $this->db->select($select);
        $this->db->where($where_field1, $where_value1);
        if(!is_null($where_field2)) {
                $this->db->or_where($where_field2, $where_value2);
        }
        if(!is_null($where_field3)) {
                $this->db->or_where($where_field3, $where_value3);
        }
        $query = $this->db->get($table_name);
        //print_r($thi->db->last_query());die();
        return($query->result_array());
}

// new function written By sachin 

    function get_clients_settings($client_slug){
         
        //$sql="select * from user_accounts join ar_clients_2018 on user_accounts.uacc_id=ar_clients_2018.customer_uacc_fk";
        $sql="select * from user_accounts join ar_clients_2018 where customer_company_name='$client_slug'";
        $query=$this->db->query($sql);
        $results=array();
        if($query->num_rows()){
            $results=$query->row_array();
        }
       return $results;
    }
    
    
    
    
   function change_client_status($client_id,$action){
     $status=($action=='Active')?'1':0;
     $data=array("activation_flag"=>$status);
     //$this->db->update('activation_plan',$status);
     $this->db->where('customer_id',$client_id);
     return $result = $this->db->update('ar_clients_2018', $data);
     //echo $this->db->last_query();
      
  }
  
  
    function send_email($data){

         #print_r($data); die;

            $this->load->library('email');

            $config['smtp_host']	= 'smtp.elasticemail.com';
            $config['smtp_port']	= 2525;
            $config['smtp_user']	= 'b7de0525-1808-4214-83b6-dcfaabbbe996';
            $config['smtp_pass']	= 'b7de0525-1808-4214-83b6-dcfaabbbe996';
            $config['newline'] = "\r\n";
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $this->email->to($data['mail_to']);

            if(isset($data['mail_cc'])){
                $this->email->cc($data['mail_cc']);  
            }
            
            if(!isset($data['mail_from'])){
               $this->email->from('varun@flashonmind.com','Varun');   
            }else{
              $this->email->from($data['mail_from']);   
            }

            $this->email->from('info@arresto.in','Arresto'); 
            $this->email->reply_to('info@arresto.in','Arresto');   

            $this->email->subject($data['mail_subject']);
            $this->email->message($data['mail_message']);

            if($this->email->send()){
                #echo "mail send";
                return true;            
            }else{

                #echo  $this->email->print_debugger(); die;
                return false;
            }

    }    
  
  

	
}

/* End of file api_model.php */
/* Location: ./application/model/api_model.php */

