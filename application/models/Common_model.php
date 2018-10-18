<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Common_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
		$this->table_name = 'manage_categories';
	}
	
	function insert_table_data_old($dbdata,$tableName){		
		return $res = $this->db->insert($tableName,$dbdata);
	}
	
	function insert_table_data($dbdata,$tableName){	
		$this->db->insert($tableName,$dbdata);
		$insert_id = $this->db->insert_id();
		if($insert_id > 0){
			return 1;
		}else{
		   return -1;
		}
	}
	
	function delete_table_data($id,$tableName){
		$this->db->where('id',$id);
		return $this->db->delete($tableName);
	}
	
	function update_tabel_data($id, $dbarray, $tableName){
		$this->db->where('id',$id);
		return $this->db->update($tableName,$dbarray);
	}
	
	function update_table_data($id, $dbarray, $tableName){
		$this->db->where('id',$id);
		return $this->db->update($tableName,$dbarray);
	}
	
	function get_loggegIn_user_fullName(){
		$userdata 	= $this->session->userdata();
		return $userdata['firstName'].' '.$userdata['lastName'];
	}
	
	function get_loggegIn_user_groupID(){
		$userdata 		= $this->session->userdata();
		$group 	= $userdata['flexi_auth']['group'];
		foreach($group as $key=>$val){
			$groupID = $key;
		}
		$_SESSION['user_group'] = $groupID;
		return $groupID;
	}
	
	function get_loggedIn_user_dashboard(){
		$groupID = $this->get_loggegIn_user_groupID();
		if($groupID =='8'){
			redirect('Clientuser_dashboard/');
		}if($groupID =='10'){
			redirect('infonet_details/about_us');
		}if($groupID =='11'){
			redirect('infonet_details/about_us');
		}else{
			redirect('auth_admin/dashboard/');
		}
		// if($groupID =='8'){
			// redirect('Clientuser_dashboard/');
		// }if($groupID =='10'){
			// redirect('Client_view/');
		// }if($groupID =='11'){
			// redirect('Client_view/');
		// }else{
			// redirect('auth_admin/dashboard/');
		// }
	}
	
	function fetchcategoryHri($cat_parentid = ''){
		if(!empty($cat_parentid)){
			$this->db->from($this->table_name);
			$this->db->where('id',$cat_parentid);
			$res = $this->db->get(); 
			if ($res->num_rows() > 0){
					$res = $res->result_array();
					$return = $res;
			} else {
					$return = -2;
			} 
		}else{
			$return = -1;
		}
		return $return;
	}
	
	function get_user_data($user_id){
		
		$query = $this->db->query("	
									SELECT CONCAT(A.upro_first_name,' ',A.upro_last_name) as full_name, B.*
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_uacc_fk = B.uacc_id
									WHERE B.uacc_id=".$user_id
								) or die(mysql_error());
		return ($query->num_rows() !== 1)? FALSE : $query->row_array();
	}
	
	function get_kdmanager_data(){
		$query = $this->db->query("	
									SELECT CONCAT(A.upro_first_name,' ',A.upro_last_name) as full_name, B.*
									FROM demo_user_profiles as A 
									INNER JOIN user_accounts as B
									ON A.upro_uacc_fk = B.uacc_id
									WHERE B.uacc_group_fk=10"
								) or die(mysql_error());

		return ($query->num_rows() !== 1)? FALSE : $query->result_array();
	}
	
}// end of Model class 
?>