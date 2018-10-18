<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class SiteId_kare extends CI_Controller{
   
   // Initializing siteid controller 
	public function __construct(){
		parent::__construct();
		$this->load->model('SiteId_model');
		$this->load->model('kare_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
		
                 $this->auth = new stdClass;
                 
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id = $_SESSION['client']['client_id'];

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			//$this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
			$this->session->set_flashdata('msg','<p class="alert alert-success capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect($this->client_url.'auth');
		}
		// if($_SESSION['user_group'] =='8'){
			// redirect('Clientuser_dashboard');
			// exit();
		// }
		$this->load->vars('base_url', base_url()."/".$this->client_url);
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data['lang']  = $this->sma->get_lang_level('first');
		
	}
		

  	public function index(){
		redirect($this->client_url.'auth');
	}
	
		
	public function angular(){
		
		if($_GET['action'] == 'getSiteIdAjax'){
			// SQL order
			$aColumns = array(	'site_jobcard', 'site_sms', 'site_id', 'site_location', 'site_city',
							'site_address','site_lattitude','site_longitude','site_contact_name',
							'site_contact_number','site_contact_email','status' );
		
		}else if($_GET['action'] == 'getMasterDataAjax'){
			/* Start of Client ID updation in master data */
			$this->load->model('kare_model');
			$client_types=$this->kare_model->get_client_master();
			/* End */
			$aColumns = array(	'mdata_jobcard', 'mdata_sms', 'mdata_batch', 'mdata_serial', 'mdata_rfid',
							'mdata_barcode','mdata_uin','mdata_client',
							'client_name','mdata_po','mdata_material_invoice','mdata_material_invoice_date',
							'mdata_qty'
							);

		}else if($_GET['action'] == 'getsubAssetDataAjax'){
			$aColumns = array(	'sub_assets_code', 'sub_assets_description','sub_assets_imagepath','sub_assets_uom', 'sub_assets_inspection', 'sub_assets_result',
							 'sub_assets_observation','sub_assets_repair','status'
							);
		}else if($_GET['action'] == 'getSmsComponentAjax'){
			$aColumns = array(	'jc_number', 'sms_number', 'series', 'item_code', 'item_quantity',
							'no_of_lines','status'
							);
		}else if($_GET['action'] == 'getAssetAjax'){
			$aColumns = array(	'component_code', 'component_description', 'component_sub_assets', 'component_image', 'component_imagepath',
							'component_uom','component_inspectiontype','component_expectedresult','component_observation',
							'component_repair','infonet_status','status'
							);
		}else if($_GET['action'] == 'getClientAjax'){
			$aColumns = array(	'client_name', 'client_district', 'client_circle', 'client_contactPerson', 'client_contactNo',
							'client_contactPerson_email','client_type','client_status'
							);
		}else if($_GET['action'] == 'getAssetSeriesAjax'){
			$aColumns = array(	'product_code', 'product_description', 'product_components', 'product_inspectiontype','infonet_status','status'
							);
		}
		$relation = $_GET['action'];		
		$this->get_values_of_tables($aColumns,$relation);
	}

	function get_values_of_tables($aColumns,$relation){
		// SQL limit
		$sLimit = '';
		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = 'LIMIT ' . (int)$_GET['iDisplayStart'] . ', ' . (int)$_GET['iDisplayLength'];
		}

		$sOrder = '';
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = 'ORDER BY  ';
			for ($i=0 ; $i<(int)$_GET['iSortingCols'] ; $i++) {
				if ( $_GET[ 'bSortable_'.(int)$_GET['iSortCol_'.$i] ] == 'true' ) {
					$sOrder .= '`'.$aColumns[ (int)$_GET['iSortCol_'.$i] ].'` '.
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .', ';
				}
			}

			$sOrder = substr_replace($sOrder, '', -2);
			if ($sOrder == 'ORDER BY') {
				$sOrder = '';
			}
		}

		// SQL where
		//$sWhere = 'WHERE 1';
		if($relation =='getSmsComponentAjax'){
			$sWhere = "WHERE item_code NOT LIKE 'INS-%' ";
		}else{
			$sWhere = 'WHERE 1 ';
		}
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != '') {
			if($relation =='getSmsComponentAjax'){
				$sWhere .= " AND (";
			}else{
				$sWhere = 'WHERE 1 AND (';
			}

			for ($i=0; $i<count($aColumns) ; $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == 'true') {
					$sWhere .= '`' . $aColumns[$i]."` LIKE '%".$_GET['sSearch']."%' OR ";
				}
			}
			$sWhere = substr_replace( $sWhere, '', -3 );
			$sWhere .= ')';
		}

		if($relation =='getSiteIdAjax'){
			return $this->getSiteidAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getMasterDataAjax'){
			return $this->getMasterDataAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getsubAssetDataAjax'){
			return $this->getsubAssetDataAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getSmsComponentAjax'){
			return $this->getSmsComponentAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getAssetAjax'){
			return $this->getAssetAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getClientAjax'){
			return $this->getClientAjax($sWhere,$sOrder,$sLimit);
		}else if($relation =='getAssetSeriesAjax'){
			return $this->getAssetSeriesAjax($sWhere,$sOrder,$sLimit);
		}
		
	}
	
	function getClientAjax($sWhere,$sOrder,$sLimit) {
		
		$this->load->model('Subassets_model');
                
		$client_types=$this->Subassets_model->get_inspection_list('client');
                  #print_r($client_types);
                
		if(is_array($client_types)){
			$insdatas = array();
			foreach($client_types as $resVal){
				$insdatas[$resVal['id']] = strtolower($resVal['type_name']);
			}
			$clientTypes = $insdatas;
		}else{
			$clientTypes='';
		}
                
                
			
		$aMembers = $this->SiteId_model->get_client_list_via_ajax($sWhere,$sOrder,$sLimit);
                #print_r($aMembers); die;
                
                
		$iCnt = $this->SiteId_model->get_total_count_client();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => $iCnt,
			'iTotalDisplayRecords' => count($aMembers),
			'aaData' => array()
		);
		

			foreach ($aMembers as $iID => $aInfo) {
					
					if(is_array($clientTypes)){
						if( array_key_exists($aInfo['client_type'],$clientTypes)){
							$client_value = "<p>".$clientTypes[$aInfo['client_type']]."</p>";
						}else{
							//$client_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['client_type']."</p>";
							$client_value = "<p>".$aInfo['client_type']."</p>";
						}
					}else{
						$client_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['client_type']."</p>";
					}
					
					
					$status =  ($aInfo['client_status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$aItem = array(
						'<a class="text-primary" href="'.base_url().'client_kare/edit_client?id='.$aInfo['client_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.base_url().'client_kare/delete_client?id='.$aInfo['client_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$aInfo['client_name'],
						$aInfo['client_district'],
						$aInfo['client_circle'],
						$aInfo['client_contactPerson'],
						$aInfo['client_contactNo'],
						$aInfo['client_contactPerson_email'],
						$client_value,
						$status,
						'DT_RowId' => $aInfo['client_id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
	
	function getAssetSeriesAjax($sWhere,$sOrder,$sLimit) {
		
		$this->load->model('Subassets_model');
		
			$components=$this->kare_model->get_components_list();
			if($components){
				foreach($components as $component){
					$components_list[]=$component['component_code'];
				}
			}else{
				$components_list = '';
			}
			$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
			if(!empty($subAssets)){
				foreach($subAssets as $sAssets){
					$sub_assets_list[]=$sAssets['sub_assets_code'];
				}
			}else{
				$sub_assets_list = '';
			}
			if(is_array($components_list) && is_array($sub_assets_list)){
				$asset_array = array_merge($components_list,$sub_assets_list);
			}else if(is_array($components_list) && !is_array($sub_assets_list)){
				$asset_array = $components_list;
			}else if(is_array($sub_assets_list) && !is_array($components_list)){
				$asset_array = $sub_assets_list;
			}else if(!is_array($sub_assets_list) && !is_array($components_list)){
				$asset_array = '';
			}
			
			$data['asset_array'] = $asset_array;
		
			/* for comparison */
			if($asset_array){
				$asset_arrays = array();
				foreach($asset_array as $asset){
					$asset_arrays[]	= strtolower(trim($asset));
				}
			}else{
				$asset_arrays = '';
			}
		$data['asset_array_compare'] = $asset_array_compare = $asset_arrays;
		
		
		
		$inspection		=	$this->Subassets_model->get_type_category('inspection');
		
		$aMembers = $this->SiteId_model->get_AssetSeries_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->SiteId_model->get_total_count_AssetSeries();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
		

			foreach ($aMembers as $iID => $aInfo) {
					
					if(is_array($inspection) && !empty($aInfo['product_inspectiontype'])){
						if(array_key_exists($aInfo['product_inspectiontype'],$inspection)){
							$inspection_value = "<p>".$inspection[$aInfo['product_inspectiontype']]."</p>";
						}else{
							$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['product_inspectiontype']."</p>";
						}
					}else{
						$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['product_inspectiontype']."</p>";
					}
					
					if($aInfo['product_image']!=''){
						$imagePath = '<img src="'.base_url().'uploads'.$_SESSION['client']['client_id'].'/images/products/'.$aInfo['product_image'].'" width="60" height="60" />';
					}else{
						$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
					}
					
					$components = ($aInfo['product_components']!='')?json_decode($aInfo['product_components'],true):$aInfo['product_components'];
						if(is_array($components)){
							$asset ='';
							foreach($components as $code){
								$tCode = trim($code);
								$value = strtolower($tCode);
								if(is_array($asset_array_compare)){
									if(in_array(strtolower($value),$asset_array_compare)){
										$asset .=  "<p>".$tCode."</p>";
									}else{
										$asset .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
									}
								}else{
									$asset = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
								}
							}	
						}
					
					
					
					$status =  ($aInfo['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$infonet_status =  ($aInfo['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$aItem = array(
						'<a class="text-primary" href="'.$base_url.'manage_kare/asset_series_list/'.$aInfo['product_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.$base_url.'manage_kare/delete_asset_series/'.$aInfo['product_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$aInfo['product_code'],
						$aInfo['product_description'],
						$imagePath,
						$asset,
						$inspection_value,
						$infonet_status,
						$status,
						'DT_RowId' => $aInfo['product_id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
	
	function getAssetAjax($sWhere,$sOrder,$sLimit) {
		
		$this->load->model('Subassets_model');
		$inspection		=	$this->Subassets_model->get_type_category('inspection');
		$uom			=	$this->Subassets_model->get_type_category('uom');
		$result			=	$this->Subassets_model->get_type_category('result');
		$observations	=	$this->Subassets_model->get_type_category('observations');

		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		
		$aMembers = $this->SiteId_model->get_Asset_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->SiteId_model->get_total_count_Asset();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
		

			foreach ($aMembers as $iID => $aInfo) {
				
					if(is_array($uom) && !empty($aInfo['component_uom'])){
						if(array_key_exists($aInfo['component_uom'],$uom)){
							$uom_value = "<p>".$uom[$aInfo['component_uom']]."</p>";
						}else{
							$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_uom']."</p>";
						}
					}else{
						$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_uom']."</p>";
					}
					
					if(is_array($inspection) && !empty($aInfo['component_inspectiontype'])){
						if(array_key_exists($aInfo['component_inspectiontype'],$inspection)){
							$inspection_value = "<p>".$inspection[$aInfo['component_inspectiontype']]."</p>";
						}else{
							$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_inspectiontype']."</p>";
						}
					}else{
						$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_inspectiontype']."</p>";
					}
					
					if(is_array($result) && !empty($aInfo['component_expectedresult'])){
						$excpected_result = json_decode($aInfo['component_expectedresult'],true);
						$result_value = '';
						foreach($excpected_result as $expResult){
							if(array_key_exists($expResult,$result)){
								$result_value .=  "<p>".$result[$expResult]."</p><hr>";
							}else{
								if(!empty($aInfo['component_expectedresult'])){
									$ob = json_decode($aInfo['component_expectedresult'],true);
									$ob = implode('<br/>',$ob);
									$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$ob."</p>";
								}
							}
						}
					}else{
						$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_expectedresult']."</p>";
					}
					
				
					if(is_array($observations) && !empty($aInfo['component_observation'])){
							$observations_array = json_decode($aInfo['component_observation'],true);
							$observation_value = '';
							foreach($observations_array as $obsResult){
								if(array_key_exists($obsResult,$observations)){
									$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
								}else{
									
									if(!empty($aInfo['component_observation'])){
										$ob = json_decode($aInfo['component_observation'],true);
										$ob = implode('<br/>',$ob);
									   $observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$ob."</p>";
									}
								}
							}
							
					}else{
						$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['component_observation']."</p>";
					}
					
					
					
					if($aInfo['component_image']!=''){
						$imagePath = '<img src="'.str_replace('FCPATH',base_url(),$aInfo['component_imagepath']).'" width="60" height="60" />';
					}else{
						$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
					}
					
					$status =  ($aInfo['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$infonet_status =  ($aInfo['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=asset&id='.$aInfo['component_id'].'">Featured Image</a>';
					$aItem = array(
						'<a class="text-primary" href="'.base_url().'manage_kare/edit_assets/'.$aInfo['component_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.base_url().'manage_kare/delete_component/'.$aInfo['component_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$aInfo['component_code'],
						$aInfo['component_description'],
						$imagePath,
						strtoupper($uom_value),
						$inspection_value,
						$result_value,
						$observation_value,
						strtoupper($aInfo['component_repair']),
						$infonet_status,
						$status,
						$featuredImage,
						'DT_RowId' => $aInfo['component_id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
	
	function getSmsComponentAjax($sWhere,$sOrder,$sLimit) {
            
                #echo $sWhere."======".$sOrder."================".$sLimit ; die;
    
		$this->load->model('Sms_model');
		$aMembers = $this->SiteId_model->get_smsComponent_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->SiteId_model->get_total_count_SmsComponent();

		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
                
                $sys_assets = array_column($this->kare_model->get_components_list(),'component_code');
                $sys_series = array_column($this->kare_model->get_products_list('product_code'),'product_code');
                
		
			foreach ($aMembers as $iID => $aInfo) {
					
					$jobCards 		= $aInfo['jc_number'];
					$sms_number 		= $aInfo['sms_number'];
					$asset_series 		= $aInfo['series'];
					$assets			= $aInfo['item_code'];
                                        
                                        $sms_number_code=$sms_number;
                                        $jobCards_code=$jobCards;
                                                
                                        if(in_array($asset_series, $sys_series)){
                                            $asset_series_code = $asset_series;   
                                         }else{
                                            $asset_series_code =  "<span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series."</p>";   
                                        }
                                        
                                        if(in_array($assets, $sys_assets)){
                                           $asset_code=$asset_series; 
                                        }else{
                                          $asset_code =  "<span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series;   
                                        }

					/*$this->load->model('Sms_model');
					$array = array('mdata_jobcard' => $jobCards);
					$job = $this->Sms_model->get_job_masterData($array);
					if($job){
						$jobCards_code  = "<p>".$jobCards."</p>";
						$array1 = array('mdata_jobcard' => $jobCards, 'mdata_sms'=>$sms_number);
						$jobSms = $this->Sms_model->get_jobSMS_masterData($array1);
						if($jobSms){
							$sms_number_code =  "<p>".$sms_number."</p>";
							$series = ($jobSms['mdata_item_series'] != '')? json_decode($jobSms['mdata_item_series']): '';
							if($series !=''){
								if(in_array($asset_series,$series)){
									$asset_series_code =  "<p>".$asset_series."</p>";
									$asset = $this->Sms_model->get_asset_components($assets);
									if(is_array($asset)){
										$asset_code =  "<p>".$assets."</p>";
									}else{
										$asset_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$assets."</p>";
									}
								}else{
									$asset_series_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series."</p>";
									$asset_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$assets."</p>";
								}
							}else{
								$asset_series_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series."</p>";
								$asset_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$assets."</p>";
							}
						}else{
							$sms_number_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$sms_number."</p>";
							$asset_series_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series."</p>";
							$asset_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$assets."</p>";
						}
						
					}else{
						$jobCards_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$jobCards."</p>";
						$sms_number_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$sms_number."</p>";
						$asset_series_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$asset_series."</p>";
						$asset_code =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$assets."</p>";
					}*/
                                        
					
					$status =  ($aInfo['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$aItem = array(
						'<a class="text-primary" href="'.base_url($this->client_url).'/sms_controller/edit_sms_component?id='.$aInfo['id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.base_url($this->client_url).'/sms_controller/delete_sms_component?id='.$aInfo['id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$jobCards_code,
						$sms_number_code,
						$asset_series_code,
						$asset_code,
						$aInfo['item_quantity'],
						$aInfo['no_of_lines'],
						$status,
						'DT_RowId' => $aInfo['id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
	
	function getsubAssetDataAjax($sWhere,$sOrder,$sLimit) {
		
		$this->load->model('Subassets_model');
		$inspection		=	$this->Subassets_model->get_type_category('inspection');
		$uom			=	$this->Subassets_model->get_type_category('uom');
		$result			=	$this->Subassets_model->get_type_category('result');
		$observations	=	$this->Subassets_model->get_type_category('observations');
		
		$aMembers = $this->SiteId_model->get_subAsset_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->SiteId_model->get_total_count_subAsset();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
		

			foreach ($aMembers as $iID => $aInfo) {
					
					if(is_array($uom) && !empty($aInfo['sub_assets_uom'])){
						if(array_key_exists($aInfo['sub_assets_uom'],$uom)){
							$uom_value = "<p>".$uom[$aInfo['sub_assets_uom']]."</p>";
						}else{
							$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_uom']."</p>";
						}
					}else{
						$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_uom']."</p>";
					}
					
					if(is_array($inspection) && !empty($aInfo['sub_assets_inspection'])){
						if(array_key_exists($aInfo['sub_assets_inspection'],$inspection)){
							$inspection_value = "<p>".$inspection[$aInfo['sub_assets_inspection']]."</p>";
						}else{
							$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_inspection']."</p>";
						}
					}else{
						$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_inspection']."</p>";
					}
					
					if(is_array($result) && !empty($aInfo['sub_assets_result'])){
							$excpected_result = json_decode($aInfo['sub_assets_result'],true);
							$result_value = '';
							foreach($excpected_result as $expResult){
								if(array_key_exists($expResult,$result)){
									$result_value .=  "<p>".$result[$expResult]."</p><hr>";
								}else{
									$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_result']."</p>";
								}
							}
					}else{
						$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_result']."</p>";
					}
					
					if(is_array($observations) && !empty($aInfo['sub_assets_observation'])){
							$observations_array = json_decode($aInfo['sub_assets_observation'],true);
							$observation_value = '';
							foreach($observations_array as $obsResult){
								if(array_key_exists($obsResult,$observations)){
									$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
								}else{
									$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_observation']."</p>";
								}
							}
					}else{
						$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$aInfo['sub_assets_observation']."</p>";
					}
					
					$status =  ($aInfo['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=subAsset&id='.$aInfo['sub_assets_id'].'">Featured Image</a>';
					
					if($aInfo['sub_assets_image']!=''){
						$imagePath = '<img src="http://karam.in/kare'.$aInfo['sub_assets_imagepath'].'" width="60" height="60" />';
					}else{
						$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
					}
					
					$aItem = array(
						'<a class="text-primary" href="'.base_url().'Subassets_kare/sub_assets_list/'.$aInfo['sub_assets_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.base_url().'Subassets_kare/delete_sub_assets/'.$aInfo['sub_assets_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$aInfo['sub_assets_code'],
						$aInfo['sub_assets_description'],
						$imagePath,
						strtoupper($uom_value),
						$inspection_value,
						$result_value,
						$observation_value,
						strtoupper($aInfo['sub_assets_repair']),
						$status,
						$featuredImage,
						'DT_RowId' => $aInfo['sub_assets_id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
        
        function verify_assets($asset){
           # if(in_array($assets,))
            
        }
        
	
	function getMasterDataAjax($sWhere,$sOrder,$sLimit) {
            $this->load->model('kare_model');
		
		/* code to remove */
		//	$client_names =$this->kare_model->get_client_master();
		
		/* code to remove */
		
		$duplicate_jobSMS_list=$this->kare_model->duplicate_jobSMS_list();
                
		$aMembers = $this->SiteId_model->get_masterData_list_via_ajax($sWhere,$sOrder,$sLimit);
                
                #echo "<pre>";
                #print_r($aMembers);die;

		$iCnt = $this->SiteId_model->get_total_count_masterData();
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
                
			foreach ($aMembers as $iID => $aInfo) {
                            
                                       #print_r($aInfo); die;  
                                       if($aInfo['mdata_error_flag']==1 ){
                                           $error_summery=json_decode($aInfo['mdata_error_summery'],true);
                                           $e_flieds=$error_summery['e_fields'];
                                           $e_series=$error_summery['series'];
                                           $e_assets=$error_summery['assets'];
                                        }
                                        
                                        if(in_array('mdata_client', $e_flieds)){
                                          $client = '<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$aInfo['mdata_client'];  
                                        }else{
                                          $client = $aInfo['mdata_client'];   
                                        }
                                        
                                        $asset_series_arr=json_decode($aInfo['mdata_item_series'],true);
                                        if(count($asset_series)){
                                            $asset_series='';
                                            foreach($asset_series_arr as $series){
                                                if(in_array($series, $e_series)){
                                                  $asset_series .= '<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$series."<br/>";   
                                                }else{
                                                   $asset_series .= $series."<br/>";  
                                                }
                                            }    
                                        }else{
                                           $asset_series=""; 
                                        }
                                        
                                        $assets=json_decode($aInfo['mdata_asset'],true);
                                        if(count($assets)){
                                            $assets_str='';
                                            foreach($assets as $asset){
                                                if(in_array($asset, $e_assets)){
                                                  $assets_str .= '<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$asset."<br/>";   
                                                }else{
                                                   $assets_str .= $asset."<br/>";  
                                                }
                                            }    
                                        }else{
                                           $assets_str=""; 
                                        }
                                             
					
                                        $status =  ($aInfo['Status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                                        
					$aItem = array(
						'<a class="text-primary" href="'.base_url($this->client_url).'/manage_kare/mdata_inspection/'.$aInfo['mdata_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                 <a href="'.base_url($this->client_url).'/manage_kare/delete_mdata/'.$aInfo['mdata_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$aInfo['mdata_jobcard'],
						$aInfo['mdata_sms'],
                                                $asset_series,
                                                $assets_str,
						$aInfo['mdata_batch'],
						$aInfo['mdata_serial'],
						$aInfo['mdata_rfid'],
						$aInfo['mdata_barcode'],
						$aInfo['mdata_uin'],
						$client,
						$status,
						'DT_RowId' => $aInfo['mdata_id']
					);
                                        
                                        # print_r($aItem); die;

					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}

	function getSiteidAjax($sWhere,$sOrder,$sLimit) {
            
		$duplicate_jobSMSSite_list=$this->SiteId_model->duplicate_jobSMSSite_list();
                
		$aMembers = $this->SiteId_model->get_siteID_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->SiteId_model->get_total_count_siteid();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($aMembers),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
                
               
	        $sys_jobcard_list = array_column($this->SiteId_model->get_jobcard_list(),'mdata_jobcard');
	        $sys_sms_list = array_column($this->SiteId_model->get_sms_list(),'mdata_sms');
                #echo "<pre>";
                #print_r($sys_jobcard_list);                
                #die;
      

			foreach ($aMembers as $iID => $aInfo) {
                            
					$status = ($aInfo['status'] == 'Active')? 'Active' : 'Inactive';
					$a = ''; $b ='';
					$site = $aInfo['site_id'];
					$sitew = '<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$aInfo['site_id'];
                                        
					if(!is_array($duplicate_jobSMSSite_list) && $duplicate_jobSMSSite_list == ''){
						$a = 'YES';
					}else{
						foreach($duplicate_jobSMSSite_list as $dVal){
							if(in_array($aInfo['site_jobcard'],$dVal) && in_array($aInfo['site_sms'],$dVal) && in_array($aInfo['site_id'],$dVal)){
								$b = 'NO';
							}
						}
						$a = (isset($b) && $b !='')? 'NO' : 'YES';
					}
					
					$siteID = ($a == 'YES')? $site : $sitew;

                                        if(in_array($aInfo['site_jobcard'], $sys_jobcard_list)){
                                            $jobcard_txt=$aInfo['site_jobcard'];
                                        }else{
                                            $jobcard_txt='<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$aInfo['site_id'];
                                        }
                                        
                                        if(in_array($aInfo['site_sms'], $sys_sms_list)){
                                            $sms_txt=$aInfo['site_sms'];
                                        }else{
                                          $sms_txt='<span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>'.$aInfo['site_id'];  
                                        }
                                        
					
					$aItem = array(
						'<a class="text-primary" href="'.base_url($this->client_url).'/SiteId_kare/siteId_master/'.$aInfo['siteID_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.base_url($this->client_url).'/SiteId_kare/delete_siteID/'.$aInfo['siteID_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>',
						$jobcard_txt,
						$sms_txt,
						$siteID,
						$aInfo['site_location'],
						$aInfo['site_city'],
						$aInfo['site_address'],
						$aInfo['site_lattitude'],
						$aInfo['site_longitude'],
						$aInfo['site_contact_name'],
						$aInfo['site_contact_number'],
						$aInfo['site_contact_email'],
						$status,
						'DT_RowId' => $aInfo['siteID_id']
					);
					$output['aaData'][] = $aItem;
			}
		
			echo json_encode($output);
	}
	

	public function siteId_master($itemid=NULL){
            
		$data['lang'] = $this->data['lang'];
		$group_array = $this->session->flexi_auth['group'];
                
		$client_id = $_SESSION['client']['client_id'];
                
		foreach($group_array as $key=>$val){
			$data['group_id'] = $group_id = $key;
		}
		
		/* Inspector cannot come to this page. */
		if ($group_id == 9)
		{
			redirect($this->client_url.'auth_admin');		
		}
		
               if($itemid==NULL && !isset($_POST['submit_siteID'])){

                    #echo "into view"; die;
                    // viewing data
                    $data['item'] = null;
                    if ($group_id == 9) {
                        
                        //$data['products_list']=$this->kare_model->get_mdata_list_of_inspector(NULL,$group_id);
                        $data['siteID_list'] = $this->SiteId_model->get_siteID_list_of_inspector(NULL, $group_id);
                        $data['displayValues'] = '';
                        
                    } else {

                        $displayValue = array();
                        // Get all job card present in site it model
                        $jobcard = $this->SiteId_model->get_jobcard_from_siteID();

                        //echo $count = count($jobcard);
                        // Get total no of siteID present for the fetched Job Card and SMS #
                        if (is_array($jobcard) && !empty($jobcard)) {
                            foreach ($jobcard as $key => $value) {
                                $job = $value->site_jobcard;
                                $sms = $value->site_sms;

                                // Get total no of siteID present for the fetched Job Card and SMS #
                                $totalSiteID = $this->SiteId_model->get_total_siteID_count($job, $sms);

                                // Get No. of lines from SMS Component for fetched jobcard and Site ID
                                $lines = $this->SiteId_model->get_lines_from_smsController($job, $sms);

                                if (is_object($totalSiteID) && is_object($lines)) {
                                    // Get the difference between the lines to find how many site id are not provided
                                    $difference = $lines->no_of_lines - $totalSiteID->totalSiteID;

                                    if ($difference != 0) {
                                        $displayValue[$key] = array(
                                            'job' => $job,
                                            'sms' => $sms,
                                            'lines' => $lines->no_of_lines,
                                            'totalSiteID' => $totalSiteID->totalSiteID,
                                            'difference' => $difference,
                                        );
                                    } else {
                                        $displayValues = '';
                                    }
                                } else {
                                    $displayValues = '';
                                }
                            }
                        } else {
                            $displayValues = '';
                        }
                        
                        
                        
                       /*****************************************************/ 
                                                
                        #$arrjobcard = $this->SiteId_model->get_jobcard();
                        
                        $arrjobcard = $this->SiteId_model->get_jobcard_list();

                        if ($arrjobcard) {
                            foreach ($arrjobcard as $jobcards) {
                                $arrFinal[$jobcards['mdata_id']] = $jobcards['mdata_jobcard'];
                            }
                        } else {
                            $arrFinal = '';
                        }
                                
                        $data['displayValues'] = (!empty($displayValue)) ? $displayValue : $displayValues;
                        
                        $data['jobcards'] = $arrFinal;
                        
                        #$data['siteID_list']=$this->SiteId_model->get_siteID_list();
                    }
                    /*echo "<pre>";
                    print_r($data); die;*/
                    $this->load->view('siteId_master', $data);
                    
                    
                }else{
			if($itemid!=''){
				$data['item']=$this->SiteId_model->get_siteID_item($itemid);	
				$this->load->view('siteId_master_edit',$data);	
			}
		 
			if(isset($_POST['submit_siteID'])){
				$jc_number = $this->input->post('jc_number');
				$sms_number = $this->input->post('sms_number');
				
				$dbdata['site_client_fk']			=	$client_id;
                                
				$dbdata['site_jobcard']				=	$this->input->post('jc_number');
				$dbdata['site_sms']				=	$this->input->post('sms_number');
				$dbdata['status']				=	$this->input->post('status');
				$dbdata['master_id']				=	$this->input->post('master_id');
				$dbdata['created_date']				= 	now();
				
				$id 						= 	$this->SiteId_model->get_masterData_id($jc_number,$sms_number);
				$data[$key]['master_id']			=	$id;
				
				$siteID 					= 	$this->input->post('siteID_info');
                                
                                
                                
				foreach($siteID as $Val){
					$dbdata['site_id']			=	strtoupper(strtolower(trim($Val['site_id'])));
					$dbdata['site_location']		=	$Val['site_location'];
					$dbdata['site_city']			=	$Val['site_city'];
					$dbdata['site_address']			=	$Val['site_address'];
					$dbdata['site_lattitude']		=	$Val['site_lattitude'];
					$dbdata['site_longitude']		=	$Val['site_longitude'];
					//$dbdata['site_length']		=	$Val['site_length'];
					$dbdata['site_contact_name']            =	$Val['site_contact_name'];
					$dbdata['site_contact_number']  	=	$Val['site_contact_number'];
					$dbdata['site_contact_email']   	=	$Val['site_contact_email'];
                                        
                                        //print_r($dbdata); die;
					
					if($itemid==NULL){
						$result = $this->SiteId_model->check_siteID_unique($dbdata['site_jobcard'], $dbdata['site_sms'], $dbdata['site_id']);

						if($result =='Yes'){
								if(!$this->SiteId_model->insert_siteID($dbdata)){
									$error = 'Error';
								}
						}else{
							//echo 'This SITE ID <b><font color="red">'.$result.'</font></b> is already exist. Please insert another site id.';
							$this->session->set_flashdata('msg','<div class="alert alert-danger capital">This Site ID <b><font color="red">'.$result.'</font></b> already exist. Please insert another Site ID.</div>');
							redirect($this->client_url.'/SiteId_kare/siteId_master');
						}
					}else{
							if(!$this->SiteId_model->update_siteID($dbdata,$itemid)){
								$error = 'Error';
							}
						}
				}

				if($itemid==NULL){
						if(!isset($error)){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully inserted</div>');
							redirect($this->client_url.'SiteId_kare/siteId_master');
						}
				}else{
					if(!isset($error)){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully updated</div>');
						redirect($this->client_url.'SiteId_kare/siteId_master');	
					}else{
						$data['item']=$this->kare_model->get_mdata_item($itemid);	
						$this->load->view('siteId_master_edit',$data);
					}
				}
			}
		}
	}


    public function import_siteID_data(){
        
                $client_id=$_SESSION['client']['client_id'];                                 
        
		if(isset($_POST['import_siteID_xls'])&& $_SERVER['REQUEST_METHOD']=='POST'){

			$jc_number = $this->input->post('jc_number');
			$sms_number = $this->input->post('sms_number');
                        
                         $dir = FCPATH."uploads/{client_id}/xls/";
                            if(!file_exists($dir)){
                                    mkdir($dir, 0777,true);                                            
                            }
                            
			$config['upload_path'] =$dir;
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['max_size'] = '2048';
			$config['max_width'] = '0';
			$config['max_height'] = '0';	
			$this->load->library('upload',$config);
			
			if(!$this->upload->do_upload('file_upload')){
				 $error=$this->upload->display_errors();
				 $this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$error.'</div>');
			}else{
				$upload_data=$this->upload->data();
				$file_path=$upload_data['full_path'];
				if($file_path){
					$result=$this->import_siteID_xls($file_path,$jc_number,$sms_number);
					if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
						unlink($file_path); // delete the uploded file 	
					}else{
						echo "file uploading problem";	 
					}
				}
			}
		}
		redirect($this->client_url.'SiteId_kare/siteId_master');
	 }    
        
    public function import_siteID_xls($file_path=null,$jc_number,$sms_number){
	
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
		//get only row iterator
		foreach($objWorksheet->getRowIterator() as $key=>$row){
		
			if($key==1) continue;
			
			/* Check if row in excel is empty or not. */
			if($this->SiteId_model->isRowNotEmpty($row)) {
				
				$cellIterator=$row->getCellIterator();
                                
                                
				$data[$key]['site_jobcard']=$jc_number;
				$data[$key]['site_sms']=$sms_number;
                                $data[$key]['site_client_fk']=$_SESSION['client']['client_id'];
				
				
				foreach($cellIterator as $cell){
					
					switch($cell->getColumn()){
						case 'A':
						$data[$key]['site_id']=$cell->getValue();
						break;
						case 'B':
						$data[$key]['site_location']=$cell->getValue();
						break;
						case 'C':
						$data[$key]['site_city']=$cell->getValue();
						break;
						case 'D':
						$data[$key]['site_address']=$cell->getValue();
						break;
						case 'E':
						$data[$key]['site_lattitude']=$cell->getValue();
						break;
						case 'F':
						$data[$key]['site_longitude']=$cell->getValue();
						break;
						case 'G':
						$data[$key]['site_contact_name']=$cell->getValue();
						break;
						case 'H':
						$data[$key]['site_contact_number']=$cell->getValue();
						break;
						case 'I':
						$data[$key]['site_contact_email']=$cell->getValue();
						break;  
					}// end of switch 
					
				}// end celliterator
				
				$data[$key]['status']=1;
				$data[$key]['created_date']= now();
				$id = $this->SiteId_model->get_masterData_id($jc_number,$sms_number);
				$data[$key]['master_id']=$id;
			}
			
	    }// End row Iterator
            
           //echo "<pre>";
           // print_r($data);  die;

		// insert data into database
		$result=$this->SiteId_model->import_siteID_xls($data);	
		if($result){
			$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>SITE ID LIST SUCCESSFULLY IMPORTED</div>");
			return true;
		}else{
			return false;
		}
	}
	
	function ajax_get_components(){
		$query=$_GET['search'];
		$this->load->database();
		if(!empty($query)){
			$sql="select component_code from components where component_code like '%$query%' AND status=1";
		}else{
			$sql="select component_code from components where status=1";
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$components=$query->result_array();
			$respose='';
			foreach($components as $component){
				$respose.="<p>".$component['component_code'];
				$respose.='<input class="pull-right" type="checkbox" name="product_components[]" id="chk_'.$component['component_code'].'"';
				$respose.='value="'.$component['component_code'].'"';
				$respose.='/></p>'; 
			}  
			echo $respose;
		}
	}
	
	public function delete_siteID($siteID_id=null){

		if($siteID_id!=null){
			if($this->SiteId_model->delete_siteId_inspector_form($siteID_id)){
				$this->SiteId_model->delete_siteID_data($siteID_id);
				$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>Record successfully deleted.</div>");
				redirect($this->client_url.'SiteId_kare/siteId_master');	
			}
		}else{
			echo "No id found";
		}
	}	
	
	
	function ajax_update_mdata(){
		$id=$_GET['id'];
		$field=$_GET['field'];
		$value=$_GET['value'];
		$this->load->database();
		if(!empty($value)){
			$sql="update master_data set $field ='$value' where mdata_id=$id";
			$query=$this->db->query($sql);
			if($query){
				echo true;
			} 
		}else{
			echo false;
		}
	}	
	

	function ajax_get_jobCards(){
		$query=$_GET['search'];
		
		$this->load->database();
		if(!empty($query)){
			$sql="select mdata_id,mdata_jobcard,mdata_sms from master_data where (mdata_jobcard like '%$query%' or mdata_sms like '%$query%')  AND status=1 ";
		}else{
			$sql="select mdata_id,mdata_jobcard,mdata_sms from master_data where  status='Active'"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$components=$query->result_array();
			$respose='';
			foreach($components as $component){
				$respose.="<p>".$component['mdata_jobcard'].'_'.$component['mdata_sms'];
				$respose.='<input class="pull-right" type="checkbox" name="jobCardNo[]" id="chk_'.$component['mdata_id'].'_'.$component['mdata_sms'].'" value="'.$component['mdata_id'].'" rel="'.$component['mdata_jobcard'].'_'.$component['mdata_sms'].'"';
				$respose.='/></p>'; 
			}
			echo $respose;
		}
	}
	
	
	public function valid_jobSMS_from_ajax(){

		$job = strtoupper(strtolower(trim($_POST['jobCard'])));
		$sms = strtoupper(strtolower(trim($_POST['sms'])));
		$jobSMS = $this->kare_model->jobcardSMS_unique_identity($job,$sms);
		if(!$jobSMS){
			echo 'NO';
		}else{
			echo "YES";
		}
	}

	function ajax_get_sms()
	{
		$jobcard = $_POST['jobcard'];
		$sms = $this->SiteId_model->ajax_get_sms($jobcard);
	
		if($sms){
			echo "<option value=''> - Select SMS Number - </option>";
			foreach($sms as $val){
				echo "<option value='".$val->mdata_sms."'>".$val->mdata_sms."</option>";
			}
			echo "#".$val->mdata_id;
		}
	}
	
	function ajax_get_sms_inspector(){
		$jobcard = $_POST['jobcard'];
		$sms = $this->SiteId_model->ajax_get_sms($jobcard);
		
		if($sms){
			echo "<option value=''> - Select SMS Number - </option>";
			foreach($sms as $val){
				echo "<option value='".$val->mdata_sms."'>".$val->mdata_sms."</option>";
			}
		}
	}
	
	function ajax_search_get_siteID(){
		$jobCard	=	trim($_GET['jobCard']);
		$sms_number	=	trim($_GET['sms_number']);
		$query		=	$_GET['search'];
		
		$this->load->database();
		if($query!='blank'){
			$sql="select siteID_id,site_id from siteID_data where (site_jobcard='".$jobCard."' AND site_sms='".$sms_number."' and site_id like '%$query%') and status=1";
		}else{
			$sql="select siteID_id,site_id from siteID_data where (site_jobcard='".$jobCard."' AND site_sms='".$sms_number."') and status=1"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$siteID=$query->result_array();
			
			// Remove site_ids which are duplicate.
			$this->load->model('Siteid_model');
			$duplicate_site_id = $this->Siteid_model->duplicate_jobSMSSite_list();
			
			foreach($siteID as $skey=>$sVal){
				foreach($duplicate_site_id as $dVal){
					if($sVal['site_id'] == $dVal['site']){
						unset($siteID[$skey]);
					}
				}
			}
			$new_arr = array();
			foreach($siteID as $vals){
				$new_arr[] = $vals;
			}
			
			$respose='';
			foreach($new_arr as $component){
				$site_id = $component['siteID_id'];
				$this->load->model('kare_model');
				$result = $this->kare_model->check_site_id_in_inspectorData($jobCard,$sms_number,$site_id);
				if(!$result){
					$respose.="<p>".$component['site_id'];
					$respose.='<input class="pull-right" type="checkbox" name="jobCardNo[]" id="chk_'.$component['site_id'].'" value="'.$component['siteID_id'].'" rel="'.$component['site_id'].'"';
					$respose.='/></p>'; 
				}
			}  
			echo $respose;
		}
	}
	
	function ajax_search_get_jobCard(){
		$query		=	$_GET['search'];
		if($query!='blank'){
			$sql="select mdata_jobcard from master_data where ( mdata_jobcard like '%$query%' and status='Active') GROUP BY mdata_jobcard ";
		}else{
			$sql="select mdata_jobcard from master_data where status='Active' GROUP BY mdata_jobcard"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$jobCard=$query->result_array();
				echo "<option value=''> Select Job Card <?option>";
			foreach($jobCard as $jobValue){ ?>
				<option value='<?php echo $jobValue['mdata_jobcard']; ?>'><?php echo $jobValue['mdata_jobcard']; ?></option>
			<?php }
		}else{
			echo "<option value=''> No Result Found <?option>";
		}
	}
	
	function export_siteID_data(){
		
		$jobCard = $this->input->post('jc_number');
		$sms = $this->input->post('sms_number');
		if($jobCard !='' && $sms !=''){
			if($this->input->post('export_filetype') == 'CSV Format'){
				$rel = $this->input->post('export_filetype');
				$this->export_siteID_csv($rel,$jobCard,$sms);
			}else if($this->input->post('export_filetype') == 'XLS Format'){
				$rel = $this->input->post('export_filetype');
				$this->export_siteIDdata_xls($rel,$jobCard,$sms);
			}
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Please Select both Job Card and SMS Number to Export the data.</div>');
			redirect($this->client_url.'siteId_kare/siteId_master');
		}
		
	}
	
	
	public function export_siteID_csv($rel,$jobCard,$sms){
		
		
		$result =	$this->SiteId_model->get_siteID_list_forDownload($rel,$jobCard,$sms);
		//$afftectedRows=$result->affected_rows();
		if(!$result->num_rows()>0){
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">No data available for selected Job card and Sms Number.</div>');
			redirect($this->client_url.'siteId_kare/siteId_master');
		}
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "SiteID_Excel_test.csv";
		
		$data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
		force_download($filename, $data);
	}
	
	
	public function export_siteIDdata_xls($rel,$jobCard,$sms)
	{
		//error_reporting(E_ALL);
		//	ini_set("memory_limit","512M");
	
		$master_list =	$this->SiteId_model->get_siteID_list_forDownload($rel,$jobCard,$sms);
		if(empty($master_list)){
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">No data available for selected Job card and Sms Number.</div>');
			redirect($this->client_url.'siteId_kare/siteId_master');
		}
		foreach($master_list as $key=>$value){
			$master_list[$key]['Site Status'] = ($value['Site Status'] == 'Active')? 'Active' : 'Inactive';
		}

		$this->load->library('excel');
		
		$objPHPExcel = new PHPExcel();
			
		$objPHPExcel->getProperties()->setTitle("Site ID Data")
						->setSubject("Site Id XLSX ")
						->setDescription("SiteID data for Office 2007 XLSX, generated by PHPExcel.")
						->setKeywords("site id office 2007 openxml php")
						->setCategory("Site ID Data File");
		$objPHPExcel->getActiveSheet()->setTitle('Site ID List');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Job Card')
					->setCellValue('B1', 'SMS Number')
					->setCellValue('C1', 'Site ID')
					->setCellValue('D1', 'Site Location')
					->setCellValue('E1', 'Site City')
					->setCellValue('F1', 'Site Address')
					->setCellValue('G1', 'Site Lattitude')
					->setCellValue('H1', 'Site Longitude')
					->setCellValue('I1', 'Contact Person Name')
					->setCellValue('J1', 'Contact Person Number')
					->setCellValue('K1', 'Contact Person Email')
					->setCellValue('L1', 'Site Status');

		  // Style Header
			$header = 'A1:L1';
			$style = array(
				'font' => array('bold' => true,),
				'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
				'startcolor' => array(
									 'rgb' => '000000'
								)
			);
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style);
			

			for($col = ord('A'); $col <= ord('L'); $col++){
				$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(10);
				$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
			}
		
		// read data to active sheet
	
		$objPHPExcel->getActiveSheet()->fromArray($master_list, null, 'A2');
		
		$filename='Site_ID_LIST.xls'; //save our workbook as this file name
 
		header('Content-Type: application/vnd.ms-excel'); //mime type
 
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
 
		header('Cache-Control: max-age=0'); //no cache
					
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}
	
}// end of controller class 




?>