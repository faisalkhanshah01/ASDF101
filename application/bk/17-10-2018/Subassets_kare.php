<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Subassets_kare extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct(){
		parent::__construct();
		$this->load->model('Subassets_model');
		$this->load->helper(array('url','date','form','kare'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
                
                if(1==1){
                    
                    $sections = array(
                        'benchmarks' => FALSE, 'memory_usage' => FALSE,
                        'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE,
                        'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
                    );
                    
                    $this->output->set_profiler_sections($sections);
                    $this->output->enable_profiler(TRUE);                    
                    
                }
                
                // current domian/client info 
                #$this->domain = $_SESSION['client'];
                #$this->domian_client = $_SESSION['client'];
                
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id = $_SESSION['client']['client_id'];
            
                $this->auth = new stdClass;
		$this->load->library('flexi_auth');	
		if (! $this->flexi_auth->is_logged_in_via_password()){
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
	
	function exportAnalytics(){
		$param = array();
		$param['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$param['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';
		$param['defaultId'] = '6';
		
		$this->load->model('Excelsheet'); 
		$result = $this->Excelsheet->subAssetView($param);

		exit;
	}
	
	function searchSubassetsAjax(){
		$params = array();
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		
		$this->load->model('Subassets_model');
		$inspection		=	$this->Subassets_model->get_type_category('inspection');
		$uom			=	$this->Subassets_model->get_type_category('uom');
		$result			=	$this->Subassets_model->get_type_category('result');
		$observations           =	$this->Subassets_model->get_type_category('observations');
		
		$this->load->model('SiteId_model');
		$subAssetData = $this->SiteId_model->get_subAsset_list($params);
		$html = '';
		
         $html = '<table id="example_datatable1" class="table table-bordered table-hover">
                    <thead>
						<tr>
							<th>Action</th>
							<th>Sub Assets Code</th>
							<th>Description</th>
							<th>Image</th>
							<th>UOM</th>
							<th>Inspection Type</th>
							<th>Expected Result</th>
							<th>Observation</th>
							<th>Repair</th>
							<th>Status</th>
							<th>Featured Image</th>
							<th>Date</th>
						</tr>
                    </thead>';
         if(!empty($subAssetData) && is_array($subAssetData)){
            $html .= '<tbody>';
            $c = 1;
             foreach ($subAssetData as $ky => $value) {
                $html .= '<td>';
					$html .= '<a class="text-primary" href="'.base_url().'Subassets_kare/sub_assets_list/'.$value['sub_assets_id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;';
					$html .= '<a href="'. base_url().'Subassets_kare/delete_sub_assets/'.$value['sub_assets_id'].'" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>';
				$html .= '</td>';
				
                $html .= '<td>'.$value['sub_assets_code'].'</td>';
                $html .= '<td>'.$value['sub_assets_description'].'</td>';
				if($value['sub_assets_imagepath']!=''){
					$imagePath = '<img src="'.base_url().$value['sub_assets_imagepath'].'" width="60" height="60" />';
				}else{
					$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
				}
                $html .= '<td>'.$imagePath.'</td>';
				if(is_array($uom) && !empty($value['sub_assets_uom'])){
					if(array_key_exists($value['sub_assets_uom'],$uom)){
						$uom_value = "<p>".$uom[$value['sub_assets_uom']]."</p>";
					}else{
						$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
					}
				}else{
					$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
				}
                $html .= '<td>'.$uom_value.'</td>';
				if(is_array($inspection) && !empty($value['sub_assets_inspection'])){
					if(array_key_exists($value['sub_assets_inspection'],$inspection)){
						$inspection_value = "<p>".$inspection[$value['sub_assets_inspection']]."</p>";
					}else{
						$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
					}
				}else{
					$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
				}
                $html .= '<td>'.$inspection_value.'</td>';
				if(is_array($result) && !empty($value['sub_assets_result'])){
					$excpected_result = json_decode($value['sub_assets_result'],true);
					$result_value = '';
					foreach($excpected_result as $expResult){
						if(array_key_exists($expResult,$result)){
							$result_value .=  "<p>".$result[$expResult]."</p><hr>";
						}else{
							$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
						}
					}
				}else{
					$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
				}
                $html .= '<td>'.$result_value.'</td>';
				if(is_array($observations) && !empty($value['sub_assets_observation'])){
						$observations_array = json_decode($value['sub_assets_observation'],true);
						$observation_value = '';
						foreach($observations_array as $obsResult){
							if(array_key_exists($obsResult,$observations)){
								$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
							}else{
								$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_observation']."</p>";
							}
						}
				}else{
					$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_observation']."</p>";
				}
                $html .= '<td>'.$observation_value.'</td>';
                $html .= '<td>'.strtoupper($value['sub_assets_repair']).'</td>';
				$status =  ($value['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
                $html .= '<td>'.$status.'</td>';
				$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=subAsset&id='.$value['sub_assets_id'].'">Featured Image</a>';
				$html .= '<td>'.$featuredImage.'</td>';
                $html .= '<td>'.date("M jS, Y", strtotime($value['time'])).'</td>';
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
	
	function subassets_search(){
		$data = array();
		$data['lang'] = $this->data['lang'];	
		$params['startTime'] = (!empty($_REQUEST['fromDate'])) ? $_REQUEST['fromDate']: date( 'Y-m-d', strtotime("-30 days",time()) );
		$params['endTime'] = (!empty($_REQUEST['toDate']))? $_REQUEST['toDate']: '';

		$_REQUEST['fromDate'] = $params['startTime'];
		
		$this->load->model('Subassets_model');
		$data['inspection']		=	$this->Subassets_model->get_type_category('inspection');
		$data['uom']			=	$this->Subassets_model->get_type_category('uom');
		$data['result']			=	$this->Subassets_model->get_type_category('result');
		$data['observations']           =	$this->Subassets_model->get_type_category('observations');
		
		$this->load->model('SiteId_model');
		$data['subAssetData'] = $this->SiteId_model->get_subAsset_list();

		
		
		$this->load->view('search/subassets_search',$data);
	}
		
	public function sub_assets_list($sub_assets_id=NULL){
		$data['lang'] = $this->data['lang'];		
		$data['inspection'] = $this->Subassets_model->get_type_category('inspection');
                
		$data['uom'] = $this->Subassets_model->get_type_category('uom');

		$data['result'] = $this->Subassets_model->get_type_category('result');
		
		$data['observation'] = $this->Subassets_model->get_type_category('observations');
		
		//$data['action_proposed'] = $this->Subassets_model->get_type_category('action_proposed');
		
		if($sub_assets_id==NULL){
			// insert record
			//$sub_assets_list=$this->Subassets_model->get_sub_assets_list();
			if(isset($_POST['save_sub_assets'])){
				if($this->insert_sub_assets()){
					redirect($this->client_url.'subassets_kare/sub_assets_list');
				};
			}else{
				//$data['sub_assets']=$sub_assets_list;	
				$this->load->view('sub_assets_list',$data);  
			}
		}else{
			// update record	
		    $sub_assets=$this->Subassets_model->get_sub_assets($sub_assets_id);
			
			if(is_array($sub_assets)){
				if(isset($_POST['edit_sub_assets'])){
					if($this->edit_sub_assets($sub_assets_id)){
						redirect($this->client_url.'subassets_kare/sub_assets_list');
					}
				}else{
					$data['sub_assets']=$sub_assets;
					$this->load->view('edit_sub_assets',$data);
				}
			}else{
			show_404();	
			}
	    }
	}
	
	public function insert_sub_assets(){

		if(isset($_POST['save_sub_assets'])){
		$dbdata['speci_file_id']            = $this->input->post('speci_file_id');
		$dbdata['sub_assets_code']			=	strtoupper(strtolower(trim($this->input->post('sub_assets_code'))));
		$dbdata['sub_assets_description']	=	trim($this->input->post('sub_assets_description'));
		$dbdata['sub_assets_uom']			=	$this->input->post('subasset_uom');
		$dbdata['sub_assets_inspection']	=	$this->input->post('subasset_inspectiontype');
		$dbdata['sub_assets_result']		=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';
		$dbdata['sub_assets_observation']	=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';
	//	$dbdata['sub_assets_excerpt']		=	$this->input->post('subasset_excerpt');
		$dbdata['sub_assets_repair']		=	$this->input->post('subasset_repair');
		$dbdata['status']					=	$this->input->post('sub_assets_status');
		$dbdata['user_email']				=	$this->session->flexi_auth['user_identifier'];
		$dbdata['created_date']				=	now();
                
                #client specific code added by sachin 
                $dbdata['sub_assets_client_fk']=$_SESSION['client']['client_id'];
	
		$this->form_validation->set_rules('sub_assets_code','Sub Assets Code','required');
		$this->form_validation->set_rules('sub_assets_status','Status','required');
		
			if($this->form_validation->run()==false){
				return false;
			}else{
				
				if(!empty($_FILES['assets_image']['name'])){
					$random_code=md5(uniqid(rand()));
					#$image_path= $random_code.$_FILES ["assets_image"] ["name"]; 
					$image_path= $random_code.".".pathinfo($_FILES ["assets_image"] ["name"],PATHINFO_EXTENSION); 
                                        
                                         $dir = FCPATH."uploads/{$this->client_id}/images/sub_assets/";
                                        if(!file_exists($dir)){
                                            mkdir($dir, 0777,true);                                            
                                        }
                                        move_uploaded_file($_FILES ["assets_image"] ["tmp_name"], $dir.$image_path);   
					$path= "/uploads/$this->client_id/images/sub_assets".'/'.$image_path;
					$dbdata['sub_assets_imagepath'] = $path;
					$dbdata['sub_assets_image'] = $image_path;  
				}
				
				// submit form data
				if($this->Subassets_model->save_sub_assets($dbdata)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Sub Assets Successfully submited.</div>');
				   return true; 	
				}
			}
		}
	}
	
	function edit_sub_assets($sub_assets_id=NULL){
		
	    if($sub_assets_id==NULL){
			show_404();
		}else{
			// get the sub_assets data
			$sub_assets=$this->Subassets_model->get_sub_assets($sub_assets_id);
		
			if(isset($_POST['edit_sub_assets'])&& $_SERVER['REQUEST_METHOD']=='POST'){
                $dbdata['speci_file_id']            = $this->input->post('speci_file_id');
				$dbdata['sub_assets_code']			=	strtoupper(strtolower(trim($this->input->post('sub_assets_code'))));
				$dbdata['sub_assets_description']	=	trim($this->input->post('sub_assets_description'));
				$dbdata['sub_assets_uom']			=	$this->input->post('subasset_uom');
				$dbdata['sub_assets_inspection']	=	$this->input->post('subasset_inspectiontype');
				$dbdata['sub_assets_result']		=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';
				$dbdata['sub_assets_observation']	=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';
		//		$dbdata['sub_assets_excerpt']		=	$this->input->post('subasset_excerpt');
				$dbdata['sub_assets_repair']		=	$this->input->post('subasset_repair');
				$dbdata['status']					=	$this->input->post('sub_assets_status');
				$dbdata['user_email']				=	$this->session->flexi_auth['user_identifier'];
				$dbdata['updated_date']				=	now();
				
				if(!empty($_FILES['assets_image']['name'])){
					$random_code=md5(uniqid(rand()));
					#$image_path= $random_code.$_FILES ["assets_image"] ["name"]; 
					$image_path= $random_code.".".pathinfo($_FILES ["assets_image"] ["name"],PATHINFO_EXTENSION); 
                                        
                                         $dir = FCPATH."uploads/{$this->client_id}/images/sub_assets/";
                                        if(!file_exists($dir)){
                                            mkdir($dir, 0777,true);                                            
                                        }
                                        move_uploaded_file($_FILES ["assets_image"] ["tmp_name"], $dir.$image_path);   
					$path= "/uploads/$this->client_id/images/sub_assets".'/'.$image_path;
					$dbdata['sub_assets_imagepath'] = $path;
					$dbdata['sub_assets_image'] = $image_path;  
				}
				
				$result	=	$this->Subassets_model->update_sub_assets($dbdata,$sub_assets_id);	
		        if($result){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Sub Assets Successfully updated</div>.');
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error in updation.</div>');
				}
				redirect($this->client_url.'subassets_kare/sub_assets_list');
			}
			
			$data['sub_assets']=$sub_assets;
			$this->load->view('edit_sub_assets',$data);
	    }	
	}	
		
	public function import_sub_assets_list(){
		
		if(isset($_POST['import_sub_assets_list'])&& $_SERVER['REQUEST_METHOD']=='POST'){
                    
                       $dir = FCPATH."uploads/{$this->client_id}/xls/";
                        if(!file_exists($dir)){
                            mkdir($dir, 0777,true);                                            
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
					
					$result=$this->import_sub_assets_xls($file_path);
					if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
						unlink($file_path); // delete the uploded file 	
					}else{
						echo "file uploading problem";	 
					}
				}
			}
		}
		redirect($this->client_url.'subassets_kare/sub_assets_list');
	}

	//  NULL is case insenstive constant of type null
	public function import_sub_assets_xls($file_path=null){
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
		//get only row iterator
		foreach($objWorksheet->getRowIterator() as $key=>$row){
			if($key==1) continue;

			$data[$key]['sub_assets_client_fk']=$_SESSION['client']['client_id'];
			
			$this->load->model('SiteId_model');
			if($this->SiteId_model->isRowNotEmpty($row)) {          
				$cellIterator = $row->getCellIterator();

				#echo "<pre>"."+++++++++++++++++++++++++".$key;
                 #print_r($row);
				 #print_r($cellIterator); //die;
                                
				foreach($cellIterator as $cell){
					#echo $cell->getColumn();
					switch($cell->getColumn()){
						case 'A':
						$sub_assets_code =strtoupper(trim($cell->getValue()));
						$data[$key]['sub_assets_code']=preg_replace('/\s+/', '', $sub_assets_code);
						break;
						case 'B':
						$data[$key]['sub_assets_description']	=	trim($cell->getValue());
						break; 
						case 'C':
							if($cell->getValue() !=''){
									$uomtype = ucwords(strtolower(trim($cell->getValue())));
									$this->load->model('Subassets_model');
									$uom=$this->Subassets_model->get_inspection_list('uom',$uomtype);
									
									if($uom){
										$data[$key]['sub_assets_uom'] = $uom;
									}else{
										$data[$key]['sub_assets_uom']	=	$uomtype;
									}
							}else{
								$data[$key]['sub_assets_uom']	=	"";
							}
							//$data[$key]['sub_assets_uom']	=	strtolower(trim($cell->getValue()));
						break;
						case 'D':
							if($cell->getValue() !=''){

								$inspectiontype = ucwords(strtolower(trim($cell->getValue())));
								$this->load->model('Subassets_model');
								$inspection=$this->Subassets_model->get_inspection_list('inspection',$inspectiontype);
								if($inspection){
									$data[$key]['sub_assets_inspection'] 	= 	$inspection;
								}else{
									$data[$key]['sub_assets_inspection']	=	$inspectiontype;
								}
							}else{
								$data[$key]['sub_assets_inspection']	=	'';
							}
							#echo $data[$key]['sub_assets_inspection']	=	trim($cell->getValue());

						break; 
						case 'E':
							#echo $resulttype=$cell->getValue(); die;
							if($resulttype !=''){
								/* check if it has multiple values or single value */
								if(strpos($resulttype, ',') != false){
									$this->load->model('Subassets_model');
									$sub_assets = explode(',',$cell->getValue());
									
									$keyID = array();
									foreach($sub_assets as $subKey=>$subValue){
										$result=$this->Subassets_model->get_inspection_list('result',ucwords(strtolower(trim($subValue))));
										if($result){
											$keyID[$subKey] = $result;
										}else{
											$keyID[$subKey] = ucwords(strtolower($subValue));
										}
									}
									$data[$key]['sub_assets_result'] = json_encode($keyID);
								}else{
									echo "******************";
									$expResult = array();
									$this->load->model('Subassets_model');
									echo $result=$this->Subassets_model->get_inspection_list('result',ucwords(strtolower(trim($resulttype)))); die;
									if($result){
										$expResult[] = $result;
										$data[$key]['sub_assets_result']=json_encode($expResult);
									}else{
										$expResult[] = ucwords(strtolower(trim($cell->getValue())));
										$data[$key]['sub_assets_result']=json_encode($expResult);
									}
								}
							}else{
								$data[$key]['sub_assets_result']=json_encode($cell->getValue());
							}
						break; 
						case 'F':
							$observations=$cell->getValue();
							if($observations !=''){
								/* check if it has multiple values or single value */
								if(strpos($observations, ',') != false){
									$this->load->model('Subassets_model');
									$observation_array = explode(',',$cell->getValue());
									
									$objkeyID = array();
									foreach($observation_array as $subKey=>$subValue){
										$obValue = ucwords(strtolower(trim($subValue)));
										$result=$this->Subassets_model->get_inspection_list('observations',$obValue);
										if($result){
											$objkeyID[$subKey] = $result;
										}else{
											$objkeyID[$subKey] = $obValue;
										}
									}
									$data[$key]['sub_assets_observation'] = json_encode($objkeyID);
								}else{
									$obsResult = array();
									$this->load->model('Subassets_model');
									$result=$this->Subassets_model->get_inspection_list('observations',ucwords(strtolower(trim($observations))));
									if($result){
										$obsResult[] = $result;
										$data[$key]['sub_assets_observation']=json_encode($obsResult);
									}else{
										$obsResult[] = ucwords(strtolower(trim($cell->getValue())));
										$data[$key]['sub_assets_observation']=json_encode($obsResult);
									}
								}
							}else{
								$data[$key]['sub_assets_observation']=json_encode($cell->getValue());
							}
						break;
						
						case 'G':
						$data[$key]['sub_assets_repair']	=	strtolower(trim($cell->getValue()));
						break;
					} // end of switch 
					$data[$key]['status'] = 'Active';
					$dbdata['user_email']=$this->session->flexi_auth['user_identifier'];
					$data[$key]['created_date'] = now();
					$data[$key]['updated_date'] = 0;
				}	 // end celliterator
			}	

	    } // End row Iterator
		// insert data into database
        //echo "<pre>";
        //print_r($data); 
        //die;

		$result=$this->Subassets_model->import_sub_assets_list($data);	
		if($result){
			$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>SUB ASSETS LIST SUCCESSFULLY IMPORTED</div>");
			return true;
		}else{
			return false;
		}
	}	
	
	function ajax_get_subAssets(){
		$query=$_GET['search'];
		$this->load->database();
		if($query != 'blank'){
			$sql="select sub_assets_code from sub_assets where sub_assets_code like '%$query%' AND status=1";
		}else{
			$sql="select sub_assets_code from sub_assets WHERE status=1"; 
		}
		$query=$this->db->query($sql);
		if($query->num_rows()){
			 //print_r($query->result_array());
			
			$result=$query->result_array();
			$respose='';
			foreach($result as $sub_assets){
			$respose.="<p>".$sub_assets['sub_assets_code'];
			$respose.='<input class="pull-right" type="checkbox" name="sub_assets[]" id="chk_'.$sub_assets['sub_assets_code'].'"';
			$respose.='value="'.$sub_assets['sub_assets_code'].'"';
			$respose.='/></p>'; 
			}  
			echo $respose;
		}

	}
	
	public function delete_sub_assets($component_id){
		if($component_id!=null){
			$this->load->model('kare_model');
			if($this->kare_model->delete_sub_assets($component_id)){
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Component deleted successfully.</div>');
				redirect($this->client_url.'subassets_kare/sub_assets_list');	
			}   
		}	
	
	}
	
	/* **************************************************************** */
	/* **************************************************************** */
	//						Inspection/Result Form
	/* **************************************************************** */
	/* **************************************************************** */
	 
	public function inspection_result_list($id=NULL){
		$data['lang'] = $this->data['lang'];
		$data['type_category']=$this->Subassets_model->get_type_list('id,type_name,type_category');
		$inspection_result_list=$this->Subassets_model->get_inspection_result_list();
		if($id==NULL){
				if(isset($_POST['save_inspection_result'])){	
					if($this->insert_inspection_result()){
						redirect($this->client_url.'/subassets_kare/inspection_result_list');
					};
				}else{

					# echo "****************************"; die;
					$data['inspection_result_list']=$inspection_result_list;
                    #echo "<pre>";
					#print_r($data); die; 
					$this->load->view('inspection_result_list',$data);  
				}
		}else{
			// update record	
		    $sub_assets=$this->Subassets_model->get_inspection_result($id);
			if(is_array($sub_assets)){
				if(isset($_POST['edit_inspection_result'])){
					if($this->edit_inspection_result($id)){
						redirect($this->client_url.'/subassets_kare/inspection_result_list');
					}
				}else{
					$data['inspection_list']=$sub_assets;
					$data['inspection_result_list']=$inspection_result_list;
					$this->load->view('inspection_result_list',$data);  
				}
			}else{
			show_404();	
			}
	    }  
	}
	
/*  Ravindra Changes 26-8-2016 START */
	
	public function inspection_observation_list($id=NULL){
		$data['lang'] = $this->data['lang'];
		//$type_id = 31;
                
		$data['type_category']	=	$this->Subassets_model->get_observation_type_list('id,type_name,type_category');
		$inspection_result_list	=	$this->Subassets_model->get_inspection_observation_list();
                
                
                //	echo "<pre>";
                //	print_r($inspection_result_list);
                //	die;
		//$data['type_category']	=	$this->Subassets_model->get_observation_type_list($type_id);
		
		if($id==NULL){
				if(isset($_POST['save_inspection_result'])){	
					if($this->insert_inspection_result()){
						redirect($this->client_url.'/subassets_kare/inspection_observation_list');
					};
				}else{
					$data['inspection_result_list']=$inspection_result_list;	
					$this->load->view('observation_result_list',$data);  
				}
		}else{
			// update record	
		    $sub_assets=$this->Subassets_model->get_inspection_result($id);
			if(is_array($sub_assets)){
				if(isset($_POST['edit_inspection_result'])){
					if($this->edit_inspection_result($id)){
						redirect($this->client_url.'/subassets_kare/inspection_observation_list');
					}
				}else{
					$data['inspection_list']=$sub_assets;
					$data['inspection_result_list']=$inspection_result_list;
					$this->load->view('observation_result_list',$data);  
				}
			}else{
			show_404();	
			}
	    }  
	}

/*  Ravindra Changes 26-8-2016  END */
	
	
	public function insert_inspection_result(){
		
		if(isset($_POST['save_inspection_result'])){

		$dbdata['type_name']	=		ucwords(strtolower(trim($this->input->post('type_name'))));
		$dbdata['type_category']=		$this->input->post('type');
		$dbdata['status']		=		$this->input->post('status');
		$dbdata['user_email']	=		$this->session->flexi_auth['user_identifier'];
		$dbdata['created_date']	=		now();
                
                if($this->client_id){
                       $dbdata['type_client_fk']	=       $this->client_id; 
                }                               
                
                
		$this->form_validation->set_rules('type_name','Name','required');
		$this->form_validation->set_rules('type','Type','required');
		$this->form_validation->set_rules('status','Status','required');
		
			if($this->form_validation->run()==false){
					 return false;
			}else{
				// submit form data
				if($this->Subassets_model->save_inspection_result($dbdata)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Submited.</div>');
				   return true; 	
				}
			}
		}
	}
        
        
	
	function edit_inspection_result($id=NULL){
		
	    if($id==NULL){
			show_404();
		}else{
			// get the sub_assets data
			$sub_assets=$this->Subassets_model->get_inspection_result($id);
		
			if(isset($_POST['edit_inspection_result'])&& $_SERVER['REQUEST_METHOD']=='POST'){
				$dbdata['type_name']		=	ucwords(strtolower(trim($this->input->post('type_name'))));
				$dbdata['type_category']	=	$this->input->post('type');
				$dbdata['status']			=$this->input->post('status');
				$dbdata['user_email']		=	$this->session->flexi_auth['user_identifier'];
				$dbdata['updated_date']		=	now();

					$result=$this->Subassets_model->update_inspection_result($dbdata,$id);	
						if($result){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Updated</div>.');
						}else{
							$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error in updation.</div>');
						}
					return $result;
			}
			$data['inspection_result']=$sub_assets;
			$this->load->view('inspection_result_list',$data);
	    }	
	}
	
	
	public function delete_inspection_result($id){

			if($id!=null){
			 
			$related = $this->Subassets_model->get_related_result($id);
			if($this->Subassets_model->delete_inspection_result($related)){
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully.</div>');
				redirect($this->client_url.'subassets_kare/inspection_result_list');	
				//redirect($_SERVER['REQUEST_URI']);		
			}   
		}
	}
	
	function delete_action_proposed_result($id){
		
			if($this->Subassets_model->delete_action_proposed_result($id)){
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully.</div>');
				redirect($this->client_url.'subassets_kare/inspection_observation_list');	
			}
	}
	
	public function manage_stand_certificate($id=NULL){
		$data['lang'] = $this->data['lang'];
		$this->load->model('Subassets_model');
		$manage_standard_result_list=$this->Subassets_model->get_manage_standard_result_list();
		//echo "<pre>"; print_r($manage_standard_result_list); echo "</pre>";
		if($id==NULL){
				if(isset($_POST['save_manage_certificate_result'])){	
					if($this->insert_manage_stand_certificate_result()){
						redirect($this->client_url.'subassets_kare/manage_stand_certificate');
					};
				}else{
					$data['manage_standard_result_list']=$manage_standard_result_list;	
					$this->load->view('kare/manage_stand_certificate',$data);  
				}
		}else{
			// update record	
		    $sub_assets=$this->Subassets_model->get_standard_result($id);
			if(is_array($sub_assets)){ 
				if(isset($_POST['edit_manage_certificate_result'])){  
					if($this->edit_manage_certificate_result($id)){
						redirect($this->client_url.'subassets_kare/manage_stand_certificate');
					}
				}else{
					$data['manage_standard_list']=$sub_assets;
					$data['manage_standard_result_list']=$manage_standard_result_list;
					$this->load->view('kare/manage_stand_certificate',$data);  
				}
			}else{
			show_404();	
			}
	    }  
	}

	public function insert_manage_stand_certificate_result(){
		
		if(isset($_POST['save_manage_certificate_result'])){

		$dbdata['type']			=		trim($this->input->post('type'));
		$dbdata['name']			=		$this->input->post('name');
		$dbdata['status']		=		$this->input->post('status');
		$dbdata['user_email']	=		$this->session->flexi_auth['user_identifier'];
		$dbdata['created_date']	=		date("Y-m-d H:i:s");

		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('type','Type','required');
		$this->form_validation->set_rules('status','Status','required');
		
			if($this->form_validation->run()==false){
					 return false;
			}else{   
				$filtArray = array('type'=>$dbdata['type'], 'name'=>$dbdata['name']);
				if($this->Subassets_model->get_manage_cert_filt_result_list($filtArray)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">There is dublicate entry with respect to either type or name.</div>');
				   return true; 	
				}
				if($this->Subassets_model->save_manage_certificate_result($dbdata)){
				   $this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Submited.</div>');
				   return true; 	
				}
			}
		}
	}


	public function delete_manage_stand_certificate_result($id){
			if($id!=null){			 
			$related = $this->Subassets_model->get_standard_result($id);			
			if($this->Subassets_model->delete_manage_stand_certificate_result($related)){
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully.</div>');
				redirect($this->client_url.'subassets_kare/manage_stand_certificate');	
				//redirect($_SERVER['REQUEST_URI']);		
			}   
		}
	}	
	
	function edit_manage_certificate_result($id=NULL){
		
	    if($id==NULL){
			show_404();
		}else{
			// get the sub_assets data
			$sub_assets=$this->Subassets_model->get_manage_certificate_result($id);
		
			if(isset($_POST['edit_manage_certificate_result'])&& $_SERVER['REQUEST_METHOD']=='POST'){  
				$dbdata['type']				=		trim($this->input->post('type'));
				$dbdata['name']				=	$this->input->post('name');
				$dbdata['status']			=	$this->input->post('status');
				$dbdata['user_email']		=	$this->session->flexi_auth['user_identifier'];
				

					$result=$this->Subassets_model->update_manage_certificate_result($dbdata,$id);	
						if($result){
							$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Successfully Updated</div>.');
						}else{
							$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error in updation.</div>');
						}
					return $result;
			}
			$data['manage_standard_list']=$sub_assets;
			$this->load->view('kare/manage_stand_certificate',$data);
	    }	
	}

	
}// end of controller class 
?>