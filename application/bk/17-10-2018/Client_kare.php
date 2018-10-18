<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client_kare extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Client_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
		
                $this->auth = new stdClass;
                
                // current domian/client info 
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id =  $_SESSION['client']['client_id'];
                

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect($this->client_url.'auth');
		}
		
		$this->load->vars('base_url', base_url().$this->client_url);
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data['lang']  = $this->sma->get_lang_level('first');

	}
		
	function index(){                           
		redirect($this->client_url.'auth');
	}
	
		public function client_add(){
			
			$this->load->model('Subassets_model');
			$data['client_types']=$this->Subassets_model->get_inspection_list('client');
			$this->load->view('userpanel/client/add_client',$data);
			
		}
	
		public function add_clients() 
		{ 
			if (isset($_POST['add_clients']))
			{
				$client_name=strtoupper(trim($this->input->post('client_name')));
				$district=$this->input->post('district');
				$circle=$this->input->post('circle');
				$contactPerson=$this->input->post('contactPerson');
				$contactNo=$this->input->post('contactNo');
				$contactPerson_email=$this->input->post('contactPerson_email');
				$client_type=$this->input->post('client_type');
				$status=$this->input->post('status');

				$dbdata=array( 
                                               'client_client_fk'=>$_SESSION['client']['client_id'], 
                                               'client_name'=>$client_name, 
                                               'client_district'=>$district,
                                               'client_circle'=>$circle,
                                               'client_contactPerson'=>$contactPerson,
                                               'client_contactNo'=>$contactNo,
                                               'client_contactPerson_email'=>$contactPerson_email,
                                               'client_type'=>$client_type, 
                                               'client_status'=>$status);
				
				
				$result= $this->Client_model->add_clients($dbdata);
				
				if(!$result)
				{
					//echo "FORCED to Stop the Process, DEAD END!";
					//die();
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Warning:This Client name is already registered</div>');
					redirect($this->client_url.'/client_kare/client_view');
				}
				else
				{
					//echo "Hurry! Data inserted smoothly";
					//die();
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">User successfully registered. Thankyou.</div>');
					redirect($this->client_url.'/client_kare/client_view');
				}
			}
		}
		
            public function client_view()
            {       

                    $data['lang'] = $this->data['lang'];  
                    $this->load->model('Subassets_model');
                   
                    $data['client_types']=$this->Subassets_model->get_inspection_list('client');

                    
                    $data['posts'] = $this->Client_model->client_view();
                    #print_r($data['posts']); die;
                    
                    $this->load->view('userpanel/client/client_view', $data);
            }
		
		public function edit_client()
		{   
			$userid=$_GET['id'];
			$this->load->model('Subassets_model');
			$this->data['client_types']=$this->Subassets_model->get_inspection_list('client');
			$this->data['edit'] = $this->Client_model->edit_client($userid);
			
			$this->load->view('userpanel/client/edit_client', $this->data);
		}
		
		public function update_client()
		{
			
			if (isset($_POST['update_clients']) )
			{
				$client_id=$this->input->post('client_id');
				$client_name=strtoupper(trim($this->input->post('client_name')));
				$district=$this->input->post('district');
				$circle=$this->input->post('circle');
				$contactPerson=$this->input->post('contactPerson');
				$contactNo=$this->input->post('contactNo');
				$contactPerson_email=$this->input->post('contactPerson_email');
				$client_type=$this->input->post('client_type');
				$status=$this->input->post('status');

				$dbdata=array('client_name'=>$client_name, 'client_district'=>$district, 'client_circle'=>$circle,'client_contactPerson'=>$contactPerson,'client_contactNo'=>$contactNo, 'client_contactPerson_email'=>$contactPerson_email, 'client_type'=>$client_type, 'client_status'=>$status);
				
				$result= $this->Client_model->update_clients($dbdata,$client_id);
				
				if(!$result)
				{
					//echo "FORCED to Stop the Process, DEAD END!";
					//die();
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">User not Updated</div>');
					redirect($this->client_url.'/client_kare/client_view');
				}
				else
				{
					//echo "Hurry! Data inserted smoothly";
					//die();
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">User successfully Updated. Thankyou.</div>');
					redirect($this->client_url.'/client_kare/client_view');
				}
			}
			
		}
		
		public function delete_client()
		{
			$userid=$_GET['id'];
			$result=$this->Client_model->client_delete($userid);
			
			if($result)
			{
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data deleted successfully</div>');
				redirect($this->client_url.'/client_kare/client_view');
			}
			else 
			{
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! no data deleted</div>');
				redirect($this->client_url.'/client_kare/client_view');
			}
					
		}
		
		public function add_client_excel()
		{
                    $client_id=$_SESSION['client']['client_id'];
                    
			if (isset($_POST['add_clientexcel'])&& $_SERVER['REQUEST_METHOD']=='POST')
			{
                                $file=  $_FILES["excel_file"]["name"];                        
                                $nfile= "client_excel_".time().".".pathinfo($file,PATHINFO_EXTENSION);
				$allowedExts = array("csv", "xls", "xlsx");
				$ext = explode(".", $_FILES["excel_file"]["name"]);
				$extension = $ext[1];
				
				if ((in_array($extension, $allowedExts)))
				{
				
					if ($_FILES["excel_file"]["error"] > 0)
					{
						$data['msg']='Invalid file';
						$this->load->view('userpanel/client/add_client_excel',$data);
					}
					else
					{
						$dir = FCPATH.$client_id."/uploads/xls/";
                                                $recursive=true;
                                                if(!file_exists($dir)){
                                                    mkdir($dir,0777, $recursive);
                                                }
						move_uploaded_file($_FILES["excel_file"]["tmp_name"], $dir.$nfile);
						$file_path= $dir.$nfile;
						$result=$this->import_client_xls($file_path);
					}
				}
				else
				{
					//$data['msg']='Invalid file';
					//$this->load->view('add_client_excel',$data);
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Invalid file</div>');
					redirect($this->client_url.'/client_kare/add_client_excel');
				}
			}
                        
                     $this->load->view('userpanel/client/add_client_excel');       
		}
		
		
		public function import_client_xls($file_path=null)
		{
                    #echo $file_path; die;
			// load library
			$this->load->library('excel');
			$objPHPExcel=PHPExcel_IOFactory::load($file_path);
			// get worksheet
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$data=array();
			//get only row iterator
			$count=0;
                        $client_id=$_SESSION['client']['client_id'];
                        $this->load->model('SiteId_model');
			foreach($objWorksheet->getRowIterator() as $key=>$row)
			{
                              $count++;
                              if($count==1) continue;

				if($this->SiteId_model->isRowNotEmpty($row)) {
                                    
                                        $data[$count]['client_client_fk']=$client_id;
                                        
					$cellIterator=$row->getCellIterator();

					foreach($cellIterator as $cell)
					{
						switch($cell->getColumn())
						{
							case 'A':
							$data[$count]['client_name']=strtoupper(trim($cell->getValue()));
							break;
							case 'B':
							$data[$count]['client_district']=trim($cell->getValue());
							break;
							case 'C':
							$data[$count]['client_circle']=trim($cell->getValue());
							break;
							case 'D':
							$data[$count]['client_contactPerson']=trim($cell->getValue());
							break;
							case 'E':
							$data[$count]['client_contactNo']=trim($cell->getValue());
							break;
							case 'F':
							$data[$count]['client_contactPerson_email']=trim($cell->getValue());
							break;
							case 'G':
								/*if($cell->getValue() !=''){
									$clientType	= ucfirst(strtolower(trim($cell->getValue())));
									$this->load->model('Subassets_model');
									$client=$this->Subassets_model->get_inspection_list('client',$clientType);
									if($client){
										$data[$count]['client_type'] = $client;
									}else{
										$data[$count]['client_type'] = $clientType;
									}
								}else{
									$data[$count]['client_type']='';
								}*/
                                                         
                                                        $data[$count]['client_type']=trim($cell->getValue());        
							$data[$count]['client_status']='Active';
							$data[$count]['created_date'] = now();
							break;
						}
					}	
					
				}
                                
			}
                        /*echo "<pre>";
                        print_r($data);
                        die;*/
                        
			$result=$this->Client_model->add_excelclients($data);	
			if($result){
				$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>CLIENT LIST SUCCESSFULLY IMPORTED</div>");
			}else{
				$this->session->set_flashdata('msg',"<div class='alert alert-danger capital'>FAILED TO UPLOAD CLIENT LIST </div>");
			}
			redirect($this->client_url.'client_kare/add_client_excel');
		}

	
		public function download_clientExcel()
		{
			if (file_exists('uploads/client_list.xls')) {
				if(!unlink('uploads/client_list.xls')){
					echo "Not deleted";
				}
			}
			
			$this->load->library('excel');
			
			$objPHPExcel = new PHPExcel();
				
			$objPHPExcel->getProperties()->setTitle("Office 2007 Client XLSX List")
							->setSubject("Client List XLSX")
							->setDescription("Client List for Office 2007 XLSX.")
							->setKeywords("office 2007 Client List")
							->setCategory("Client List File");
			$objPHPExcel->getActiveSheet()->setTitle('Client List');
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Client ID')
						->setCellValue('B1', 'Client Name')
						->setCellValue('C1', 'Client District')
						->setCellValue('D1', 'Client Circle')
						->setCellValue('E1', 'Client Contact Person')
						->setCellValue('F1', 'Client Contact Number')
						->setCellValue('G1', 'Client Contact Email')
						->setCellValue('H1', 'Client Type')
						->setCellValue('I1', 'Client Status');
			
			$header = 'A1:I1';
			$style = array(
				'font' => array('bold' => true,),
				'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
				'startcolor' => array(
									 'rgb' => '000000'
								)
			);
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style);
			
				for($col = ord('A'); $col <= ord('I'); $col++){
                //set column dimension
					$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					 //change the font size
					$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
					 
					$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
			
			$client_list = $this->Client_model->client_view(1);
			foreach($client_list as $key=>$value){
				$client_list[$key]['client_type'] = $this->Client_model->client_type($value['client_type']);
			}

			// read data to active sheet
		
			$objPHPExcel->getActiveSheet()->fromArray($client_list, null, 'A2');
			
			$filename='client_list.xls'; //save our workbook as this file name
	 
			header('Content-Type: application/vnd.ms-excel'); //mime type
	 
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
	 
			header('Cache-Control: max-age=0'); //no cache
						
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 
			//force user to download the Excel file without writing it to server's HD
			//$objWriter->save('php://output');
			
			//To download via ajax 
			$objWriter->save('uploads/'.$filename);
			$url = base_url().'uploads/'.$filename;
			echo $url;
		}
	
		
	}// end of controller class 




?>