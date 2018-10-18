<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {
    
    private $client;
    private $client_url;
    
 
    function __construct() 
    {
        
        parent::__construct();

        ini_set('display_errors',1);
        error_reporting(E_ALL);

		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2)		 
		{
               $sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
			); 
			$this->output->set_profiler_sections($sections);
			$this->output->enable_profiler(TRUE);
		}
		
		// Load required CI libraries and helpers.
		 $this->load->database();
		 $this->load->driver("session");
 		 $this->load->helper('url');
 		 $this->load->helper('form');
 		 
  		// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
		$this->load->library('flexi_auth');	

		//$this->verify_client_slug();

		/*echo "###############";
		echo "<pre>";
                print_r($_SESSION); 
                die;*/


	}






        

function index()
{
    echo phpinfo();
    
    
}



}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
