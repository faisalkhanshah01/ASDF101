<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends Flexi_auth_model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('date');
		$this->load->helper('file');
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

	public function get_and_data($select, $where_field1, $where_value1, $table_name, $where_field2 = NULL, $where_value2 = NULL, $where_field3 = NULL, $where_value3 = NULL) {
		$this->db->select($select);
		$this->db->where($where_field1, $where_value1);
		if(!is_null($where_field2)) {
			$this->db->where($where_field2, $where_value2);
		}
		if(!is_null($where_field3)) {
			$this->db->where($where_field3, $where_value3);
		}
		$query = $this->db->get($table_name);
		return($query->result_array());
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
	
	function get_siteid_inspector($temp){
		if(!empty($temp) && is_array($temp)){
			$this->db->select('t1.mdata_dealer,t1.mdata_rfid,t1.mdata_barcode,t1.mdata_uin,t1.mdata_client,t1.mdata_po,t1.mdata_item_series,t2.*');
			$this->db->from('master_data as t1');
			$this->db->join('siteID_data as t2', 't1.mdata_id = t2.master_id');
			if(!empty($temp['mdata_uin'])){
				$this->db->where('t1.mdata_uin', $temp['mdata_uin']);
			}
			if(!empty($temp['mdata_barcode'])){
				$this->db->where('t1.mdata_barcode', $temp['mdata_barcode']);
			}
			if(!empty($temp['mdata_rfid'])){
				$this->db->where('t1.mdata_rfid', $temp['mdata_rfid']);
			}
			print_r($this->db->last_query());die;
			//$this->db->where('t1.status', 'Active');
			$res = $this->db->get();
			if ($res->num_rows() > 0) {
				$return = $res->result_array();
			} else {
				$return = -2;
			}
			return $return;
			
		}
		
	}
	
	function update_factory_data($param){
		if(!empty($param) && is_array($param)){
			$data = array();
			if(!empty($param['manual_client_user_id'])){
				$data['manual_client_user_id'] = $param['manual_client_user_id'];
			}
			if(!empty($param['manual_client_name'])){
				 $data['manual_client_name'] = $param['manual_client_name'];
			}
			if(!empty($param['manual_client_address'])){
				$data['manual_client_address'] = $param['manual_client_address'];
			}
			if(!empty($param['manual_serial_number'])){
				 $data['manual_serial_number'] = $param['manual_serial_number'];
			}
			if(!empty($param['manual_batch_code'])){
				 $data['manual_batch_code'] = $param['manual_batch_code'];
			
			}
			if(!empty($param['manual_assets_series'])){
					$data['manual_assets_series'] = $param['manual_assets_series'];
			}
			if(!empty($param['manual_invoice_number'])){
				$data['manual_invoice_number'] = $param['manual_invoice_number'];
			}
			
			$this->db->insert('factory_manual_data',$data);
			$result = $this->db->insert_id();
			if($result > 0){
				return $result;
			}else{
				 return -1;
			}
		}  else{
			return -2;
		}  
	} 

	public function insert_data($data, $table_name) {
		$result = $this->db->insert($table_name, $data);
		if($result) {
        	$result = array(
				'id' 		=>$this->db->insert_id(), 
            	'msg_code'  => 200
          	);
      	} else {
        	$result = array(
        		'id'		=> '',
            	'msg_code'  => 404
          	);
      	}
      	return $result;
	}
	
	function scheduler_insert($data){
		if(!empty($data) && is_array($data)){
			$dublicateSite = $this->scheduler_fetch($data);
			if(!empty($dublicateSite['s_id'])){
				return -3;
			}
			
			$this->db->insert('scheduler',$data);
			$result = $this->db->insert_id();
			if($result > 0){
				return $result;
			}else{
				 return -1;
			}
		}  else{
			return -2;
		}  
	}
	
	function scheduler_fetch($data){
		$this->db->select('*');
        $this->db->where('s_site_id', $data['s_site_id']);
        $this->db->where('s_user_id', $data['s_user_id']);
        $query = $this->db->get('scheduler');
        if ($query->num_rows() > 0) {
            $return = $query->row_array();
			if($return['s_id']){
				$this->db->where('s_id', $return['s_id']);  
				$this->db->update('scheduler', $data); 
			}
			return $return;
        } else {
            return false;
        }
	}
	
	function scheduler_fetch_sites($user_id){
		$this->db->select('s_site_id as site_id');
        $this->db->where('s_user_id', $user_id);
        $query = $this->db->get('scheduler');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return -1;
        }
	}
	
	function update_dealer_data($param){
		if(!empty($param) && is_array($param)){
			$data = array();
			if(!empty($param['dealer_user_id'])){
				$data['dealer_user_id'] = $param['dealer_user_id'];
			}
			if(!empty($param['dealer_name'])){
				 $data['dealer_name'] = $param['dealer_name'];
			}
			if(!empty($param['dealer_address'])){
				$data['dealer_address'] = $param['dealer_address'];
			}
			if(!empty($param['dealer_contactNo'])){
				 $data['dealer_contactNo'] = $param['dealer_contactNo'];
			}
			if(!empty($param['dealer_email'])){
				 $data['dealer_email'] = $param['dealer_email'];
			}
			if(!empty($param['dealer_input_data'])){
					$data['dealer_input_data'] = $param['dealer_input_data'];
			}
			
			$this->db->insert('manual_dealer_data',$data);
			$result = $this->db->insert_id();
			if($result > 0){
				return $result;
			}else{
				 return -1;
			}
		}  else{
			return -2;
		}  
	}
	
	
	public function hasprofile($user_id){
        $this->db->select('*');
        $this->db->where('upro_uacc_fk', $user_id);
        $query = $this->db->get('demo_user_profiles');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
	
	public function update_profile($user_id, $data) {
		//print_r($data);die();
        $this->db->where('upro_uacc_fk', $user_id);
        $this->db->update('demo_user_profiles',$data);
        if ($this->db->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
	
	public function user_info($user_id){
		$this->db->select('*');
        $this->db->from('user_accounts');
        $this->db->join('demo_user_profiles', 'user_accounts.uacc_id = demo_user_profiles.upro_uacc_fk');
        $this->db->where('user_accounts.uacc_id',$user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
	
	
	 public function register_account() {
		if(!empty($_REQUEST['email'])  && !empty($_REQUEST['password'])){
			// Get user login details from input.
			$email = strtolower(trim($_REQUEST['email']));
			$password = trim($_REQUEST['password']);
			$imei_no = trim($_REQUEST['imei_no']);
			$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
			
			// Get user profile data from input.
			// You can add whatever columns you need to customise user tables.
			/*************************pofile image upload start*********************************/
		
		 if(!empty($_REQUEST['from']) && ($_REQUEST['from'] == 'ios')){
			if(isset($_REQUEST['user_img'])){
					$datetime = date("Y-m-d h:i:s");
					$timestamp = strtotime($datetime);						
					$image = $_REQUEST['user_img'];
					$imgdata = base64_decode($_REQUEST['user_img']);
					$f		 = finfo_open();
					$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
					$temp=explode('/',$mime_type);
					$path		= FCPATH."./uploads/images/users/$timestamp.$temp[1]";
					$fileName   = "$timestamp.$temp[1]";
					$success    = file_put_contents($path,base64_decode($image));
					if($success){
						$user_img =	$fileName;
					}else{
						$user_img = '';
					}
			}
		}else{
		   //upload image andorid
			if(isset($_FILES)){
				if($_FILES['user_img'] != ''){	
					$random_code=md5(uniqid(rand()));
					$image_path= $random_code.$_FILES ["user_img"] ["name"]; 
					$dir = FCPATH."./uploads/images/users/";
					move_uploaded_file($_FILES ["user_img"] ["tmp_name"], $dir.$image_path);
					//$path= "./uploads/images/users".'/'.$image_path;
					$path= $image_path;
					$user_img = $path;

				}else{
					$user_img = '';
				}
			}else{
				$user_img = '';
			}
		}
		/*************************pofile image upload end***********************************/
		// Get user profile data from input.
		// You can add whatever columns you need to customise user tables.
			$profile_data = array(
				'upro_first_name' => !empty($_REQUEST['firstname'])?$_REQUEST['firstname']:'',
				'upro_last_name' => !empty($_REQUEST['lastname'])?$_REQUEST['lastname']:'',
				'upro_country_id' => !empty($_REQUEST['country_id'])?$_REQUEST['country_id']:'',
				'upro_zone_id' => !empty($_REQUEST['state_id'])?$_REQUEST['state_id']:'',
				'upro_city_id' => !empty($_REQUEST['city_id'])?$_REQUEST['city_id']:'',
				'upro_address' => !empty($_REQUEST['address'])?$_REQUEST['address']:'',
				'upro_company' => !empty($_REQUEST['upro_company'])?$_REQUEST['upro_company']:'',
				'upro_company_address' => !empty($_REQUEST['upro_company_address'])?$_REQUEST['upro_company_address']:'',
				'upro_name' => $username,
				'upro_phone' => $phone,
				'upro_mob_imei'=>$imei_no,
				'upro_image' => $user_img,
                                'device_type'=>!empty($_REQUEST['from'])?$_REQUEST['from']:''
			);
			
						// print_r($user_img);
						 //print_r($profile_data);die("123");
			$this->load->library('flexi_auth');
			// Set whether to instantly activate account.
			// This var will be used twice, once for registration, then to check if to log the user in after registration.
			$instant_activate = false;
			// The last 2 variables on the register function are optional, these variables allow you to:
			// #1. Specify the group ID for the user to be added to (i.e. 'Moderator' / 'Public'), the default is set via the config file.
			// #2. Set whether to automatically activate the account upon registration, default is FALSE. 
			// Note: An account activation email will be automatically sent if auto activate is FALSE, or if an activation time limit is set by the config file.
			
			$default_group_id =  '1';
			
			$response = $this->flexi_auth->insert_user($email,$username, $password, $profile_data, $default_group_id, $instant_activate);
		   
			if ($response) {
				$this->flexi_auth->update_user($response, array('uacc_name' => $username));
				// This is an example 'Welcome' email that could be sent to a new user upon registration.
				// Bear in mind, if registration has been set to require the user activates their account, they will already be receiving an activation email.
				// Therefore sending an additional email welcoming the user may be deemed unnecessary.
				$email_data = array('email' => $email, 'username'=>$username );
				$this->flexi_auth->send_email($email, 'Welcome', 'registration_welcome.tpl.php', $email_data);
				// Note: The 'registration_welcome.tpl.php' template file is located in the '../views/includes/email/' directory defined by the config file.
				###+++++++++++++++++###
				// Save any public status or error messages (Whilst suppressing any admin messages) to CI's flash session data.
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

				// This is an example of how to log the user into their account immeadiately after registering.
				// This example would only be used if users do not have to authenticate their account via email upon registration.

				$this->load->model('flexi_auth_model');
				$result = $this->flexi_auth_model->get_users('*', "uacc_email='$email'");  // return sql object 
				$user = $result->row_array();   
				$name = (!empty($user['upro_first_name']) || !empty($user['upro_last_name']))? $user['upro_first_name'].' '.$user['upro_last_name']:'';
				// Register user Info 	
				$user = array(
					'register' => 1,
					'email' => $email,
					'error' => '',
					'profile' => array(
						'email' => $email,
						'phone' => $profile_data['upro_phone'],
						'name' => $name,
						'user_id' => $user['uacc_id'],
						'group_id' => $user['uacc_group_fk'],
						'group_name' => $user['ugrp_name'],
						'imei_no' =>$user['upro_mob_imei']
					),
					'message' => 'Register success',
					'error' => ''
				);
				return  json_encode($user);
			} else {
				// $error = array(
					// 'register' => 0,
					// 'email' => $email,
					// 'message' => 'Register fail',
					// 'profile' => '',
					// 'error' => strip_tags($this->flexi_auth->get_messages())
				// );
								$user = array(
					'register' => -1,
					'email' => $email,
					'message' => 'Register fail',
					'profile' => '',
					'error' => strip_tags($this->flexi_auth->get_messages())
				);
				return json_encode($user);
			}
		}
	}
	
	/**
	 * Authenticat User 
	 * Verify that a submitted password matches a user database record.
	 * This one is made for API
	 * Shakti Singh on 14-Jan-2016
	*/
	
	function _match_imei($imei_no,$identity){
		if(!empty($imei_no) && !empty($identity)){
			$sql = "select * from demo_user_profiles where upro_uacc_fk = (SELECT `uacc_id` FROM `user_accounts` WHERE `uacc_email` = '".$identity."') AND `upro_mob_imei` = '".$imei_no."'";
			
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return -2;
			}
		}else{
			return -1;
		}
	}
	
	function _userID_get($identity){
		if(!empty($identity)){
			$sql = "SELECT `uacc_id`,`uacc_email` FROM `user_accounts` WHERE `uacc_email` = '".$identity."'";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return -2;
			}
		}else{
			return -1;
		}
	}
	
	public function verify_user_login_new($identity,$verify_password,$imei_no){
		if (empty($identity) || empty($verify_password) ||  empty($imei_no))
		{
			return FALSE;
		}
		//print_r($identity);print_r($verify_password);print "<br/>";print_r($imei_no);die("  123300");
		if($this->verify_password($identity,$verify_password)){
			$checkIMEI = $this->_match_imei($imei_no,$identity);
			
			if(($checkIMEI > 0) && ($checkIMEI['upro_mob_imei'] == $imei_no)){
				$result =$this->get_users('*',"uacc_email='$identity'");  // return sql object 
				$user=$result->row_array();
				$user_fullName = $user['upro_first_name'].' '.$user['upro_last_name'];
				return $user = array('email'=>$identity,'user_fullName'=>$user_fullName, 'userID'=>$user['uacc_id'],'userGroupID'=>$user['uacc_group_fk'], 'userType'=>$user['ugrp_name'],
                                    'imei_no'=>$user['upro_mob_imei'],'authenticaton'=>1,'error'=>'');
			}else{
				
				$id = $this->_userID_get($identity);
				if(strtolower($id['uacc_email']) == strtolower($identity)){
					return $error = array('email'=>$identity,'authenticaton'=>0,'user_fullName'=>'', 'userID'=>$id['uacc_id'],'userGroupID'=>'', 'userType'=>'','imei_no'=>$imei_no,'error'=>"IMEI No. is Incorrect");	
			    }
			}
		}else{
			//return false;
		return $error = array('email'=>$identity,'authenticaton'=>0,'user_fullName'=>'', 'userID'=>'','userGroupID'=>'', 'userType'=>'','error'=>"Username or Password  is Incorrect");	
		}
	}
	
	public function verify_user_login($identity, $verify_password)
	{
		if (empty($identity) || empty($verify_password))
		{
			return FALSE;
		}
		
		if($this->verify_password($identity,$verify_password)){

			$result =$this->get_users('*',"uacc_email='$identity'");  // return sql object 
			$user=$result->row_array();
			$uacc_active = $user['uacc_active'];
			if( $uacc_active == 0 ){
			   return $error = array('email'=>$identity,'authenticaton'=>0,'user_fullName'=>'', 'userID'=>'','userGroupID'=>'', 'userType'=>'','error'=>"Please activate your account");	
			   exit;
			}
			$user_fullName = $user['upro_first_name'].' '.$user['upro_last_name'];
                        if($user['uacc_client_fk']){
                                    $client_id=$user['uacc_client_fk'];
                        }else if($user['uacc_client_fk']==0 && $user['uacc_group_fk']==3){
                                    $client_id=$user['uacc_id'];
                        }
                        $config= new stdClass();
                        if($client_id){
                            $config=$this->get_config($client_id);
                        }
                        #print_r($config); die; 
			return $user = array('email'=>$identity,'user_fullName'=>$user_fullName, 'userID'=>$user['uacc_id'],'userGroupID'=>$user['uacc_group_fk'], 'userType'=>$user['ugrp_name'],'authenticaton'=>1,'error'=>'','config'=>$config);
		}else{
			//return false;
		return $error = array('email'=>$identity,'authenticaton'=>0,'user_fullName'=>'', 'userID'=>'','userGroupID'=>'', 'userType'=>'','error'=>"Username or Password  is Incorrect");	
		}
	}
        
        
       function get_config($client_id){
            $config=array('client_id'=>$client_id);
            $this->db->select('customer_logo_path,customer_theme_color');
            $this->db->where('customer_uacc_fk',$client_id);
            $query=$this->db->get('ar_clients_2018');  
            #echo $this->db->last_query(); 
            if($query->num_rows()){
                $result=$query->row_array();
                $result['customer_logo_path']= str_replace("./",base_url(),$result['customer_logo_path']);
                if($result['customer_theme_color']){
                    $result['customer_theme_color_rgb']=hexdec($result['customer_theme_color']);  
                }
               return  array_merge($config,$result);  
            }
       }
        
	
	function get_totalCount($tableName,$type=''){
		if($type!=''){
			$this->db->select('id')->from('type_category')->where('type_category',17); 
			$q = $this->db->get(); 
			return $q->num_rows();
		}else{
			return $table_row_count = $this->db->count_all($tableName);
		}
	}

	function register_data_insert($data){
		if(!empty($data) && is_array($data)){
			$this->db->insert('register_data',$data);
			$result = $this->db->insert_id();
			if($result ){
				if( $data['master_data_id']){
					$todate = date("Y-m-d");
					$updateArray = array('date_of_first_use'=>$todate);
					$this->db->where('mdata_id', $data['master_data_id']);  
					$this->db->update('master_data', $updateArray); 

					$this->db->last_query(); 
				}
				return $result;
			}else{
				 return -1;
			}
		}  else{
			return -2;
		}  
	}
	
    public function frequency_month_mdata_dates($DatesArray){
		$threeDateArray					= array();
		$frequency_month				= $DatesArray['frequency_month'];
		
		$mdata_material_invoice_date	= ( ($DatesArray['mdata_material_invoice_date'] == '1970-01-01' ) || ($DatesArray['mdata_material_invoice_date'] == '0000-00-00' )  ) ? '' : $DatesArray['mdata_material_invoice_date'] ;

		$date_of_first_use				= ( ($DatesArray['date_of_first_use'] == '1970-01-01' ) || ($DatesArray['date_of_first_use'] == '0000-00-00' )  ) ? '' : $DatesArray['date_of_first_use'] ;
		$date_of_inspection				= ( ($DatesArray['date_of_inspection'] == '1970-01-01' ) || ($DatesArray['date_of_inspection'] == '0000-00-00' )  ) ? '' : $DatesArray['date_of_inspection'] ;
		$inspection_due_date			= ( ($DatesArray['inspection_due_date'] == '1970-01-01' ) || ($DatesArray['inspection_due_date'] == '0000-00-00' )  ) ? '' : $DatesArray['inspection_due_date'] ;
 
		if( $date_of_first_use == ''){  
			if( $mdata_material_invoice_date != ''  ){	
				  $firstMonthFrequency  = 1;
				  $effectiveDate = strtotime("+".$firstMonthFrequency." months", strtotime($mdata_material_invoice_date));
				  $date_of_first_use	= date('Y-m-d',$effectiveDate);					
			}
		}
		$threeDateArray['mdata_material_invoice_date']  = $mdata_material_invoice_date;
		$threeDateArray['date_of_first_use']			= $date_of_first_use;
		$threeDateArray['date_of_inspection']		    = $date_of_inspection;

		
		if( isset($DatesArray['frequency_month']) ){  
			if( ( $DatesArray['frequency_month'] > 0 ) && ( $date_of_inspection != '' ) ){    
				$frequency_month  = $DatesArray['frequency_month'];
				$effectiveDate = strtotime("+".$frequency_month." months", strtotime($date_of_inspection));
				$inspection_due_date	= date('Y-m-d',$effectiveDate);					
			}else if( ( $DatesArray['frequency_month'] > 0 ) && ( $date_of_first_use != '' ) ){   
				$frequency_month  = $DatesArray['frequency_month'];
				$effectiveDate = strtotime("+".$frequency_month." months", strtotime($date_of_first_use));
				$inspection_due_date	= date('Y-m-d',$effectiveDate);					
			}
			$threeDateArray['inspection_due_date']		   = $inspection_due_date;			
		}       
		
		return $threeDateArray;
    }	
	
}

/* End of file api_model.php */
/* Location: ./application/model/api_model.php */

