<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Search_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			//$this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
	}
	
	
	function get_data_for_dashboard(){
		$this->data['data']=$this->Search_model->get_detail_list();
		$this->load->view('demo/admin_examples/dashboard_view', $this->data);
			
	}
	
	function search_asset_series_data()
	{
		
		$asset_series = $_POST['asset_series'];		
		$result = $this->Search_model->search_asset_series_data($asset_series);

		if($result){
			foreach ($result as $row){
				//echo "<li>" . $row['product_code'] . "</li>";
				echo "<option value='". $row['product_code'] ."'>". $row['product_code'] ."</option>";
			}
		}
		
	}
	
	function search_asset_data()
	{
		
		$asset = $_POST['asset'];		
		$result = $this->Search_model->search_asset_data($asset);

		if($result){
			foreach ($result as $row){
				echo "<li>" . $row['component_code'] . "</li>";
			}
		}
		
	}
/* testing function only, start */	
	public function index(){ 
		$this->load->view('admin_inspection/guest_receipt');
	}	
	  
	public function guestInvoice()
	{ 
	   $this->load->view('admin_inspection/guest_invoice');
	}	
/* testing function only, end */

	
}// end of controller class 
?>