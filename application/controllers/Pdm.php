<?php

class Pdm extends CI_Controller
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

            #$this->output->enable_profiler(TRUE);
        }

        //$this->dbkare = $this->load->database('karedb',true);

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
        
        if(isset($_POST['submit_siteID'])){
            
            $pdm_asset=$_POST['pdm_asset'];
            
            $stepCount=$_POST['pdm_step'];
            $pdm_data=array();
            $step_data=array();
            
           foreach($_POST['pdm_step'] as $key=>$val){
                $step_data=array(
                    'pdm_step'=>$val,
                    'pdm_process'=>$_POST['pdm_process'][$key],
                    'pdm_observations'=>$_POST['pdm_observations'][$key],
                    'pdm_expresults'=>$_POST['pdm_expresults'][$key],
                    'pdm_document'=>'',
                    'pdm_media'=>'',
                    'pdm_image'=>''
                   );
                
                 $pdm_data[]=$step_data;  
            }
            echo "<pre>";
            
            if(isset($_FILES['pdm_document']['name'])){
                
                $fileCount=count($_FILES['pdm_document']['name']);
                $pdm_documents=$this->upload_media('pdm_document');
                #print_r($pdm_documents);
                if($pdm_documents){
                     for($i=0; $i<$fileCount;$i++){
                         if($pdm_documents[$i]){
                             $pdm_data[$i]['pdm_document']=$pdm_documents[$i];
                          }  
                     } 
                }     
            }
            
             if(isset($_FILES['pdm_media']['name'])){
                $fileCount=count($_FILES['pdm_media']['name']);
                $pdm_media_list=$this->upload_media('pdm_media');
                #print_r($pdm_media_list);
                if($pdm_media_list){ 
                     for($i=0; $i<$fileCount;$i++){  
                         if($pdm_media_list[$i]){
                             $pdm_data[$i]['pdm_media']=$pdm_media_list[$i];
                          }  
                     } 
                }     
            }
                        
             if(isset($_FILES['pdm_image']['name'])){
    
                $fileCount=count($_FILES['pdm_image']['name']);
                $pdm_images=$this->upload_media('pdm_image');
                #print_r($pdm_images);
                if($pdm_images){
                    
                     for($i=0; $i<$fileCount;$i++){
                         $pdm_data[$i]['pdm_image']=$pdm_images[$i]; 
                     } 
                }     
            }
            
            #echo "<pre>"; 
            //print_r($_POST);
            //print_r($_FILES);
            #print_r($pdm_data);
            #die; 
            
            
            $this->load->model('build_model');
            foreach ($pdm_data as $pdm){
                #print_r($pdm); die; 
                
                $dbdata['pdm_assets_fk']=$pdm_asset;
                $dbdata['pdm_step']=$pdm['pdm_step'];
                $dbdata['pdm_process']=$pdm['pdm_process'];
                $dbdata['pdm_observations']=$pdm['pdm_observations'];
                $dbdata['pdm_expresults']=$pdm['pdm_expresults'];
                
                if(isset($pdm['pdm_document']['file_data'])){
                   $dbdata['pdm_document'] = json_encode($pdm['pdm_document']['file_data']); 
                }
                
                if(isset($pdm['pdm_media']['file_data'])){
                   $dbdata['pdm_media'] = json_encode($pdm['pdm_media']['file_data']); 
                }
                
                if(isset($pdm['pdm_image']['file_data'])){
                   $dbdata['pdm_image'] = json_encode($pdm['pdm_image']['file_data']); 
                }
                #echo "<pre>"; print_r($dbdata); 
                
                $result=$this->build_model->insert_data('periodic_maintenance',$dbdata);
            }
            if($result){
                $this->session->set_flashdata('message','Steps Successfully recorded');
                redirect($this->client_url.'pdm');
            }
               
        }
        
        $data =array();
        $this->load->view('userpanel/pdm/index',$data);
       //$this->report();
     }
     
     function upload_media($field){
         
        $client_id=$_SESSION['client']['client_id']; 
        $dir=FCPATH."/uploads/".$client_id."/pdm_assets/";
        //die;
        if(!file_exists($dir)){
            mkdir($dir,0777,true);
        }
        $this->load->library('upload');
        
        $files=$_FILES[$field];
        #print_r($files);
        $filesCount=count($_FILES[$field]['name']);
        $fileData=array();   
        for( $i=0; $i<= $filesCount; $i++ ){
            //echo $files['type'][$i]; die; 
            $_FILES[$field]['name']         =    $files['name'][$i];
            $_FILES[$field]['type']         =    $files['type'][$i];
            $_FILES[$field]['tmp_name']     =    $files['tmp_name'][$i];
            $_FILES[$field]['error']        =    $files['error'][$i];
            $_FILES[$field]['size']         =    $files['size'][$i];  
            
            #print_r($_FILES); die; 
                $config['upload_path'] = $dir; 
                $config['allowed_types'] = 'jpg|png|gif|jpeg|pdf|mp4|mp3';
                //$config['max_size'] = '2048';
                $config['max_width'] = '0';
                $config['max_height'] = '0';	
                $this->upload->initialize($config);
                
                #print_r($_FILES); die; 
                
               if(!$this->upload->do_upload($field)){		
                    $fileData[$i]['error'] =$this->upload->display_errors();
                }else{
                    $file_data = $this->upload->data();
                    $fileData[$i]['file_data']=$file_data;
              }
              
        }// end of for loop
        #print_r($fileData);    
       return $fileData; 
         
     
     }
     
     
     
     



}
?>
