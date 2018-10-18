<?php
 
class Asm extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        
         if(1===1){
            
             $sections = array(
				'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
			); 
            
            $this->output->set_profiler_sections($sections);
            $this->output->enable_profiler(true);
                    
            #$this->output->enable_profiler(TRUE);
        }
        
        $this->load->model('asm_model');
        $this->load->model('kare_model');
        $this->load->helper('url');
        
        
        $this->auth = new stdClass;
        $this->load->library('flexi_auth');
        

        if (! $this->flexi_auth->is_logged_in_via_password()) 
        {
            $this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
            redirect('auth');
        }    

        // current domian/client info 
        $this->client_url = $_SESSION['client']['url_slug']."/";
        $this->client_id =  $_SESSION['client']['client_id'];

        $this->load->vars('base_url', base_url().$this->client_url);
        $this->load->vars('includes_dir', base_url().'includes/');
        $this->load->vars('current_url', $this->uri->uri_to_assoc(1));
        #$this->data['lang']  = $this->sma->get_lang_level('first');        
        
    }

    
    function index(){
        //echo "index function ASM MODULE";
        $this->load->view('userpanel/asm/select_inspector');
    }
    
    // insert the product into the store  
    //function add_product_instore_post(){
    
    // function store_product_post(){
    // function asm_product_post()
    
}
?>