<?php
require(APPPATH.'/libraries/REST_Controller.php');
 
class Asm_api extends REST_Controller
//class Asm_api extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('asm_model');
        $this->load->model('kare_model');
        $this->load->helper('url');
        
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
        
        $product_id=$this->post('product_id'); 
        $user_id=$this->post('user_id');
        $group_id=$this->post('group_id');

    
        // if gpid==13 scanned by manager 
        if($group_id==13){
            $manager_id=$user_id;
            #$store_id=$this->asm_model->get_sto
            $store_id=$user_id;
        }
        
        $tdata['pt_product_id']= $dbdata['ps_product_id']=$product_id;
        $tdata['pt_smanager_id']= $dbdata['ps_smanager_id']=$manager_id;
        $tdata['pt_store_id']= $dbdata['ps_store_id']=$manager_id;
        
        #print_r($dbdata); 
        if($this->_verifyASMProduct($product_id)){
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
   
   
   
   
    function return_to_store_post(){

        $product_id =   $this->post('product_id');
        $user_id    =   $this->post('user_id');
        $group_id   =   $this->post('group_id'); 
        $project_id=$this->post('project_id');
        
        $smanager_id=$store_id=$this->post('store_id');
        
        
        #print_r($dbdata); 
        $products=$this->asm_model->get_data('asm_products',array('ps_product_id'=>$product_id,'ps_user_id'=>$user_id,'ps_store_id'=>$smanager_id));
        #print_r($products); 
        #die;
        $product=$products[0];
        
        if($product['ps_product_id']){
               // verify that product is return to the same store & product is not inuse 
                if($product['ps_store_id']=$store_id && $product['checkedin']==0){
                    
                     $update_data['ps_user_id']=0;
                     $update_data['ps_isused']=0;
                     $update_data['ps_project_id']=0;
                  
                    
                     $rs= $this->asm_model->update_table('asm_products',$update_data,array('ps_product_id'=>$product_id));
                    
                    if($rs){

                    //update the user_project_product table - remove the entry 
                    #$project_id = $product['ps_project_id'];
                    $where = array('upp_user_id' => $user_id, 'upp_project_id' => $project_id,'upp_product_id'=>$product_id);
                    $result = $this->asm_model->delete_record('asm_user_project_products', $where);
                    if ($result) {

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
         $product_id=$this->post('product_id');
         $user_id=$this->post('user_id');

        // add the product againest the project id 

        $dbdata['upp_project_id']=$project_id;
        $dbdata['upp_product_id']=$product_id;
        $dbdata['upp_user_id']=$user_id;
        
        if($this->_verifyASMProduct($product_id)){
            //echo "here"; die;
           
            // insert into user project and update the ASM products table 
            
            // verify the asm_user_project_products table if product already exist
            $product=$this->asm_model->get_data('asm_user_project_products',array('upp_product_id'=>$product_id,'upp_user_id'=>$user_id));
            
            if(!$product){
                $id = $this->asm_model->insert_data('asm_user_project_products', $dbdata);
                if ($id) {
                    $update_data=array('ps_user_id' => $user_id,'ps_project_id'=>$project_id,'ps_isused'=>1);
                    
                    $where = array('ps_product_id' => $product_id);   // manager id logic pending 
                    $result = $this->asm_model->update_table('asm_products',$update_data, $where);
                    
                    // save the transaction 
                      $tdata['pt_product_id']=$product_id;
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
   function  _verifyASMProduct($product_id){
           #echo $product_id;
            $product= $this->asm_model->get_data('asm_products',array('ps_product_id'=>$product_id));
            #print_r($product); die;
            if($product){
                return 1;
                #return $product['ps_product_id'];
            }else{
                return false;
            }
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
            
//            echo "<pre>";
//            print_r($products);
            
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
        
        if($this->auth_params($params)){
            
            $product_id=$this->post('product_id');
            $user_id=$this->post('user_id');
            $action=$this->post('action');
            $time=$this->post('time');
            
            $dbdata['pu_user_id']=$user_id;
            $dbdata['pu_product_id']=$product_id;
            
            $action_time='';
            if($action=='checkin'){
             $action_time =  $dbdata['pu_checkin']=$time;
            }elseif($action=='checkout'){
              $action_time =  $dbdata['pu_checkout']=$time;
            }else{}
            
            
            $pu_id=$this->asm_model->insert_data('asm_product_usecase',$dbdata);
            
            if($pu_id){
                
                // update the status into ASM product Table and save the Transation
                if($action=='checkin'){
                    $update_data['ps_checkedin']=1;
                    $update_data['ps_checkedout']=0;
                    
                    }elseif($action=='checkout'){
                        
                        $update_data['ps_checkedout']=1;
                        $update_data['ps_checkedin']=0;
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
    
    
    
    
}
?>