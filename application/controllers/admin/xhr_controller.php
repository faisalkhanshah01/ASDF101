<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Xhr_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        ini_set('display_errors', 1);
        error_reporting(E_ALL);


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


        /* echo "###############";
          echo "<pre>";
          print_r($_SESSION);
          die; */


        // Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
        $this->load->vars('base_url', base_url());
        $this->load->vars('includes_dir', base_url() . 'includes/');
        //$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
        // Define a global variable to store data that is then used by the end view page.
        $this->data = null;
    }


    function ajax_get_zone_list() {
        $country_id = $_REQUEST['country_id'];
        $this->load->model('kare_model');
        $respose = '';
        if ($country_id != '') {
            $filtArray = array('country_id' => $country_id);
            $zone_list = $this->kare_model->get_manage_zone_filt_result_list($filtArray);
            foreach ($zone_list as $key => $val) {
                $id = $val['id'];
                $name = $val['name'];
                $respose .= '<option value="' . $id . '" >' . $name . '</option>';
            }
        } else {
            
        }
        echo $respose;
    }

    function ajax_get_cities_list() {
        $state_id = $_REQUEST['province_id'];
        $this->load->model('kare_model');
        $respose = '';
        if ($state_id != '') {
            $filtArray = array('state_id' => $state_id);
            $zone_list = $this->kare_model->get_manage_city_filt_result_list($filtArray);
            foreach ($zone_list as $key => $val) {
                $id = $val['city_id'];
                $name = $val['city_name'];
                $respose .= '<option value="' . $id . '" >' . $name . '</option>';
            }
        } else {
            
        }
        echo $respose;
    }
    
    
    function ajax_change_client_status($client_id,$action){
        
        $this->load->model('arresto_model');
       
        $response=array();
        if( ($action=='Active'||$action=='Inactive') && $client_id){
            
             $response['status']=$this->arresto_model->change_client_status($client_id,$action);
             $response['action_code']=1;
             $response['action_message']="status changed !";
             }else{
            $response= array('status'=>0,'action_code'=>0,'action_message'=>'staus not changed');
        }
       echo json_encode($response);
        
    }
    
    
    

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
