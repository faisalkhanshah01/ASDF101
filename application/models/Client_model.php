<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Client_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	 }
	 
	function add_excelclients($dbdata){
		return $this->db->insert_batch('clients',$dbdata);	
	}
	
	public function add_clients($dbdata)
	{
		$query1 = $this->db->query("SELECT * FROM clients WHERE client_name='$dbdata[client_name]'");
		
		if($query1->num_rows() == 0)
		{
			$this->db->insert('clients',$dbdata);
			return true;
		}
		else
		{
			return false;
		}
		
		
		/*
			if($this->db->insert('clients',$dbdata))
			{
				return true;
			}
			else
			{
			   return $this->db->error();
			 //return false;   
			}
		*/
	}
	
	public function client_view($excel=''){
		$this->db->select("client_id, client_name, client_district, client_circle, client_contactPerson, client_contactNo, client_contactPerson_email,client_type,client_status");
		$this->db->from('clients');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return ($excel != '')? $query->result_array() : $query->result();
		}else{
			return false;
		}
	}
	
	public function client_view_old($excel='')
	{
		$this->db->select("client_id, client_name, client_district, client_circle, client_contactPerson, client_contactNo, client_contactPerson_email,client_type,client_status");
		$this->db->from('clients');
		$query = $this->db->get();		
		if($excel != ''){
			return $query->result_array();
		}else{
			return $query->result();	
		}
				
	}

	
	public function edit_client($userid)
	{
		$this->db->select("client_id, client_name, client_district, client_circle, client_contactPerson, client_contactNo, client_contactPerson_email,client_type,client_status");
		$this->db->from('clients');
		$this->db->where('client_id',$userid);
		$query = $this->db->get();		
		return $query->result();
	
	}
	
	public function update_clients($dbdata,$client_id)
	{
		//var_dump($dbdata);
		//die();
		$this->db->where('client_id', $client_id);
		return $this->db->update('clients', $dbdata);
	}
	
	public function client_delete($userid)
	{
		$query= $this->db->query("DELETE FROM clients WHERE client_id='$userid'");
		return true;
	}
	 
	function client_type($id){

		$this->db->select('type_name');
		$this->db->where('type_category',9);
		$this->db->where('id',$id);
		$this->db->where('status',1);
		$result=$this->db->get('type_category');
		if($result->num_rows()>0){
			$res = $result->row_array();
			return $res['type_name'];
		}else{
			return false;
		}
	}
	
	function get_clientDealer_name($id){
		$this->db->select('client_id,client_name');
		$this->db->where('client_id',$id);
		$result=$this->db->get('clients');
		if($result->num_rows()>0){
			return $result->row_array();
		}else{
			return false;
		}
	}
}
?>