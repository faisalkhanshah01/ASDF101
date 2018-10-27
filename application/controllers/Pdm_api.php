<?php
require(APPPATH.'/libraries/REST_Controller.php');

class Pdm_api extends REST_Controller
//class Asm_api extends CI_Controller
{
    private static $ASM_PRODUCTS=null;

    #const ASM_PRODUCTS='ASDF';
   # const ASM_STORE_PRODUCTS=
   # const ASM_PRODUCTS=

    public function __construct(){

        parent::__construct();
        #$this->load->database('karedb');
        $this->load->model('build_model');
        $this->load->model('kare_model');
        $this->load->helper('url');
        $this->client_id=$_SESSION['client']['client_id'];

        // PDM TABLES CONSTANT
        #self:: $ASM_PRODUCTS = "asm_{$this->client_id}_products";
    }


    /*function get($query){
        return $this->input->get($query);
    }*/
    /*function post($query){
        return $this->input->post($query);
    }*/

   function index(){
        echo "index function PDM MODULE"; die;
    }

    // PDM STEPS DISPLY CREATED IN BACKEND
   function pdm_steps_get(){

       $asset_code=trim($this->get('asset_code'));
       $err_params=array();
       if(!$asset_code){
           $err_params[]='asset_code';
        }
       $client_id=trim($this->get('client_id'));
       if(!$client_id){

           $err_params[]='client_id';
       }

       if(count($err_params)){
           $e_str=  implode(',', $err_params);
           $response=array('status_code'=>400,'message'=>"Bad Request- {$e_str} is missing");
       }else{
           $where=array('pdm_client_fk'=>$client_id,'pdm_asset_code'=>$asset_code);
           $steps=$this->build_model->get_data('periodic_maintenance',$where);

           if($steps){
               $response=array('status_code'=>200,'message'=>'Steps found','data'=>$steps);
           }else{
              $response=array('status_code'=>404,'message'=>'Steps not found','data'=>$steps);
           }
           #echo "<pre>";
           #print_r($steps);
      }

      $this->response($response,200);

   }

   // Insert inspection data API
    function submit_inspection_post(){
      $_POST = json_decode(file_get_contents('php://input'), true);
      if(empty($_POST['before_images']) || empty($_POST['after_images']) || empty($_POST['action_propose']) || empty($_POST['action_taken'])) {
        $response = array(
          'msg_code'	=> 404,
          'msg'		=> 'Fields cannot be blank'
        );
      }
      else{

        $params = [
          'before_images' => $_POST['before_images'],
          'after_images' => $_POST['after_images'],
          'action_propose' => trim(strip_tags($_POST['action_propose'])),
          'action_taken' => trim(strip_tags($_POST['action_taken'])),
        ];
        $where=array(); //where condition if any
        $tablename = 'xyz';
        $query_result = $this->build_model->submit_inspection($tablename, $where, $params);
        
        if($query_result['affected_rows']>0){

          $response = array(
            'msg_code'	=> 200,
            'msg'		=> 'Data updated successfully',
            'last_id' => $query_result['last_id'],
            'affected_rows' => $query_result['affected_rows']
          );
        }
        else{

          $response = array(
            'msg_code'	=> 200,
            'msg'		=> 'Data not Inserted',
            'affected_rows' => $query_result['affected_rows']
          );
        }
      }
      $this->response($response,200);
    }


}
?>
