<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ProductCategory_model');
		$this->load->model('Common_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
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
		$this->manage_category();
	}
	
	function manage_category(){
		if(isset($_SESSION['category'])){
			unset($_SESSION['category']);
		}
		
		$this->data['categories']=$this->ProductCategory_model->get_all_categories();
		//print_r($this->data);die('123');
		$this->load->view('manage_products/category/view_category',$this->data);
	}
	
	/*function add_category(){
		if(isset($_POST['add_category'])){
			$this->load->library('form_validation');
			$val = array(
				array('field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|required'),
				array('field' => 'category_status', 'label' => 'Status', 'rules' => 'trim|required'),
			);

			$this->form_validation->set_rules($val);

			if ($this->form_validation->run() == FALSE) {
				if (!validation_errors() == '' && $this->input->post('cat_update') == 'cat_update') {
					  setMessages(validation_errors(),'error');
				}
			}else{
				$cat_parentid = ($_POST['cat_parentid'] == '')? '0' :  $_POST['cat_parentid'];
				
				$cat_uniqueName = str_replace(' ','_',strtolower(trim($_POST['category_name'])));
				
				
				if($_FILES['cat_image']['name']!=''){
					$field_name 		= 'cat_image';
					$folder_path 	= 	"./uploads/category/";
					// do_upload function is present in kare_helper file.
					$data 			= do_upload($field_name,$folder_path);
					
					if(isset($data['error'])){
					   $error	=	$data['error'];
					   // setMessages function is present in kare_helper file.
					   setMessages($error,'error');
					   redirect('category_controller/manage_category', 'refresh');
					}else{
						$cat_image	=	$data['file_path'];
					}
				}else{
					$cat_image = NULL;
				}
				
				
				$dbarray = array(
								'cat_parentid' 	=> $cat_parentid,
								'cat_name' 		=> ucwords(trim($_POST['category_name'])),
								'cat_uniqueName'=> $cat_uniqueName,
								'cat_image'		=> $cat_image,
								'cat_status' 	=> $_POST['category_status'],
								);
				
				$this->load->model('Common_model');
				$res = $this->Common_model->insert_table_data($dbarray,'manage_categories');
				if($res){
					setMessages('<strong>Category</strong> Added Successfully.');
					redirect('category_controller/manage_category', 'refresh');
				}else{
					setMessages("Could't Insert Category. Please Try Again Later.",'error');
				}
			}
		}
		
		$this->load->view('manage_products/category/add_category');
	}*/
	
	function add_category(){
		if(isset($_POST['add_category'])){
			$this->load->library('form_validation');
			$val = array(
				array('field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|required'),
				array('field' => 'category_status', 'label' => 'Status', 'rules' => 'trim|required'),
			);

			$this->form_validation->set_rules($val);

			if ($this->form_validation->run() == FALSE) {
				if (!validation_errors() == '' && $this->input->post('cat_update') == 'cat_update') {
					  setMessages(validation_errors(),'error');
				}
			}else{
				$cat_parentid = ($_POST['cat_parentid'] == '')? '0' :  $_POST['cat_parentid'];
				
				$cat_uniqueName = str_replace(' ','_',strtolower(trim($_POST['category_name'])));
				
				$category_hir = ($_POST['cat_parentid'] == '')? '0' : $this->_categoryString($_POST['cat_parentid']);
				
                                if(!empty($category_hir) && is_array($category_hir) && !empty($_POST['cat_parentid'])){
                                    $category = $_POST['cat_parentid'].','.implode(',', $category_hir).',0';
                                }else if(!empty($_POST['cat_parentid'])){
                                    $category = $_POST['cat_parentid'].',0';
                                }else {
                                    $category = 0;
                                }   
				
				//print_r($category);die;
				/* Start: For Image Upload */
				if($_FILES['cat_image']['name']!=''){
					$field_name 		= 'cat_image';
					$folder_path 	= 	"./uploads/category/";
					// do_upload function is present in kare_helper file.
					$data 			= do_upload($field_name,$folder_path);
					
					if(isset($data['error'])){
					   $error	=	$data['error'];
					   // setMessages function is present in kare_helper file.
					   setMessages($error,'error');
					   redirect('category_controller/manage_category', 'refresh');
					}else{
						$cat_image	=	$data['file_path'];
					}
				}else{
					$cat_image = NULL;
				}
				
				
				
				/* End: For Image Upload */
				$dbarray = array(
                                            'cat_parentid' 	=> $cat_parentid,
                                            'cat_name' 		=> ucwords(trim($_POST['category_name'])),
                                            'cat_uniqueName'=> $cat_uniqueName,
                                            'cat_image'		=> $cat_image,
                                            'cat_status' 	=> $_POST['category_status'],
                                            'category'         => $category,
                                            
                                        );
				
				$this->load->model('Common_model');
				$res = $this->Common_model->insert_table_data($dbarray,'manage_categories');
				if($res){
					setMessages('<strong>Category</strong> Added Successfully.');
					redirect('category_controller/manage_category', 'refresh');
				}else{
					setMessages("Could't Insert Category. Please Try Again Later.",'error');
				}
			}
		}
		
		$this->load->view('manage_products/category/add_category');
	}
	
	/*................................._categoryString.............*/
        //global $return;
        function _categoryString($cat_parentid = ''){
            global $return;
            if(isset($cat_parentid)){
               $this->load->model('Common_model');
               $cID= $this->Common_model->fetchcategoryHri($cat_parentid);
               $parent_id = $cID[0]['cat_parentid'];
               if ($parent_id != 0) {
                   // echo $parent_id; // geting parent id as 432 
                    $return[] = $parent_id;
                    $a = $this->_categoryString($parent_id);
                }else{
                   // return $parent_id;
                }
                return $return;
            } 
	}
    /*.................................End _categoryString..........*/
	
	function edit_category($id){
		
		if (isset($_POST['cat_update'])){
			$this->_update($id);
		}else{
			$this->data['category_data'] = $category_data = $this->ProductCategory_model->get_category_for_id($id);
			if($category_data['cat_image'] != NULL){
				$_SESSION['category']['imagePath'] = $category_data['cat_image'];
			}
			$this->load->view('manage_products/category/edit_category',$this->data);
		}
	}
	
	/*function _update($id)
    {
        $this->load->library('form_validation');
        $val = array(
            array('field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|required'),
            array('field' => 'category_status', 'label' => 'Status', 'rules' => 'trim|required'),
        );

        $this->form_validation->set_rules($val);

        if ($this->form_validation->run() == FALSE) {
            if (!validation_errors() == '' && $this->input->post('cat_update') == 'cat_update') {
				 $this->session->set_flashdata('msg',"<p class='alert alert-danger capital'>".validation_errors()."</p>");
            }
        }else{
            $cat_parentid = ($_POST['cat_parentid'] == '')? '0' :  $_POST['cat_parentid'];
				
			$cat_uniqueName = str_replace(' ','_',strtolower(trim($_POST['category_name'])));
			
			if($_FILES['cat_image']['name']!=''){
				$file_name 		= 'cat_image';
				$folder_path 	= "./uploads/category/";
				$data 			= do_upload($file_name,$folder_path);

				if(isset($data['error'])){
				   $error	=	$data['error'];
				   setMessages($error,'error');
				   redirect('category_controller/manage_category', 'refresh');
				}else{
					$cat_image		=	$data['file_path'];
				}
				// Delete Old Image from the folder if new image got uploaded.
				if(isset($_SESSION['category']['imagePath'])){
					delete_file($_SESSION['category']['imagePath']);
					unset($_SESSION['category']);
				}
			}else{
				$cat_image = (isset($_SESSION['category']['imagePath']))? $_SESSION['category']['imagePath'] : NULL;
			}
			
			$dbdata=array(
				'cat_name'		=>ucwords(trim($_POST['category_name'])),
				'cat_parentid'	=>$cat_parentid,
				'cat_uniqueName'=> $cat_uniqueName,
				'cat_image'		=> $cat_image,
				'cat_status' 	=> $_POST['category_status'],
			);
			$this->load->model('Common_model');
			$res = $this->Common_model->update_tabel_data($id, $dbdata, 'manage_categories');	 
            if($res){
                setMessages('<strong>Category</strong> Successfully Updated');
			}else{
                setMessages("Could't update Category. Please try again later.", 'error');
            }
        }
		redirect('category_controller/edit_category/'.$id);
    }*/
	
	function _update($id){
            $this->load->library('form_validation');
            $val = array(
                array('field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|required'),
                array('field' => 'category_status', 'label' => 'Status', 'rules' => 'trim|required'),
            );

            $this->form_validation->set_rules($val);

            if ($this->form_validation->run() == FALSE) {
                if (!validation_errors() == '' && $this->input->post('cat_update') == 'cat_update') {
                                     $this->session->set_flashdata('msg',"<p class='alert alert-danger capital'>".validation_errors()."</p>");
                }
            }else{
                $cat_parentid = ($_POST['cat_parentid'] == '')? '0' :  $_POST['cat_parentid'];

                            $cat_uniqueName = str_replace(' ','_',strtolower(trim($_POST['category_name'])));

                            /*$category_hir = (isset($_SESSION['category']))? $_SESSION['category'] : '0';
                            if($category_hir !='0'){
                                    $category_val = $category_hir.'-'.$id;
                            }*/
                            $category_hir = ($_POST['cat_parentid'] == '')? '0' : $this->_categoryString($_POST['cat_parentid']);
                          //  print_r($category_hir); print "<br/>";
                            if(!empty($category_hir) && is_array($category_hir) && !empty($_POST['cat_parentid'])){
                                $category = $_POST['cat_parentid'].','.implode(',', $category_hir).',0';
                            }else if(!empty($_POST['cat_parentid'])){
                                $category = $_POST['cat_parentid'].',0';
                            }else {
                                $category = 0;
                            }   
                           // print_r($category);die("123");
                            if($_FILES['cat_image']['name']!=''){
                                    $file_name 		= 'cat_image';
                                    $folder_path 	= "./uploads/category/";
                                    $data 			= do_upload($file_name,$folder_path);

                                    if(isset($data['error'])){
                                       $error	=	$data['error'];
                                       setMessages($error,'error');
                                       redirect('category_controller/manage_category', 'refresh');
                                    }else{
                                            $cat_image		=	$data['file_path'];
                                    }
                                    // Delete Old Image from the folder if new image got uploaded.
                                    if(isset($_SESSION['category']['imagePath'])){
                                            delete_file($_SESSION['category']['imagePath']);
                                            unset($_SESSION['category']);
                                    }
                            }else{
                                    $cat_image = (isset($_SESSION['category']['imagePath']))? $_SESSION['category']['imagePath'] : NULL;
                            }

                            $dbdata=array(
                                    'cat_name'		=>ucwords(trim($_POST['category_name'])),
                                    'cat_parentid'	=>$cat_parentid,
                                    'cat_uniqueName'=> $cat_uniqueName,
                                    'cat_image'		=> $cat_image,
                                    'cat_status' 	=> $_POST['category_status'],
                                    'category'      => $category,
                            );
                            //print_r($dbdata);die;
                            $this->load->model('Common_model');
                            $res = $this->Common_model->update_tabel_data($id, $dbdata, 'manage_categories');	 
                if($res){
                                    unset($_SESSION['category']);
                    setMessages('<strong>Category</strong> Successfully Updated');
                            }else{
                    setMessages("Could't update Category. Please try again later.", 'error');
                }
            }
                    redirect('category_controller/edit_category/'.$id);
        }
	
	function delete_category($id){
		
		$data 		= $this->ProductCategory_model->get_category_for_id($id);
		$imagePath 	= $data['cat_image'];
		$tableName 	= 'manage_categories';
		$result 	= $this->Common_model->delete_table_data($id,$tableName);
		if($result){
			if($imagePath != NULL){
				delete_file($imagePath);
			}
			setMessages('Data Deleted Successfully');
			redirect('category_controller/manage_category/', 'refresh');
		}else{
			setMessages('Data Not Deleted.','error');
			redirect('category_controller/manage_category/', 'refresh');
		}
	}
	
	function delete_categoryImage(){
		$id 	= $_POST['id'];
		$path 	= $_POST['path'];
		
		$dbdata = array('cat_image'=>NULL);
		$row = $this->Common_model->update_table_data($id, $dbdata, 'manage_categories');
		if($row){
			delete_file($path);
			unset($_SESSION['category']);
			echo true;
		}else{
			echo "NO UPDATE";die;
		}
	}
	
	
}// end of controller class 
?>