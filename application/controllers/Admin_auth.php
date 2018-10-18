<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_auth extends CI_Controller {
 
    function __construct() 
    {
        
      parent::__construct();
      ini_set('display_errors',1);
      error_reporting(E_ALL);
		
		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2) 
		{
	
			// $sections = array(
			// 	'benchmarks' => TRUE, 'memory_usage' => TRUE, 
			// 	'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
			// 	'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
			// ); 

$sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
			); 
			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(TRUE);
		}
	
	}

	function index()
    {
        redirect('admin'); 	
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
