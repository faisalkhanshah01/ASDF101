<?php

	class Error404_Controller extends CI_Controller {

		public function __construct() {
			parent::__construct();
                        #echo "this called"; die;
		}

		public function index() {
			$this->output->set_status_header('404'); 
			$data = array(
							'header_data' 	=> array(
								'title' 	=> '404 - Page Not Found',
							),
							'page'			=> 'admin/errors/404',
						);
			if(!$this->flexi_auth->is_logged_in_via_password()) {
	        	#$this->load->view('admin/template/template_before_login', $data);
                        
	        	$this->load->view('admin/template/template_before_login', $data);
                        
                        } else {
	        	#$this->load->view('admin/template/template_after_login', $data);
                     }
                 
		}


	}

?>