<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class M_client_user_dashboard extends CI_Model{
		function __construct(){
			parent::__construct();
			$this->load->database();
		} 

		function get_client_data_by_user($id,$group_id){
			$sql = "SELECT client_ids,site_id FROM assign_client_data WHERE client_ids like '%".$id."%'  AND status ='Active' AND `client_type` = ".$group_id;
			//print_r($sql);die;
			$query = $this->db->query($sql);
			if($query->num_rows()>0){
				$result = $query->result_array();
				return $result;
			}else{
				return -1;
			}
		}

		function get_client_data($group_id){
			$this->db->select('*');
			$this->db->from('assign_client_data');
			$this->db->where('client_type',$group_id);
			$query = $this->db->get();
			return $query->result_array();
		}
	}
	
?>	