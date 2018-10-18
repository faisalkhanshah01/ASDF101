<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Client_manual_data_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	
	function get_all_data(){
		$this->db->select('*');
		$this->db->from('client_manual_data');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function get_data_from_id($id){		
		$this->db->select('*');
		$this->db->from('client_manual_data');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();	
	}
	
	
}// end of Model class 
?>