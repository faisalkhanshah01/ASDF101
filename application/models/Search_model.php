<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*  	Author 	: Shakti Singh
*	Email 	: shakti.singh@flashonmind.com
*/
class Search_model extends CI_Model{
	
	function __construct(){
	    parent::__construct();
		$this->load->database();
	}
	 
	function get_client_dealer_detail_list($client_id= '')
	{
		if($client_id == '')
		{
			$this->db->select("client_id,client_name,client_type");
			$this->db->from('clients');	
			$this->db->where('client_status','active');
			$query = $this->db->get();		
			return $query->result_array();		
		}
		else
		{
			$this->db->select("client_id,client_name,client_type");
			$this->db->from('clients');	
			$this->db->where('client_id',$client_id);
			$query = $this->db->get();		
			return $query->result_array();
		}
	}
	
	function get_district_circle_detail_list()
	{
		$this->db->select("client_id,client_circle,client_district");
		$this->db->from('clients');	
		$this->db->where('client_status','active');
		$query = $this->db->get();		
		return $query->result_array();		
		
	}
	
	/* Search Sub-Asset Data */
	function search_sub_asset_data($query=''){
		if($query!=''){
			$this->db->select("sub_assets_code");
			$this->db->from('sub_assets');
			$this->db->like('sub_assets_code', $query);
		}else{
			$this->db->select("sub_assets_code");
			$this->db->from('sub_assets');
		}
		$query_result = $this->db->get();
		$sub_assets = ($query_result->num_rows())? $query_result->result_array() : '';
		return $sub_assets;
	}
	
	/* Search Asset Data */
	function search_asset_data($query = ''){
		if($query!=''){
			$this->db->select("component_code");
			$this->db->from('components');
			$this->db->like('component_code', $query);
		}else{
			$this->db->select("component_code");
			$this->db->from('components');
			$this->db->where('status','Active');
		}
		$query_result = $this->db->get();
		$assets = ($query_result->num_rows())? $query_result->result_array() : '';
		return $assets;
	}
	
	/* Search Asset Series Data */
	function search_asset_series_data($query = ''){
		if($query!=''){
			$this->db->select("product_code");
			$this->db->from('products');
			$this->db->like('product_code', $query);
		}else{
			$this->db->select("product_code");
			$this->db->from('products');
			$this->db->where('status','Active');
		}
		$query_result = $this->db->get();
		$asset_series = ($query_result->num_rows())? $query_result->result_array() : '';
		return $asset_series;
	}
	
