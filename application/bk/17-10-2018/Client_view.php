<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client_view extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->model('Specification_model');
		$this->load->model('ProductCategory_model');
		$this->load->model('Client_view_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		
		//$this->load->library('s3');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		if (!$this->flexi_auth->is_privileged('View Infonet')) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital"><b>Error 404! :</b> Page Not Found.</p>');
			$this->load->model('Common_model');
			$groupID = $this->Common_model->get_loggedIn_user_dashboard();
			exit();
		}
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		$this->data = null;
	}
	
	function index(){
		
		$userID = $this->session->flexi_auth['id'];
		$usergroup = $this->session->user_group;
		if($usergroup =='3' || $usergroup =='2' || $usergroup =='10' || $usergroup =='11'){
			$this->client_view();
		}else{
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>ERROR 404 ! </b>Page Not Found. </p>');
			redirect('auth_admin/');
		}
		
		
	}
	
	public function client_view(){
		if(isset($_SESSION['infornetView'])){
			unset($_SESSION['infornetView']);
		}
		//data for category
		$data['categories'] = $categories = $this->ProductCategory_model->get_all_categories('0');
		// echo "<pre>";
		// print_r($categories);
		// die();
		
		$this->load->view('manage_products/client_view/client_view_main',$data);
	}
	
	public function client_view_subCategory($id){
		$this->data['categories'] = $subcategories = $this->ProductCategory_model->get_all_categories($id);
		
		if($subcategories){
			if(isset($_SESSION['infornetView']['products'])){
				unset($_SESSION['infornetView']['products']);
			}
			if(isset($_SESSION['infornetView']['breadCrum']['mainLinkNew'])){
				unset($_SESSION['infornetView']['breadCrum']['mainLinkNew']);
			}
			
			
			$this->get_breadcrum($subcategories);
			$this->load->view('manage_products/client_view/client_view_subCategory',$this->data);
		}else{
			
			$cat = $this->ProductCategory_model->get_category_for_id($id);

			if(!isset($_SESSION['infornetView']['breadCrum']['mainLinkNew'])){
				$breadcrum 	= $_SESSION['infornetView']['breadCrum']['mainLink'];
				$arr 		= explode('<li class="active">',$breadcrum);
				$tiltleName = str_replace('</li>','',$arr[1]);
				$titleUrl 	= base_url('Client_view/client_view_subCategory/'.$cat['cat_parentid']);
				$arr[1] 	= '<li><a href="'.$titleUrl.'">'.$tiltleName.'</a></li><li class="active">'.$cat['cat_name'].'</li>';
				$arr = implode('',$arr);
				$_SESSION['infornetView']['breadCrum']['mainLink'] = $arr;
			}else{
				unset($_SESSION['infornetView']['breadCrum']['mainLinkNew']);
			}
			
			$id = $cat['id'];
			$parentID = $cat['cat_parentid'];
			$result = $this->ProductCategory_model->get_all_products($id, $parentID);
			
			if($result){
				$assets	 		= json_decode($result['assets'],true);
				$sub_assets 	= json_decode($result['sub_assets'],true);
				$assets_series 	= json_decode($result['assets_series'],true);
				if(!empty($assets)){
					
					$aseet_arr = array();
					foreach($assets as $aVal){
						$tableName = 'components';
						$this->load->model('Specification_model');
						$res = $this->Specification_model->get_all_values($aVal, $tableName);
						$asset_arr[] = array('name'=>$aVal, 'url'=>$res['imagePath']);
					}
				}
				if(!empty($assets_series)){
					$aseet_sarr = array();
					foreach($assets_series as $asVal){
						$tableName = 'products';
						$this->load->model('Specification_model');
						$res = $this->Specification_model->get_all_values($asVal, $tableName);
						$asset_sarr[] = array('name'=>$asVal, 'url'=>$res['imagePath']);
					}
				}
				
				$result['assets']	 		= $asset_arr;
				$result['sub_assets'] 		= json_decode($result['sub_assets'],true);
				$result['assets_series'] 	= $asset_sarr;
				
			}else{
				// Error  KD-0002: No Assets, Sub-assets, Asset Series data Found in table( manage_products ) !! 
				$result = '';
			}
		
			$_SESSION['infornetView']['pageTitle'] = 'Products';
			$_SESSION['infornetView']['products'] = $result;
			
			redirect('client_view/client_view_products');
		}
	}
	
	function get_breadcrum($subcategories){
		if(isset($_SESSION['infornetView']['breadCrum'])){
			unset($_SESSION['infornetView']['breadCrum']);
		}
		$res = $this->Client_view_model->get_breadCrum($subcategories);
	}
	
	public function client_view_products(){
		$this->data['products'] =  $_SESSION['infornetView']['products'];
		$this->load->view('manage_products/client_view/client_view_products',$this->data);
	}
	
	public function client_view_products_details($data_type='', $data=''){
		
		// get ID of data from table
		$id = $this->Client_view_model->get_data_id($data_type, $data);
		
		$this->data['data_name'] = $data;
		$this->data['table_name'] = $data_type;
		$this->data['product_specification'] = $this->Client_view_model->get_data_specification($data_type, $id['id']);
		
		// For Breadcrum
		if($_SESSION['infornetView']['pageTitle'] == 'Products'){
			$_SESSION['infornetView']['pageTitle'] = 'ProductDetails';
			$breadcrum 	= $_SESSION['infornetView']['breadCrum']['mainLink'];
			$arr 		= explode('<li class="active">',$breadcrum);
			$tiltleName = str_replace('</li>','',$arr[0]);
			$titleUrl 	= base_url('Client_view/client_view_subCategory/'.$_SESSION['infornetView']['products']['category_id']);
			$arr[0] 	= '<li><a href="'.$titleUrl.'">'.$tiltleName.'</a></li><li class="active">'.$this->data['data_name'].'</li>';
			$arr = implode('',$arr);
			$_SESSION['infornetView']['breadCrum']['mainLinkNew'] = $arr;
		}


		$this->load->view('manage_products/client_view/client_view_products_detail',$this->data);
	}
	
	function unset_session_values(){
		
	}
	
}// end of controller class 
?>