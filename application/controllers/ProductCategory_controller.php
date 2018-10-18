<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ProductCategory_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ProductCategory_model');
		$this->load->model('Subassets_model');
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		/*if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			//$this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
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
	
	/*public function add_product(){
		
		if(isset($_POST['add_product_category'])){
			// echo "<pre>";
			// print_r($_POST);
			//die;
			$this->load->library('form_validation');
			// Set validation rules.
				$validation_rules = array(array('field' =>'cat_parentid', 'label' =>'Category Name','rules' => 'required','errors' => array('required' => '%s is required')));
			
			$this->form_validation->set_rules($validation_rules);
			
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Category Name is required.</div>');
				echo "error in validation";
			}else{
				$tableName 		= 'manage_products';
				$cat_id = $this->input->post('cat_parentid');
				$cat_details	= $this->ProductCategory_model->get_category_for_id($cat_id);				
				// print_r($cat_details);
				// die;
				$dbarray	= array(
					'category_id' 	=> $cat_id,
					'catParent_id' 	=> $cat_details['cat_parentid'],
					'assets' 		=> json_encode($this->input->post('assets')),
					'sub_assets' 	=> json_encode($this->input->post('sub_assets')),
					'assets_series' => json_encode($this->input->post('assets_series')),
					'status' => trim($this->input->post('product_category_status'))
					);
					
				$res = $this->Common_model->insert_table_data($dbarray,$tableName);
				if($res){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Added Successfully.</div>');
					redirect('ProductCategory_controller/manage_product_categogy', 'refresh');
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Inserted. Plz try again</div>');
				}
			}
		}
		
		// for category list 
		$category = $this->ProductCategory_model->get_category_list();
		// echo "<pre>";
		// print_r($category);
		// echo "</pre>";
		if($category != ''){			
			$data['category_list'] = $category;
		}else{
			$data['category_list'] = '';
		}
		//$data['category_list'] = $category;
		// for category list END 		
		
		// for assets 
		$components=$this->kare_model->get_components_list();
		if($components){
			foreach($components as $component){
				$components_list[]=$component['component_code'];
			}
		}else{
			$components_list = '';
		}
		$data['components_list'] = $components_list;
		// for assets 
		
		// for assets series 
		$products_list=$this->kare_model->get_products_list();
		if($products_list){
			foreach($products_list as $products){					
				$products_series_list[]=$products['product_code'];
			}
		}else{
			$products_series_list = '';
		}
		$data['products_series_list'] = $products_series_list;
		// for assets series 
		
		// for Sub assets
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		// for Sub assets
		
		$this->load->view('manage_products/product_category/add_product',$data);
	}*/
	
	public function add_product(){
		
		if(isset($_POST['add_product_category'])){
			
			$this->load->library('form_validation');
			// Set validation rules.
				$validation_rules = array(array('field' =>'cat_parentid', 'label' =>'Category Name','rules' => 'required','errors' => array('required' => '%s is required')));
			
			$this->form_validation->set_rules($validation_rules);
			
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Category Name is required.</div>');
				echo "error in validation";
			}else{
				$tableName 		= 'manage_products';
				$cat_id = $this->input->post('cat_parentid');
				$cat_details	= $this->ProductCategory_model->get_category_for_id($cat_id);				
				// print_r($cat_details);
				// die;
				$dbarray	= array(
					'category_id' 	=> $cat_id,
					'catParent_id' 	=> $cat_details['cat_parentid'],
					'assets' 		=> json_encode($this->input->post('assets')),
					'sub_assets' 	=> json_encode($this->input->post('sub_assets')),
					'assets_series' => json_encode($this->input->post('assets_series')),
					'status' => trim($this->input->post('product_category_status'))
					);
					//print_r($dbarray);die;
				$res = $this->Common_model->insert_table_data($dbarray,$tableName);
				if($res){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Added Successfully.</div>');
					redirect('ProductCategory_controller/manage_product_categogy', 'refresh');
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Inserted. Plz try again</div>');
				}
			}
		}
		
		/* for category list */
		$category = $this->ProductCategory_model->get_category_list();
		// echo "<pre>";
		// print_r($category);
		// echo "</pre>";
		if($category != ''){			
			$data['category_list'] = $category;
		}else{
			$data['category_list'] = '';
		}
		//$data['category_list'] = $category;
		/* for category list END */		
		
		/* for assets */
		$components=$this->kare_model->get_components_list();
		if($components){
			foreach($components as $component){
				$components_list[]=$component['component_code'];
			}
		}else{
			$components_list = '';
		}
		$data['components_list'] = $components_list;
		/* for assets */
		
		/* for assets series */
		$products_list=$this->kare_model->get_products_list();
		if($products_list){
			foreach($products_list as $products){					
				$products_series_list[]=$products['product_code'];
			}
		}else{
			$products_series_list = '';
		}
		$data['products_series_list'] = $products_series_list;
		/* for assets series */
		
		/* for Sub assets*/
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		/* for Sub assets*/
		
		$this->load->view('manage_products/product_category/add_product',$data);
	}
	
	function manage_product_categogy(){
		$this->data['product']=  $product =$this->ProductCategory_model->get_all_products();
		$this->load->view('manage_products/product_category/manage_product_categogy',$this->data);
	}
	
	function delete_product($id){
		$tableName = 'manage_products';
		$result = $this->Common_model->delete_table_data($id,$tableName);
		if($result){
			$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Deleted Successfully.</div>');
			redirect('productCategory_controller/manage_product_categogy/', 'refresh');
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Deleted.</div>');
			redirect('productCategory_controller/manage_product_categogy/', 'refresh');
		}
	}
	
	public function edit_product($id= null){
		
		if(isset($_POST['edit_product_category'])){
			$this->load->library('form_validation');
			// Set validation rules.
				$validation_rules = array(array('field' =>'cat_parentid', 'label' =>'Category Name','rules' => 'required','errors' => array('required' => '%s is required')));
			
			$this->form_validation->set_rules($validation_rules);
			
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Category Name is required.</div>');
				echo "error in validation";
			}else{
				$tableName 		= 'manage_products';
				$cat_id 		= $this->input->post('cat_parentid');
				$cat_details	= $this->ProductCategory_model->get_category_for_id($cat_id);
				$dbarray	= array(
					'category_id' 	=> $cat_id,
					'catParent_id' 	=> $cat_details['cat_parentid'],
					'assets' 		=> json_encode($this->input->post('assets')),
					'sub_assets' 	=> json_encode($this->input->post('sub_assets')),
					'assets_series' => json_encode($this->input->post('assets_series')),
					'status' => trim($this->input->post('product_category_status'))
					);
				$res = $this->Common_model->update_tabel_data($id,$dbarray,$tableName);
				if($res){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Updated Successfully.</div>');
					redirect('ProductCategory_controller/manage_product_categogy', 'refresh');
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Updated. Plz try again</div>');
				}
			}
		}
		
		/* for category list */
		$category = $this->ProductCategory_model->get_category_list();
		if($category != ''){			
			$data['category_list'] = $category;
		}else{
			$data['category_list'] = '';
		}
		//$data['category_list'] = $category;
		/* for category list END */	
		
		/* for assets */
		$components=$this->kare_model->get_components_list();
		if($components){
			foreach($components as $component){
				$components_list[]=$component['component_code'];
			}
		}else{
			$components_list = '';
		}
		$data['components_list'] = $components_list;
		/* for assets */
		
		/* for assets series */
		$products_list=$this->kare_model->get_products_list();
		if($products_list){
			foreach($products_list as $products){					
				$products_series_list[]=$products['product_code'];
			}
		}else{
			$products_series_list = '';
		}
		$data['products_series_list'] = $products_series_list;
		/* for assets series */
		
		/* for Sub assets*/
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		/* for Sub assets*/
		
		
		/* Get data from database */
		$data['product_result'] = $product_result = $this->ProductCategory_model->get_category_for_productId($id);
		/* Get data from database END*/
		
		$this->load->view('manage_products/product_category/edit_product',$data);
	}
	
	/*public function edit_product($id= null){
		
		if(isset($_POST['edit_product_category'])){
			
			$this->load->library('form_validation');
			// Set validation rules.
				$validation_rules = array(array('field' =>'cat_parentid', 'label' =>'Category Name','rules' => 'required','errors' => array('required' => '%s is required')));
			
			$this->form_validation->set_rules($validation_rules);
			
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Category Name is required.</div>');
				echo "error in validation";
			}else{
				$tableName 		= 'manage_products';
				$cat_id 		= $this->input->post('cat_parentid');
				$cat_details	= $this->ProductCategory_model->get_category_for_id($cat_id);				
				
				$dbarray	= array(
					'category_id' 	=> $cat_id,
					'catParent_id' 	=> $cat_details['cat_parentid'],
					'assets' 		=> json_encode($this->input->post('assets')),
					'sub_assets' 	=> json_encode($this->input->post('sub_assets')),
					'assets_series' => json_encode($this->input->post('assets_series')),
					'status' => trim($this->input->post('product_category_status'))
					);
				$res = $this->Common_model->update_tabel_data($id,$dbarray,$tableName);
				if($res){
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Updated Successfully.</div>');
					redirect('ProductCategory_controller/manage_product_categogy', 'refresh');
				}else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Updated. Plz try again</div>');
				}
			}
		}
		
		// for category list 
		$category = $this->ProductCategory_model->get_category_list();
		if($category != ''){			
			$data['category_list'] = $category;
		}else{
			$data['category_list'] = '';
		}
		//$data['category_list'] = $category;
		// for category list END 
		
		// for assets 
		$components=$this->kare_model->get_components_list();
		if($components){
			foreach($components as $component){
				$components_list[]=$component['component_code'];
			}
		}else{
			$components_list = '';
		}
		$data['components_list'] = $components_list;
		// for assets 
		
		// for assets series 
		$products_list=$this->kare_model->get_products_list();
		if($products_list){
			foreach($products_list as $products){					
				$products_series_list[]=$products['product_code'];
			}
		}else{
			$products_series_list = '';
		}
		$data['products_series_list'] = $products_series_list;
		// for assets series 
		
		// for Sub assets
		$subAssets=$this->Subassets_model->get_sub_assets_list('sub_assets_code');
		if(!empty($subAssets)){
			foreach($subAssets as $sAssets){
				$sub_assets_list[]=$sAssets['sub_assets_code'];
			}
		}else{
			$sub_assets_list = '';
		}
		$data['sub_assets_list'] = $sub_assets_list;
		// for Sub assets
		
		
		// Get data from database 
		$data['product_result'] = $product_result = $this->ProductCategory_model->get_category_for_productId($id);
		// Get data from database END
		
		$this->load->view('manage_products/product_category/edit_product',$data);
	}*/
	
	function ajax_get_multiple_assets(){
		 $query=$_GET['search'];
		
		$this->load->database();
		if($query != 'blank'){
			$sql="select component_code,component_id from components where component_code like '%$query%' AND status='Active'";
		}else{
			$sql="select component_code,component_id from components WHERE status='Active'"; 
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
		 
			$result=$query->result_array();
			$respose='';
			foreach($result as $assets){
			$respose.="<p>".$assets['component_code'];
			$respose.='<input class="pull-right" type="checkbox" name="asset[]" id="chk_'.$assets['component_code'].'_asset"';
			$respose.='value="'.$assets['component_code'].'"';
			$respose.='/></p>'; 
			}
			echo $respose;
		}

	}
	
	function ajax_get_multiple_assetsSeries(){
		 $query=$_GET['search'];
		
		$this->load->database();
		if($query != 'blank'){
			$sql="select product_code,product_id from products where product_code like '%$query%' AND status='Active'";
		}else{
			$sql="select product_code,product_id from products WHERE status='Active'"; 
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
		 
			$result=$query->result_array();
			$respose='';
			foreach($result as $assetsSeries){
			$respose.="<p>".$assetsSeries['product_code'];
			$respose.='<input class="pull-right" type="checkbox" name="asset_series[]" id="chk_'.$assetsSeries['product_code'].'"';
			$respose.='value="'.$assetsSeries['product_code'].'"';
			$respose.='/></p>'; 
			}
			echo $respose;
		}

	}
	
	function ajax_multiple_assets(){
		 $query=$_GET['search'];
		
		$this->load->database();
		if($query != 'blank'){
			$sql="select component_code,component_id from components where component_code like '%$query%' AND status='Active'";
		}else{
			$sql="select component_code,component_id from components WHERE status='Active'"; 
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
		 
			$result=$query->result_array();
			$respose='';
			foreach($result as $assets){
			$respose.="<p>".$assets['component_code'];
			$respose.='<input class="pull-right" type="checkbox" name="sub_assets[]" rel="'.$assets['component_code'].'" id="chk_'.$assets['component_id'].'"';
			$respose.='value="'.$assets['component_id'].'"';
			$respose.='/></p>'; 
			}
			echo $respose;
		}

	}
	
	function ajax_multiple_subassets(){
		 $query=$_GET['search'];
		
		$this->load->database();
		if($query != 'blank'){
			$sql="select sub_assets_code,sub_assets_id from sub_assets where sub_assets_code like '%$query%' AND status='Active'";
		}else{
			$sql="select sub_assets_code,sub_assets_id from sub_assets WHERE status='Active'"; 
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
		 
			$result=$query->result_array();
			$respose='';
			foreach($result as $assets){
			$respose.="<p>".$assets['sub_assets_code'];
			$respose.='<input class="pull-right" type="checkbox" name="sub_assets[]" rel="'.$assets['sub_assets_code'].'" id="chk_'.$assets['sub_assets_id'].'"';
			$respose.='value="'.$assets['sub_assets_id'].'"';
			$respose.='/></p>'; 
			}
			echo $respose;
		}

	}
	
	function ajax_multiple_assetsSeries(){
		 $query=$_GET['search'];
		
		$this->load->database();
		if($query != 'blank'){
			$sql="select product_code,product_id from products where product_code like '%$query%' AND status='Active'";
		}else{
			$sql="select product_code,product_id from products WHERE status='Active'"; 
		}
		
		$query=$this->db->query($sql);
		if($query->num_rows()){
		 
			$result=$query->result_array();
			$respose='';
			foreach($result as $assets){
			$respose.="<p>".$assets['product_code'];
			$respose.='<input class="pull-right" type="checkbox" name="sub_assets[]" rel="'.$assets['product_code'].'" id="chk_'.$assets['product_id'].'"';
			$respose.='value="'.$assets['product_id'].'"';
			$respose.='/></p>'; 
			}
			echo $respose;
		}

	}
	
	
}// end of controller class 
?>