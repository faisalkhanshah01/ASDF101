<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class M_api_model extends CI_Model{
		
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		function asset_search($temp){
			$this->db->select('t1.*');
			$this->db->from('master_data as t1');
			if(!empty($temp['mdata_uin'])){
				$this->db->where('t1.mdata_uin', $temp['mdata_uin']);
			}
			if(!empty($temp['mdata_barcode'])){
				$this->db->where('t1.mdata_barcode', $temp['mdata_barcode']);
			}
			if(!empty($temp['mdata_rfid'])){
				$this->db->where('t1.mdata_rfid', $temp['mdata_rfid']);
			}
			
			//$this->db->where('t1.status', 'Active');
			$res = $this->db->get();
			if ($res->num_rows() > 0) {
				$return = $res->row_array();
			} else {
				$return = 0;
			}
			return $return;
		}
		
		function get_siteid_inspector($temp){
			if(!empty($temp) && is_array($temp)){
				$checkAsset = $this->asset_search($temp);
				
				if(empty($checkAsset['mdata_jobcard']) && empty($checkAsset['mdata_sms'])){
					$param[0]['mdata_dealer'] = $checkAsset['mdata_dealer'];
					$param[0]['mdata_rfid'] = $checkAsset['mdata_rfid'];
					$param[0]['mdata_barcode'] = $checkAsset['mdata_barcode'];
					$param[0]['mdata_uin'] = $checkAsset['mdata_uin'];
					$param[0]['mdata_client'] = $checkAsset['mdata_client'];
					$param[0]['mdata_po'] = $checkAsset['mdata_po'];
					$param[0]['mdata_item_series'] =!empty($checkAsset['mdata_item_series'])?$checkAsset['mdata_item_series']:$checkAsset['mdata_asset'];
					$param[0][siteID_id] = '';
					$param[0][site_jobcard] ='';
					$param[0][site_sms] ='';
					$param[0][site_id] ='';
					$param[0][site_location] ='';
					$param[0][site_city] ='';
					$param[0][site_address] ='';
					$param[0][site_lattitude] ='';
					$param[0][site_longitude] ='';
					$param[0][site_contact_name] ='';
					$param[0][site_contact_number] ='';
					$param[0][site_contact_email] ='';
					$param[0][status] = $checkAsset['status'];
					$param[0][created_date] = $checkAsset['created_date'];
					$param[0][master_id] = $checkAsset['mdata_id'];
					return $param;
				}else{
					
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
			
		}
		
		function inspector($siteID_id){
			$query = "SELECT inspector_ids, site_id FROM inspector_data WHERE site_id like '%".$siteID_id."%'";
			$res = $this->db->query($query);
			if($res->num_rows()>0){
				$return = $res->result_array();
				if(!empty($return) && is_array($return)){
					$mergeSiteID = array();
					$$mergeinspector_ids = array();
					$c = 0;
					foreach($return as $key => $value){
						$siteID = json_decode($value['site_id'],true);
						$mergeSiteID = array_merge($mergeSiteID,$siteID);
						$inspector_ids = json_decode($value['inspector_ids'],true);
						$mergeinspector_ids = array_merge($mergeinspector_ids,$inspector_ids);
						$c++;
					}
					
				}
				return $mergeinspector_ids;
			} else {
				$return = -2;
			}
			return $return;
		}




		function get_register_data_num($chk_site_id='',$chk_master_data_id=''){
			$this->db->select("id ");
			$this->db->from('register_data');
			
			if($chk_site_id !='' ){
					$this->db->where('site_id',$chk_site_id);
			}
			if($chk_master_data_id !='' ){
					$this->db->where('master_data_id',$chk_master_data_id);
			}
			$query = $this->db->get();
			
			$numrows   = $query->num_rows();
			return $numrows;
		}


		function get_register_user_data($chk_user_id=''){	
            if($chk_user_id !='' ){
				$query = "SELECT id, user_id, site_id, master_data_id, scheduled_date FROM register_data WHERE user_id = '".$chk_user_id."' ORDER BY id DESC ";
				$res = $this->db->query($query);
				if($res->num_rows()>0){
					$return = $res->result_array();					
					return $return;
				} else {
					return false;
				}
			}

		}

		function get_data_register($data){	
		$this->load->model('kare_model');
		$this->load->model('Siteid_model');
		$checkAsset = $this->kare_model->get_mdata_item($data['master_data_id']);
          if( $checkAsset ){  	
			
			if($data['site_id'] !=''){
				    $site_data=$this->Siteid_model->get_siteID_item($data['site_id']);
				    $site_data['mdata_dealer'] = $checkAsset['mdata_dealer'];
					$site_data['mdata_rfid'] = $checkAsset['mdata_rfid'];
					$site_data['mdata_barcode'] = $checkAsset['mdata_barcode'];
					$site_data['mdata_uin'] = $checkAsset['mdata_uin'];
					$site_data['mdata_client'] = $checkAsset['mdata_client'];
					$site_data['mdata_po'] = $checkAsset['mdata_po'];
					$site_data['mdata_item_series'] =!empty($checkAsset['mdata_item_series'])?$checkAsset['mdata_item_series']:$checkAsset['mdata_asset'];
					$site_data['status'] = $checkAsset['status'];
					$site_data['created_date'] = $checkAsset['created_date'];
					$site_data['master_id'] = $checkAsset['mdata_id'];	
					return $site_data;
				}else{
					$param['mdata_dealer'] = $checkAsset['mdata_dealer'];
					$param['mdata_rfid'] = $checkAsset['mdata_rfid'];
					$param['mdata_barcode'] = $checkAsset['mdata_barcode'];
					$param['mdata_uin'] = $checkAsset['mdata_uin'];
					$param['mdata_client'] = $checkAsset['mdata_client'];
					$param['mdata_po'] = $checkAsset['mdata_po'];
					$param['mdata_item_series'] =!empty($checkAsset['mdata_item_series'])?$checkAsset['mdata_item_series']:$checkAsset['mdata_asset'];
					$param['siteID_id'] = '';
					$param['site_jobcard'] ='';
					$param['site_sms'] ='';
					$param['site_id'] ='';
					$param['site_location'] ='';
					$param['site_city'] ='';
					$param['site_address'] ='';
					$param['site_lattitude'] ='';
					$param['site_longitude'] ='';
					$param['site_contact_name'] ='';
					$param['site_contact_number'] ='';
					$param['site_contact_email'] ='';
					$param['status'] = $checkAsset['status'];
					$param['created_date'] = $checkAsset['created_date'];
					$param['master_id'] = $checkAsset['mdata_id'];					
					return $param;
				}	

		  }else{
		    return false;
		  }
				
		}
			
		function get_register_data_rows($fildArry){
			$this->db->select('id, user_id, group_id, site_id, master_data_id');
			if(!empty($fildArry['user_id'])){
			 $this->db->where('user_id',$fildArry['user_id']);
			}
			if(!empty($fildArry['site_id'])){
			 $this->db->where('site_id',$fildArry['site_id']);
			}
			if(!empty($fildArry['master_data_id'])){
			 $this->db->where('master_data_id',$fildArry['master_data_id']);
			}
			$result=$this->db->get('register_data');
			if($result->num_rows()>0){
				$inspection_type=$result->result_array();
				$insdatas = array();
				foreach($inspection_type as $resVal){
					$insdatas[] = $resVal;
				}
				return $insdatas;
			}else{
				return false;
			}
		}

		function del_register_data($fildArry){			
			$chkParaFlag = 0;
			if(!empty($fildArry['user_id'])){
             $chkParaFlag = 1;
			 $this->db->where('user_id',$fildArry['user_id']);
			}
			if(!empty($fildArry['site_id'])){
			 $chkParaFlag = 1;
			 $this->db->where('site_id',$fildArry['site_id']);
			}
			if(!empty($fildArry['master_data_id'])){
             $chkParaFlag = 1;
			 $this->db->where('master_data_id',$fildArry['master_data_id']);
			}
            if( $chkParaFlag == 1){
				$this->db->delete('register_data');
				if ($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}else{	
				return false;
			}
		}
		
		function get_chk_register_data_rows($fildArry){
			$this->db->select('id, user_id, group_id, site_id, master_data_id');
			$whArray  = array();
			if( (!empty($fildArry['master_data_id'])) && ( !empty($fildArry['site_id']) ) ){
			  $whArray  = array('master_data_id'=>$fildArry['master_data_id'], 'site_id' => $fildArry['site_id'] );
			}
			if(!empty($fildArry['site_id'])){
			  $whArray  = array( 'site_id' => $fildArry['site_id'] );
			}
			if(!empty($fildArry['master_data_id'])){
			  $whArray  = array( 'master_data_id' => $fildArry['master_data_id'] );
			}
			$this->db->or_where($whArray); 
			$result=$this->db->get('register_data');
				if($result->num_rows()>0){
				$inspection_type=$result->result_array();
				$insdatas = array();
				foreach($inspection_type as $resVal){
					$insdatas[] = $resVal;
				}
				return $insdatas;
			}else{
				return false;
			}
		}





		function get_assetseries_masterid($temp){
			if(!empty($temp) && is_array($temp)){
				$checkAsset = $this->asset_search($temp);				
				if(empty($checkAsset['mdata_jobcard']) && empty($checkAsset['mdata_sms'])){					
					$param[0]['mdata_item_series'] =!empty($checkAsset['mdata_item_series'])?$checkAsset['mdata_item_series']:""; 
					$param[0]['mdata_asset'] =!empty($checkAsset['mdata_asset'])?$checkAsset['mdata_asset']:"";					
					$param[0][master_id] = $checkAsset['mdata_id'];
					return $param;
				}else{
					
					$this->db->select('t1.mdata_id, t1.mdata_item_series,t1.mdata_asset,t2.*');
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
			
		}
			
		
	}	