	function search_invoice(){
		$this->db->select("*");
		$this->db->from('master_data');
		$this->db->where('status','Active');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return -2;
		}
	}
	
	function fetch_district_circle_detail_list(){
		$this->db->select("*");
		$this->db->from('clients');	
		$this->db->where('client_status','active');
		$query = $this->db->get();		
		return $query->result_array();		
		
	}
	
	function get_assign_client($param){
		$sql = "SELECT * FROM assign_client_data WHERE client_ids like '%".$param['user_id']."%'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$result = $query->result_array();
			$siteID = array();
			foreach($result as $key => $value){
				if($param['group_id'] == $value['client_type']){
					$site_id = json_decode($value['site_id'],true);
					$siteID = array_merge($site_id,$siteID);
				}	
			}
			
			$siteID= array_unique($siteID);
			if(!empty($siteID) && is_array($siteID)){
				$where = '';
				$where = "1 = 1";
				if(!empty($param['client_name'])){
					$where .= " AND C.client_name = '".$param['client_name']."'";
				}
				if(!empty($param['client_circle'])){
					$where .= " AND A.site_location = '".$param['client_circle']."'";
				}	
				
				if(!empty($param['client_district'])){
					$where .= " AND A.site_city = '".$param['client_district']."'";
				}
				if(!empty($param['client_circle'])){
					$where .= " AND B.mdata_material_invoice = '".$param['mdata_material_invoice']."'";
				}
				
				$row = array();
				foreach($siteID as $key => $Val){
					if(!empty($param['asset_name']) || !empty($param['asset_series'])){
						$result = $this->get_siteID_item(" AND A.siteID_id = '".$Val."'",$where,$param);
					}else{
						$result = $this->get_siteID_item(" AND A.siteID_id = '".$Val."'",$where,'');
					}	
					if($result > 0){
						$row[$key] = $result;
					}	
				}
				
				if(!empty($row) && is_array($row)){
					$temp = array();
					$c = 0;
					foreach ($row as $kay => $value) {
						if(!empty($value['asset']) && !empty($value['asset_series'])){
							if(!empty($param['asset_series'])){
								if($param['asset_series'] == $value['asset_series']){
									$temp[$c]['asset_series'] = $value['asset_series'];
									$temp[$c]['asset'] = $value['asset'];
									$temp[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
									$temp[$c]['report_no'] = !empty($value['report_no'])?$value['report_no']:'NA';
									$temp[$c]['site_id'] = $value['site_id'];
									$temp[$c]['job_card'] = $value['mdata_jobcard'];
									$temp[$c]['sms'] = $value['mdata_sms'];
									$temp[$c]['client_name'] = $value['client_name'];
									$temp[$c]['status'] = 'Pending';
									$temp[$c]['time'] = $value['created_date'];
								}	
							}else{
								$temp[$c]['asset_series'] = $value['asset_series'];
								$temp[$c]['asset'] = $value['asset'];
								$temp[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
								$temp[$c]['report_no'] = !empty($value['report_no'])?$value['report_no']:'NA';
								$temp[$c]['site_id'] = $value['site_id'];
								$temp[$c]['job_card'] = $value['mdata_jobcard'];
								$temp[$c]['sms'] = $value['mdata_sms'];
								$temp[$c]['client_name'] = $value['client_name'];
								$temp[$c]['status'] = 'Pending';
								$temp[$c]['time'] = $value['created_date'];
							}	
						}	
						$c++;
					}
				}
				//print_r($temp);die('123');
				return $temp;
				
			}else{
				return -2;
			}	
			
		}else{
			return -1;
		}	
	}
	
	function get_asset_from_assetSeriesCode($assetId,$jobCard,$sms){
		$this->load->model('kare_model');
		$asset=$this->kare_model->get_asset_from_assetSeriesCode($assetId);
		//print_r($assetId);die("123");
		if($asset){
			$param = array();
			if($asset['product_components'] != ""){
				$newData = array();
				$component_array =	json_decode($asset['product_components'],true);
					//print_r($component_array);die;
				foreach($component_array as $qKey=>$qVal){
					$this->load->model('Form_model');
					$get_asset_uom 	= $this->Form_model->get_asset_values($qVal);
					$asset_quantity = $this->kare_model->get_asset_quantity($qVal,$assetId,$jobCard,$sms);
					if(is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal;
					}else if(!is_array($asset_quantity) && is_array($get_asset_uom)){
						$newData[$qKey] = $qVal;
					}else if(is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal;
					}else if(!is_array($asset_quantity) && !is_array($get_asset_uom)){
						$newData[$qKey] = $qVal;
					}
				}
				return $newData;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
	}
	
	
	
	function get_siteID_item($wheresiteID_id,$where,$param=''){ 
		$sql = "SELECT A.*,B.*,C.client_name
		FROM siteID_data AS A
		LEFT JOIN master_data as B
		ON A.master_id = B.mdata_id 
		LEFT JOIN clients as C
		ON B.mdata_client = C.client_name OR B.mdata_client = C.client_id 
		where ".$where.''.$wheresiteID_id." ORDER BY A.created_date DESC";
		//print_r($sql);die;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$res = $query->row_array();
			
			/**************************************************/
				$asset_image = '0'; $item_arrays ='0';
				if($res['mdata_item_series'] != null && $res['mdata_item_series'] != 'null' && $res['mdata_item_series'] != ''){
					
					$counter = 0;
					$item_array = json_decode($res['mdata_item_series'],true);
					$res['asset_series'] = !empty($item_array)?$item_array[0]:'';
					$count = count($item_array);
					if($count > 1){
						$item_arrays = implode('##',$item_array);
						$res['mdata_item_series'] = (($item_arrays == null) ||  ($item_arrays == NULL))?'':$item_arrays;
						$res['assetCodes'] = '';
					}else{
						$assetCodes = $this->get_asset_from_assetSeriesCode($item_array[0],$res['mdata_jobcard'],$res['mdata_sms']);
						if(!empty($param['asset_name'])){
							if(!empty($assetCodes) && is_array($assetCodes)){
								$assetNameKey = array_search($param['asset_name'],$assetCodes);
								foreach($assetCodes as $key =>$value){
									if($param['asset_name'] == $value){
										$assetName[$key] = $value;
									}
								}
								
								$res['asset'] = !empty($assetName[$assetNameKey])?$assetName[$assetNameKey]:'';
							}
						}else{
							$res['asset'] = $assetCodes[0];
						}	
						$item_arrays = $item_array[0];
						$res['mdata_item_series'] = (($item_arrays == null) ||  ($item_arrays == NULL))?'':$item_arrays;
					}
				}
			/**************************************************/
			return $res;
		} else {
			return -1;
		}
	}				
					
	function get_siteid_list_of_inspector($param){
		$query = $this->db->query("SELECT inspector_ids, site_id FROM inspector_data WHERE inspector_ids like '%".$param['user_id']."%' ");
		if($query->num_rows()>0){
			$res = $query->result_array();
			$site_ids = array();
			foreach($res as $key=>$val){
				$array_ins_id = json_decode($val['inspector_ids'],true);
				if(in_array($param['user_id'],$array_ins_id)){
					$site_ids_arr = json_decode($val['site_id'],true);
					foreach($site_ids_arr as $sVal){
						$site_ids[] =  $sVal;
					}
				}
			}
			
			if(!empty($site_ids) && is_array($site_ids)){
				$where = '';
				$where = "1 = 1";
				if(!empty($param['client_name'])){
					$where .= " AND C.client_name = '".$param['client_name']."'";
				}
				if(!empty($param['client_circle'])){
					$where .= " AND A.site_location = '".$param['client_circle']."'";
				}	
				
				if(!empty($param['client_district'])){
					$where .= " AND A.site_city = '".$param['client_district']."'";
				}
				if(!empty($param['client_circle'])){
					$where .= " AND B.mdata_material_invoice = '".$param['mdata_material_invoice']."'";
				}				
				$row = array();
				$all_siteID = array_unique($site_ids);
				foreach($all_siteID as $key => $Val){
					if(!empty($param['asset_name']) || !empty($param['asset_series'])){
						$result = $this->get_siteID_item(" AND A.siteID_id = '".$Val."'",$where,$param);
					}else{
						$result = $this->get_siteID_item(" AND A.siteID_id = '".$Val."'",$where,'');
					}	
					if($result > 0){
						$row[$key] = $result;
					}	
				}
				
				if(!empty($row) && is_array($row)){
					$temp = array();
					$c = 0;
					foreach ($row as $kay => $value) {
						if(!empty($value['asset']) && !empty($value['asset_series'])){
							if(!empty($param['asset_series'])){
								if($param['asset_series'] == $value['asset_series']){
									$temp[$c]['asset_series'] = $value['asset_series'];
									$temp[$c]['asset'] = $value['asset'];
									$temp[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
									$temp[$c]['report_no'] = !empty($value['report_no'])?$value['report_no']:'NA';
									$temp[$c]['site_id'] = $value['site_id'];
									$temp[$c]['job_card'] = $value['mdata_jobcard'];
									$temp[$c]['sms'] = $value['mdata_sms'];
									$temp[$c]['client_name'] = $value['client_name'];
									$temp[$c]['status'] = 'Pending';
									$temp[$c]['time'] = $value['created_date'];
								}	
							}else{
								$temp[$c]['asset_series'] = $value['asset_series'];
								$temp[$c]['asset'] = $value['asset'];
								$temp[$c]['invoice'] = !empty($value['mdata_material_invoice'])?$value['mdata_material_invoice']:'NA';
								$temp[$c]['report_no'] = !empty($value['report_no'])?$value['report_no']:'NA';
								$temp[$c]['site_id'] = $value['site_id'];
								$temp[$c]['job_card'] = $value['mdata_jobcard'];
								$temp[$c]['sms'] = $value['mdata_sms'];
								$temp[$c]['client_name'] = $value['client_name'];
								$temp[$c]['status'] = 'Pending';
								$temp[$c]['time'] = $value['created_date'];
							}	
						}	
						$c++;
					}
				}
				//print_r($temp);die('123');
				return $temp;
			}else{
				return -2;
			}
			
		}else{
			return -1;
		}	
	}			
	
	function fetch_search_data($where){
		if(!empty($where)){
			$sql = "SELECT A.*,B.asset_name,C.mdata_material_invoice,D.client_name
						FROM inspection_list_1 AS A
						LEFT JOIN inspection_list_2 AS B
						ON A.id = B.inspection_list_id
						LEFT JOIN master_data as C
						ON A.job_card = C.mdata_jobcard AND A.sms = C.mdata_sms
						LEFT JOIN clients as D
						ON C.mdata_client = D.client_name OR C.mdata_client = D.client_id where ".$where." Group By A.wpermit_id ORDER BY A.created_date DESC";
						//print_r($sql);die;
			$query = $this->db->query($sql);
			if($query->num_rows()>0){
		    	$res = $query->result_array();
                return $res;
		    } else {
		    	return -1;
		    }
		}else{
			return -2;
		}
	}
	
	
	function fetch_search_inspector($where){
		if(!empty($where)){
			$sql = "SELECT A.*,C.mdata_material_invoice,D.client_name
						FROM inspection_list_1 AS A
						LEFT JOIN master_data as C
						ON A.job_card = C.mdata_jobcard AND A.sms = C.mdata_sms
						LEFT JOIN clients as D
						ON C.mdata_client = D.client_name OR C.mdata_client = D.client_id where ".$where." Group By A.wpermit_id ORDER BY A.created_date DESC";
						//print_r($sql);die;
			$query = $this->db->query($sql);
			
			//$query->result_array();print_r($query->result_array());die('123');
			if($query->num_rows()>0){
		    	$res = $query->result_array();
                return $res;
		    } else {
		    	return -1;
		    }
		}else{
			return -2;
		}
	}
	
	function fetch_asset_name_inspector($id){
		$this->db->select("asset_name");
		$this->db->from('inspection_list_2');
		$this->db->where('inspection_list_id',$id);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$res = $query->row_array();
			return $res;
		} else {
			return -1;
		}
		
	}
	/* search Invoice data */
	
	function search_invoice_data($query = '')
	{
		if($query!=''){
			$this->db->select("mdata_material_invoice");
			$this->db->from('master_data');
			$this->db->like('mdata_material_invoice', $query);
		}else{
			$this->db->select("mdata_material_invoice");
			$this->db->from('master_data');
			$this->db->where('status','Active');
			
		}
		$query_result = $this->db->get();
		$asset_series = ($query_result->num_rows())? $query_result->result_array() : '';
		return $asset_series;
	}

	function search_client($client= ''){
		
		if (is_numeric($client)){
			$client_details = $this->get_client_dealer_detail_list($client_id);
						
			if(!empty($client_details)){
					foreach($client_details as $client_detail)
					{
						$client_name = $client_detail['client_name'];
					}
			}
			else{
				$client_name = '<span style="color:red">Nill</span>';
			}
		}
		else{
			$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
			$this->db->from('master_data');
			$this->db->where('mdata_client',$client);
			
			$query = $this->db->get();		
			$result = $query->result_array();
			
			if(!empty($result))
			{
				foreach($result as $val)
				{
					$client_id 			= $val['mdata_client'];
					//$item_series 		= $val['mdata_item_series'];
					$material_invoice	= $val['mdata_material_invoice'];
					$qty				= $val['mdata_qty'];

					if((is_numeric($client)) && ($client_id != '')){
						$client_details = $this->get_client_dealer_detail_list($client_id);
						
						if(!empty($client_details)){
								foreach($client_details as $client_detail)
								{
									$client_name = $client_detail['client_name'];
								}
						}
						else{
							$client_name = '<span style="color:red">Nill</span>';
						}
					}
					else{
						$client_name = $client_id;
					}
					
					$item_array 	= json_decode($val['mdata_item_series']);			
						
					foreach($item_array as $item_series)
					{
						if($item_series!=''){
							$this->db->select("product_code,product_components");
							$this->db->from('products');
							$this->db->where('product_code',$item_series);
							$query = $this->db->get();		
							$result1 = $query->result_array();
							foreach($result1 as $val){
								$asset_array	= json_decode($val['product_components']);
								$asset = implode(',',$asset_array);		
							}	
						}
					}
				}
					
				$data= array();
				//$data['dealer_name'] = $dealer_id;
				$data['client_name'] = $client_name;
				$data['invoice'] = $material_invoice;
				$data['asset_series'] = $item_series;
				$data['asset'] = $asset;
				$data['qty'] = $qty;
				
				return $data;
			}
			else{
				$this->session->set_flashdata('msg','Given data did not matched with database');
				redirect(base_url('auth_admin/dashboard'));
			}
		}
	}
	
	function search_district($district=''){
		if($district!='')
		{
			$this->db->select("client_id,client_name,client_district,client_circle,client_type");
			$this->db->from('clients');
			$this->db->where('client_district',$district);
				
			$query = $this->db->get();		
			$result = $query->result_array();
			
		// echo "<pre>";
		// print_r($result);
		// die();
					
			foreach($result as $data){
				
				$id = $data['client_id'];
				$name = $data['client_name'];
				$circle = $data['client_circle'];
				$type = $data['client_type'];
				
				if($type==15) //Client
				{
					$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
					$this->db->from('master_data');
					$this->db->where('mdata_client',$id);
			
					$query = $this->db->get();		
					$result = $query->result_array();
					if(!empty($result))
					{
						foreach($result as $val){	
							$dealer_id			= $val['mdata_dealer'];
							$client_id 			= $val['mdata_client'];
							//$item_series 		= $val['mdata_item_series'];
							$material_invoice	= $val['mdata_material_invoice'];
							$qty	= $val['mdata_qty'];
					
							if($dealer_id!=''){
								$dealer_details = $this->get_client_dealer_detail_list($dealer_id);
						
								if(!empty($dealer_details)){
									foreach($dealer_details as $dealer_detail)
									{
										$dealer_name = $dealer_detail['client_name'];
									}
								}
								else{
									$dealer_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$dealer_name = '<span style="color:red">Nill</span>';
							}
							if($client_id!=''){
								$client_details = $this->get_client_dealer_detail_list($client_id);
								
								if(!empty($client_details)){
										foreach($client_details as $client_detail)
										{
											$client_name = $client_detail['client_name'];
										}
								}
								else{
									$client_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$client_name = '<span style="color:red">Nill</span>';
							}
					
							$item_array 	= json_decode($val['mdata_item_series']);			
					
							foreach($item_array as $item_series)
							{
								if($item_series!=''){
									$this->db->select("product_code,product_components");
									$this->db->from('products');
									$this->db->where('product_code',$item_series);
									$query = $this->db->get();		
									$result1 = $query->result_array();
									
									foreach($result1 as $val){
										$asset_array	= json_decode($val['product_components']);
										
										$asset = implode(', ',$asset_array);
											
									}
							
								}
						
							}	
						}
						$data= array();
						$data['dealer_name'] = $dealer_name;
						$data['client_name'] = $client_name;
						$data['invoice'] = $material_invoice;
						$data['asset_series'] = $item_series;
						$data['asset'] = $asset;
						$data['qty'] = $qty;
						
						return $data;
							
					}
				}
			
				elseif($type==11) //Dealer
				{
					$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
					$this->db->from('master_data');
					$this->db->where('mdata_dealer',$id);
			
					$query = $this->db->get();		
					$result = $query->result_array();
			
					if(!empty($result))
					{
						foreach($result as $val){	
							$dealer_id			= $val['mdata_dealer'];
							$client_id 			= $val['mdata_client'];
							//$item_series 		= $val['mdata_item_series'];
							$material_invoice	= $val['mdata_material_invoice'];
							$qty	= $val['mdata_qty'];
							
							if($dealer_id!=''){
								$dealer_details = $this->get_client_dealer_detail_list($dealer_id);
								
								if(!empty($dealer_details)){
									foreach($dealer_details as $dealer_detail)
									{
										$dealer_name = $dealer_detail['client_name'];
									}
								}
								else{
									$dealer_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$dealer_name = '<span style="color:red">Nill</span>';
							}
							if($client_id!=''){
								$client_details = $this->get_client_dealer_detail_list($client_id);
								
								if(!empty($client_details)){
										foreach($client_details as $client_detail)
										{
											$client_name = $client_detail['client_name'];
										}
								}
								else{
									$client_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$client_name = '<span style="color:red">Nill</span>';
							}
					
							$item_array = json_decode($val['mdata_item_series']);			
					
							foreach($item_array as $item_series)
							{
								if($item_series!=''){
									$this->db->select("product_code,product_components");
									$this->db->from('products');
									$this->db->where('product_code',$item_series);
									$query = $this->db->get();		
									$result1 = $query->result_array();
									
									foreach($result1 as $val){
										$asset_array	= json_decode($val['product_components']);								
										$asset = implode(', ',$asset_array);											
									}									
								}								
							}
						}
						$data= array();
						$data['dealer_name'] = $dealer_name;
						$data['client_name'] = $client_name;
						$data['invoice'] = $material_invoice;
						$data['asset_series'] = $item_series;
						$data['asset'] = $asset;
						$data['qty'] = $qty;
				
						return $data;
					}
				}
				else{
				$this->session->set_flashdata('msg','Given data did not matched with database');
				redirect(base_url('auth_admin/dashboard'));
				}
			}
		
		}
	}
	
	function search_circle($circle=''){
		if($circle!='')
		{
			$this->db->select("client_id,client_name,client_district,client_circle,client_type");
			$this->db->from('clients');
			$this->db->where('client_circle',$circle);
				
			$query = $this->db->get();		
			$result = $query->result_array();
			
			foreach($result as $data){
				$id = $data['client_id'];
				$name = $data['client_name'];
				$circle = $data['client_circle'];
				$type = $data['client_type'];
				
				if($type==10) //Client
				{
					$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
					$this->db->from('master_data');
					$this->db->where('mdata_client',$id);
			
					$query = $this->db->get();		
					$result = $query->result_array();
					if(!empty($result))
					{
						foreach($result as $val){	
							$dealer_id			= $val['mdata_dealer'];
							$client_id 			= $val['mdata_client'];
							//$item_series 		= $val['mdata_item_series'];
							$material_invoice	= $val['mdata_material_invoice'];
							$qty	= $val['mdata_qty'];
					
							if($dealer_id!=''){
								$dealer_details = $this->get_client_dealer_detail_list($dealer_id);
								
								if(!empty($dealer_details)){
									foreach($dealer_details as $dealer_detail)
									{
										$dealer_name = $dealer_detail['client_name'];
									}
								}
								else{
									$dealer_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$dealer_name = '<span style="color:red">Nill</span>';
							}
							if($client_id!=''){
								$client_details = $this->get_client_dealer_detail_list($client_id);
								
								if(!empty($client_details)){
										foreach($client_details as $client_detail)
										{
											$client_name = $client_detail['client_name'];
										}
								}
								else{
									$client_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$client_name = '<span style="color:red">Nill</span>';
							}
					
							$item_array 	= json_decode($val['mdata_item_series']);			
					
							foreach($item_array as $item_series)
							{
								if($item_series!=''){
									$this->db->select("product_code,product_components");
									$this->db->from('products');
									$this->db->where('product_code',$item_series);
									$query = $this->db->get();		
									$result1 = $query->result_array();
									
									foreach($result1 as $val){
										$asset_array	= json_decode($val['product_components']);
										
										$asset = implode(',',$asset_array);
											
									}
							
								}
						
							}	
						}
						$data= array();
						$data['dealer_name'] = $dealer_name;
						$data['client_name'] = $client_name;
						$data['invoice'] = $material_invoice;
						$data['asset_series'] = $item_series;
						$data['asset'] = $asset;
						$data['qty'] = $qty;
						
						return $data;
							
					}
				}
				elseif($type==11) //Dealer
				{
					$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
					$this->db->from('master_data');
					$this->db->where('mdata_dealer',$id);
			
					$query = $this->db->get();		
					$result = $query->result_array();
			
					if(!empty($result))
					{
						foreach($result as $val){	
							$dealer_id			= $val['mdata_dealer'];
							$client_id 			= $val['mdata_client'];
							//$item_series 		= $val['mdata_item_series'];
							$material_invoice	= $val['mdata_material_invoice'];
							$qty	= $val['mdata_qty'];
							
							if($dealer_id!=''){
								$dealer_details = $this->get_client_dealer_detail_list($dealer_id);
								
								if(!empty($dealer_details)){
									foreach($dealer_details as $dealer_detail)
									{
										$dealer_name = $dealer_detail['client_name'];
									}
								}
								else{
									$dealer_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$dealer_name = '<span style="color:red">Nill</span>';
							}
							if($client_id!=''){
								$client_details = $this->get_client_dealer_detail_list($client_id);
								
								if(!empty($client_details)){
										foreach($client_details as $client_detail)
										{
											$client_name = $client_detail['client_name'];
										}
								}
								else{
									$client_name = '<span style="color:red">Nill</span>';
								}
							}
							else{
								$client_name = '<span style="color:red">Nill</span>';
							}
					
							$item_array 	= json_decode($val['mdata_item_series']);			
					
							foreach($item_array as $item_series)
							{
								if($item_series!=''){
									$this->db->select("product_code,product_components");
									$this->db->from('products');
									$this->db->where('product_code',$item_series);
									$query = $this->db->get();		
									$result1 = $query->result_array();
									
									foreach($result1 as $val){
										$asset_array	= json_decode($val['product_components']);
										
										$asset = implode(',',$asset_array);
											
									}
									
								}
								
							}
						}
						$data= array();
						$data['dealer_name'] = $dealer_name;
						$data['client_name'] = $client_name;
						$data['invoice'] = $material_invoice;
						$data['asset_series'] = $item_series;
						$data['asset'] = $asset;
						$data['qty'] = $qty;
				
						return $data;
					}
				}
				else{
				$this->session->set_flashdata('msg','Given data did not matched with database');
				redirect(base_url('auth_admin/dashboard'));
				}
			}
		
		}
	}
	
	/*
	function search_client_dealer($client,$dealer){
		if($client!='' && $dealer!='')
		{
			$this->db->select("mdata_dealer,mdata_client,mdata_item_series,mdata_material_invoice,mdata_qty");
			$this->db->from('master_data');
			$this->db->where('mdata_client',$client);
			$this->db->where('mdata_dealer',$dealer);
			
			$query = $this->db->get();		
			$result = $query->result_array();
			
			if(!empty($result))
			{
				foreach($result as $val){	
					$dealer_id			= $val['mdata_dealer'];
					$client_id 			= $val['mdata_client'];
					//$item_series 		= $val['mdata_item_series'];
					$material_invoice	= $val['mdata_material_invoice'];
					$qty	= $val['mdata_qty'];
					
					if($dealer_id!=''){
						$dealer_details = $this->get_client_dealer_detail_list($dealer_id);
						
						if(!empty($dealer_details)){
							foreach($dealer_details as $dealer_detail)
							{
								$dealer_name = $dealer_detail['client_name'];
							}
						}
						else{
							$dealer_name = '<span style="color:red">Nill</span>';
						}
					}
					else{
						$dealer_name = '<span style="color:red">Nill</span>';
					}
					if($client_id!=''){
						$client_details = $this->get_client_dealer_detail_list($client_id);
						
						if(!empty($client_details)){
								foreach($client_details as $client_detail)
								{
									$client_name = $client_detail['client_name'];
								}
						}
						else{
							$client_name = '<span style="color:red">Nill</span>';
						}
					}
					else{
						$client_name = '<span style="color:red">Nill</span>';
					}
					
					$item_array 	= json_decode($val['mdata_item_series']);			
					
					foreach($item_array as $item_series)
					{
						if($item_series!=''){
							$this->db->select("product_code,product_components");
							$this->db->from('products');
							$this->db->where('product_code',$item_series);
							$query = $this->db->get();		
							$result1 = $query->result_array();
							
							foreach($result1 as $val){
								$asset_array	= json_decode($val['product_components']);
								
								$asset = implode(',',$asset_array);
									
							}
							
						}
						
					}	
				}
				$data= array();
				$data['dealer_name'] = $dealer_name;
				$data['client_name'] = $client_name;
				$data['invoice'] = $material_invoice;
				$data['asset_series'] = $item_series;
				$data['asset'] = $asset;
				$data['qty'] = $qty;
				
				return $data;
			}
			else{
				$this->session->set_flashdata('msg','Given data did not matched with database');
				redirect(base_url('auth_admin/dashboard'));
			}
		}
		else{
			return false;			
		}
	}
	*/
// Inspection data starts
	/* Search the allotted site ID's to the respective logged in User */
	function search_alloted_inspection($user_id,$user_groupID)
	{
		if($user_groupID == 9){
			$this->load->model('SiteId_model');
			$assign_list = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$user_groupID);
			if(is_array($assign_list)){
				$count = count($assign_list);
			}else{
				$count = '0';
			}
			return $count;
		}else{
			/* If user is admin, then display all the site id's allotted */
			$this->load->model('kare_model');
			$site_ids = $this->kare_model->get_inspector_list('site_id');
			$total_approved_site_ids = $this->search_approved_inspection();
			$total_rejected_site_ids = $this->search_rejected_inspection();
			if(is_array($site_ids)){
				$data_array 	= array();
				$siteId_array	= array();
				foreach($site_ids as $val){
					$siteId_array = json_decode($val['site_id'],true);
					if(empty($siteId_array)){
						$data_array = $siteId_array;
					}else{
						$data_array = array_merge($data_array,$siteId_array);
					}
				}
				$count = count($data_array);
			}else{
				$count = '0';
			}
			
			$total = $count + $total_approved_site_ids + $total_rejected_site_ids;
			return $total;
		}
		
	}
	
	function get_total_inspection(){
		return $table_row_count = $this->db->count_all('inspection_list_1');
	}
	
	function search_inspection_results($user_id='', $group_id='',$type){
		
		$this->db->select("*");
		$this->db->from('inspection_list_1');
		$this->db->where('approved_status',$type);
		if($user_id !='' && $group_id !='' && $group_id=='9'){
			$this->db->where('inspected_by',$user_id);
		}
		$query = $this->db->get();		
		$count = $query->num_rows();
		return $count;
	}
	
	/*
	function search_rejected_inspection()
	{
		$this->db->select("*");
		$this->db->from('inspection_list_1');
		$this->db->where('approved_status','Rejected');
		if(($user_id !='' && $group_id !='') && $group_id=='9'){
			$this->db->where('inspected_by',$user_id);
		}
		$query = $this->db->get();		
		$count = $query->num_rows();
		return $count;
		
	}
	
	function search_pending_inspection()
	{
		$this->db->select("*");
		$this->db->from('inspection_list_1');
		$this->db->where('approved_status','Pending');
		$query = $this->db->get();		
		$count = $query->num_rows();
		return $count;
	}
	*/
	
	function search_not_inspected_siteIDs($user_id,$user_groupID)
	{
		if($user_groupID == 9){
			$this->load->model('SiteId_model');
			$assign_list = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$user_groupID);
			
			if(is_array($assign_list)){
				$array = array();
				foreach($assign_list as $val){
					/* Check if particular assigned site id is available in table or not */
					$result = $this->check_siteID_in_inspection_list_1($val['siteID_id']);
					if(!$result){
						$array[] = $val['siteID_id'];
					}
				}
				$count = count($array);
			}else{
				$count = '0';
			}
			return $count;
		}else{
			/* If user is admin, then display all the site id's allotted */
			$this->load->model('kare_model');
			$site_ids = $this->kare_model->get_inspector_list();
			if(is_array($site_ids)){
				$data_array 	= array();
				$siteId_array	= array();
				
				foreach($site_ids as $val){
					$siteId_array = json_decode($val['site_id'],true);
					$data_array = array_merge($data_array,$siteId_array);
				}
				$array = array();
				foreach($data_array as $val){
					/* Check if particular assigned site id is available in table or not */
					$result = $this->check_siteID_in_inspection_list_1($val);
					if(!$result){
						$array[] = $val;
					}
				}
				$count = count($array);
			}else{
				$count = '0';
			}
			return $count;
		}
	}
	
	/* Get Site Id Data which are not yet inspected by Inspector */
	function get_not_inspected_siteID_data($user_id,$user_groupID){
	
		if($user_groupID == 9){
			$this->load->model('SiteId_model');
			$site_ids = $this->SiteId_model->get_siteID_list_of_inspector(NULL,$user_groupID);
			if(is_array($site_ids)){
				$array = array();
				
				foreach($site_ids as $val){
					/* Check if particular assigned site id is available in table or not */
					$result = $this->check_siteID_in_inspection_list_1($val['siteID_id']);
					if(!$result){
						$array[] = $val;
					}
				}
				return $array;
			}else{
				return  false;
			}
			
		}else{
			$this->load->model('kare_model');
			$site_ids = $this->kare_model->get_inspector_list();
		
			if(is_array($site_ids)){
				$data_array 	= array();
				//$siteId_array	= array();
				$siteid_data	= array();
				foreach($site_ids as $val){
					$siteId_array = json_decode($val['site_id'],true);
					$data_array = array_merge($data_array,$siteId_array);
					foreach($siteId_array as $sVal){
						$siteid_data[$sVal] = $val;
					}
				}
				$array = array();
				foreach($data_array as $val){
					/* Check if particular assigned site id is available in table or not */
					$result = $this->check_siteID_in_inspection_list_1($val);
					if(!$result){
						$siteid_data[$val]['siteID_name'] = $this->get_siteID_name($val);
						$siteid_data[$val]['inspector_name'] = $this->get_inspector_name($siteid_data[$val]['inspector_ids']);
						$array[$val] = $siteid_data[$val];
					}
				}
				$non_inspected_values = $array;
				$count = count($array);
				//$count = count($data_array);
			}else{
				$non_inspected_values = '';
				$count = '0';
			}
			
			return $non_inspected_values;
		}
	}
	
	function get_siteID_name($id){
		$this->db->select('site_id');
		$this->db->from('siteID_data');
		$this->db->where('siteID_id',$id);
		$query = $this->db->get();
		$res = $query->row_array();
		return $res['site_id'];
	}
	
	/* Get Inspector Name  */
	function get_inspector_name($id_array){
		$ins_array = json_decode($id_array,true);
		$name = array();
		foreach($ins_array as $insVal){
			$this->db->select('upro_first_name, upro_last_name');
			$this->db->from('demo_user_profiles');
			$this->db->where('upro_id',$insVal);
			$query = $this->db->get();
			$res = $query->row_array();
			$name[] = $res['upro_first_name'].' '.$res['upro_last_name'];
		}
		return implode(',',$name);
	}
	
	function check_siteID_in_inspection_list_1($id){
		$this->db->select('id');
		$this->db->from('inspection_list_1');
		$this->db->where('siteID_id',$id);
		$this->db->where('approved_status','Pending');
		$query = $this->db->get();
		if($query->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}
	
	function get_search_result($query){
		$query = $this->db->query("SELECT A.asset_series,B.asset_name,C.mdata_material_invoice,D.client_name
									FROM inspection_list_1 AS A
									LEFT JOIN inspection_list_2 AS B
									ON A.id = B.inspection_list_id
									LEFT JOIN master_data as C
									ON A.job_card = C.mdata_jobcard AND A.sms = C.mdata_sms
									LEFT JOIN clients as D
									ON C.mdata_client = D.client_name OR C.mdata_client = D.client_id
									{$query} Group By A.wpermit_id");
		return $row = $query->result_array();
	}
	
	
	function get_approved_rejected_pending_list($type='',$user_id='', $user_groupID=''){

		$this->db->select("A.id,A.report_no,A.site_id,A.job_card,sms,A.approved_status,B.upro_first_name,B.upro_last_name,C.upro_first_name as admin_fname,C.upro_last_name as admin_lname");
		$this->db->from('inspection_list_1 as A');
		$this->db->join('demo_user_profiles as B', 'A.inspected_by = B.upro_uacc_fk', 'left');
		$this->db->join('demo_user_profiles as C', 'A.approved_by = C.upro_uacc_fk', 'left');
		$this->db->where('approved_status',$type);
		if($user_groupID=='9'){
			$this->db->where('inspected_by',$user_id);
		}
		$query = $this->db->get();
		return $query->result_array();
	}
	
// Inspection data ends
	
}// end of Model class 
?>