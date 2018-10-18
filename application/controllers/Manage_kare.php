<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_kare extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct(){
		parent::__construct();
		$this->load->model('kare_model');
		$this->load->helper(array('url','date','form','kare','download'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
		$this->load->library('s3');
		$this->auth = new stdClass;
		$this->load->library('flexi_auth');
                
                if (!$this->input->is_ajax_request()) {
                     if(1==1){
                    $sections = array(
                        'benchmarks' => FALSE, 'memory_usage' => FALSE,
                        'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE,
                        'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
                    );  
                    $this->output->set_profiler_sections($sections);
                    $this->output->enable_profiler(TRUE);                    
                  }
                   
                }
               
                // current domian/client info 
                #$this->domain = $_SESSION['client'];
                #$this->domian_client = $_SESSION['client'];
                
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id = $_SESSION['client']['client_id'];
                
		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			//$this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect($this->client_url.'auth');
		}
		
		$this->load->vars('base_url', base_url().$this->client_url);
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data = null;
		$this->data['lang']  = $this->sma->get_lang_level('first');
	}
		
		
  	public function index(){ 
		redirect($this->client_url.'auth');
	}
		
		
	public function angular(){
		$this->load->view('angular');
	}
	
	function ajax_get_asset(){
		//$component_list=array_column($CI->kare_model->get_components_list(),'component_code');
		$query=$_GET['search'];
		$this->load->database();
		if($query != 'blank'){
				$sql="select * from components where component_code like '%$query%'";
		}else{
			$sql="select * from components"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$result=$query->result_array();
			$respose='';
			foreach($result as $key =>$sub_assets){
				$respose.="<p>".$sub_assets['component_code'];
				$respose.='<input class="pull-right" type="checkbox" name="assetSerise[]" id="chk_'.$key.'"';
				$respose.='value="'.$sub_assets['component_code'].'"';
				$respose.='rel="'.$key.'"';
				$respose.='/></p>'; 
			}  
			echo $respose;
		}

	}
	
	function ajax_get_expected_result(){
		$query=$_GET['search'];
		$this->load->database();
		if($query != 'blank'){
				$sql="select id,type_name from type_category where type_name like '%$query%' AND status=1 AND type_category = 3";
		}else{
			$sql="select id,type_name from type_category WHERE status=1 AND type_category = 3"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$result=$query->result_array();
			$respose='';
			foreach($result as $key =>$sub_assets){
				$respose.="<p>".$sub_assets['type_name'];
				$respose.='<input class="pull-right" type="checkbox" name="expectedResult[]" id="chk_'.$sub_assets['id'].'"';
				$respose.='value="'.$sub_assets['id'].'"';
				$respose.='rel="'.$sub_assets['type_name'].'"';
				$respose.='/></p>'; 
			}  
			echo $respose;
		}

	}
	
	function ajax_get_observations(){
		$query=$_GET['search'];
		$this->load->database();
		if($query != 'blank'){
				$sql="select id,type_name from type_category where type_name like '%$query%' AND status=1 AND type_category = 17";
		}else{
			$sql="select id,type_name from type_category WHERE status=1 AND type_category = 17"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$result=$query->result_array();
			$respose='';
			foreach($result as $key =>$sub_assets){
				$respose.="<p>".$sub_assets['type_name'];
				$respose.='<input class="pull-right" type="checkbox" name="observation[]" id="chk_'.$sub_assets['id'].'"';
				$respose.='value="'.$sub_assets['id'].'"';
				$respose.='rel="'.$sub_assets['type_name'].'"';
				$respose.='/></p>'; 
			}  
			echo $respose;
		}

	}
	
	function assetexport(){
		$param = array();
		$param['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$param['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
		$this->load->model('Excelsheet'); 
		$result = $this->Excelsheet->assets_list($param);
		
		exit;
	}

	function assetseriesexport(){
		$param = array();
		$param['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$param['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
		$this->load->model('Excelsheet'); 
		$result = $this->Excelsheet->assets_series_list($param);
		
		exit;
	}

	function searchassetsAjax(){
		$params = array();
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		
		$this->load->model('Subassets_model');
		$inspection		=	$this->Subassets_model->get_type_category('inspection');
		$uom			=	$this->Subassets_model->get_type_category('uom');
		$result			=	$this->Subassets_model->get_type_category('result');
		$observations	=	$this->Subassets_model->get_type_category('observations');
		
		$this->load->model('SiteId_model');
		$assetData = $this->SiteId_model->get_asset_list($params);
		
		$html = '';
		
         $html = '<table id="example_datatable1" class="table table-bordered table-hover">
                    <thead>
						<tr>
							<th>Action</th>
							<th>Assets Code</th>
							<th>Description</th>
							<th>Image</th>
							<th>UOM</th>
							<th>Inspection Type</th>
							<th>Expected Result</th>
							<th>Observations</th>
							<th>Repair</th>
							<th>Infonet Status</th>
							<th>Status</th>
							<th> Add Featured Image</th>
							<th>Date</th>
						</tr>
                    </thead>';
         if(!empty($assetData) && is_array($assetData)){
            $html .= '<tbody>';
            $c = 1;
             foreach ($assetData as $ky => $value) {
                $html .= '<td>';
					$html .= '<a class="text-primary" href="'.base_url().'manage_kare/edit_assets/'.$value['component_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;';
					$html .= '<a href="'. base_url().'manage_kare/delete_component/'.$value['component_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>';
				$html .= '</td>';
				
                $html .= '<td>'.$value['component_code'].'</td>';
                $html .= '<td>'.$value['component_description'].'</td>';
				if($value['component_imagepath']!=''){
					$imagePath = '<img src="'.$value['component_imagepath'].'" width="60" height="60" />';
				}else{
					$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
				}
                $html .= '<td>'.$imagePath.'</td>';
				if(is_array($uom) && !empty($value['component_uom'])){
					if(array_key_exists($value['component_uom'],$uom)){
						$uom_value = "<p>".$uom[$value['component_uom']]."</p>";
					}else{
						$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_uom']."</p>";
					}
				}else{
					$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_uom']."</p>";
				}
                $html .= '<td>'.$uom_value.'</td>';
				if(is_array($inspection) && !empty($value['component_inspectiontype'])){
					if(array_key_exists($value['component_inspectiontype'],$inspection)){
						$inspection_value = "<p>".$inspection[$value['component_inspectiontype']]."</p>";
					}else{
						$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_inspectiontype']."</p>";
					}
				}else{
					$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_inspectiontype']."</p>";
				}
                $html .= '<td>'.$inspection_value.'</td>';
				if(is_array($result) && !empty($value['component_expectedresult'])){
					$excpected_result = json_decode($value['component_expectedresult'],true);
					$result_value = '';
					foreach($excpected_result as $expResult){
						if(array_key_exists($expResult,$result)){
							$result_value .=  "<p>".$result[$expResult]."</p><hr>";
						}else{
							$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_expectedresult']."</p>";
						}
					}
				}else{
					$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_expectedresult']."</p>";
				}
                $html .= '<td>'.$result_value.'</td>';
				if(is_array($observations) && !empty($value['component_observation'])){
						$observations_array = json_decode($value['component_observation'],true);
						$observation_value = '';
						foreach($observations_array as $obsResult){
							if(array_key_exists($obsResult,$observations)){
								$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
							}else{
								$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_observation']."</p>";
							}
						}
				}else{
					$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_observation']."</p>";
				}
                $html .= '<td>'.$observation_value.'</td>';
                $html .= '<td>'.strtoupper($value['component_repair']).'</td>';
				$infonet_status =  ($value['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                $html .= '<td>'.$infonet_status.'</td>';
				$status =  ($value['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                $html .= '<td>'.$status.'</td>';
				$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=asset&id='.$value['component_id'].'">Featured Image</a>';
				$html .= '<td>'.$featuredImage.'</td>';
                $html .= '<td>'.date("M jS, Y", strtotime($value['component_created_date'])).'</td>';
                $html .= '</tr>';
                $c++;
             }
           
            $html .= '</tbody>'; 
         }else{
             $html .= '<tbody><tr><td colspan="7" class="highlight_red"> No Data are available. </td> </tr></tbody>';
         }
         
         $html .= '</table>';
        
        
         print $html;
         exit(); 
	}
	
	
	function searchassetsSeriesAjax(){
		$params = array();
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		
		$data = array();
		$this->load->model('kare_model');
		$components = $this->kare_model->get_components_list();
		if(!empty($components) && is_array($components)){
			foreach($components as $component){
				$components_list[]=$component['component_code'];
			}
		}else{
			$components_list = '';
		}
		
		$this->load->model('Subassets_model');
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
			
		$inspection	=	$this->Subassets_model->get_type_category('inspection');
		
		$this->load->model('SiteId_model');
		$assetData = $this->SiteId_model->get_assets_series_list($params);
		
		$html = '';
		
         $html = '<table id="example_datatable1" class="table table-bordered table-hover">
                    <thead>
						<tr>
							<th>Action</th>
							<th>Assets Code</th>
							<th>Description</th>
							<th>Image</th>
							<th>Asset List</th>
							<th>Inspection Type</th>
							<th>Infonet Status</th>
							<th>Status</th>
							<th>Date</th>
						</tr>
                    </thead>';
         if(!empty($assetData) && is_array($assetData)){
            $html .= '<tbody>';
            $c = 1;
             foreach ($assetData as $ky => $value) {
                $html .= '<td>';
					$html .= '<a class="text-primary" href="'.base_url().'manage_kare/asset_series_list/'.$value['product_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;';
					$html .= '<a href="'. base_url().'manage_kare/delete_asset_series/'.$value['product_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>';
				$html .= '</td>';
				
                $html .= '<td>'.$value['product_code'].'</td>';
                $html .= '<td>'.$value['product_description'].'</td>';
				if($value['product_imagepath']!=''){
					$imagePath = '<img src="'.$value['product_imagepath'].'" width="60" height="60" />';
				}else{
					$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
				}
                $html .= '<td>'.$imagePath.'</td>';
				$components = ($value['product_components']!='')?json_decode($value['product_components'],true):$value['product_components'];
				if(is_array($components)){
					$asset ='';
					foreach($components as $code){
						$tCode = trim($code);
						$value = strtolower($tCode);
						if(is_array($asset_array)){
							if(in_array(strtolower($value),$asset_array)){
								$asset .=  "<p>".$tCode."</p>";
							}else{
								$asset .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
							}
						}else{
							$asset = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
						}
					}	
				}
				
                $html .= '<td>'.$asset.'</td>';
				if(is_array($inspection) && !empty($value['product_inspectiontype'])){
					if(array_key_exists($value['product_inspectiontype'],$inspection)){
						$inspection_value = "<p>".$inspection[$value['product_inspectiontype']]."</p>";
					}else{
						$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['product_inspectiontype']."</p>";
					}
				}else{
					$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['product_inspectiontype']."</p>";
				}
                $html .= '<td>'.$inspection_value.'</td>';
				$infonet_status =  ($value['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                $html .= '<td>'.$infonet_status.'</td>';
				$status =  ($value['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                $html .= '<td>'.$status.'</td>';
				$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=asset&id='.$value['product_id'].'">Featured Image</a>';
				$html .= '<td>'.$featuredImage.'</td>';
                $html .= '<td>'.date("M jS, Y", strtotime($value['product_created_date'])).'</td>';
                $html .= '</tr>';
                $c++;
             }
           
            $html .= '</tbody>'; 
         }else{
             $html .= '<tbody><tr><td colspan="7" class="highlight_red"> No Data are available. </td> </tr></tbody>';
         }
         
         $html .= '</table>';
        
        
         print $html;
         exit(); 
	}
	
	public function assets_list(){
            
                // client_id
                $client_id=$this->client_id;
                
            
		$data['lang'] = $this->data['lang'];
		$data['components']=$this->kare_model->get_components_list();
		$this->load->model('Subassets_model');
		$data['inspection'] = $this->Subassets_model->get_type_category('inspection');
		
		$data['uom'] = $this->Subassets_model->get_type_category('uom');

		$data['result'] = $this->Subassets_model->get_type_category('result');
		
		$data['observation'] = $this->Subassets_model->get_type_category('observations');
                $data['standCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Standards','status'=>1));
		$data['NotifiedBodyCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Certification)','status'=>1));
		$data['Article11BCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Article 11B)','status'=>1));
		//print_r($data);die;
		//$data['action_proposed'] = $this->Subassets_model->get_type_category('action_proposed');

		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		
		if(isset($_POST['save_assets'])){
		   
			$productCode			=	strtoupper(strtolower(trim($this->input->post('product_code'))));
			$productDescription		=	trim($this->input->post('product_description'));
			$productSubAssets		=	count($this->input->post('sub_assets[]'))?json_encode($this->input->post('sub_assets[]')):'';
			$productUom				=	$this->input->post('product_uom');
			$productInspectiontype	=	$this->input->post('product_inspectiontype');	
			$productExpectedresult	=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';	
			$productObservation		=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';	
		//	$productExcerpt			=	trim($this->input->post('product_excerpt'));	
			$productRepair			=	$this->input->post('product_repair');
			
			$geo_fancing			=	$this->input->post('geo_fancing');
                        $work_permit			=	$this->input->post('work_permit');

			$standards_certificate	=	$this->input->post('standards_certificate');
			$ec_type_certificate_txt=	$this->input->post('ec_type_certificate_txt');
			$notified_body_certificate	=	$this->input->post('notified_certified');
			$article_11b_certificate=	$this->input->post('article_11b_certificate');
                        
                        $product_frequency=	$this->input->post('product_frequency');

			//$infonet_status_status			=	$this->input->post('infonet_status_status');
			if($this->input->post('infonet_status_status')=='Active'){
				$infonet_status_status = 1;				
			}elseif($this->input->post('infonet_status_status')==1){
				$infonet_status_status = 1;	
			}else{
				$infonet_status_status = 0;
			}
			$createdDate 			= 	date('Y-m-d H:i:s',now());
			$productStatus			=	$this->input->post('status');
                        
			/*if($this->input->post('frequency_asset') < 4){
				$frequency_asset	= 12;
			}else{
				$frequency_asset	= $this->input->post('frequency_asset');
			}*/ 
                        
                        $frequency_asset	=       $this->input->post('frequency_asset');
                        $frequency_hours        =	$this->input->post('frequency_hours');
                        $lifespan_month         =	$this->input->post('lifespan_month');
                        $lifespan_hours         =	$this->input->post('lifespan_hours');
                        $permain                =	$this->input->post('permain');
			
			// set form validation 
			$this->form_validation->set_rules('product_code','Product Code','required');
			$this->form_validation->set_rules('product_uom','Product UOM','required');
			$this->form_validation->set_rules('status','Status','required');
			// $this->form_validation->set_rules('date_of_first_use','Date of First Use','required');
			// $this->form_validation->set_rules('date_of_inspection','Date of Inspection','required');
			// $this->form_validation->set_rules('inspection_due_date','Inspection Due Date','required');
			
			if($this->form_validation->run()==false){
				$data['msg']='Validation Error';
				$this->load->view('assets_list',$data);
			}else{
				$productImage=$productImagePath='';
				if($_FILES['product_image']['name']!=''){
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/".$this->client_id."/images/assets";
                                        if(!file_exists($file_path)){
                                            mkdir($file_path,0777,true);
                                        }
					$field_name = 'product_image';
                                        
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
                                        
					if($this->upload_photo($file_path,$newName,$field_name)){
                                            
						$productImage = $newName;
						$dbdata['component_image'] = $productImage;
						$productImagePath='./uploads/'.$this->client_id.'/images/assets/'.$newName;	
						$dbdata['component_imagepath']=$productImagePath; 
                                                
					}else{
						redirect($this->client_url.'manage_kare/assets_list');
					}
				}
				
				$dbdata	=	array('component_code'=>$productCode,
                                                                'component_client_fk'=>$this->client_id,
								'component_description'=>trim($productDescription),
								'component_sub_assets'=>$productSubAssets,
								'component_uom'=>$productUom,
								'component_image'=>$productImage,
								'component_imagepath'=>$productImagePath,
								'component_inspectiontype'=>$productInspectiontype,
								'component_expectedresult'=>$productExpectedresult,
								'component_observation'=>$productObservation,
								'component_repair'=>$productRepair,
								'component_geo_fancing'=>$geo_fancing,
								'component_work_permit'=>$work_permit,
								'component_frequency_asset'=>$frequency_asset,
                                                                'component_frequency_hours'=>$frequency_hours,
                                                                'component_lifespan_month'=>$lifespan_month,    
                                                                'component_lifespan_hours'=>$lifespan_hours,
                                                                'component_pm_freq'=>$permain,                      
								'component_created_date'=>$createdDate,
								'infonet_status'=>$infonet_status_status,
								'standard_certificate_id'=>$standards_certificate,
								'ec_type_certificate_text'=>$ec_type_certificate_txt,
								'notified_body_certificate_id'=>$notified_body_certificate,
								'article_11b_certificate_id'=>$article_11b_certificate,
								'status'=>$productStatus,

							);
				#print_r($dbdata);die('123');
				$dublicateProduct = $this->kare_model->check_product('components',$productCode);
				if($dublicateProduct > 0){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Please check product name.</div>');
					redirect(current_url());
				}
				$result	= $this->kare_model->save_components_list($dbdata);
				if(!is_array($result)){     
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Product Successfully submited.</div>');
					redirect(current_url());
				}else{
					if($result['code']==1062){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Component code could not be duplicate.</div>');
					}else{
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$result['message'].'</div>');  
					}
					redirect(current_url());
				}
			}
		}else{	
			$this->load->view('assets_list',$data);
		}
	}

	public function assets_list_old(){
		
		$data['components']=$this->kare_model->get_components_list();
		$this->load->model('Subassets_model');
		$data['inspection'] = $this->Subassets_model->get_type_category('inspection');
		
		$data['uom'] = $this->Subassets_model->get_type_category('uom');

		$data['result'] = $this->Subassets_model->get_type_category('result');
		
		$data['observation'] = $this->Subassets_model->get_type_category('observations');
        

		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		
		if(isset($_POST['save_assets'])){
			$speci_file_id          =   $this->input->post('speci_file_id');
			$productCode			=	strtoupper(strtolower(trim($this->input->post('product_code'))));
			$productDescription		=	trim($this->input->post('product_description'));
			$productSubAssets		=	count($this->input->post('sub_assets[]'))?json_encode($this->input->post('sub_assets[]')):'';
			$productUom				=	$this->input->post('product_uom');
			$productInspectiontype	=	$this->input->post('product_inspectiontype');	
			$productExpectedresult	=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';	
			$productObservation		=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';	
		//	$productExcerpt			=	trim($this->input->post('product_excerpt'));	
			$productRepair			=	$this->input->post('product_repair');
			$createdDate 			= 	date('Y-m-d H:i:s',now());
			
			$productStatus			=	$this->input->post('status');
			$date_of_first_use      =   $this->input->post('date_of_first_use');
			$date_of_inspection     =   $this->input->post('date_of_inspection');
			$inspection_due_date    =   $this->input->post('inspection_due_date');
			
			// set form validation 
			$this->form_validation->set_rules('product_code','Product Code','required');
			$this->form_validation->set_rules('product_uom','Product UOM','required');
			$this->form_validation->set_rules('status','Status','required');
			$this->form_validation->set_rules('date_of_first_use','Date of First Use','required');
			$this->form_validation->set_rules('date_of_inspection','Date of Inspection','required');
			$this->form_validation->set_rules('inspection_due_date','Inspection Due Date','required');
			
			if($this->form_validation->run()==false){
				$data['msg']='Validation Error';
				$this->load->view('assets_list',$data);
			}else{
				$productImage=$productImagePath='';
				if($_FILES['product_image']['name']!=''){
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/images/assets";
					$field_name = 'product_image';
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
					if($this->upload_photo($file_path,$newName,$field_name)){
						$productImage = $newName;
						$dbdata['component_image'] = $productImage;
						$productImagePath='FCPATH/uploads/images/assets/'.$newName;	
						$dbdata['component_imagepath']=$productImagePath; 
					}else{
						redirect($this->client_url.'manage_kare/assets_list');
					}
				}
				
				$dbdata	=	array(
								'speci_file_id'=>$speci_file_id,
								'component_code'=>$productCode,
								'component_description'=>trim($productDescription),
								'component_sub_assets'=>$productSubAssets,
								'component_uom'=>$productUom,
								'component_image'=>$productImage,
								'component_imagepath'=>$productImagePath,
								'component_inspectiontype'=>$productInspectiontype,
								'component_expectedresult'=>$productExpectedresult,
								'component_observation'=>$productObservation,
								'component_repair'=>$productRepair,
								'component_created_date'=>$createdDate,
								'status'=>$productStatus,
								'date_of_first_use'=>date_format($date_of_first_use,"Y-m-d"),
								'date_of_inspection'=>date_format($date_of_inspection,"Y-m-d"),
								'inspection_due_date'=>date_format($inspection_due_date,"Y-m-d"),
							);
				print_r($dbdata);die();
							/*$result = $this->kare_model->save_product($dbdata);
				if($result > 0){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Asset Series Successfully submited.</div>');
				   return true; 	
				}else{
				}*/
				
				$dublicateProduct = $this->kare_model->check_product('components',$productCode);
				if($dublicateProduct > 0){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Please check product name.</div>');
					return true;
				
				}
				
				$result	= $this->kare_model->save_components_list($dbdata);
				//if(!is_array($result)){
				if($result > 0){	
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Product Successfully submited.</div>');
					redirect(current_url());
				}else{
					if($result['code']==1062){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Component code could not be duplicate.</div>');
					}else{
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$result['message'].'</div>');  
					}
					redirect(current_url());
				}
			}
		}else{	
			$this->load->view('assets_list',$data);
		}
	}

	
    function edit_assets($component_id=NULL){
		
	    if($component_id==NULL){
			show_404();
		}else{
			// get the component data
			$component=$this->kare_model->get_component($component_id);
			if(isset($_POST['edit_assets'])&& $_SERVER['REQUEST_METHOD']=='POST'){
				$dbdata['speci_file_id']            =   $this->input->post('speci_file_id');
				$dbdata['component_code']		=	strtoupper(strtolower(trim($this->input->post('component_code'))));
				$dbdata['component_description']	=	trim($this->input->post('component_description'));
				$dbdata['component_sub_assets']		=	count($this->input->post('sub_assets[]'))?json_encode($this->input->post('sub_assets[]')):'';
				$dbdata['component_uom']                =	$this->input->post('component_uom');
				$dbdata['component_inspectiontype']	=	$this->input->post('component_inspectiontype');
				$dbdata['component_expectedresult']	=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';
				$dbdata['component_observation']	=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';
				$dbdata['component_repair']			=	$this->input->post('component_repair');
				$dbdata['status']					=	$this->input->post('status');
				//$dbdata['infonet_status']					=	$this->input->post('infonet_status_status');
				if($this->input->post('infonet_status_status')=='Active'){
					$dbdata['infonet_status'] = 1;				
				}elseif($this->input->post('infonet_status_status')==1){
					$dbdata['infonet_status'] = 1;	
				}else{
					$dbdata['infonet_status'] = 0;
				}	
				$dbdata['component_geo_fancing']			=	$this->input->post('geo_fancing');
				$dbdata['component_work_permit']			=	$this->input->post('work_permit');
                                
				/*if($this->input->post('frequency_asset') < 4){
					$dbdata['component_frequency_asset']	= 12;
				}else{
					$dbdata['component_frequency_asset']	= $this->input->post('frequency_asset');
				}*/
                                
                                $dbdata['component_frequency_asset']	=       $this->input->post('frequency_asset');
                                $dbdata['component_frequency_hours']    =	$this->input->post('frequency_hours');
                                $dbdata['component_lifespan_month']     =	$this->input->post('lifespan_month');
                                $dbdata['component_lifespan_hours']     =	$this->input->post('lifespan_hours');
                                $dbdata['component_pm_freq']            =	$this->input->post('permain');
                        
				$dbdata['standard_certificate_id']	=       $this->input->post('standards_certificate');
				$dbdata['ec_type_certificate_text']	=       $this->input->post('ec_type_certificate_txt');
				$dbdata['notified_body_certificate_id'] =       $this->input->post('notified_certified');
				$dbdata['article_11b_certificate_id']	=       $this->input->post('article_11b_certificate');
				// upload image file 
				
				$productImage=$productImagePath='';
                                
                                if($_FILES['product_image']['name']!=''){
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/".$this->client_id."/images/assets";
                                        if(!file_exists($file_path)){
                                            mkdir($file_path,0777,true);
                                        }
					$field_name = 'product_image';
                                        
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
                                        
					if($this->upload_photo($file_path,$newName,$field_name)){
                                            
						$productImage = $newName;
						$dbdata['component_image'] = $productImage;
						$productImagePath='./uploads/'.$this->client_id.'/images/assets/'.$newName;	
						$dbdata['component_imagepath']=$productImagePath; 
                                                
					}else{
						redirect($this->client_url.'manage_kare/assets_list');
					}
				}
                                
				//print_r($dbdata);die;
				if($dbdata){
					$result=$this->kare_model->update_component($dbdata,$component_id);	
		            if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">Product Successfully updated</div>.');
					}else{
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error in updation.</div>');
					}
					redirect($this->client_url.'manage_kare/assets_list');
				}
			}else{
				$this->load->model('Subassets_model');
				$data['inspection'] = $this->Subassets_model->get_type_category('inspection');
				$data['uom'] = $this->Subassets_model->get_type_category('uom');
				$data['result'] = $this->Subassets_model->get_type_category('result');
				$data['observation'] = $this->Subassets_model->get_type_category('observations');
				$data['standCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Standards','status'=>1));
				$data['NotifiedBodyCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Certification)','status'=>1));
				$data['Article11BCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Article 11B)','status'=>1));
			//	$data['action_proposed'] = $this->Subassets_model->get_type_category('action_proposed');
				$data['component']=$component;
                                
                                #echo "<pre>";
                                #print_r($data); die; 
                                
				$this->load->view('edit_assets',$data);
			}
	    }	
	}

	function upload_photo($file_path, $filename, $field_name){
		$config = array();
		$config['upload_path'] = $file_path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['file_name'] = $filename;
		
		//$this->load->library('upload',$config);
		$this->load->library('upload');
		$this->upload->initialize($config);
		
		if ( ! $this->upload->do_upload($field_name)){
				$error=$this->upload->display_errors();
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$error.'</div>');
				return false;
		}else{
			return true;
		}
	}
	
	public function delete_component($component_id){
        if($component_id!=null){
			if($this->kare_model->delete_component($component_id)){
			    $this->session->set_flashdata('msg','<div class="alert alert-success capital">Component deleted successfully.</div>');
				redirect($this->client_url.'manage_kare/assets_list');	
			}   
		}	
	}

	public function import_assets_list(){
            
            $clinet_id=$_SESSION['client']['client_id'];
		if(isset($_POST['import_assets_list'])&& $_SERVER['REQUEST_METHOD']=='POST'){

                       $dir=FCPATH."/uploads/".$client_id."/xls/";
                       if(!file_exists($dir)){
                           mkdir($dir,0777,true);
                       }
			
			$config['upload_path'] = $dir; 
			$config['allowed_types'] = 'xls|xlsx|csv';
			//$config['max_size'] = '2048';
			$config['max_width'] = '0';
			$config['max_height'] = '0';	
			$this->load->library('upload',$config);
			
			if(!$this->upload->do_upload('file_upload')){
				// $error = array('error' => $this->upload->display_errors());
				 $error=$this->upload->display_errors();
				 $this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$error.'</div>');
				// print_r($error);
	
			}else{
				$upload_data=$this->upload->data();
				
				// get uploaded file path 
				$file_path=$upload_data['full_path'];
				if($file_path){
					$result=$this->import_assets_xls($file_path);
					if($result){
					  $this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
					//  unlink($file_path); // delete the uploded file 	
					 }else{
					  echo "<div class='alert alert-danger capital>file uploading problem</div>";	 
					}
				}
			}
		}
		redirect($this->client_url.'manage_kare/assets_list');
	}

	//  NULL is case insenstive constant of type null
	public function import_assets_xls($file_path=null){
                
                $clinet_id=$_SESSION['client']['client_id'];
	
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
                
                $this->load->model('SiteId_model');
		$this->load->model('Subassets_model');
                
		//get only row iterator
		foreach($objWorksheet->getRowIterator() as $key=>$row){
			
			if($key==1) continue;
			
			if($this->SiteId_model->isRowNotEmpty($row)) {
                            
				$cellIterator=$row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(false); 
				$data[$key]['component_client_fk']=$clinet_id;
                               
				foreach($cellIterator as $cell){        // component_sub_assets
                                    // echo "<pre>";
                                    // print_r($cell->getColumn()); 
                                    // continue;
					switch($cell->getColumn()){
						case 'B':
						$component_code =strtoupper(trim($cell->getValue()));
						$data[$key]['component_code']=preg_replace('/\s+/', '', $component_code);
						break;
						case 'C':
						$data[$key]['component_description']=trim($cell->getValue());
						break;
						case 'D':
						if($cell->getValue() != ''){
							$sub_assets = preg_replace('/\s+/', '',strtoupper(trim($cell->getValue())));
							if(strpos($sub_assets, ',') != false){
								$sub_assets = explode(',',$cell->getValue());
								$data[$key]['component_sub_assets']=json_encode($sub_assets);
							}else{
								$single_value = array();
								$single_value[] =$sub_assets;
								$data[$key]['component_sub_assets']=json_encode($single_value);
							}
						}else{
							$data[$key]['component_sub_assets']='';
						}
						break;
						case 'E':
							if($cell->getValue() !='' ){
								$uomtype = ucwords(strtolower(trim($cell->getValue())));
								$uom=$this->Subassets_model->get_inspection_list('uom',$uomtype);
								
								if($uom){
									$data[$key]['component_uom'] = $uom;
								}else{
									$data[$key]['component_uom'] = $uomtype;
								}
							}else{
								$data[$key]['component_uom']	=	"";
							}
						
						//	$data[$key]['component_uom'] = strtolower($cell->getValue());
						break;
						case 'F':
							if($cell->getValue() !=''){
								$inspectiontype	=	ucwords(strtolower(trim($cell->getValue())));
								$inspection=$this->Subassets_model->get_inspection_list('inspection',$inspectiontype);
								
								if($inspection){
									$data[$key]['component_inspectiontype'] = $inspection;
								}else{
									$data[$key]['component_inspectiontype'] = $inspectiontype;
								}
							}else{
								$data[$key]['component_inspectiontype'] = $cell->getValue();
							}
						break;
                                                
						case 'G':
							if($cell->getValue() !=''){
								$resulttype	=	$cell->getValue();
								if(strpos($resulttype, ',') != false){
									$sub_assets = explode(',',$cell->getValue());
									
									$keyID = array();
									foreach($sub_assets as $subKey=>$subValue){
										$sub_value = ucwords(strtolower(trim($subValue)));
										$result=$this->Subassets_model->get_inspection_list('result',$sub_value);
										if($result){
											$keyID[$subKey] = $result;
										}else{
											$keyID[$subKey] = $sub_value;
										}
									}
									$data[$key]['component_expectedresult'] = json_encode($keyID);
								}else{
									$expResult = array();
									$result=$this->Subassets_model->get_inspection_list('result',ucwords(strtolower(trim($resulttype))));
									if($result){
										$expResult[] = $result;
										$data[$key]['component_expectedresult']=json_encode($expResult);
									}else{
										$expResult[] = ucwords(strtolower(trim($cell->getValue())));
										$data[$key]['component_expectedresult']=json_encode($expResult);
									}
								}
							}else{
								$data[$key]['component_expectedresult']=json_encode($cell->getValue());
							} 
						break;
						case 'H':
							$observations=$cell->getValue();
							if($observations !=''){
								/* check if it has multiple values or single value */
								if(strpos($observations, ',') != false){
									$observation_array = explode(',',$cell->getValue());
									$objkeyID = array();
									foreach($observation_array as $subKey=>$subValue){
										$result=$this->Subassets_model->get_inspection_list('observations',ucwords(strtolower(trim($subValue))));
										if($result){
											$objkeyID[$subKey] = $result;
										}else{
											$objkeyID[$subKey] = ucwords(strtolower(trim($subValue)));
										}
									}
									$data[$key]['component_observation'] = json_encode($objkeyID);
								}else{
									$obsResult = array();
									
									$result=$this->Subassets_model->get_inspection_list('observations',ucwords(strtolower(trim($observations))));
									if($result){
										$obsResult[] = $result;
										$data[$key]['component_observation']=json_encode($obsResult);
									}else{
										$obsResult[] = ucwords(strtolower(trim($cell->getValue())));
										$data[$key]['component_observation']=json_encode($obsResult);
									}
								}
							}else{
								$data[$key]['component_observation']=json_encode($cell->getValue());
							}
						break;
						case 'I':
						     $data[$key]['component_repair'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'J':
						    $data[$key]['component_geo_fancing'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'K':
						   $data[$key]['component_work_permit'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'L':
						    $data[$key]['component_frequency_asset'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'M':
						    $data[$key]['component_frequency_hours'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'N':
						    $data[$key]['component_lifespan_month'] = strtolower(trim($cell->getValue()));
						break;
                                                case 'O':
						    $data[$key]['component_lifespan_hours'] = strtolower(trim($cell->getValue()));
						break;                                                                                     
                                                case 'P':
							$data[$key]['infonet_status'] = strtolower(trim($cell->getValue()));
						break;
					/*case 'O':
						if($cell->getValue() !=''){
							$stantardsStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Standards', 'name'=> $stantardsStr, 'status'=>1 );
							
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$key]['standard_certificate_id']= $certificateArray['id'];
							}else{
								$data[$key]['standard_certificate_id']= '';
							}
						}else{
						   $data[$key]['standard_certificate_id']= '';
						}
					break;

					case 'P':
						if($cell->getValue() !=''){
							$data[$key]['ec_type_certificate_text']= trim($cell->getValue());
						}else{
							$data[$key]['ec_type_certificate_text']= '';
						}
					break;

					case 'Q':
						if($cell->getValue() !=''){
							$notifyBodyStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Notified Body(Certification)', 'name'=> $notifyBodyStr, 'status'=>1 );
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$key]['notified_body_certificate_id']= $certificateArray['id'];
							}else{
								$data[$key]['notified_body_certificate_id']= '';
							}
						}else{
						 $data[$key]['notified_body_certificate_id']= '';
						}
					break;

					case 'R':
						if($cell->getValue() !=''){
							$notifyBodyStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Notified Body(Article 11B)', 'name'=> $notifyBodyStr, 'status'=>1 );
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$key]['article_11b_certificate_id']= $certificateArray['id'];
							}else{
								$data[$key]['article_11b_certificate_id']= '';
							}	
						}else{
						 $data[$key]['article_11b_certificate_id']= '';
						}
					break;*/
					   
					}// end of switch
					$data[$key]['component_created_date'] = date('Y-m-d H:i:s',now());
					$data[$key]['status'] = 'Active';
				}// end celliterator
			}
	    }// End row Iterator
            
            //echo "<pre>";                                    
            //print_r($data); die;
		$result=$this->kare_model->import_assets_list($data);	
		if($result){
			$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>ASSETS LIST SUCCESSFULLY IMPORTED</div>");
			return true;
		}else{
			return false;
		}
		
	}
	
	function assets_search(){
		$params = array();
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		$data = array();
		$this->load->model('Subassets_model');
		$data['inspection']		=	$this->Subassets_model->get_type_category('inspection');
		$data['uom']			=	$this->Subassets_model->get_type_category('uom');
		$data['result']			=	$this->Subassets_model->get_type_category('result');
		$data['observations']	=	$this->Subassets_model->get_type_category('observations');
		
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		
		$this->load->model('SiteId_model');
		$data['assetData'] = $this->SiteId_model->get_asset_list();
		//print_r($data);die;
		$this->load->view('search/assets_search',$data);
	}
        
        // List the product/assets-series
	
	function assets_series_search(){
		$params = array();
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		$data = array();
		$this->load->model('kare_model');
		$components = $this->kare_model->get_components_list('component_code');
		if(!empty($components) && is_array($components)){
			foreach($components as $component){				
				$components_list[]= strtolower($component['component_code']);
			}
		}else{
			$components_list = '';
		}
		
		$this->load->model('Subassets_model');
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]= strtolower($sAssets['sub_assets_code']);
			}
		}else{
			$sub_assets_list = '';
		}
		
		if(is_array($components_list) && is_array($sub_assets_list)){
			$data['asset_array'] = array_merge($components_list,$sub_assets_list);
		}else if(is_array($components_list) && !is_array($sub_assets_list)){
			$data['asset_array'] = $components_list;
		}else if(is_array($sub_assets_list) && !is_array($components_list)){
			$data['asset_array'] = $sub_assets_list;
		}else if(!is_array($sub_assets_list) && !is_array($components_list)){
			$data['asset_array'] = '';
		}
			
		$data['inspection']	=	$this->Subassets_model->get_type_category('inspection');
		$this->load->model('SiteId_model');
		$data['assetData'] = $this->SiteId_model->get_assets_series_list();
		
		$this->load->view('search/assets_series_search',$data);
	}
        
        
        // Add and edit the product/assets-series 

	public function asset_series_list($product_id=NULL){
			$data['lang'] = $this->data['lang'];
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
		
		
		//$data['action_proposed'] = $this->Subassets_model->get_type_category('action_proposed');
		$data['inspection'] = $this->Subassets_model->get_type_category('inspection');
                $data['standCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Standards','status'=>1));
		$data['NotifiedBodyCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Certification)','status'=>1));
		$data['Article11BCerticate'] = $this->Subassets_model->get_manage_cert_filt_result_list(array('type'=>'Notified Body(Article 11B)','status'=>1));	
		
		if($product_id==NULL){
			// insert record
			$products_list=$this->kare_model->get_products_list();
			
			if(isset($_POST['save_asset_series'])){	 	
				if($this->insert_asset_series()){ 
					redirect($this->client_url.'manage_kare/asset_series_list');
				};
			}else{ 
				$data['products_list']=$products_list;	
				$this->load->view('asset_series_list',$data);  
			}
		}else{
		   // update record	
		   
		    $product=$this->kare_model->get_product($product_id);
			
			if(is_array($product)){
				if(isset($_POST['edit_asset_series'])){
					if($this->edit_asset_series($product_id)){
						redirect($this->client_url.'manage_kare/asset_series_list');
					}
				}else{
					$data['product']=$product;	
					$this->load->view('edit_asset_series',$data);  
				}
			}else{
				show_404();	
			}
	    }
	}
	
	
	
	
	public function insert_asset_series(){
                $client_id=$_SESSION['client']['client_id'];                                

		if(isset($_POST['save_asset_series'])){
			$dbdata['speci_file_id']		    =   $this->input->post('speci_file_id');
			$dbdata['product_code']				=	strtoupper(strtolower(trim($this->input->post('product_code'))));
			$dbdata['product_description']		=	trim($this->input->post('product_description'));
			$dbdata['product_components']		=	count($this->input->post('product_components[]'))?json_encode($this->input->post('product_components[]')):'';
			$dbdata['product_inspectiontype']	=	$this->input->post('product_inspectiontype');
		//	$dbdata['product_excerpt']			=	trim($this->input->post('product_excerpt'));
			$dbdata['product_geo_fancing']		=	$this->input->post('geo_fancing');
			$dbdata['product_work_permit']			=	$this->input->post('work_permit');
			if($this->input->post('frequency_asset') < 4){
				$dbdata['product_frequency_asset']	= 12;
			}else{
				$dbdata['product_frequency_asset']	= $this->input->post('frequency_asset');
			} 
			
			$dbdata['standard_certificate_id']		= $this->input->post('standards_certificate');
			$dbdata['ec_type_certificate_text']		= $this->input->post('ec_type_certificate_txt');
			$dbdata['notified_body_certificate_id'] = $this->input->post('notified_certified');
			$dbdata['article_11b_certificate_id']	= $this->input->post('article_11b_certificate');			
			$dbdata['status']					=	$this->input->post('status');
                        
                        $dbdata['product_client_fk']=$client_id;
                        
                        

			//$dbdata['infonet_status']					=	$this->input->post('infonet_status_status');
			if($this->input->post('infonet_status_status')=='Active'){
				$dbdata['infonet_status'] = 1;				
			}elseif($this->input->post('infonet_status_status')==1){
				$dbdata['infonet_status'] = 1;	
			}else{
				$dbdata['infonet_status'] = 0;
			}	
			#print_r($dbdata);die;
                        
			$this->form_validation->set_rules('product_code','Product Code','required');
			$this->form_validation->set_rules('status','Status','required');
		
			if($this->form_validation->run()==false){
					 return false;
			}else{
				
				$productImage=$productImagePath='';
                                
                                if($_FILES['product_image']['name']!=''){
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/".$this->client_id."/images/products";
                                        if(!file_exists($file_path)){
                                            mkdir($file_path,0777,true);
                                        }
					$field_name = 'product_image';
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
                                        
					if($this->upload_photo($file_path,$newName,$field_name)){
                                            
						$productImage = $newName;
						$dbdata['product_image'] = $productImage;
						$productImagePath='./uploads/'.$this->client_id.'/images/products/'.$newName;	
						$dbdata['product_imagepath']=$productImagePath; 
                                                
					}else{
						redirect($this->client_url.'manage_kare/asset_series_list');
					}
				}
                                
				/*if($_FILES['product_image']['name']!=''){
					
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/images/products";
					$field_name = 'product_image';
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
					if($this->upload_photo($file_path,$newName,$field_name)){
						$dbdata['product_image'] = $newName;
						$productImagePath=base_url().'uploads/images/products/'.$newName;	
						$dbdata['product_imagepath']=$productImagePath; 
					}else{
						redirect('manage_kare/asset_series_list');
					}
				}*/
                                
                                
                                
				$dublicateProduct = $this->kare_model->check_product('products',$dbdata['product_code'],1);
				if($dublicateProduct > 0){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Please check product name.</div>');
					return true;
				}
				// submit form data
				$result = $this->kare_model->save_product($dbdata);
				if($result > 0){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Asset Series Successfully submited.</div>');
				   return true; 	
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Please check product name.</div>');
					return true;
				}
			}
		}
	}
	
	public function edit_asset_series($product_id){
		$product=$this->kare_model->get_product($product_id);
		
		if(isset($_POST['edit_asset_series'])){
			$dbdata['speci_file_id']		    =   $this->input->post('speci_file_id');
			$dbdata['product_code']				=	strtoupper(strtolower(trim($this->input->post('product_code'))));
			$dbdata['product_description']		=	trim($this->input->post('product_description'));
			$dbdata['product_components']		=	count($this->input->post('product_components[]'))?json_encode($this->input->post('product_components[]')):'';
			$dbdata['product_inspectiontype']	=	$this->input->post('product_inspectiontype');
		//	$dbdata['product_excerpt']			=	trim($this->input->post('product_excerpt'));
			$dbdata['product_geo_fancing']		=	$this->input->post('geo_fancing');
			$dbdata['product_work_permit']			=	$this->input->post('work_permit');
			if($this->input->post('frequency_asset') < 4){
				$dbdata['product_frequency_asset']	= 12;
			}else{
				$dbdata['product_frequency_asset']	= $this->input->post('frequency_asset');
			} 
			$dbdata['standard_certificate_id']		= $this->input->post('standards_certificate');
			$dbdata['ec_type_certificate_text']		= $this->input->post('ec_type_certificate_txt');
			$dbdata['notified_body_certificate_id'] = $this->input->post('notified_certified');
			$dbdata['article_11b_certificate_id']	= $this->input->post('article_11b_certificate');

			$dbdata['status']					=	$this->input->post('status');
			if($this->input->post('infonet_status_status')=='Active'){
				$dbdata['infonet_status'] = 1;				
			}elseif($this->input->post('infonet_status_status')==1){
				$dbdata['infonet_status'] = 1;	
			}else{
				$dbdata['infonet_status'] = 0;
			}	
			//print_r($dbdata);die;
			$this->form_validation->set_rules('product_code','Product Code','required');
			$this->form_validation->set_rules('status','Status','required');

			if($this->form_validation->run()==false){
				 return false;
				
			}else{
				$productImage=$productImagePath='';
                                      if($_FILES['product_image']['name']!=''){
					$name = $_FILES['product_image']['name'];
					$file_path = "./uploads/".$this->client_id."/images/products";
                                        if(!file_exists($file_path)){
                                            mkdir($file_path,0777,true);
                                        }
					$field_name = 'product_image';
					$newName = time().".".pathinfo($name, PATHINFO_EXTENSION);
                                        
					if($this->upload_photo($file_path,$newName,$field_name)){
                                            
						$productImage = $newName;
						$dbdata['product_image'] = $productImage;
						$productImagePath='./uploads/'.$this->client_id.'/images/products/'.$newName;	
						$dbdata['product_imagepath']=$productImagePath; 
                                                
					}else{
						redirect($this->client_url.'manage_kare/asset_series_list');
					}
				}
				
				// submit form data
				if($this->kare_model->update_product($dbdata,$product_id)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Asset Series Successfully Updated.</div>');
					return true;
				}	
			}
		}
	}
	
	
	
	
	public function delete_asset_series($product_id){
		if($product_id!=null){
			if($this->kare_model->delete_prodcut($product_id)){
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Asset Series deleted successfully.</div>');
				redirect($this->client_url.'manage_kare/asset_series_list');	
			 }   
		}
	}	
	
	
	function upload_featured_images($featured_image_id = null){
		
		if(isset($_REQUEST['id']) && isset($_REQUEST['type'])){
			$request_id 	= $_SESSION['request_id'] = $_REQUEST['id'];
			$request_type 	= $_SESSION['request_type'] = $_REQUEST['type'];
			$featuredData=$this->kare_model->get_featured_images_list($request_id,$request_type);
			
			if(empty($featuredData)){
				$data['view'] = 'insert';
				$data['image'] = '';
				$this->load->view('component_featured_images',$data);
			}else{
				$data['image'] = $featuredData;
				$data['view'] = '';
				$this->load->view('component_featured_images',$data);
			}
		}
		
		if($featured_image_id !=null){
			$url			= current_url();
			if(isset($_POST['fimage_submit'])){
				$this->insertUpdate_featured_image($featured_image_id,'','',$url);
			}else{
				$dbData=$this->kare_model->get_featured_images_list_by_id($featured_image_id);
				$data['view'] = 'update';
				$data['image'] = $dbData;
				$_SESSION['pass_image'] = $_SESSION['pass_image'] = $dbData['pass_image'];
				$_SESSION['fail_image'] = $dbData['fail_image'];
				$_SESSION['repair_image'] = $dbData['repair_image'];
				$this->load->view('component_featured_images',$data);
			}
		}else{
			if(isset($_POST['fimage_submit'])){
				$request_id 	= $_SESSION['request_id'];
				$request_type 	= $_SESSION['request_type'];
				$url			= current_url();
				if(isset($_SESSION['pass_image'])){
					unset($_SESSION['pass_image']);
				}
				if(isset($_SESSION['pass_image'])){
					unset($_SESSION['pass_image']);
				}
				if(isset($_SESSION['fail_image'])){
				unset($_SESSION['fail_image']);
				}
				if(isset($_SESSION['repair_image'])){
					unset($_SESSION['repair_image']);
				}
				
				$this->insertUpdate_featured_image(null,$request_id,$request_type,$url);
			}
		}
	}	
	
	function set_imageName_path_featuredImage($fileNames,$folderName){

			$names = $_FILES[$fileNames]['name'];
			$newNames = $folderName.time().".".pathinfo($names, PATHINFO_EXTENSION);
			$file_paths = "./uploads/images/featured/".$folderName;
			if(!is_dir($file_paths)){
				mkdir($file_paths,0777,true);
			}

			if($this->upload_photo($file_paths,$newNames,$fileNames)){
				return $newNames;
			}else{
				return false;
			}


	}
	
	function insertUpdate_featured_image($featured_image_id = null,$request_id = '',$request_type='',$url=''){
		// upload image
				
				$pass_imageName = (isset($_SESSION['pass_image']))? $_SESSION['pass_image'] : '';
				$fail_imageName = (isset($_SESSION['fail_image']))? $_SESSION['fail_image'] : '';
				$repair_imageName = (isset($_SESSION['repair_image']))? $_SESSION['repair_image'] : '';

				if(!empty($_FILES['pass_image']['name'])){
					$fileName = 'pass_image';
					$folderName = 'pass';
					if($pass_imageName!=''){
						$old_pic_path = "./uploads/images/featured/pass/".$pass_imageName;
							$this->delete_old_image($old_pic_path);
					}
					$pass_imageName = $this->set_imageName_path_featuredImage($fileName,$folderName);
					if(!$pass_imageName){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! pass image not added</div>');
						redirect($url);
					}
				}
				
				if(!empty($_FILES['fail_image']['name'])){
					$fileName1 = 'fail_image';
					$folderName1 = 'fail';
					if($fail_imageName!=''){
						$old_pic_path = "./uploads/images/featured/fail/".$fail_imageName;
						$this->delete_old_image($old_pic_path);
					}
					$fail_imageName = $this->set_imageName_path_featuredImage($fileName1,$folderName1);					
					if(!$fail_imageName){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! fail image not added</div>');
						redirect($url);
					}
				}
		
				if(!empty($_FILES['repair_image']['name'])){
					$fileName2 = 'repair_image';
					$folderName2 = 'repair';
					$fileName = $folderName= '';
					if($repair_imageName!=''){
							$old_pic_path = "./uploads/images/featured/repair/".$repair_imageName;
							$this->delete_old_image($old_pic_path);
					}
					$repair_imageName = $this->set_imageName_path_featuredImage($fileName2,$folderName2);
					if(!$repair_imageName){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! repair image not added</div>');
						redirect($url);
					}
				}
				
				
				$dbdata['pass_image']	=	$pass_imageName;
				$dbdata['fail_image']	=	$fail_imageName;
				$dbdata['repair_image']	=	$repair_imageName;
				
				if($featured_image_id == null){
					$dbdata['image_id'] 	= 	$request_id;
					$dbdata['type'] 		= 	$request_type;
					
					$ret=$this->kare_model->add_featured_images($dbdata);
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">featured image successfully added</div>');
					
					if($ret!=''){
						$urls = $url.'/'.$ret;
						redirect($urls);
					}
				}else{
					$ret=$this->kare_model->update_featured_images($dbdata,$featured_image_id);
					unset($_SESSION['pass_image']);
					unset($_SESSION['fail_image']);
					unset($_SESSION['repair_image']);
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">featured image successfully updated</div>');
					if($ret){
						redirect($url);	
					}
				}
	}
	
	function delete_featured_images($id){

		$dbData=$this->kare_model->get_featured_images_list_by_id($id);
		if($dbData['pass_image']!=''){
			$pic = "./uploads/images/featured/pass/".$dbData['pass_image'];
			$this->delete_old_image($pic);
		}if($dbData['fail_image']!=''){
			$pic = "./uploads/images/featured/pass/".$dbData['fail_image'];
			$this->delete_old_image($pic);
		}if($dbData['repair_image']!=''){
			$pic = "./uploads/images/featured/pass/".$dbData['repair_image'];
			$this->delete_old_image($pic);
		}
		$result = $this->kare_model->delete_featured_images($id);
		if($result){
			$this->session->set_flashdata('msg','<div class="alert alert-success capital">featured image successfully deleted</div>');
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">error! featured image not deleted </div>');
		}
		if($_SESSION['request_type'] =='asset'){
			redirect($this->client_url.'manage_kare/assets_list');
		}else if($_SESSION['request_type'] =='subAsset'){
			redirect($this->client_url.'subassets_kare/sub_assets_list');
		}else{
			redirect($this->client_url.'auth');
		}
		
	}
	
	function delete_old_image($pic){
		if(file_exists($pic)){
			if(!unlink($pic)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	
	public function mdata_inspection($itemid=NULL){
		
		$group_array = $this->session->flexi_auth['group'];

		foreach($group_array as $key=>$val){
			$data['group_id'] = $group_id = $key;
		}
                
		$data['client_list'] = $client_list=$this->kare_model->get_clientName_list();
                
                //echo "<pre>";
                //print_r($data['client_list']); die;

		if( $itemid == NULL && !isset( $_POST['submit_mdata'] ) ){
			$data['item'] = null;
			if($group_id == 9){
				$data['products_list'] = $this->kare_model->get_mdata_list_of_inspector(NULL,$group_id);
			}else{
			 //	$data['products_list']=$this->kare_model->get_mdata_list();
			}
			$this->load->view('mdata_inspection',$data);	
		}else{

			if(isset($_POST['submit_mdata'])){ 
                                $dbdata['mdata_client_fk']                      = $_SESSION['client']['client_id'];                 
				$job_card					= strtoupper(strtolower(trim($this->input->post('mdata_jobcard'))));
				$sms_number					= strtoupper(strtolower(trim($this->input->post('mdata_sms'))));				
				$dbdata['mdata_jobcard']		= ($job_card != '' )?$job_card:'';
				$dbdata['mdata_sms']			= ($sms_number != '')?$sms_number:'';
				$dbdata['mdata_batch']			= $this->input->post('mdata_batch');	
				$dbdata['mdata_serial']			= $this->input->post('mdata_serial');	
				$dbdata['mdata_rfid']			= $this->input->post('mdata_rfid');	
				$dbdata['mdata_barcode']		= $this->input->post('mdata_barcode');
				$dbdata['mdata_uin']			= $this->input->post('mdata_uin');
				$dbdata['mdata_client']			= $this->input->post('mdata_client');
				$dbdata['mdata_asset']			= !empty($this->input->post('assetSerise'))?json_encode($this->input->post('assetSerise')):'';
				$dbdata['mdata_po']				= $this->input->post('mdata_po');
				$dbdata['date_of_first_use']	= date("Y-m-d", strtotime($this->input->post('date_of_first_use')) );
				$dbdata['date_of_inspection']	= date("Y-m-d", strtotime($this->input->post('date_of_inspection')) );				
				$dbdata['mdata_material_invoice_date']= date("Y-m-d", strtotime($this->input->post('mdata_material_invoice_date')) );
				
				if(!empty($this->input->post('product_components[]'))){
					$dbdata['mdata_item_series']=json_encode($_POST['product_components']);
				}else{
					$dbdata['mdata_item_series'] = '';
				}

				$dbdata['mdata_material_invoice']		= $this->input->post('mdata_material_invoice');					
				$dbdata['status']				= $this->input->post('status');
                                
                                    //echo "<pre>";
                                    //print_r($dbdata); die;
               
				if($itemid==NULL){
					
                                         if( (empty($dbdata['mdata_asset'])) && ( empty($dbdata['mdata_item_series'])) ){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">There be assets or asset series taken.</div>');
							redirect($this->client_url.'manage_kare/mdata_inspection');					
					} 

					if(!empty($dbdata['mdata_asset'])){
						# insert Assets master data 
						if($this->kare_model->insert_mdata($dbdata)){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully inserted</div>');
							redirect($this->client_url.'manage_kare/mdata_inspection');
						}
					}else if( ( !empty($dbdata['mdata_item_series'])) &&  ($jobCard != 0) ){
                                            
                                             //print_r($dbdata); die;	
                                                # insert asset-series/ product master data 
						if($this->kare_model->insert_mdata($dbdata)){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully inserted</div>');
							redirect($this->client_url.'manage_kare/mdata_inspection');
						}
					}else{
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">This job card with same SMS number already exists. Please enter another Job Card No or SMS number. </div>');
						redirect($this->client_url.'manage_kare/mdata_inspection');
					}
				}else{  
                                         if( (empty($dbdata['mdata_asset'])) && ( empty($dbdata['mdata_item_series'])) ){ 
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">There be assets or asset series taken.</div>');
							redirect($this->client_url.'manage_kare/mdata_inspection/'.$itemid);					
					}else{

						if(!empty($dbdata['mdata_asset'])){	  					
							if($this->kare_model->update_mdata($dbdata,$itemid)){
								$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully updated</div>');	
								redirect($this->client_url.'manage_kare/mdata_inspection');
							}
						}else if( !empty($dbdata['mdata_item_series'])){								
							if($this->kare_model->update_mdata($dbdata,$itemid)){
								$this->session->set_flashdata('msg','<div class="alert alert-success capital">Item successfully updated</div>');	
								redirect($this->client_url.'manage_kare/mdata_inspection');
							}
						}
				    }
					//$data['item']=$this->kare_model->get_mdata_item($itemid);	
				}
			}


			if($itemid !=''){					
					$data['item'] = $this->kare_model->get_mdata_item($itemid);	
					if( empty($data['item'])){ 
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">There invalid parameter.</div>');
						redirect($this->client_url.'manage_kare/mdata_inspection');						
					}
					
					if( !empty($data['item']['mdata_asset'] )){  
					   $mdata_assetArray			= !empty($data['item']['mdata_asset'])?json_decode($data['item']['mdata_asset'], true): NULL;
					   if( isset($mdata_assetArray) && ( is_array($mdata_assetArray))){						
							$component_frequency_asset_first = $mdata_assetArray[0];
							$this->load->model('kare_model'); 
							$productComp = $this->kare_model->get_component($component_frequency_asset_first);  
							$frequency_month = (int)$productComp['component_frequency_asset'];
							if( $frequency_month >= 0 ){							
								$data['item']['frequency_month'] = $frequency_month;
							} 
					   }
					}else if( !empty($data['item']['mdata_item_series'] )){ 
					   $mdata_item_seriesArray			= !empty($data['item']['mdata_item_series'])?json_decode($data['item']['mdata_item_series'], true): NULL;
					   if( isset($mdata_item_seriesArray) && ( is_array($mdata_item_seriesArray))){
							$product_frequency_asset_first = $mdata_item_seriesArray[0];					  
							$this->load->model('kare_model');
							$productComp = $this->kare_model->get_product($product_frequency_asset_first);  
							$frequency_month = (int)$productComp['product_frequency_asset'];
							if( $frequency_month >=0  ){							
								$data['item']['frequency_month'] = $frequency_month;
							}											   
					   }
					}
					/*
					$data['item']['frequency_month'] = 12;
					$data['item']['mdata_material_invoice_date']	= '2018-02-15';
					$data['item']['date_of_first_use']				= '';
					$data['item']['date_of_inspection']				= '2018-03-16';
					$data['item']['inspection_due_date']			= '';
					*/
					$threeDateArray  = array(
							'frequency_month'				=> $data['item']['frequency_month'],
							'mdata_material_invoice_date'	=> $data['item']['mdata_material_invoice_date'], 
							'date_of_first_use'				=> $data['item']['date_of_first_use'], 
							'date_of_inspection'			=> $data['item']['date_of_inspection'], 
							'inspection_due_date'			=> $data['item']['inspection_due_date']
					);
				    
					$this->load->model('api_model');
					$threeDaysArry = $this->api_model->frequency_month_mdata_dates($threeDateArray);  
					$data['item']['mdata_material_invoice_date']	= $threeDaysArry['mdata_material_invoice_date'];
					$data['item']['date_of_first_use']				= $threeDaysArry['date_of_first_use'];
					$data['item']['date_of_inspection']				= $threeDaysArry['date_of_inspection'];
					$data['item']['inspection_due_date']			= $threeDaysArry['inspection_due_date'];
					$this->load->view('mdata_inspection_edit',$data);	
			}

		}
	}


	public function import_asset_series_list(){
            $client_id=$_SESSION['client']['client_id'];
		
		if(isset($_POST['import_asset_series_list'])&& $_SERVER['REQUEST_METHOD']=='POST'){
                    
                       $dir=FCPATH."/uploads/".$client_id."/xls/";
                       if(!file_exists($dir)){
                           mkdir($dir,0777,true);
                       }
			
			#$config['upload_path'] = "./uploads/xls/";
			$config['upload_path'] = $dir;
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
				// get uploaded file path 
				$file_path=$upload_data['full_path'];
				if($file_path){
					$result=$this->import_asset_series_xls($file_path);
					if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
						unlink($file_path); // delete the uploded file 	
					}else{
						echo "<div class='alert alert-danger capital'>file uploading problem</div>";	 
					}
				}
			}
		}
		redirect($this->client_url.'manage_kare/asset_series_list');
	}
	
	/*  NULL is case insenstive constant of type null */
	public function import_asset_series_xls($file_path=null){
	
		// load library
                $client_id=$_SESSION['client']['client_id'];
                
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
		//get only row iterator
		$this->load->model('Subassets_model');
		$count = 0;
                $this->load->model('SiteId_model');
                
		foreach($objWorksheet->getRowIterator() as $key=>$row){
			
			if($key==1) continue;
                        
			if($this->SiteId_model->isRowNotEmpty($row)) {
				$cellIterator=$row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(false); // accepts blank column also 
                                $data[$count]['product_client_fk']=$client_id;
				foreach($cellIterator as $cell){
				   switch($cell->getColumn()){
					case 'B':
						$product_code =strtoupper(strtolower(trim($cell->getValue())));
						$data[$count]['product_code']=preg_replace('/\s+/', '', $product_code);
					break;
					case 'C':
						$data[$count]['product_description']=trim($cell->getValue());
					break;
					case 'D':
						if($cell->getValue() !=''){
							$comp_code = strtoupper(strtolower(trim($cell->getValue())));
							$assets = preg_replace('/\s+/', '', $comp_code);
							if(strpos($assets, ',') != false){
								$component_array = explode(',',$assets);
								$data[$count]['product_components']=json_encode($component_array);
							}else{
								$array = array();
								$array[] =  $assets;
								$data[$count]['product_components']=json_encode($array);
							}
						}else{
							$data[$count]['product_components']	= '';
						}
					break;
					case 'E':
						if($cell->getValue() !=''){
							$inspectiontype	=	ucwords(strtolower(trim($cell->getValue())));
							
							$inspection=$this->Subassets_model->get_inspection_list('inspection',$inspectiontype);
							
							if($inspection){
								$data[$count]['product_inspectiontype'] = $inspection;
							}else{
								$data[$count]['product_inspectiontype']=$cell->getValue();
							}
						}else{
							$data[$count]['product_inspectiontype']= '';
						}
					break;

					case 'K':
						if($cell->getValue() !=''){
							$stantardsStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Standards', 'name'=> $stantardsStr, 'status'=>1 );
							
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$count]['standard_certificate_id']= $certificateArray['id'];
							}else{
								$data[$count]['standard_certificate_id']= '';
							}
						}else{
						   $data[$count]['standard_certificate_id']= '';
						}
					break;

					case 'L':
						if($cell->getValue() !=''){
							$data[$count]['ec_type_certificate_text']= trim($cell->getValue());
						}else{
							$data[$count]['ec_type_certificate_text']= '';
						}
					break;

					case 'M':
						if($cell->getValue() !=''){
							$notifyBodyStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Notified Body(Certification)', 'name'=> $notifyBodyStr, 'status'=>1 );
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$count]['notified_body_certificate_id']= $certificateArray['id'];
							}else{
								$data[$count]['notified_body_certificate_id']= '';
							}
						}else{
						 $data[$count]['notified_body_certificate_id']= '';
						}
					break;

					case 'N':
						if($cell->getValue() !=''){
							$notifyBodyStr	=	trim($cell->getValue());
							$condFilterArry  =  array('type' => 'Notified Body(Article 11B)', 'name'=> $notifyBodyStr, 'status'=>1 );
							$certificateArray  = $this->Subassets_model->get_manage_cert_filt_chk_result($condFilterArry);
							if($certificateArray){
								$data[$count]['article_11b_certificate_id']= $certificateArray['id'];
							}else{
								$data[$count]['article_11b_certificate_id']= '';
							}	
						}else{
						 $data[$count]['article_11b_certificate_id']= '';
						}
					break;


					} /* end of switch */
					
					$data[$count]['status'] = 'Active';
					
				} /* end cell iterator */
			}
			$count++;
	    }	/* End row Iterator	*/
		/* insert data into database */
		//echo "<pre>"; print_r($data); echo "</pre>";  exit;
		$result=$this->kare_model->import_products_list($data);	
		if($result){
			$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>ASSET SERIES LIST XLS/CSV FILE SUCCESSFULLY IMPORTED</div>");
			return true;
		}else{
			return false;
		}
	}
	
    public function import_master_data(){
        
            $client_id=$_SESSION['client']['client_id'];
        
		if(isset($_POST['import_inspection_xls'])&& $_SERVER['REQUEST_METHOD']=='POST'){
                    
                       $dir=FCPATH."/uploads/".$client_id."/xls/";
                       if(!file_exists($dir)){
                           mkdir($dir,0777,true);
                       }
			
			$config['upload_path'] = $dir;
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
				
				// get uploaded file path 
				$file_path=$upload_data['full_path'];
				if($file_path){
					$result=$this->import_mdata_xls($file_path);
					if($result){
					  $this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
					  unlink($file_path); // delete the uploded file 	
					 }else{
					  echo '<div class="alert alert-danger capital">file uploading problem</div>';	 
					}
				}
			}
		}
		redirect($this->client_url.'manage_kare/mdata_inspection');
	}    
        
    public function import_mdata_xls($file_path=null){
        
                $sys_clients = array_column($this->kare_model->get_clientName_list(),'client_name');
                $sys_components = array_column($this->kare_model->get_components_list(),'component_code');
                $sys_products = array_column($this->kare_model->get_products_list('product_code'),'product_code');
        
	
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
                $this->load->model('SiteId_model');
		//get only row iterator
		$count = 0;
		foreach($objWorksheet->getRowIterator() as $key=>$row){
			if($key==1) continue;
                        
                        $cellIterator=$row->getCellIterator();
			
			if($this->SiteId_model->isRowNotEmpty($row)) {
				//print_r($cellIterator);die;
                              $error_flag=0; 
                              $error_summery=array();
                              $e_fields=array();
                              
                              $data[$count]['mdata_client_fk']=$_SESSION['client']['client_id'];
                                
				foreach($cellIterator as $cell){
					switch($cell->getColumn()){   // mdata_material_invoice
						case 'B':
							$data[$count]['mdata_jobcard']=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'C':
							$data[$count]['mdata_sms']= preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'D':
							$data[$count]['mdata_batch']=trim($cell->getValue());
						break;
						case 'E':
							$data[$count]['mdata_serial']=trim($cell->getValue());
						break;
						case 'F':
							$data[$count]['mdata_rfid']=trim($cell->getValue());
						break;
						case 'G':
							$data[$count]['mdata_barcode']=trim($cell->getValue());
						break;
						case 'H':
							$data[$count]['mdata_uin']=trim($cell->getValue());
						break;
						case 'I':
							/*if($cell->getValue()!=''){
								$client = $this->kare_model->get_client_ids(strtoupper(trim($cell->getValue())));
								if($client){
									$client_id = $client->client_id;
								}else{
									$client_id = trim($cell->getValue());
								}
								$data[$count]['mdata_client']= $client_id;
							}else{
								$data[$count]['mdata_client']= '';
							}
                                                         */
                                                        
                                                    if($cell->getValue()!=''){
                                                        $client=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
                                                        $data[$count]['mdata_client']= $client;
                                                                                                                
                                                    }else{
                                                        $data[$count]['mdata_client']= "";
                                                    }
                                                       
                                                    if(!in_array($client, $sys_clients)){
                                                        $error_flag=1;
                                                        $e_fields[]='mdata_client';
                                                        //$error_summery[]=array('e_field'=>'mdata_client','client'=>$client);
                                                        $error_summery['clients']=$client;
                                                    }      
	
						break;
						case 'J':
                                                if($cell->getValue() !=''){
                                                            $data[$count]['mdata_po']=trim($cell->getValue());
                                                    }else{
                                                            $data[$count]['mdata_po']='';
                                                    }
						break;
						
						case 'K':
							if($cell->getValue() !=''){
								$series = preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
								if(strpos($series, ',') != false){
									$asset_array = explode(',',$series);
									$data[$count]['mdata_material_invoice']=json_encode($asset_array); // mdata_item_series
								}else{
									$array = array();
									$array[] =  $series;
									$data[$count]['mdata_material_invoice']=json_encode($array);
								}
							}else{
								$data[$count]['mdata_material_invoice']	= '';
							}
						break;
						case 'L':
							$data[$count]['mdata_material_invoice_date']= date( 'Y-m-d', strtotime(trim($cell->getValue())));
						break;	
						case 'O':
							if($cell->getValue() !=''){
							        $series = preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
								if( 1||strpos($series, ',') != false){
									$asset_array = explode(',',$series);
									$data[$count]['mdata_item_series']=json_encode($asset_array);
								}else{
									$array = array();
									$array[] =  $series;
									$data[$count]['mdata_item_series']=json_encode($array);
								} 
                                                                    
							}else{
								$data[$count]['mdata_item_series']	= '';
							} 
                                                        
                                                        $series_arr=array();
                                                        foreach($asset_array as $series){
                                                                   if(!in_array($series, $sys_products)){
                                                                       $error_flag=1;
                                                                       $series_arr[]=$series;
                                                                   }
                                                                   
                                                               }
                                                                                                               
                                                        //print_r($series_arr); die;        
                                                               
                                                        $e_fields[]='mdata_item_series';
                                                        #$error_summery[]=array('e_field'=>'mdata_item_series','series'=>$series); 
                                                        $error_summery['series']=$series_arr;

						break;						
						case 'P':
							$data[$count]['mdata_qty']=trim($cell->getValue());  
						break;
						case 'Q':
							if($cell->getValue() !=''){
								$series = preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
								if( 1 ||strpos($series, ',') != false){
									$asset_array = explode(',',$series);
									$data[$count]['mdata_asset']=json_encode($asset_array);
								}else{
									$array = array();
									$array[] =  $series;
									$data[$count]['mdata_asset']=json_encode($array);
								}  
							}else{
								$data[$count]['mdata_asset']	= '';
							} 
                                                        $asset_arr=array();
                                                        foreach($asset_array as $asset){
                                                                   if(!in_array($asset, $sys_components)){
                                                                       $error_flag=1;
                                                                       $asset_arr[]=$asset;
                                                                   }
                                                                   
                                                               } 
                                                              $e_fields[]='mdata_asset'; 
                                                              //$error_summery[]=array('e_field'=>'mdata_asset','assets'=>$asset_arr); 
                                                              $error_summery['assets']=$asset_arr;
                                                        
						break;
					}// end of switch 
				}// end celliterator
                                $error_summery['e_fields']=$e_fields;
                                
                                $data[$count]['mdata_error_flag']=$error_flag;
                                $data[$count]['mdata_error_summery']=json_encode($error_summery);
				$data[$count]['status']='Active';
				$todaytime  = date("Y-m-d H:i:s");
				$data[$count]['created_date']= $todaytime;
				$count++;
			}
	    }// End row Iterator
            
            #echo "<pre>";
            #print_r($data); 
            #print_r($sys_components);
            #die;
       
		// insert data into database
		$result=$this->kare_model->import_inspection_xls($data);	
		if($result){
			$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>MASTER DATA LIST SUCCESSFULLY IMPORTED</div>");
			return true;
		}else{
			return false;
			}
		
	}
	
	function download_mdata_sample(){
		$data = file_get_contents("./uploads/sampleFile/Sample_master_data.xlsx"); // Read the file's contents
		$name = 'Sample_master_data.xlsx';
		force_download($name, $data); 
	}
   	
	function download_asset_sample(){
		$data = file_get_contents("./uploads/sampleFile/Sample_asset_data.xlsx"); // Read the file's contents
		$name = 'Sample_asset_data.xlsx';
		force_download($name, $data); 
	}
	
	function download_asset_series_sample(){
		$data = file_get_contents("./uploads/sampleFile/Sample_asset_series_data.xlsx"); // Read the file's contents
		$name = 'Sample_asset_series_data.xlsx';
		force_download($name, $data); 
	}
	
	function download_subAsset_sample(){
		$data = file_get_contents("./uploads/sampleFile/Sub_assets_sample.xlsx"); // Read the file's contents
		$name = 'Sample_subAsset_data.xlsx';
		force_download($name, $data); 
	}
	
	function ajax_get_components(){
		$query=$_GET['search'];
		$this->load->model('Search_model');
		if(!empty($query)){
			$components = $this->Search_model->search_asset_data($query);
			$subassets = $this->Search_model->search_sub_asset_data($query);
		}else{
			$components = $this->Search_model->search_asset_data();
			$subassets = $this->Search_model->search_sub_asset_data();
		}
		if(is_array($components) && is_array($subassets)){
			$array = array_merge($components,$subassets);
		}else if(is_array($components) && !is_array($subassets)){
			$array =$components;
		}else if(!is_array($components) && is_array($subassets)){
			$array =$subassets;
		}else if(!is_array($components) && !is_array($subassets)){
			$array ='';
		}
		if(is_array($array)){
			foreach($array as $arrayVal){
				$arr[]  = (isset($arrayVal['component_code']))? $arrayVal['component_code'] : $arrayVal['sub_assets_code'];
			}

			if(isset($arr)){
				$respose='';
				foreach($arr as $key=>$component){
					$respose.="<p id='". $key."'>".$component;
					$respose.="<input class='pull-right' type='checkbox' name='product_components[]'  id='chk_".$component."'";
					$respose.='value="'.$component.'"';
					$respose.='/></p>';
				}
				echo $respose;
			}
		}
	}
	
	function ajax_get_assetSeries(){
		$query=$_GET['search'];
		$this->load->model('Search_model');
		
		//$product_list=array_column($this->kare_model->get_products_list('product_code'),'product_code');
		if(!empty($query)){
			$product_list = $this->Search_model->search_asset_series_data($query);
		}else{
			$product_list = $this->Search_model->search_asset_series_data();
		}
		
		
		$respose='';
		foreach($product_list as $key=>$product_code){
			$product = $product_code['product_code'];
			$respose.="<p id='". $key."'>".$product;
			$respose.="<input class='pull-right' rel='".$key."' type='checkbox' name='product_components[]'  id='chk_".$product."'";
			$respose.='value="'.$product.'"';
			$respose.='/></p>';
		}
		echo $respose;
		
		
	}
	
	public function delete_mdata($mdata_id=null){
		if($mdata_id!=null){
			if($this->kare_model->delete_madata($mdata_id)){
				$this->kare_model->delete_jobId_inspector_form($mdata_id);
				$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>Record successfully deleted.</div>");
				redirect($this->client_url.'manage_kare/mdata_inspection');	
			};
		}else{
			echo "no id found";
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
	
	
	 function manageInspector_doc(){
            $data = array();
			$data['lang'] = $this->data['lang'];
            $inspector_list =$this->kare_model->get_inspector_name();
			
            if(!empty($inspector_list)){
                    $inspector_flip = array_flip($inspector_list);
            }
			$assignInspector_list = $this->kare_model->get_inspector_list();
            if(!empty($assignInspector_list) && is_array($assignInspector_list)){
                foreach($assignInspector_list as $key => $value){
                    $assignInspector[$key]['inspectorID'] = $value['id'];
                    $assignInspector[$key]['inspector_jobCard'] = $value['inspector_jobCard'];
					
                    $assign = json_decode($value['inspector_ids']);
                    $assignInspector[$key]['inspector_ids'] = $assign[0];
					// $assignInspector[$key]['inspector_ids53456'] = $assign;
					 
                    $file = !empty($value['file'])?json_decode($value['file'],TRUE):'';
                    if(!empty($file)){
                        foreach($file as $k => $v){
                            if(!empty($v['url'])){
                                $fileTitle[$k]['title'] = $v['title'];
                                $fileTitle[$k]['url'] = $v['url'];
                            }    
                        }
                    }else{
                        $fileTitle = '';
                    }   

					if(!empty($fileTitle)){
                       $assignInspector[$key]['display_url'] = array_column($fileTitle,'url');
                       $titlefirst = array('WAH','Installation','Medical Certificate','Inspection');
                       $titlesecond = array_column($fileTitle,'title');
                       $assignInspector[$key]['display_title'] = array_diff($titlefirst,$titlesecond);
                    } else{
                        $assignInspector[$key]['display_title'] = array('WAH','Installation','Medical Certificate','Inspection');
                         $assignInspector[$key]['display_url'] = 0;
                    }
					
                    $assignInspector[$key]['file_title'] = $fileTitle;
                    $assignInspector[$key]['file'] = !empty($value['file'])?$value['file']:'';
                    // if(!empty($inspector_flip)){
                         // $assignInspector[$key]['inspector_name'] = array_search($assign[0], $inspector_flip);
                    // } 
					if(!empty($inspector_flip)){
                         $assignInspector[$key]['inspector_name'] = array_search($assign[0], $inspector_flip);
                    }  
                }
				
               $data['assignInspector_list'] = $assignInspector;
            }
            
            $this->load->view('manage_inspector_doc',$data); 
        }
		
		
		
		function edit_doc(){
            $data = array();
            
            if(isset($_POST['add_multiSpeci'])){
                    $this->add_multiSpecification();
            }
            
            $inspector_list =$this->kare_model->get_inspector_name();
            //print_r($inspector_list);die;
            if(!empty($inspector_list)){
                $inspector_flip = array_flip($inspector_list);
            }
            $assignInspector_list = $this->kare_model->get_inspector_item($_REQUEST['inspectorID']);
			//print_r($assignInspector_list);die;
                $assignInspector['inspectorID'] = $assignInspector_list['id'];
                $assignInspector['inspector_jobCard'] = $assignInspector_list['inspector_jobCard'];
             
                $assign = json_decode($assignInspector_list['inspector_ids']);
                $assignInspector['inspector_ids'] = $assign[0];
               
                $file = !empty($assignInspector_list['file'])?json_decode($assignInspector_list['file'],TRUE):'';
                if(!empty($file)){
                    foreach($file as $k => $v){
                        if(!empty($v['url'])){
                            $fileTitle[$k]['title'] = $v['title'];
                            $fileTitle[$k]['url'] = $v['url'];
                        }    
                    }
                }else{
                    $fileTitle = '';
                } 
                /*
                    print_r($fileTitle);
                     array_column($fileTitle, 'url'));die;

                }*/
              
                if(!empty($fileTitle)){
                   $assignInspector['display_url'] = array_column($fileTitle,'url');
                   $titlefirst = array('WAH','Installation','Medical Certificate','Inspection');
                   $titlesecond = array_column($fileTitle,'title');
                   $assignInspector['display_title'] = array_diff($titlefirst,$titlesecond);
                } else{
                    $assignInspector['display_title'] = array('WAH','Installation','Medical Certificate','Inspection');
                     $assignInspector['display_url'] = 0;
                }
                $assignInspector['file_title'] = $fileTitle;
                $assignInspector['file'] = !empty($assignInspector_list['file'])?$assignInspector_list['file']:'';
                if(!empty($inspector_flip)){
                     //$assignInspector['inspector_name'] = array_search($assignInspector_list['id'], $inspector_flip);
                    $assignInspector['inspector_name'] = array_search($assign[0], $inspector_flip);
                }     
				//print_r($assignInspector);die;
               $data['assignInspector_list'] = $assignInspector;
				
            
            $this->load->view('manage_inspector_uploadFile',$data); 
        }
        
	function is_postImage_empty($name){
		foreach($name as $nVal){
			if(!empty('url')){
				$file_exist = 'Conatin File';
			}
		}
		return (isset($file_exist))? true : false ;
	}
	
	function add_multiSpecification(){
		if(isset($_POST['inspectorID'])){
			if(isset($_FILES['upload'])){
				$names = $_FILES['upload']['name'];
				if($this->is_postImage_empty($names)){
					
					$file_url = array();
					$error = array();
					// single or multiple files are send to upload
					
					foreach($names as $nKey=>$nVal){
						$title = $_POST['upload'][$nKey]['title'];
						
						if(!empty($_FILES['upload']['name'][$nKey]['url'])){
							
							$ext 	=	strtolower(pathinfo($_FILES['upload']['name'][$nKey]['url'], PATHINFO_EXTENSION));
							if($ext =='gif' || $ext =='jpg'|| $ext=='png' || $ext =='jpeg' || $ext =='pdf'){
								$name 			= $_FILES['upload']['name'][$nKey]['url'];
								$fileTempName 	= $_FILES['upload']['tmp_name'][$nKey]['url'];
								// new name 
								$confirm_code	=	md5(uniqid(rand()));
								$ext 			=	pathinfo($_FILES['upload']['name'][$nKey]['url'], PATHINFO_EXTENSION);
								$newfileName	=	$confirm_code.'.'.strtolower($ext);
								
								$contentType = array('Content-Type' =>$_FILES['upload']['type'][$nKey]['url']);
								
								//S3::putObject($string, $bucketName, $uploadName, S3::ACL_PUBLIC_READ, array(), array('Content-Type' => 'text/plain'))
								if ($this->s3->putObjectFile($fileTempName, "karam-kare", $newfileName,S3::ACL_PUBLIC_READ,array(),$contentType)){
									//echo "We successfully uploaded your file.";
									$file_url[$nKey] = array('title'=>$title,'url' => "https://s3.ap-south-1.amazonaws.com/karam-kare/".$newfileName,
									"file_name" => $newfileName);
								}else{
									// file not uploaded
									$error[$nKey] = $_FILES['upload']['name'][$nKey]['url'];
									
									$error['msg'] = "Syntax Error ! Error In Uploading Files on the Server. Please contact Admin.";
								}
							}else{
								$error[] = $_FILES['upload']['name'][$nKey]['url'];
								
								$error['msg'] = 'Syntax Error ! Only gif|jpg|png|jpeg|pdf| type files are allowed to upload. Please Try Again.';
							}
						}else{
						
							$file_url[$nKey] = array('title'=>$title, "url" =>"", 'file_name'=>'');
						}
					}
                                        
					if(empty($error)){
						$tableName = 'inspector_data';
                                          
						$fileupload = $this->kare_model->get_fileData($_POST['inspectorID']);
						$fileupload = json_decode($fileupload['file'],TRUE);
						if(!empty($fileupload) && is_array($fileupload)){
								$c = 0;
								foreach($fileupload as $fk =>$fv){
									foreach($file_url as $nk => $nv){
										$fatchdata[$c]['title'] = $fv['title'];
										if(!empty($nv['url']) && ($fv['title'] == $nv['title'])){
											$fatchdata[$c]['url'] =  $nv['url'];
										} else  if(!empty($fv['url']) && ($fv['title'] == $nv['title'])){
											$fatchdata[$c]['url'] =  $fv['url'];
										} 
										if(!empty($nv['file_name']) && ($fv['title'] == $nv['title'])){
											$fatchdata[$c]['file_name'] =  $nv['file_name'];
										} else  if(!empty($fv['file_name']) && ($fv['title'] == $nv['title'])){
											$fatchdata[$c]['file_name'] =  $fv['file_name'];
										}
									}
									$c++;
								}
							
							$_SESSION['inspector_data']['upload_data'] = $fatchdata;
							$file = json_encode($fatchdata);
						}else{
							
							$_SESSION['inspector_data']['upload_data'] = $file_url;
							$file = json_encode($file_url);
						}
						
					
						
					   $result = $this->kare_model->manageInspector_doc_update($_POST['inspectorID'],$file);
					   if($result > 0){
							unset($_SESSION['inspector_data']['upload_data']);
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Files Uploaded Successfully.</p>');
							redirect($this->client_url.'manage_kare/manageInspector_doc/', 'refresh');
					   }else{
							unset($_SESSION['inspector_data']['upload_data']);
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Pls. Try Again.</p>');
					   }     
						return true;
					}else{
						// If there is an Error in Uploading the file 
						// Then delete the uploaded files which are uploaded on the server.
						if(!empty($file_url)){
							foreach($file_url as $name){
								if($name['file_name']!=''){
									// Delete the file from Aws Server.
									$this->delete_file_from_aws($name['file_name']);
								}
							}
						}
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital">'.$error['msg'].'</p>');
						return false;
					}
				}else{
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b>Please upload the file and try again.</p>');
					return false;
				}
			}
		}else{
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b>Please Select The Category Type and Then Try Again.</p>');
			return false;
		}
	}
	
	
	public function assignInspector_list($inspector_id=NULL){
		
		$data = array();
		$data['lang'] = $this->data['lang'];
		$inspector_list=$this->kare_model->get_inspector_name();
		foreach ($inspector_list as $key => $value){
			$data['inspectorData'][$key]['inspectorId'] = $key;
			$data['inspectorData'][$key]['inspectorName'] = $value;
			$file = $this->kare_model->get_inspector_item($key);
			if(!empty($file['file'])){
				$count = array_column(json_decode($file['file'],TRUE), 'url');
				$data['inspectorData'][$key]['file'] = count(array_filter($count));
				
				$urlName = json_decode($file['file'],TRUE);
				foreach($urlName as $k => $v){
					if(@$v['url'] == ''){
						 $data['inspectorData'][$key]['title'][$k] =  $v['title'];
					}
				}
			}else{
				$data['inspectorData'][$key]['file'] = 0;
			}            
		} 
               
               //print_r($data);die; 
		if($inspector_id==NULL){
			// viw / insert record
			
			$this->load->model('Siteid_model');
			$arrjobcard = $this->Siteid_model->get_jobcard();
			if($arrjobcard){
				foreach ($arrjobcard as $jobcards)
				{
					$arrFinal[$jobcards->mdata_id] = $jobcards->mdata_jobcard;
				}
			}else{
				$arrFinal ='';
			}
			
			$data['jobcards'] = $arrFinal;

			if(isset($_POST['save_assignInspector'])){
				$this->form_validation->set_rules('jc_number','Job Card Number','required');
				$this->form_validation->set_rules('sms_number','SMS Number','required');
				
				if($this->form_validation->run()==false){
					$data['msg']='<div class="alert alert-danger capital">Validation Error</div>';
					$this->load->view('assignInspector_list',$data);	
				}else{
					if(!isset($_POST['inspector_ids']) && !isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Both Site ID and Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Site ID should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['inspector_ids'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					
					if($this->insert_assignInspector()){
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
				}
			}else{
                           
				$assignInspector_list=$this->kare_model->get_inspector_list();
				$data['assignInspector_list']=$assignInspector_list;	
				$this->load->view('assign_inspector',$data);  
			}
			
		}else{
		   // update record	
		    $assignInspector=$this->kare_model->get_inspector_item($inspector_id);
			$assignInspector_list=$this->kare_model->get_inspector_list();
			if(is_array($assignInspector)){
				if(isset($_POST['edit_assignInspector'])){
					if(!isset($_POST['inspector_ids']) && !isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Both Site ID and Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Site ID should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['inspector_ids'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if($this->edit_assignInspector($inspector_id)){
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
				}else{
                                    //die("123456");
					$data['assignInspector_list']=$assignInspector_list;
					$data['assignInspector']=$assignInspector;	
					$this->load->view('assign_inspector',$data);  
					//$this->load->view('edit_assignInspector',$data);  
				}
			}else{
			show_404();	
			}
	    }
		
	}
	
	public function assignInspector_list_old($inspector_id=NULL){
		
		if($inspector_id==NULL){
			// viw / insert record
			
			$this->load->model('Siteid_model');
			$arrjobcard = $this->Siteid_model->get_jobcard();
			if($arrjobcard){
				foreach ($arrjobcard as $jobcards)
				{
					$arrFinal[$jobcards->mdata_id] = $jobcards->mdata_jobcard;
				}
			}else{
				$arrFinal ='';
			}
			
			$data['jobcards'] = $arrFinal;

			if(isset($_POST['save_assignInspector'])){
				$this->form_validation->set_rules('jc_number','Job Card Number','required');
				$this->form_validation->set_rules('sms_number','SMS Number','required');
				
				if($this->form_validation->run()==false){
					$data['msg']='<div class="alert alert-danger capital">Validation Error</div>';
					$this->load->view('assignInspector_list',$data);	
				}else{
					if(!isset($_POST['inspector_ids']) && !isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Both Site ID and Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Site ID should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					if(!isset($_POST['inspector_ids'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Inspector should be selected.</div>');
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
					
					if($this->insert_assignInspector()){
						redirect($this->client_url.'manage_kare/assignInspector_list');
					}
				}
			}else{
				$assignInspector_list=$this->kare_model->get_inspector_list();
				$data['assignInspector_list']=$assignInspector_list;	
				$this->load->view('assign_inspector',$data);  
			}
			
		}else{
		   // update record	
		    $assignInspector=$this->kare_model->get_inspector_item($inspector_id);
			$assignInspector_list=$this->kare_model->get_inspector_list();
			if(is_array($assignInspector)){
				if(isset($_POST['edit_assignInspector'])){
					if(!isset($_POST['inspector_ids']) && !isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Both Site ID and Inspector should be selected.</div>');
						redirect('manage_kare/assignInspector_list');
					}
					if(!isset($_POST['site_id'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Site ID should be selected.</div>');
						redirect('manage_kare/assignInspector_list');
					}
					if(!isset($_POST['inspector_ids'])){
						$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Inspector should be selected.</div>');
						redirect('manage_kare/assignInspector_list');
					}
					if($this->edit_assignInspector($inspector_id)){
						redirect('manage_kare/assignInspector_list');
					}
				}else{
					$data['assignInspector_list']=$assignInspector_list;
					$data['assignInspector']=$assignInspector;	
					$this->load->view('assign_inspector',$data);  
					//$this->load->view('edit_assignInspector',$data);  
				}
			}else{
			show_404();	
			}
	    }
		
	}
	
	public function insert_assignInspector($id=NULL){
		
		if(isset($_POST['save_assignInspector']) && isset($_POST['site_id']) && isset($_POST['inspector_ids'])){
		
			$dbdata['inspector_jobCard']	=	$this->input->post('jc_number');
			$dbdata['inspector_sms']		=	$this->input->post('sms_number');
			$dbdata['site_id']				=	json_encode($this->input->post('site_id'));
			$dbdata['inspector_ids']		=	json_encode($this->input->post('inspector_ids'));
			$dbdata['status']				=	$this->input->post('status');

			$this->form_validation->set_rules('save_assignInspector','Save Button','required');
		
			if($this->form_validation->run()==false){
					 return false;
			}else{
				// submit form data
				if($this->kare_model->insert_inspector_data($dbdata)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Submited.</div>');
				   return true; 	
				}
			}
		}else{
			return false;
		}
	}
	

	
	public function edit_assignInspector($inspector_id){
		
		if(isset($_POST['edit_assignInspector']) && isset($_POST['site_id']) && isset($_POST['inspector_ids'])){

			$dbdata['site_id']		=	json_encode($this->input->post('site_id'));
			$dbdata['inspector_ids']=	json_encode($this->input->post('inspector_ids'));
			$dbdata['status']		=	$this->input->post('status');
	
			$this->form_validation->set_rules('edit_assignInspector','Update Button','required');

			if($this->form_validation->run()==false){
					 return false;
			}else{
				// submit form data
				if($this->kare_model->update_inspector_data($dbdata,$inspector_id)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Updated.</div>');
				   return true; 	
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error In Data Updation.</div>');
					 return false;
				}
			}
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Inspector and Site values are required.</div>');
			return false;
		}
		
	}
	
	public function delete_inspector($inspector_id){
           if($inspector_id!=null){
			if($this->kare_model->delete_inspector($inspector_id)){
			    $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully.</div>');
				redirect($this->client_url.'manage_kare/assignInspector_list');	
			 }   
		   }	
	
	}
	
	/* ************************************ */
	
	function ajax_search_get_inspector(){
		$query		=	$_GET['search'];
		$this->load->model('kare_model');
		if($query!='blank'){
			$sql = $this->kare_model->get_inspector_name($query);
		}else{
			$sql = $this->kare_model->get_inspector_name();
		}
		$respose='';
		foreach($sql as $key=>$val){
				$respose.="<p>".ucwords($val);
				$respose.='<input class="pull-right" type="checkbox" name="inspector_ids[]" id="chk_'.$key.'" value="'.$key.'" rel="'.$val.'"';
				$respose.='/></p>'; 
		}  
		echo $respose;
	}
	
	
	public function valid_jobSMS_from_ajax(){

		$job = strtoupper($_POST['jobCard']);
		$sms = strtoupper($_POST['sms']);
		$jobSMS = $this->kare_model->jobcardSMS_unique_identity($job,$sms);
		if(!$jobSMS){
			echo 'NO';
		}else{
			echo "YES";
		}
	}
	public function export_inspection_xls(){
		
		if($this->input->post('export_filetype') == 'CSV Format'){
			$rel = $this->input->post('export_filetype');
			$this->export_masterdata_csv($rel);
		}else if($this->input->post('export_filetype') == 'XLS Format'){
			$rel = $this->input->post('export_filetype');
			$this->export_masterdata_xls($rel);
		}
	}
	
	public function export_masterdata_csv($rel){
			
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "Master_Data_CSV_List.csv";
		$result =	$this->kare_model->get_mdata_list_forDownload($rel);
		$data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
		force_download($filename, $data);
	}
	
	
	public function export_masterdata_xls($rel)
	{
		$master_list = $this->kare_model->get_mdata_list_forDownload($rel);
		foreach($master_list as $key=>$value){
			if($value['Client Name']==''){
				$master_list[$key]['Client Name'] = $value['Master Client'];
			}
		}		
		$filename .= "MASTER_DATA_EXCEL_LIST.xls";
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		
		if(is_array($master_list) && count($master_list)>0){
			//column name
			echo implode("\t", array_keys($master_list[0])) . "\r\n";
			//values in columns
			foreach($master_list as $key => $value){
				if($value < 0){
					continue;
				}
				echo implode("\t", array_values($value)) . "\r\n";
			}
		} else{
			echo 'no entries';
		}		


	}
	
	function reset_table_data($table){
		
		if (! $this->flexi_auth->is_privileged('Reset Database Table'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-success capital">You do not have privilege to empty the table. Please contact your Admin.</p>');
			$this->load->library('user_agent');
			redirect($this->agent->referrer());
		}
		echo $table;
		die;
		if($table =='master'){
			$table_name = 'master_data';
		}else if($table =='subAssets'){
			$table_name = 'sub_assets';
		}else if($table =='smsComponent'){
			$table_name = 'sms_component';
		}else if($table =='assets'){
			$table_name = 'components';
		}else if($table =='inspector'){
			$table_name = 'inspector_data';
		}else if($table =='assetSeries'){
			$table_name = 'products';
		}else if($table =='siteID'){
			$table_name = 'siteID_data';
		}
		$result = $this->kare_model->reset_table_data($table_name);
		if($result){
			$this->session->set_flashdata('msg','<p class="alert alert-success capital">Data deleted successfully.</p>');
		}else{
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital">Error in deleteing data.</p>');
		}
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	function exportAnalytics() {
		
            $param = array();
            $this->load->library('Excel_sheet'); 
            if(!empty($_REQUEST['exportAnalytics']) && $_REQUEST['exportAnalytics'] == 1){
                $param['assets'] = 'assets';
                $param['defaultId'] = '1';
                $result = $this->excel_sheet->assets_list($param);
            }else if(!empty($_REQUEST['exportAnalytics']) && $_REQUEST['exportAnalytics'] == 2){
                $param['assets_series'] = 'assets_series';
                $param['defaultId'] = '2';
                $result = $this->excel_sheet->asset_series_list($param);
            }elseif(!empty($_REQUEST['exportAnalytics']) && $_REQUEST['exportAnalytics'] == 3){
                $param['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
                $param['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
              
                $param['defaultId'] = '3';
                $param['logsView'] = 'logs_view';
                $result = $this->excel_sheet->logsView($param);
            }elseif(!empty($_REQUEST['exportAnalytics']) && $_REQUEST['exportAnalytics'] == 4){
				$param['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
                $param['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
				$param['clientType'] = (!empty($_REQUEST['clientType']))? $_REQUEST['clientType']: '';
                $param['defaultId'] = '4';
				$result = $this->excel_sheet->assign_siteID($param);
			}elseif(!empty($_REQUEST['exportAnalytics']) && $_REQUEST['exportAnalytics'] == 5){
				if((!empty($_REQUEST['fromDate']))){
					$param['startTime'] =  $_REQUEST['fromDate'];
				}
				if((!empty($_REQUEST['toDate']))){
					$param['endTime'] =  $_REQUEST['toDate'];
				}
				if((!empty($_REQUEST['district']))){
					$param['district'] =  $_REQUEST['district'];
				}
				if((!empty($_REQUEST['circle']))){
					$param['circle'] =  $_REQUEST['circle'];
				}
				if((!empty($_REQUEST['client']))){
					$param['client'] =  $_REQUEST['client'];
				}
				if((!empty($_REQUEST['asset_series']))){
					$param['asset_series'] =  $_REQUEST['asset_series'];
				}
				if((!empty($_REQUEST['asset']))){
					$param['asset'] =  $_REQUEST['asset'];
				}
				if((!empty($_REQUEST['invoice']))){
					$param['invoice'] =  $_REQUEST['invoice'];
				}
				
                $param['defaultId'] = '5';
				$result = $this->excel_sheet->search_data($param);
			}
            exit();
	}
	
}// end of controller class 




?>