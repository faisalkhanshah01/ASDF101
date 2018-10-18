<?php
require(APPPATH.'/libraries/REST_Controller.php');
 
class Asm_api extends REST_Controller
//class Asm_api extends CI_Controller
{
    private static $ASM_PRODUCTS=null;
            
    #const ASM_PRODUCTS='ASDF';        
   # const ASM_STORE_PRODUCTS=        
   # const ASM_PRODUCTS=           
            
    public function __construct(){

        parent::__construct();
        #$this->load->database('karedb');
        $this->load->model('asm_model');
        $this->load->model('kare_model');
        $this->load->helper('url'); 
        $this->client_id=$_SESSION['client']['client_id'];
        
        // ASM TABLES CONSTANT
        #self:: $ASM_PRODUCTS = "asm_{$this->client_id}_products";
        $this->ASM_PRODUCTS = "asm_{$this->client_id}_products";
        $this->ASM_STORE_PRODUCTS="asm_{$this->client_id}_store_products";
        $this->ASM_USER_PROJECT="asm_{$this->client_id}_user_projects";
        $this->ASM_USER_PROJECT_PRODUCTS="asm_{$this->client_id}_user_project_products";
        $this->ASM_PRODUCT_USECASE="asm_{$this->client_id}_product_usecase";
        $this->ASM_PRODUCT_TRANSATIONS="asm_{$this->client_id}_product_transactions";
    }
    
    
    /*function get($query){
        return $this->input->get($query);
    }*/
    /*function post($query){
        return $this->input->post($query);
    }*/
    
    function index(){
        echo "index function ASM MODULE"; die;
    }
    
    // insert the product into the store  
    //function add_product_instore_post(){
    
    // function store_product_post(){
    // function asm_product_post()
    
   
    function assign_to_store_post(){
        
        $mdata_id=$this->post('mdata_id');
        $product_id=$this->post('product_id'); 
        $user_id=$this->post('user_id');
        $group_id=$this->post('group_id');

    
        // if gpid==13 scanned by manager 
        if($group_id==13){
            $manager_id=$user_id;
            #$store_id=$this->asm_model->get_sto
            $store_id=$user_id;
        }
        
        $tdata['pt_mdata_id']= $dbdata['ps_mdata_id']=$mdata_id;
        $tdata['pt_product_id']= $dbdata['ps_product_id']=$product_id;
        $tdata['pt_smanager_id']= $dbdata['ps_smanager_id']=$manager_id;
        $tdata['pt_store_id']= $dbdata['ps_store_id']=$manager_id;
        
        if($this->_verifyASMProduct($mdata_id)){
             $response=array('status_code'=>'404','message'=>'product already available to ASM store !');
        }else{

           $ps_id=$this->asm_model->insert_data('asm_products',$dbdata);
           if($ps_id){
               
               // update the transaction table
               $tdata['pt_comment']='product added to store by user='.$user_id.". on ".date("d/m/Y")."."; 
                       
               $this->asm_model->insert_data('asm_product_transactions',$tdata);
               
               $response=array('status_code'=>200,'message'=>'Product add to ASM store!');
               
           }else{
               $response=array('status_code'=>404,'message'=>'product not added');  
           } 
        }
        
       //print_r($response); die; 
       $this->response($response,200); 
       
        
       // sp_id==store product id   
       
       /*$sp_id=$this->asm_model->insert_product_store($dbdata);
       
       if($sp_id){
           // if product inserted successfully update the product state table &
           //  product transation/histoty table 
          $this->asm_model->insert_product_state($dbdata);
          $this->asm_model->insert_product_transaction($dbdata);
          $response=array('status'=>'succes','action'=>1,'message'=>'product add to store successfully');
          $this->response($response,200);
          
       }*/
 
   }
   
   // get the total Assets count in a pericular store 
   // store id ===smanager id 
   function store_count_get($store_id){
      $store_id=$this->get('store_id');
        if(!$store_id){
                $response = array('status_code' => 400, 'message' => 'Store_id is missing');
        }else{
            
            $post_data=array();
            $post_data['store_id']=$store_id;
            
            $post_data['total_product']=$this->asm_model->row_count('asm_products', 
                        array('ps_store_id'=>$store_id));
            $post_data['used_product']=$this->asm_model->row_count('asm_products',
                                                        array('ps_store_id'=>$store_id,
                                                              'ps_isused'=>1)
                                                       );
            $post_data['free_product']=$post_data['total_product']-$post_data['used_product'];
            #print_r($post_data);
             $response = array('status_code' => 200, 'message' => 'success','data'=>$post_data);
        }
        $this->response($response,200); 
   }
   
   function store_list_get($store_id,$filter=''){
        
         $store_id=$this->get('store_id');
         $filter=$this->get('filter');
        
        if(!$store_id){
           $response=array('status_code'=>400,'message'=>'Store_id parameter is missing !'); 
        #} else if(!$pfilter){
           #$response=array('status_code'=>400,'message'=>'user_id parameter is missing !'); 
        }else
            {
        
           $where=array('ps_store_id'=>$store_id);
           if($filter=='used'){
                $where['ps_isused']=1;    
           }
           if($filter=='free'){
                $where['ps_isused']=0;    
            }
            
            #echo "<pre>";
            #print_r($where);
            # die;
            
             $products = $this->asm_model->get_data('asm_products',$where);
             #print_r($products);
             
             $post_data=array();
             foreach($products as $product){

                 $product_detail=array();
                 $product_detail=$this->kare_model->get_component($product['ps_product_id']);
                 
                 if($product_detail){
                     
                    $product_detail['asm_user_id']=$product['ps_user_id']; 
                    $product_detail['asm_project_id']=$product['ps_project_id'];
                    
		    $asm_log_hours=$this->_product_log_hours($product['ps_product_id'])['hours'];
		    $product_detail['asm_log_hours']=($asm_log_hours)?$asm_log_hours:'';
                    
                    $asm_log_seconds=$this->_product_log_hours($product['ps_product_id'])['seconds'];
		    $product_detail['asm_log_seconds']=($asm_log_seconds)?$asm_log_seconds:'';
                    
                    
                    
                    $post_data[]=$product_detail;  
                 }
                 #print_r($product_detail); die; 
             } 

            #print_r($products); die;
            if($products){
                $response=array('status_code'=>200,'message'=>'result found','data'=>$post_data,'count'=>count($products));
            }else{
              $response=array('status_code'=>404,'message'=>'result not found');  
            }
       }
        
        $this->response($response,200);     
    }
    
   
   function asm_stores_get(){
       
       $filter=array('distinct'=>'ps_store_id');
       $result=$this->asm_model->filter_data('asm_products',$filter);
       $post_data=array();
       $this->auth = new stdClass;
       $this->load->library('flexi_auth_lite',null,'authlib');
        
       if(count($result)){

           foreach($result as $store){
               $manager_id=$store['ps_store_id'];
               $manager=$this->authlib->get_users_query(null,array('uacc_id'=>$manager_id))->row_array();
                
                if($manager && $manager_id){
                   $manager_name=$manager['upro_first_name']." ".$manager['upro_last_name'];  
                }else{
                    $manager_name='';
               }
               $post_data[] = array('ps_store_id'=>$manager_id,'ps_store'=>$manager_name); 
           }
           
          $response=array('status_code'=>200,'message'=>'result found','data'=>$post_data);  
           
       }else{  
        $response=array('status_code'=>404,'message'=>'result not found');      
       }
       
            #print_r($response);
            $this->response($response,200);     
   }
   
   
   function store_users_get(){
       
       $store_id=$this->get('store_id');
       $filter=array('distinct'=>'ps_user_id');
       $where=array('ps_store_id'=>$store_id);
       
       $results=$this->asm_model->filter_data('asm_products',$filter,$where);
       
       $post_data=array();
       $this->auth = new stdClass;
       $this->load->library('flexi_auth_lite',null,'authlib');
        
       if(count($results)){

           foreach($results as $item){
               $user_id=$item['ps_user_id'];
               $user=$this->authlib->get_users_query(null,array('uacc_id'=>$user_id))->row_array();
                
                if($user && $user_id){
                   $user_name=$user['upro_first_name']." ".$user['upro_last_name'];  
                }else{
                    $user_name='';
               }
               $post_data[] = array('ps_user_id'=>$user_id,'ps_user'=>$user_name); 
           }
           
          $response=array('status_code'=>200,'message'=>'result found','data'=>$post_data);  
           
       }else{  
        $response=array('status_code'=>404,'message'=>'result not found');      
       }
       
       #print_r($post_data);
       $this->response($response,200);    
   }
   

    function return_to_store_post(){

        $product_id =   $this->post('product_id');
        $mdata_id   =   $this->post('mdata_id');
        $user_id    =   $this->post('user_id');
        $group_id   =   $this->post('group_id'); 
        $project_id =    $this->post('project_id');
        $smanager_id=$store_id=$this->post('store_id');
        
        
        #print_r($dbdata); 
        $products=$this->asm_model->get_data_row('asm_products',array('ps_mdata_id'=>$mdata_id,'ps_product_id'=>$product_id,'ps_user_id'=>$user_id,'ps_store_id'=>$smanager_id));
        #print_r($products); 
        #die;
        #$product=$products[0];
        $product=$products;
        
        if($product['ps_mdata_id']){
               // verify that product is return to the same store & product is not inuse 
                if($product['ps_store_id']=$store_id && $product['checkedin']==0){
                    
                     $update_data['ps_user_id']=0;
                     $update_data['ps_isused']=0;
                     $update_data['ps_project_id']=0;
                  
                    
                     $rs= $this->asm_model->update_table('asm_products',$update_data,array('ps_mdata_id'=>$mdata_id));
                    
                    if($rs){

                    //update the user_project_product table - remove the entry 
                    #$project_id = $product['ps_project_id'];
                    $where = array('upp_user_id' => $user_id, 'upp_project_id' => $project_id,'upp_mdata_id'=>$mdata_id);
                    $result = $this->asm_model->delete_record('asm_user_project_products', $where);
                    if ($result) {

                        $tdata['pt_mdata_id'] = $mdata_id;
                        $tdata['pt_product_id'] = $product_id;
                        $tdata['pt_smanager_id'] = $smanager_id;
                        $tdata['pt_comment'] = 'product is return to store' . data('d/m/Y') . '. and removed from user projects';

                        $this->insert_data('asm_product_transactions', $tdata);

                        $response = array('status_code' => 200, 'message' => 'Product return success');
                    } else {

                        $response = array('status_code' => 404, 'message' => 'Product not removed from user projets products table');
                    }
                    
                }else{
                      $response=array('status_code'=>404,'message'=>'Product not return');  
                        
                    } 
                    
                }elseif($product['checkedin']==1){
                    
                    $response=array('status_code'=>404,'message'=>'Product is not checkedout by the assiny!');
                }    
            
        }else{
              $response=array('status_code'=>404,'message'=>'invalid product return to store');            
        }
           
       #print_r($response); die;
       
       $this->response($response,200);

   }
   

    

    ############################
    # user functions 
    
    // to create the new project
    //function add_poject_post()
   
    function user_project_post()
    {

       if($this->post('project_name')!=''){
           
           $dbdata['up_project_name']=$this->post('project_name');
           $dbdata['up_user_id']=$this->post('user_id');
           //$project= $this->asm_model->create_project($dbdata);
           $project= $this->asm_model->insert_project($dbdata);
           
           if($project){
               $response=array('status_code'=>'200','message'=>'Project created successfully');
               $this->response($response,200);
           }else{
              $response=array('status_code'=>'404','message'=>' Project Not created');   
           }
           
       }else{
            $response=array('status'=>'failed','action'=>0,'message'=>'project name can\'t blank');
            $this->response($response,200);
       }
        $this->response($response,200);
   
    }
    

    //function project_list_get(){
    function user_projects_get(){

        $user_id=$this->get('user_id');
       
        if(!$user_id){
            $response=array('ststus_code'=>'400','message'=>'Bad Request user_id should be there');
         }else{      
            $projects=$this->asm_model->get_data('asm_user_projects',array('up_user_id'=>$user_id));
            
            #echo "<pre>";
            #print_r($projects);
            $post_data=array();
            
            foreach($projects as $project){
                #echo $project['up_id'];
                $where=array('upp_project_id'=>$project['up_id']);
                $product_count=$this->asm_model->row_count('asm_user_project_products',$where);
                $project['product_count']=$product_count;
                $post_data[]=$project;
            }            
            #die;
            #print_r($projects); die;

            if($projects){
                $response=array('status_code'=>200,'message'=>'record fetched success','data'=>$post_data); 
             }else{
               $response=array('status_code'=>404,'message'=>'no record found =============',);  
             }
        }

         $this->response($response,200);   
    }
    
    
   # Add product to user project 
   # 
    //function add_product_inproject_post(){
    function assign_to_project_post(){
        
         $project_id=$this->post('project_id');
         $mdata_id=$this->post('mdata_id');
         $product_id=$this->post('product_id');
         $user_id=$this->post('user_id');
         $device_token=$this->post('device_token');
         $device_token='fpi4TMQEVJo:APA91bHGcZNIwK3D5A3sI6_e4vkV1q01i7JHyvUThjhy0iW2SZM9r9wBNOqbjpiUgr84QpAhXcb204vww7SSudSdiJCNO8b1A2exBBhua2hJvNJl5byGI-7eA3QqdnxXOMZ9ZsJohBs_';
         

        // add the product againest the project id 

        $dbdata['upp_project_id']=$project_id;
        $dbdata['upp_mdata_id']=$mdata_id;
        $dbdata['upp_product_id']=$product_id;
        $dbdata['upp_user_id']=$user_id;
        
       if($this->_verifyASMProduct($mdata_id)){
                 // request for U2U transfer 
                 if($this->_u2u_tansfer_request($mdata_id,$user_id)){
                        $product = $this->asm_model->get_data_row('asm_products',array('ps_mdata_id'=>$mdata_id));
                        #print_r($product); die;
                         $store_id=$product['ps_store_id'];
                         $owner_id=$product['ps_user_id'];
                         #$receiver_id=$store_id;
                         $receiver_id=$user_id;
                         #$device_token; 
                        $this->_u2u_transfer_notification($mdata_id,$owner_id,$receiver_id,$device_token);
                        return 1;  
                       // exit the further processing  
                 }
         }
         #echo  "NO TRANSFER REQUEST"; die;

        if($this->_verifyASMProduct($mdata_id)){
            //echo "here"; die;
            // insert into user project and update the ASM products table 
            
            // verify the asm_user_project_products table if product already exist
            $product=$this->asm_model->get_data('asm_user_project_products',array('upp_mdata_id'=>$mdata_id,'upp_user_id'=>$user_id));
            
            if(!$product){
                $id = $this->asm_model->insert_data('asm_user_project_products', $dbdata);
                if ($id) {
                    $update_data=array('ps_user_id' => $user_id,'ps_project_id'=>$project_id,'ps_isused'=>1);
                    
                    $where = array('ps_mdata_id' => $mdata_id);   // manager id logic pending 
                    $result = $this->asm_model->update_table('asm_products',$update_data, $where);
                    
                    // save the transaction 
                      $tdata['pt_mdata_id']=$mdata_id;
                      $tdata['pt_user_id']=$user_id;
                      $tdata['pt_comment']="product is scanned by the user ".$user_id.". on ". date('m/d/Y').'.';
                      
                      $this->asm_model->insert_data('asm_product_transactions',$tdata);
                    
                    if ($result) {
                        $response = array("status_code" => 200, 'message' => 'Product added to project successfully');
                    } else {
                        $response = array("status_code" => 404, 'message' => 'error in to add product');
                    }
                } else {
                   $response=array("status_code"=>404,'message'=>'error in to add product'); 
                }
            }else{
              $response=array("status_code"=>404,'message'=>'product is already available in project');   
           }
           
        }else{
            $response=array("status_code"=>404,'message'=>'Product is not available at ASM store !');
        }
        
       #print_r($response); die;
       $this->response($response,200);
    } 
    
    // verify the product at ASM Product store 
   function _verifyASMProduct($mdata_id){
           #echo $product_id;
            $product= $this->asm_model->get_data('asm_products',array('ps_mdata_id'=>$mdata_id));
            #print_r($product); die;
            if($product){
                return 1;
                #return $product['ps_product_id'];
            }else{
                return false;
            }
   }
   
   
  function _u2u_tansfer_request($mdata_id,$user_id){
       $product = $this->asm_model->get_data_row('asm_products',array('ps_mdata_id'=>$mdata_id));
       if( ($product['ps_user_id'] !=0 ) && ( $product['ps_user_id'] != $user_id) ){
           //echo "U2U transfer request"; 
           return 1;
       }else{
           return 0;
       }
      
  }
   
  function _u2u_transfer_notification($mdata_id,$owner_id,$receiver_id,$token){ 

      $owner=$this->_asm_get_username($owner_id);
      $receiver=$this->_asm_get_username($receiver_id);
      $title="U 2 U transfer Notification";
      $msg="{$receiver} is requesting for the approval of {$mdata_id} from {$owner}.<br/>Please Responde with following.";
      $this->asm_model->android_notification($token, $title, $msg); 
      $response=array("status_code"=>200,'message'=>'Notification send to store for approval');
      $this->response($response,200);     
  }
  
  function  u2u_transfer_status_get(){
      
        $command=$this->get('u2u_response');
        $mdata_id=$this->get('mdata_id');
        $sender_id=$this->get('u2u_sender');
        $receiver_id=$this->get('u2u_receiver');
        $store_id=$this->get('store_id');
        
        if($command=='APPROVED'){
            // settle the product to new user as the approved
            // return to store
            $param=array();
            $client_url=$_SESSION['client']['client_url'];
            $client_url='fom/kare/';
            
            $url=base_url().$client_url."return_to_store_post";
            $response= $this->_httpPost($url, $params);
            if($response){
                
                $response = json_decode($response,true);
                if($response['status_code']==200){
                    
                    // settle product to new user
                    $params = array();
                    $url    = base_url().$client_url."return_to_project_post";
                    $rtn_respo = $this->_httpPost($url,$params);
                    
                    /*if($rtn_respo){
                        echo $rtn_respo;
                    }else{
                       $response=array('status_code'=>404,'message'=>'product not assign to new user');
                    }*/  
                    
                   }else{
                   $response=array('status_code'=>404,'message'=>'product not assign to new user'); 
                }
                
            }else{
                $response=array('status_code'=>404,'message'=>'product not return to store');
            }
            
            }else{
          // settle the product to new user as the     
            
        }
        
    $this->response($response,200);  

  }
  
  
function _httpPost($url,$params)
{
   $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
	$postData = rtrim($postData, '&');
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
 
}


function _httpGet($url)
{
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    #curl_setopt($ch,CURLOPT_HEADER, false); 
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
}
  
  
  
  
  

    function project_products_get(){
        
        $user_id=$this->get('user_id');
        $project_id=$this->get('project_id');
        
        if(!$project_id){
           $response=array('status_code'=>400,'message'=>'project_id parameter is missing !'); 
        }else if(!$user_id){
           $response=array('status_code'=>400,'message'=>'user_id parameter is missing !'); 
        }else
            {
            $products=$this->asm_model->get_data('asm_user_project_products',array('upp_project_id'=>$project_id,'upp_user_id'=>$user_id));
            
            #echo "<pre>";
            #print_r($products); die;
            
           /* if(!array_key_exists('0',$products)){
                  $ndata[]=$products;
            }else{
                $ndata=$products;
            }
            #print_r($ndata);
            
            foreach($ndata as $prod){
                $productList[]=$this->kare_model->get_component($prod['upp_product_id']);
            }
            
            
            #print_r($productList); die;
            //$components[]=$this->kare_model->get_component($component_id);
            
            #echo array_key_exists('0',$products); die;
            #print_r($products); die;
            
            if(!array_key_exists('0',$products)){
                $ndata[]=$products;
                $products=$ndata;
              }*/
              
             $post_data=array();
              
             foreach($products as $product){

                 $product_detail=array();
                 
                 $product_detail=$this->kare_model->get_component($product['upp_product_id']);
				
				 
                 #print_r($product_detail); die;
                 $asm_details=$this->asm_model->get_data('asm_products',array('ps_product_id'=>$product['upp_product_id']));
                 #print_r($asm_details); die;
                 $asm_detail=$asm_details[0];
                 
                 if($asm_detail){
                        if($asm_detail['ps_checkedin']==1){

                          $product_detail['asm_product_status']='checkin'; 

                       }elseif($asm_detail['ps_checkedout']==1){

                           $product_detail['asm_product_status']='checkout'; 

                       }else{

                          $product_detail['asm_product_status']='';  
                       } 
                     
                 }else{
                    # echo "product not at ASM Store";
                 }
                 
				 
		$asm_log_hours=$this->_product_log_hours($product['upp_product_id'])['hours']; 
		$product_detail['asm_log_hours']=($asm_log_hours)?$asm_log_hours:''; 
                
        $asm_log_seconds=$this->_product_log_hours($product['upp_product_id'])['seconds'];
		$product_detail['asm_log_seconds']=($asm_log_seconds)?$asm_log_seconds:'';
                
                #print_r($product_detail); die; 
                $post_data[]=$product_detail;
                 
             } 
             
            #print_r($products); die;
            if($products){
                $response=array('status_code'=>200,'message'=>'result found','data'=>$post_data);
            }else{
              $response=array('status_code'=>404,'message'=>'result not found');  
            }
       }
        
        $this->response($response,200);     
    }
    
    
    function change_product_status_post(){

        $params=array('product_id','user_id','action','time');
        $checkout_reasons=array(
                            'ST'=>'Stolen',
                            'DA'=>'Damaged',
                            'RC'=>'Retained By Client',
                            'OT'=>'Others',
                            'NA'=>'None of Above',
            );

        if($this->auth_params($params)){
            
            $product_id=$this->post('product_id');
            $user_id=$this->post('user_id');
            $action=$this->post('action');
            $time=$this->post('time');
            $reason=$this->post('reason');
            $reason_detail=$this->post('reason_detail');
			
			#print_r($_POST);

            $dbdata['pu_user_id']=$user_id;
            $dbdata['pu_product_id']=$product_id;
            
            $action_time='';
            if($action =='checkin'){
				
                $action_time =  $dbdata['pu_checkin']=$time;
			    $pu_id=$this->asm_model->insert_data('asm_product_usecase',$dbdata);
			
             
            }elseif($action=='checkout'){
                
              $action_time =  $dbdata['pu_checkout']=$time;
              $reason_txt=$checkout_reasons[$reason];
              $checkout_reason=array('reason_abbrev'=>$reason,'reason'=>$reason,'reason_detail'=>$reason_detail);
              $dbdata['pu_reason']= json_encode($checkout_reason);
			  
			  $pu_id=$this->asm_model->update_table('asm_product_usecase',$dbdata,
                                  array('pu_user_id'=>$user_id,'pu_product_id'=>$product_id));
			  
			  //get the product log hours
			  $log_where=array('pu_user_id'=>$user_id,'pu_product_id'=>$product_id,'pu_checkout'=>$time);
			  $products = $this->asm_model->get_data('asm_product_usecase',$log_where);
			  if($products){
				  $product=$products[0];
				  #print_r($product);
				  $logtime = $product['pu_checkout']-$product['pu_checkin'];
				  $this->asm_model->update_table('asm_product_usecase',array('pu_logtime'=>$logtime),array('pu_id' => $product['pu_id'])); 
			  }
			  
            }else{}
		            
            #$pu_id=$this->asm_model->insert_data('asm_product_usecase',$dbdata);
            
            if($pu_id){
                
                // update the status into ASM product Table and save the Transation
                if($action=='checkin'){
                    $update_data['ps_checkedin']=1;
                    $update_data['ps_checkedout']=0;
                    
                    }elseif($action=='checkout'){
                        
                        $update_data['ps_checkedout']=1;
                        $update_data['ps_checkedin']=0;

                        if($reason!='NA'){
                          $update_data['ps_rejected']=1;  
                        }
                    }
                    
                    $where=array('ps_user_id'=>$user_id,
                                 'ps_product_id'=>$product_id,       
                                );
                
                $result= $this->asm_model->update_table('asm_products',$update_data,$where);

                if($result){
                        // save trnsaction log
                        $tdata['pt_user_id']=$user_id;
                        $tdata['pt_product_id']=$product_id;
                        $tdata['pt_comment']="Product ".$action." by the user at".$action_time.".";
                        $this->asm_model->insert_data('asm_product_transactions',$tdata);
                        $response=array('status_code'=>200,'message'=>"product action {$action} success");
                   }
                
            }else{
                 $response=array('status_code'=>404,'message'=>"product action {$action} Fail");
            }

         }else{
            
         $error=$this->auth_params_error($params);
         $response=array('status_code'=>400,'message'=>'Bad Request Made','keys'=>$error);   
         // print_r($error);     
        }
        
       #print_r($response);
       $this->response($response,200);      
        
    }
    

   function  _verifyUSERProduct($user_id,$product_id){

            $product= $this->asm_model->get_data('asm_user_project_products',array('upp_product_id'=>$product_id,
                                                                                    'upp_user_id'=>$user_id));
            #print_r($product); die;
            if($product){
                return $product['upp_product_id'];
            }else{
                return false;
            }
   }    
    
    

    function auth_params($params){
        $error=array();        
         foreach($params as $para){
               
             if(!array_key_exists($para,$_REQUEST)){
                $error[]=$para;
              }elseif(!$_REQUEST[$para]){
                $error[]=$para;
             }
         }  
        #print_r($error); die;
        if(count($error)==0){
            return 1;
        }else{
            return 0;
        }
         
    }
    
    function auth_params_error($params){
        $error=array();
        
         foreach($params as $para){
               
             if(!array_key_exists($para,$_REQUEST)){
                $error[]=$para;
              }elseif(!$_REQUEST[$para]){
                $error[]=$para;
             }
        }  
        #print_r($error); die;
         return $error;
    }
    
    
    function product_history_get($user_id,$product_id){
        $user_id=$this->get('user_id');
        $product_id = $this->get('product_id');
        
        $this->load->library('flexi_auth');
        $history = $this->asm_model->get_prodcut_history($product_id);
        
        if($history){
  
        $post_data=array();
        foreach($history as $hitem){
            
            $user_id=$hitem['pu_user_id'];
            $user=$this->flexi_auth->get_users('',array('uacc_id'=>$user_id))->row_array();
                if($user){
                   $user_name=$user['upro_first_name']." ".$user['upro_last_name'];  
                }else{
                    $user_name='';
               }
            $h_data['user_id']=$user_id;
            $h_data['name']=$user_name;
            $h_data['data']=$this->_product_history_1($user_id, $product_id);
            
            $post_data[]=$h_data; 
        }
        
        #print_r($post_data); die;
       $response=array('status_code'=>200,'message'=>'success','data'=>$post_data); 
      }else{
         $response=array('status_code'=>404,'message'=>'No History Found');  
      }
       #print_r($response);
       $this->response($response,200);
    }
    
    
        function product_history_v0_get($user_id,$product_id){
        $user_id=$this->get('user_id');
        $product_id = $this->get('product_id');
        
        $this->load->library('flexi_auth');
        $history = $this->asm_model->get_prodcut_history($product_id);
        
        if($history){
  
        $post_data=array();
        foreach($history as $hitem){
            
            $user_id=$hitem['pu_user_id'];
            $user=$this->flexi_auth->get_users('',array('uacc_id'=>$user_id))->row_array();
                if($user){
                   $user_name=$user['upro_first_name']." ".$user['upro_last_name'];  
                }else{
                    $user_name='';
               }
            $h_data['user_id']=$user_id;
            $h_data['name']=$user_name;
            $h_data['data']=$this->_product_history($user_id, $product_id);
            
            $post_data[]=$h_data; 
        }
        
        #print_r($post_data); die;
       $response=array('status_code'=>200,'message'=>'success','data'=>$post_data); 
      }else{
         $response=array('status_code'=>404,'message'=>'No History Found');  
      }
       #print_r($response);
       $this->response($response,200);
    }
    
    
    
    function _product_history($user_id,$product_id){
        
        $where=array('pu_user_id'=>$user_id,'pu_product_id'=> $product_id);
        $prodcut_history=$this->asm_model->get_data('asm_product_usecase',$where,'pu_id DESC');
        #print_r($prodcut_history);
         
        $post_data=array();
        foreach($prodcut_history as $item){
            $parse_data=array();
            
            $checkin=($item['pu_checkin'])?date('Y-m-d H:i:s',$item['pu_checkin']):"";
            $checkout=($item['pu_checkout'])?date('Y-m-d H:i:s',$item['pu_checkout']):"";
            
            $parse_data['pu_checkin']=$checkin;
            $parse_data['pu_checkout']=$checkout;

            $post_data[]=$parse_data;
            
        }
        return $post_data;
        
    }
    
       function _product_history_1($user_id,$product_id){
        
        $where=array('pu_user_id'=>$user_id,'pu_product_id'=> $product_id);
        $prodcut_history=$this->asm_model->get_data('asm_product_usecase',$where,'pu_id DESC');
        #print_r($prodcut_history);
         
        $post_data=array();
        foreach($prodcut_history as $item){
            $parse_data=array();
            
            $checkin=($item['pu_checkin'])?date('Y-m-d H:i:s',$item['pu_checkin']):"";
            $checkout=($item['pu_checkout'])?date('Y-m-d H:i:s',$item['pu_checkout']):"";
            
            array_push($post_data,array('pu_checkin'=>$checkin));
            array_push($post_data,array('pu_checkout'=>$checkout,'pu_reason'=>''));
            
            #$parse_data['pu_checkin']=$checkin;
            #$parse_data['pu_checkout']=$checkout;
            #$post_data[]=$parse_data;
            
        }
        return $post_data;
        
    }
    
	
    function _product_log_hours($product_id){
        $this->load->database();
        $sql="SELECT SUM(pu_logtime) AS total_log_hours FROM asm_product_usecase where pu_product_id='".$product_id."'";

            $query=$this->db->query($sql);
            if($query->num_rows()>0){
                    $row=$query->row_array();
                    $log_seconds=  $log_hours=$row['total_log_hours'];
                    if($log_hours){
                            $log_hours=(float)$row['total_log_hours']/3600;
                            $log_hours= number_format((float)$log_hours, 2, '.', '');
                    }
                    return array('hours'=>$log_hours,'seconds'=>$log_seconds);
                    //return $log_hours;
            }else{
                    return false;
            } 

    }
	
    function users_get()
    {
        $users = $this->asm_model->get_all();
         
        if($users)
        {
            $this->response($users, 200);
        }
        else
        {
            $this->response(NULL, 404);
        }
    }
    
    
    function query_data_post(){
        
       $table=$this->post('table');
       $where=($this->post('where'))?$this->post('where'):null;
       $filter=($this->post('filter'))?$this->post('filter'):null;
       $action=$this->post('action');
       
       if($filter['distinct']=='ps_user_id'){
           $where['ps_user_id!=']=0;
       }
       if($filter['distinct']=='ps_project_id'){
           $where['ps_project_id!=']=0;
       }

       $fields=($this->post('fields'))?$this->post('fields'):null;
       $post_data=array();
         
        if(!$table){
            $response=array('status_code'=>400,'message'=>'Bad Request Made');  
            $this->response($result,200);    
            
         }else{
             
           $result= $this->asm_model->query_data($table,$where,$filter,$field);
           
           //print_r($result); //die;
           
             if($filter['distinct']=='ps_user_id'){
                 foreach($result as $user){
                    $post_data[$user['ps_user_id']] = $this->_asm_get_username($user['ps_user_id']);
                 }
             }             
             if($filter['distinct'] == 'ps_project_id'){
                 foreach($result as $project){
                    $post_data[$project['ps_project_id']]=$this->_asm_get_projectname($project['ps_project_id']);
                 }
             }
             
             if($action=='asm_products'){
                
                $this->load->model('kare_model');
                 
                foreach($result as $product){
                        $component   =  $this->kare_model->get_component($product['ps_product_id']);
                        if($component){
                            $product['ps_product_image']=$component['component_imagepath'];
                            $product['ps_store_name']= $this->_asm_get_username($product['ps_store_id']);
                            $product['ps_user_name']= $this->_asm_get_username($product['ps_user_id']);
                            $product['ps_project_name']= $this->_asm_get_projectname($product['ps_project_id']);
                            
                            
                            //print_r($component); die;
                            $post_data[]=$product;
                        }
                }  
             }
            #print_r($post_data); die; 
           if($post_data){
               $response=array('status_code'=>200,'message'=>'success','data'=>$post_data);
            }else{
             $response=array('status_code'=>404,'message'=>'NO data found');
           }  
           $this->response($response, 200);  
         }    
    }
    
   
    function _asm_get_username($user_id){
        $this->load->library('flexi_auth');
                $user=$this->flexi_auth->get_users(null,array('uacc_id'=>$user_id))->row_array();
                if($user){
                    return $user['uacc_username'];
                }else{
                  return false;  
              }
        
    }
    
    function _asm_get_projectname($project_id){
        $project=$this->asm_model->get_data_row('asm_user_projects',array('up_id'=>$project_id));
        if($project){
             return $project['up_project_name'];
        }else{
            return false;
        }
       
    }
    
    

    
}
?>