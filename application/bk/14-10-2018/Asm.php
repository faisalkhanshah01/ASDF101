<?php
 
class Asm extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        
         if(1===1){
            
             $sections = array(
				'benchmarks' => FALSE, 'memory_usage' => FALSE, 
				'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => TRUE
			); 
            
            $this->output->set_profiler_sections($sections);
            $this->output->enable_profiler(true);
                    
        }


        $this->karedb=$this->load->database('karedb',true);

        /*echo "<pre>";
        print_r($this->karedb); die;
        */

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
       $this->report();       
    }

    
    function report(){
        $client_id=$_SESSION['client']['client_id'];
        $filter=array('distinct'=>'ps_store_id');
        $store_list = $this->asm_model->filter_data('asm_products',$filter,array('ps_store_id!='=>0));
        $asm_stores=array();
        if($store_list){
            foreach($store_list as $store){
                $manage_id=$store['ps_store_id'];
                $manager=$this->flexi_auth->get_users(null,array('uacc_id'=>$manage_id))->row_array();
                if($manager){
                     $asm_stores[$manage_id]=$manager['uacc_email'];
                }
               
            }
        }
        $data['asm_stores']=$asm_stores;
        
        if(!empty($_GET['asm_store']) ||!empty($_GET['asm_user'])){
           
            $product_filter=array();        
            if(isset($_GET['asm_store']) && $_GET['asm_store']!=''){
                $product_filter['ps_store_id']=$this->input->get('asm_store');
            }
            if(isset($_GET['asm_user']) && $_GET['asm_user']!=''){
                $product_filter['ps_user_id']=$this->input->get('asm_user');
            }
            if(isset($_GET['asm_project']) && $_GET['asm_project']!=''){
                $product_filter['ps_project_id']=$this->input->get('asm_project');
            }

            $product_list=$this->asm_model->get_data('asm_products',$product_filter);
            #print_r($product_list);

            $asm_products=array();
            if($product_list){
                foreach($product_list as $product){
                   $component   =  $this->kare_model->get_component($product['ps_product_id']);
                   if($component){
                       $product['ps_product_image']=$component['component_imagepath'];
                       $product['ps_store_name']= $this->asm_get_username($product['ps_store_id']);
                       $product['ps_user_name']= $this->asm_get_username($product['ps_user_id']);
                       //print_r($component); die;
                       $asm_products[]=$product;
                   }
                }
            
        }
        $data['asm_products']=$asm_products;
       }
       
       
        #echo "<pre>";
        //print_r($asm_stores);
        //print_r($asm_products);     
        $this->load->view('userpanel/asm/report_view',$data);
    }
    
    
    function asm_get_username($user_id){
                $user=$this->flexi_auth->get_users(null,array('uacc_id'=>$user_id))->row_array();
                if($user){
                     return $user['uacc_username'];
                }else{
                  return false;  
              }
        
    }
    
    
    
    // insert the product into the store  
    //function add_product_instore_post(){
    
    // function store_product_post(){
    // function asm_product_post()
    
}
?>