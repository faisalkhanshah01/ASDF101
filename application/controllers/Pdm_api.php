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
      if(empty($_POST['step_number']) || empty($_POST['step_name']) || empty($_POST['product_id']) || empty($_POST['before_images']) || empty($_POST['after_images']) || empty($_POST['action_propose']) || empty($_POST['action_taken'])) {
        $response = array(
          'msg_code'	=> 404,
          'msg'		=> 'Fields cannot be blank'
        );
      }
      else{
        $before_images_arr = array();
        $after_images_arr = array();

        foreach ($_POST['before_images'] as $key => $value) {
          // code...
          $imgpath = $this->base64toImg($value,$_POST['product_id'],$_POST['step_number'],'before');
          array_push($before_images_arr,$imgpath);
        }

        foreach ($_POST['after_images'] as $key => $value) {
          // code...
          $imgpath = $this->base64toImg($value,$_POST['product_id'],$_POST['step_number'],'after');
          array_push($after_images_arr,$imgpath);
        }

        $params = [
          'before_images' => $before_images_arr,
          'after_images' => $after_images_arr,
          'action_propose' => trim(strip_tags($_POST['action_propose'])),
          'action_taken' => trim(strip_tags($_POST['action_taken'])),
          'step_name' => trim(strip_tags($_POST['step_name'])),
          'step_number' => trim(strip_tags($_POST['step_number'])),
          'product_id' => trim(strip_tags($_POST['product_id'])),
        ];

        $where=array(); //where condition if any
        $tablename = 'xyz';
        $query_result = $this->build_model->submit_inspection($tablename, $where, $params);

        if($query_result['affected_rows']>0){

          $response = array(
            'msg_code'	=> 200,
            'msg'		=> 'Data updated successfully',
            'last_id' => $query_result['last_id'],
            'affected_rows' => $query_result['affected_rows'],
            'params' => $query_result['params']
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

    function base64toImg($decodedimg,$product_id,$step_number,$check){
      $path = PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/'.$check.'/';

      if (!is_dir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id)) {
        mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id,0755,true);
        mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number,0755,true);
        mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/before',0755,true);
        mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/after',0755,true);
        }
        else{
          if(!is_dir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number)){
            mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number,0755,true);
            mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/before',0755,true);
            mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/after',0755,true);
          }
        else{
              if(!is_dir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/'.$check)){
                mkdir(PDM_PRODUCTS_STEPS_UPLOAD_DIR.$product_id.'/step'.$step_number.'/'.$check,0755,true);
              }
        }
        }
        $imgname = $this->base64toImgfinal($decodedimg,$path);
        return $imgname;
    }

    function base64toImgfinal($base64,$path){
      $imgcode = explode(",",$base64);
      $imgdef = explode("/",explode(":",explode(";",$imgcode[0])[0])[1]);
      $type = $imgdef[0];
      $ext = $imgdef[1];
      $img = $imgcode[1];
      $data1 = base64_decode($img);
      $file = $path.uniqid().'.'.$ext;
      $filepath = (file_put_contents($file, $data1))?$file:0;
      return $filepath;
    }


}
?>
