<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Specification extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->model('Specification_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('s3');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		/*if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		if (!$this->flexi_auth->is_privileged('Manage Infonet')) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			$this->load->model('Common_model');
			$groupID = $this->Common_model->get_loggedIn_user_dashboard();
			exit();
		}*/
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
	}
	
	function index(){
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		
		if(isset($_SESSION['specification'])){
			if(isset($_SESSION['specification']['back'])){
				unset($_SESSION['specification']['back']);
			}else{
				unset($_SESSION['specification']);
			}
		}
		
		$this->load->view('specification/specification_view');
	}
	
	// 
	function specifications($id = '')
	{

		if($id!=''){
			$_SESSION['specification']['back'] = 'Yes';
		}
		redirect('specification/', 'refresh');
	}
	
	public function get_selected_data(){
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		$type_name = $_POST['type_name'];
		$_SESSION['specification']['selected_type'] = $type_name;
		
		$this->load->model('Specification_model');
		$type_details=$this->Specification_model->get_type_details_testing($type_name);
		
		$html='';
		$html ="<table id='type_value_details' class='table table-hover table-bordered home_table'>
				<thead>
					<tr>
						<th class='col-md-1'>S. No.</th>
						<th class='col-md-3'>Subtype Code</th>";
						if($type_details && isset($type_details[0]['imagePath'])){
							$html .="<th class='col-md-1'>Thumbnail</th>";
						}
					$html .="<th class='col-md-4'>Description</th>
						<th class='col-md-1'>Upload</th>
						<th class='col-md-2'>View</th>
						
					</tr>
				</thead>
				<tbody>";
					if($type_details){
						$count = 1;
						foreach($type_details as $tVal){
							//$specification_data = json_encode($tVal['file']);
							if(isset($tVal['imagePath']) && $tVal['imagePath'] != ''){
								$thumb_path = str_replace("FCPATH",base_url(),$tVal['imagePath']);
							}else{
								$thumb_path = base_url('includes/images/no_image.jpg');
							}
							// echo "<pre>";
							// print_r(json_decode($tVal['file']));
							// die;
							$html .="<tr>
										<td class='col-md-1 text-center'>".$count."</td>
										<td class='col-md-3 text-center'>".$tVal['code']."</td>";
										if(isset($tVal['imagePath'])){
											$html.="<td class='col-md-1' align='center'><img src='".$thumb_path."' width='80' height='80'  /></td>";
										}
										$html.="<td class='col-md-4'>".$tVal['description']."</td>
										<td class='col-md-1 text-center'><a href='".base_url('specification/upload_specification/'.$tVal['typeID'])."' title='Upload Specification'>Upload</a></td>
										<td class='col-md-2'>";
											if(!empty($tVal['file'])){
												
												$file = json_decode($tVal['file'], true);
												foreach($file as $key=>$val){
													if($val['url']!=''){
														$html.="<a target='_blank' href='".$val['url']."' title='".$val['title']."'><b>".$val['title']."</b></a> | ";
													}
												}
											}else{
												$html.='No Data Available';
											}
										$html .="</td>
									</tr>";
						$count++; }
					}else{
						$html .="<tr><td colspan='4'>No Data</td></tr>";
					}
				$html .="</tbody>
			<table>";
		echo $html;
	}
	
	
	function upload_specification($id=''){
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		$type_name 	= $_SESSION['specification']['selected_type'];

		if($type_name == 'components'){
			$_SESSION['specification']['selected_type_val'] = 'Assets';
		}elseif($type_name == 'sub_assets'){
			$_SESSION['specification']['selected_type_val'] = 'Sub Assets';
		}elseif($type_name == 'products'){
			$_SESSION['specification']['selected_type_val'] = 'Asset Series';
		}
		
		if(isset($_POST['add_specification'])){
			
			if(isset($_FILES['upload'])){
				$name = $_FILES['upload']['name'];
				if($this->is_postImage_empty($name)){
					
					$file_url = array();
					$error = array();
					// single or multiple files are send to upload
					
					foreach($name as $nKey=>$nVal){
						$title = $_POST['upload'][$nKey]['title'];
						
						if(!empty($_FILES['upload']['name'][$nKey]['url'])){
							
							$ext 	=	pathinfo($_FILES['upload']['name'][$nKey]['url'], PATHINFO_EXTENSION);
							if($ext =='gif' || $ext =='jpg'|| $ext=='png' || $ext =='jpeg' || $ext =='pdf'){
								$name 			= $_FILES['upload']['name'][$nKey]['url'];
								$fileTempName 	= $_FILES['upload']['tmp_name'][$nKey]['url'];
								// new name 
								$confirm_code	=	md5(uniqid(rand()));
								$ext 			=	pathinfo($_FILES['upload']['name'][$nKey]['url'], PATHINFO_EXTENSION);
								$newfileName	=	$confirm_code.'.'.strtolower($ext);
								
								//move the file
								// compulsary to define the content type of the file.
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
						$tableName 		= 'specification';
						$_SESSION['specification']['upload_data'] = $file_url;
						
						$dbdata = array(
							'codeID'		=> $id,
							'tableName'		=> $_SESSION['specification']['selected_type'],
							'file'			=> json_encode($file_url),
							'created_by'	=> $this->session->flexi_auth['user_identifier']
						);
						$result = $this->Common_model->insert_table_data($dbdata,$tableName);
						if($result){
							unset($_SESSION['specification']['upload_data']);
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Files Uploaded Successfully.</p>');
							redirect('specification/specifications/'.$id, 'refresh');
						}else{
							$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b> Error in Inserting Data in Database. Plz contact Admin.</p>');
							//return false;
						}
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
					}
				}else{
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b>Please upload the file and try again.</p>');
					//return false;
				}
			}
			
		}else if(isset($_POST['update_specification'])){
			// UPDATION 
			
			if(isset($_FILES['upload'])){
				$name = $_FILES['upload']['name'];

				if($this->is_postImage_empty($name)){
					$count = count($_FILES['upload']['name']);
					
					$file_url = array();
					$error = array();
					$prev_file_name = array();
					// single or multiple files are send to upload
					//$previous_file = json_decode($_SESSION['specification']['fetch_data']['file']);
					for($i = 0; $i< $count; $i++ ){
						$title = $_POST['upload'][$i]['title'];
						if(!empty($_FILES['upload']['name'][$i]['url'])){
							$ext 	=	pathinfo($_FILES['upload']['name'][$i]['url'], PATHINFO_EXTENSION);
							if($ext =='gif' || $ext =='jpg'|| $ext=='png' || $ext =='jpeg' || $ext =='pdf'){
								$name 			= $_FILES['upload']['name'][$i]['url'];
								$fileTempName 	= $_FILES['upload']['tmp_name'][$i]['url'];
								// new name 
								$confirm_code	=	md5(uniqid(rand()));
								$ext 			=	pathinfo($_FILES['upload']['name'][$i]['url'], PATHINFO_EXTENSION);
								$newfileName	=	$confirm_code.'.'.strtolower($ext);
								
								//move the file
								// compulsary to define the content type of the file.
								$contentType = array('Content-Type' =>$_FILES['upload']['type'][$i]['url']);
								
								//S3::putObject($string, $bucketName, $uploadName, S3::ACL_PUBLIC_READ, array(), array('Content-Type' => 'text/plain'))
								if ($this->s3->putObjectFile($fileTempName, "karam-kare", $newfileName,S3::ACL_PUBLIC_READ,array(),$contentType)){
									//echo "We successfully uploaded your file.";
									$file_url[$i] = array('title'=>$title,'url' => "https://s3.ap-south-1.amazonaws.com/karam-kare/".$newfileName,
									"file_name" => $newfileName);
									
									$prev_file_name[$i] = $title;
									// Once New file updated, Now check if that url previously contain any url.
									// If Yes, then delete that file from the server.
									
								}else{
									$error[] = $_FILES['upload']['name'][$i]['url'];
									// file not uploaded
									$error['msg'] = 'Syntax Error ! Error In Uploading Files on the Server. Please contact Admin.';
								}
							}else{
								//  Check if this title values is same and the url of title is not blank.
								//$file_url[$i] = $this->is_url_present_in_previous_data($title);
								$error[] = $_FILES['upload']['name'][$i]['url'];
								$error['msg'] = 'Syntax Error ! Only gif|jpg|png|jpeg|pdf| type files are allowed to upload. Please Try Again.';
							}
						}else{
							//  Check if this title values is same and the url of title is not blank.
							$file_url[$i] = $this->is_url_present_in_previous_data($title);
						}
					}
					
					if(empty($error)){
						$tableName 		= 'specification';
						$updateID 		= $_SESSION['specification']['updateID'];
						$_SESSION['specification']['upload_data'] = $file_url;
						$dbdata = array(
							'codeID'		=> $id,
							'tableName'		=> $_SESSION['specification']['selected_type'],
							'file'			=> json_encode($file_url),
							'created_by'	=> $this->session->flexi_auth['user_identifier']
						);
						$result = $this->Common_model->update_table_data($updateID, $dbdata, $tableName);
						if($result){
							if(!empty($prev_file_name)){
								foreach($prev_file_name as $pFile){
									$prev_file = $this->is_url_present_in_previous_data($pFile,'check');
									if($prev_file){
										$this->delete_file_from_aws($prev_file);
									}
								}
							}
							
							unset($_SESSION['specification']['upload_data']);
							unset($_SESSION['specification']['fetch_data']);
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Files Uploaded Successfully.</p>');
							redirect('specification/specifications/'.$id, 'refresh');
						}else{
							$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b> Error in Inserting Data in Database. Plz contact Admin.</p>');
							//return false;
						}
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
					}
				}
			}
		}
		
		$this->data['specification'] = $_SESSION['specification']['fetch_data']= $this->Specification_model->get_all_specification($id, $type_name);
		
		$this->load->view('specification/upload_specification',$this->data);
		
	}
	
	function delete_file_from_aws($file_name){
		
		if($this->s3->deleteObject('karam-kare', $file_name)){
			return true;
		}else{
			return false;
		}
	}
	
	function is_postImage_empty($name){
		foreach($name as $nVal){
			if(!empty('url')){
				$file_exist = 'Conatin File';
			}
		}
		return (isset($file_exist))? true : false ;
	}
	
	function is_url_present_in_previous_data($title,$check=''){
		$previous_file = json_decode($_SESSION['specification']['fetch_data']['file'],true);
		$temp_array = array();
		foreach($previous_file as $pVal){
			if(strtolower($title) == strtolower($pVal['title']) && $pVal['url']!=''){
				$temp_array = array('title'=>$title, "url" =>$pVal['url'], 'file_name'=>$pVal['file_name']);
			}
		}
		if($check ==''){
			if(!empty($temp_array)){
				$furl = $temp_array;
			}else{
				$furl = array('title'=>$title, "url" =>"", 'file_name'=>'');
			}
			return $furl;
		}else{
			if(!empty($temp_array)){
				return  $temp_array['file_name'];
			}else{
				return false;
			}
		}
	}
	
	function delete_uploadFile(){
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		// echo "<pre>";
		// print_r($_SESSION['specification']);
		// die;
		$title 		= $_REQUEST['title'];
		$fileName 	= $_REQUEST['fileName'];
		$id			= $_SESSION['specification']['fetch_data']['id'];
		$codeID		= $_SESSION['specification']['fetch_data']['codeID'];
		
		$result = $this->Specification_model->delete_uploaded_file($id, $title, $fileName);
		if($result){
			if($this->delete_file_from_aws($fileName)){
				$this->session->set_flashdata('msg','<p class="alert alert-success capital">File Deleted Successfully</p>');
			}else{
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital">Error ! File Not Deleted. Problem in Deleting File from the Server.</p>');
			}
		}else{
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital">Error in Deletion frile from Database.</p>');
		}
		redirect('specification/upload_specification/'.$codeID, 'refresh');
	}
	
		// Multi Uploads of SPecifications
	
	function multi_uploads(){
		if(isset($_SESSION['specification'])){
			unset($_SESSION['specification']);
		}
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		if(isset($_SESSION['product_edit'])){
			unset($_SESSION['product_edit']);
		}
		if(isset($_POST['add_multiSpeci'])){
			$this->add_multiSpecification();
			
			$this->get_selectedSpeci_data();
		}
		
		$this->load->view('specification/multi_upload');
	}

	
	function add_multiSpecification(){
		
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		// echo "<pre>";
		// print_r($_POST);
		// print_r($_FILES);
		// die;
		if(isset($_POST['sub_assets'])){
			if(isset($_FILES['upload'])){
				$names = $_FILES['upload']['name'];
				if($this->is_postImage_empty($names)){
					
					$_SESSION['specification']['selected_type'] = $_POST['upload_type'];
					
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
								
								//move the file
								// compulsary to define the content type of the file.
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
						$tableName 		= 'specification';
						$_SESSION['specification']['upload_data'] = $file_url;
						
						foreach($_POST['sub_assets'] as $id){
							$dbdata = array(
								'codeID'		=> $id,
								'tableName'		=> $_SESSION['specification']['selected_type'],
								'file'			=> json_encode($file_url),
								'created_by'	=> $this->session->flexi_auth['user_identifier']
							);
							$check = $this->Specification_model->is_duplicate($id, $_SESSION['specification']['selected_type']);
							
							
							if(!$check){
								// Insert The Value In the Table
								$result = $this->Common_model->insert_table_data($dbdata,$tableName);
							}else{
								// Else Update the Value In The Table
									$prev_id		=	$check['id'];
									$prev_file_arr 	= json_decode($check['file'],true);
									foreach($prev_file_arr as $key=>$val){
										$url=$val['url'];
										if($url !=''){
											$this->delete_file_from_aws($url);
										}
									}
									$this->Common_model->delete_table_data($prev_id,$tableName);
								
								$result = $this->Common_model->insert_table_data($dbdata,$tableName);
								//$result = $this->Common_model->update_table_data($id, $dbdata, $tableName);
							}
						}						
						unset($_SESSION['specification']['upload_data']);
						$this->session->set_flashdata('msg','<p class="alert alert-success capital">Files Uploaded Successfully.</p>');
						//redirect('specification/multi_uploads/', 'refresh');
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
	
	function get_selectedSpeci_data(){
		if (! $this->flexi_auth->is_privileged('View Infonet') && !$this->flexi_auth->is_privileged('Manage Infonet'))
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			redirect('auth_admin');	
		}
		$type_name = $_SESSION['specification']['selected_type'];
		
		
		$this->load->model('Specification_model');
		$type_details=$this->Specification_model->get_type_details_testing($type_name);
		
		$html='';
		if(($type_name == 'components') || ($type_name == 'sub_assets')){
			
			if($type_name == 'components'){
				$submit = 'submit_assets';
				$title 	= 'Select Sub-Asset';
			}else{
				$submit = 'submit_subAssets';
				$title 	= 'Select Asset';
			}
			
			$html ="<div class='form-group'>
					<label class='col-md-2 control-label'>".$title." <br><small>Multiple Select</small></label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-5'> 
								<div class='component-container form-control search-subAssets' id='sel_subAssets_productedit'>";
									if($type_details !=''){
										foreach($type_details as $sub_assets){ 
									$html .="<p>".$sub_assets['code']."
									<input class='pull-right' type='checkbox' name='sub_assets[]' id='chk_".$sub_assets['typeID']."' value='".$sub_assets['typeID']."' rel='".$sub_assets['code']."' /></p>";
									 } } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_subAssets_productedit' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container form-control' id='selected_subAssets'></div>
							</div>
						</div>
					</div>
				</div>";
		}else{
			$html ="<div class='form-group'>
					<label class='col-md-2 control-label'>Select Assets Series <br><small>Multiple Select</small></label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-5'> 
								<div class='component-container form-control search-subAssets' id='sel_subAssets_productedit'>";
									if($type_details !=''){
										foreach($type_details as $sub_assets){ 
									$html .="<p>".$sub_assets['code']."
									<input class='pull-right' type='checkbox' name='sub_assets[]' id='chk_".$sub_assets['typeID']."' value='".$sub_assets['typeID']."' rel='".$sub_assets['code']."' /></p>";
									 } } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_subAssets_productedit' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container form-control' id='selected_subAssets'></div>
							</div>
						</div>
					</div>
				</div>"; 
		}
		
		$_SESSION['product_edit']['fetch_data'] = $html;
		return true;
	}
	
}// end of controller class 
?>