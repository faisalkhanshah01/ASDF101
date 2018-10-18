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
	
	function pdf()
	{
		$this->load->helper('pdf_helper');
		/*
			---- ---- ---- ----
			your code here
			---- ---- ---- ----
		*/
		$this->load->view('pdfreport', $data);
	}
	
}// end of controller class 
?>