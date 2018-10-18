<?php
  class Base_Model extends CI_Model {
    public function __construct() {
      parent::__construct();
      $this->load->library('session');
    }

  /**
   * generate_token
   * Generates a random unhashed password / token / salt.
   * Includes a safe guard to ensure vowels are removed to avoid offensive words when used for password generation.
   * Additionally, 0, 1 removed to avoid confusion with o, i, l.
   *
   * @return string
   */
    public function generate_token($length = 8) 
    {
      $characters = '23456789BbCcDdFfGgHhJjKkMmNnPpQqRrSsTtVvWwXxYyZz';
      $count = mb_strlen($characters);

      for ($i = 0, $token = ''; $i < $length; $i++) 
      {
        $index = rand(0, $count - 1);
        $token .= mb_substr($characters, $index, 1);
      }
      return $token;
    }

  /**
   *
   * To store image
   * public
   * @param array $img
   * @return var $path Path of image
   *
   */
    public function fetch_image_path($img, $device, $upload_folder) {
      if(isset($device)){
        $img          		= base64_decode($img);       
        $fileName     		= uniqid();
        $folder_path  		= './uploads/'.$upload_folder.'/'; //path where image will be uploaded
        $folder_path_value 	= '/uploads/'.$upload_folder.'/'; //path to be saved in db
        $file         		= $folder_path . $fileName;
        $fileType     		= 'image/';
        $success      		= file_put_contents($file, $img);
        $path         		= $folder_path_value . $fileName;
      } 
      return $path;
    }
    //ENDS


    public function field_value_fetch($field, $col, $col_val, $tble_name) {
      //print_r($field);die();
      $this->db->select($field);
      $this->db->where($col, $col_val);
      $query = $this->db->get($tble_name);
      if($query->num_rows() > 0) {
        $data = $query->result_array();
        //print_r($this->db->last_query());die();
        //print_r($data['0'][$field]);die();
        return $data['0'][$field];
      } else {
        return  FALSE;
      }
    }

    public function field_value_fetch_multiple($field, $where1, $where_val1, $tble_name, $where2=NULL, $where_val2=NULL, $where3=NULL, $where_val3=NULL) {
      $this->db->select($field);
      $this->db->where($where1, $where_val1);
      if(!is_null($where2)) {
        $this->db->where($where2, $where_val2);
      }
      if(!is_null($where3)) {
        $this->db->where($where3, $where_val3);
      }
      $query = $this->db->get($tble_name);
      //print_r($this->db->last_query());die();
      $data = $query->result_array();
      return $data['0'][$field];
    }

    public function all_value_fetch($tble_name, $where_field, $where_value) {
      $this->db->where($where_field, $where_value);
      $query = $this->db->get($tble_name);
      $data = $query->result_array();
      return $data;
    }

    public function all_value_fetch_multiple($tble_name, $where1_field, $where1_value, $where2_field=NULL, $where2_value=NULL) {
      $this->db->where($where1_field, $where1_value);
      if($where2_field != NULL) {
        $this->db->where($where2_field, $where2_value);
      }
      $query = $this->db->get($tble_name);
      $data = $query->result_array();
      return $data;
    }

    public function all_value_fetch_sorted($tble_name, $where_field, $where_value, $order_field1, $order_value1, $order_field2=NULL, $order_value2=NULL) {
      $this->db->where($where_field, $where_value);
      $this->db->order_by($order_field1, $order_value1);
      if($order_field2 != NULL) {
        $this->db->order_by($order_field2, $order_value2);
      }
      $query = $this->db->get($tble_name);
      $data = $query->result_array();
      return $data;
    }

    

    /*Sort array based on key*/
    function array_msort($array, $cols) { //array_msort($data, array('product_name'=>SORT_ASC))
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    function insert_data($data, $tble_name) {
      $response = $this->db->insert($tble_name,$data);
      if($response) {
        $result = array(
			'id' =>$this->db->insert_id(), 
            'msg_code'  => 200
          );
      } else {
        $result = array(
            'msg_code'  => 404
          );
      }
      return $result;
    }



    function update_data($data, $tble_name, $where_field1, $where_value1, $where_field2=NULL, $where_value2=NULL, $where_field3=NULL, $where_value3=NULL) {
      $this->db->where($where_field1, $where_value1);
      if(!(is_null($where_field2))) {
        $this->db->where($where_field2, $where_value2);
      }
      if(!(is_null($where_field3))) {
        $this->db->where($where_field3, $where_value3);
      }
      $response = $this->db->update($tble_name,$data);
      if($response) {
        $result = array(
            'msg_code'  => 200
          );
      } else {
        $result = array(
            'msg_code'  => 404
          );
      }
      return $result;
    }


    function delete_data($tble_name, $where_field1, $where_value1, $where_field2=NULL, $where_value2=NULL, $where_field3=NULL, $where_value3=NULL) {
      $this->db->where($where_field1, $where_value1);
      if(!(is_null($where_field2))) {
        $this->db->where($where_field2, $where_value2);
      }
      if(!(is_null($where_field3))) {
        $this->db->where($where_field3, $where_value3);
      }
      $response = $this->db->delete($tble_name);
      if($response) {
        $result = array(
            'msg_code'  => 200
          );
      } else {
        $result = array(
            'msg_code'  => 404
          );
      }
      return $result;
    }

    public function data_exists_check($field_val, $field_name, $tbl_name) {
      $this->db->where($field_name, $field_val);
      $query = $this->db->get($tbl_name);
      if ($query->num_rows() < 1){
        $response_msg = array (
            'msg_code'  => 200
          );
        return $response_msg;
      }
      else{
          $response_msg = array (
            'msg'       => $field_val.' already exists',
            'msg_code'  => 404
          );
        return $response_msg;
      }
    }
    
    
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
    


  }

