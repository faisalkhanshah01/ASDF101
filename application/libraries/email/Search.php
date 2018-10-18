<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search {
    function __construct() { 
        $this->CI =& get_instance();
    }
	
 
	
	/////////////////////////////////////////////////////////
	//XXXXXXXXXXXXXXXXXXXXXX  Search_dashboard XXXXXXXXXXXXXXXX
	/////////////////////////////////////////////////////////
        
	
	function search_dashboard_old($param){
		$where = '';
		$where = "1 = 1";
		
		if(!empty($param['district'])){
			$where .= " AND D.client_district = '".$param['district']."'";
		}
		if(!empty($param['circle'])){
			$where .= " AND D.client_circle = '".$param['circle']."'";
		}
		if(!empty($param['client'])){
			$where .= " AND D.client_name = '".$param['client']."'";
		}
		if(!empty($param['asset_series'])){
			$where .= " AND A.asset_series = '".$param['asset_series']."'";
		}
		if(!empty($param['asset'])){
			$where .= " AND B.asset_name = '".$param['asset']."'";
		}
		if(!empty($param['invoice'])){
			$where .= " AND C.mdata_material_invoice = '".$param['invoice']."'";
		}
		
		$this->CI->load->model('search_model');
		$result = $this->CI->search_model->fetch_search_data($where);
		if($result > 0){
			$c = 0;
			foreach($result as $key => $value){
				$temp['search'][$c]['asset_series'] = $value['asset_series'];
				$temp['search'][$c]['asset'] = $value['asset_name'];
				$temp['search'][$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
				$temp['search'][$c]['report_no'] = $value['report_no'];
				$temp['search'][$c]['site_id'] = $value['site_id'];
				$temp['search'][$c]['job_card'] = $value['job_card'];
				$temp['search'][$c]['sms'] = $value['sms'];
				$temp['search'][$c]['client_name'] = $value['client_name'];
				$temp['search'][$c]['status'] = $value['approved_status'];
				$temp['search'][$c]['time'] = date("Y-m-d",strtotime($value['created_date']));
				$c++;
			}
			
			return $temp;
		}else{
			return -1;
		}
	}
	
	function search_dashboard($temp){
		$where = '';
		$where = "1 = 1";
		$param =array();
		if(!empty($temp['district'])){
			$where .= " AND D.client_district = '".$temp['district']."'";
			$param['client_district'] = $temp['district'];
		}
		if(!empty($temp['circle'])){
			$where .= " AND D.client_circle = '".$temp['circle']."'";
			$param['client_circle'] = $temp['circle'];
		}
		if(!empty($temp['client'])){
			$where .= " AND D.client_name = '".$temp['client']."'";
			$param['client_name'] = $temp['client'];
		}
		if(!empty($temp['asset_series'])){
			$where .= " AND A.asset_series = '".$temp['asset_series']."'";
			$param['asset_series'] = $temp['asset_series'];
		}
		if(!empty($temp['asset'])){
			$where .= " AND B.asset_name = '".$temp['asset']."'";
			$param['asset_name'] = $temp['asset'];
		}
		if(!empty($temp['invoice'])){
			$where .= " AND C.mdata_material_invoice = '".$temp['invoice']."'";
			$param['mdata_material_invoice'] = $temp['invoice'];
		}
		
		/*****************************************/
			$user_id	= $this->CI->session->flexi_auth['id'];
			$group_array 		= $this->CI->session->flexi_auth['group'];
			foreach($group_array as $key=>$val){
				$data1['group_id'] = $_SESSION['user_group'] = $group_id = $key;
				$data1['group_name'] = $group_name = $val;
			}
			
		/*****************************************/
		
		$this->CI->load->model('search_model');
		$this->CI->load->model('Siteid_model');
		//$result = $this->CI->search_model->fetch_search_data($where);
		if(!empty($user_id) && !empty($data1['group_id']) && ($data1['group_id'] == 9)){
			if(!empty($temp['asset'])){
				$where .= " AND A.inspected_by = '".$user_id."'";
				$result1 = $this->CI->search_model->fetch_search_data($where);
			}else{
				$where .= " AND A.inspected_by = '".$user_id."'";
				$result1 = $this->CI->search_model->fetch_search_inspector($where);
				if(is_array($result1)){
					foreach($result1 as $key => $value){
						$asset_series = $this->CI->search_model->fetch_asset_name_inspector($value['id']);
						$result1[$key]['asset_name'] = $asset_series['asset_name'];
					}
				}
			}
			
			/****************************site table search**************************************/
				$param['group_id'] = $data1['group_id'];
				$param['user_id'] = $user_id;
				
				$siteData = $this->CI->search_model->get_siteid_list_of_inspector($param);
			/***********************************************************************/
			
			/****************************assign_client_data table search**************************************/
				$param['group_id'] = $data1['group_id'];
				$param['user_id'] = $user_id;
				$assignClient = $this->CI->search_model->get_assign_client($param);
			
			/***********************************************************************/
			
			$sitedataCount = ($siteData > 0)?$siteData:'';
			$resultCount = ($result1 > 0)?$result1:'';
			$assign_client =($assignClient > 0)?$assignClient:'';
			
			if(!empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$siteResult = array_merge($sitedataCount,$resultCount);
				$result = array_merge($siteResult,$assign_client);
			}elseif(!empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$result = array_merge($sitedataCount,$resultCount);
			}elseif(empty($sitedataCount) && !empty($resultCount) && !empty($assign_client)){
				$result = array_merge($resultCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$result = array_merge($sitedataCount,$assign_client);
			}elseif(!empty($sitedataCount) && empty($resultCount) && empty($assign_client)){
				$result = $sitedataCount;
			}elseif(empty($sitedataCount) && !empty($resultCount) && empty($assign_client)){
				$result = $resultCount;
			}elseif(empty($sitedataCount) && empty($resultCount) && !empty($assign_client)){
				$result = $assign_client;
			}else{
				$result = '';
			}
			
		}else{
			if(!empty($temp['asset'])){
				$result = $this->CI->search_model->fetch_search_data($where);
			}else{
				$result = $this->CI->search_model->fetch_search_inspector($where);
				if(is_array($result)){
					foreach($result as $key => $value){
						$asset_series = $this->CI->search_model->fetch_asset_name_inspector($value['id']);
						$result[$key]['asset_name'] = $asset_series['asset_name'];
					}
				}
			}
		}
		
		if($result > 0){
			$c = 0;
			foreach($result as $key => $value){
				$temp1[$c]['asset_series'] = $value['asset_series'];
				$temp1[$c]['asset'] = !empty($value['asset_name'])?$value['asset_name']:$value['asset'];
				$temp1[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
				$temp1[$c]['report_no'] = $value['report_no'];
				$temp1[$c]['site_id'] = $value['site_id'];
				$temp1[$c]['job_card'] = $value['job_card'];
				$temp1[$c]['sms'] = $value['sms'];
				$temp1[$c]['client_name'] = $value['client_name'];
				
				if($data1['group_id'] == 9){
					$temp1[$c]['status'] = 'Pending';
					$temp1[$c]['time'] = $value['time'];
				}else{
					$temp1[$c]['status'] = $value['approved_status'];
					$temp1[$c]['time'] = strtotime($value['created_date']);
				}	
				$c++;
			}
			
			//print_r($temp1);die;
			$search_data = $this->search_time($temp1,$temp['startTime'],$temp['endTime']);
			return $search_data;
		}else{
			return -1;
		}
	}
	
	
	//$startTime >= '".$startTime."' AND $endTime <= '".$endTime."'"
	function search_time($searchData ='' ,$startTime = '', $endTime =''){
		if(!empty($searchData) && is_array($searchData)){
			$c1 = 0;
			$startTime = strtotime($startTime);
			$endTime  = strtotime($endTime);
			foreach($searchData as $key => $value){
				if(!empty($startTime) && !empty($endTime) && ($value['time'] >= $startTime) && ($value['time'] <= $endTime)){
					$data[$c1] = $value;
					$data[$c1]['time'] = date("d-m-Y",$value['time']);
				}elseif(!empty($startTime) && empty($endTime) && ($value['time'] >= $startTime)){
					$data[$c1] = $value;
					$data[$c1]['time'] = date("d-m-Y",$value['time']);
				}elseif(empty($startTime) && empty($endTime)){
					$data[$c1] = $value;
					$data[$c1]['time'] = date("d-m-Y",$value['time']);
				}
				$c1++;
			}	
			
			if(count($data) > 0){
				if(is_array($data)){
					$c1= 0;
					foreach($data as $k =>$v){
						$searchdata[$c1] = $v;
						$c1++;
					}
				}
				return $searchdata;
			}else{
				return -1;
			}
			
		}
	}
	function search_time_old($data ='' ,$startTime = '', $endTime =''){
		if(!empty($data) && is_array($data)){
			foreach($data as $key => $value){
				if(!empty($startTime) && !empty($endTime) && ($value['time'] >= $startTime) && ($value['time'] <= $endTime)){
					$temp['search'][$key] = $value;
				}elseif(!empty($startTime) && empty($endTime) && ($value['time'] >= $startTime)){
					$temp['search'][$key] = $value;
				}elseif(empty($startTime) && empty($endTime)){
					$temp['search'][$key] = $value;
				}
			}	
			
			if(!empty($temp['search']) && is_array($temp['search'])){
				return $temp['search'];
			}else{
				return -3;
			}
			
		}
	}
	
}
?>