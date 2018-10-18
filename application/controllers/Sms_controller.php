<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sms_controller extends CI_Controller{
   
	public function __construct(){
		parent::__construct();
		$this->load->model('SMS_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
                
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id = $_SESSION['client']['client_id'];

                $this->auth = new stdClass;
		$this->load->library('flexi_auth');

		if (! $this->flexi_auth->is_logged_in_via_password()){
			$this->session->set_flashdata('msg','<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect($this->client_url.'auth');
		}
		if($_SESSION['user_group'] =='8'){
			// If User is Client Redirect him back to it's own controller.
			redirect($this->client_url.'Clientuser_dashboard');
			exit();
		}
		$this->load->vars('base_url', base_url().$this->client_url);
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data['lang']  = $this->sma->get_lang_level('first');
	}
	/* Public Functions Start*/
	
	function index(){
		redirect($this->client_url.'auth');
	}
	
	function ajax_get_sms(){
		$jobcard = $_POST['jobcard'];
		$sms = $this->SMS_model->get_sms_from_masterData($jobcard);
		if(is_array($sms)){
			echo "<option value=''> - Select SMS Number - </option>";
			foreach($sms as $val){
				echo "<option value='".$val->mdata_sms."'>".$val->mdata_sms."</option>";
			}
		}
	}
		
	function ajax_get_series_from_sms(){
		$sms_number = $_POST['sms_number'];
		$jobCards = $_POST['jobCards'];
		$target = $_POST['target'];
		if($target == 'sms_component_series'){
			$series = $this->SMS_model->get_assetSeries_from_masterData($jobCards,$sms_number);
			if(is_object($series)){
				$seriesArray =	json_decode($series->mdata_item_series);
				echo '<option value=""> - Select Asset Series - </option>';
				foreach($seriesArray as $compValue){
					echo "<option value='".$compValue."'>".$compValue."</option>";
				}
			}
		}else if($target == 'inspector'){
			$this->load->model('SiteId_model');
			// got the site id from site id table
			$siteID = $this->SiteId_model->ajax_get_siteID_from_sms($sms_number,$jobCards);

			// to check if any site id is already assigned for same jobcard and sms no to any inspector
			if($siteID){
				// Start : Do not display Duplicate Site ID which are present in the siteId_kare table
				
				$this->load->model('Siteid_model');
				$duplicate_site_id = $this->Siteid_model->duplicate_jobSMSSite_list();

				foreach($siteID as $skey=>$sVal){
					foreach($duplicate_site_id as $dVal){
						if($jobCards == $dVal['job'] && $sms_number == $dVal['sms'] && $sVal['site_id'] == $dVal['site']){
							unset($siteID[$skey]);
						}
					}
				}
				$new_arr = array();
				foreach($siteID as $vals){
					$new_arr[] = $vals;
				}
					
				// End
				$arr = array();
				foreach($new_arr as $val){
					$site_id = $val['siteID_id'];
					$site_val = $val['site_id'];
					
					$this->load->model('kare_model');
					// $check_duplicate = $this->SiteId_model->check_duplicate_site_id($site_val);
					// if($check_duplicate){
						$result = $this->kare_model->check_site_id_in_inspectorData($jobCards,$sms_number,$site_id);
						if(!$result){
						?>
							<p><?php echo $site_val; ?><input class="pull-right" type="checkbox" name="site_id[]" id="<?php echo "chk_".$site_val; ?>" value="<?php echo $site_id; ?>" rel="<?php echo $site_val; ?>" /></p>
						<?php }
					//}
					
				}
			}else{
				echo "<script>alert('No Site ID available. Please choose a different SMS Number')</script>";
			}
		}
	}
	
	function ajax_get_asset_from_assetSeries(){
		$series = $_POST['series'];
		$target = $_POST['target'];
		if($target == 'series_item'){
			$asset = $this->SMS_model->get_asset_from_product($series);
			if($asset){
				$assetArray =	json_decode($asset->product_components);
				echo '<option value=""> - Select Asset - </option>';
				foreach($assetArray as $compValue){
					echo "<option value='".$compValue."'>".$compValue."</option>";
				}
			}
		}
	}

	function sms_component_view(){
		$data['lang'] = $this->data['lang'];
		$arrjobcard = $this->SMS_model->get_jobcard();
                
                //echo "<pre>";
                //print_r($arrjobcard);

		foreach ($arrjobcard as $jobcards) 
		{
			$arrFinal[$jobcards->id] = $jobcards->jc_number;
		}
                
                #print_r($arrFinal);
                
                   // die;
                
		$data['jobcards'] = (isset($arrFinal))? $arrFinal : '';

		$this->load->view('sms_component_view', $data);	
	}
	
	function edit_sms_component(){
			$id=$_REQUEST['id'];
			$data['lang'] = $this->data['lang'];
			$arrjobcard = $this->SMS_model->get_jobcard_from_masterData();
			foreach ($arrjobcard as $jobcards){
				$arrFinal[$jobcards->mdata_id] = $jobcards->mdata_jobcard;
			}
			
			$data['jobcards'] = $arrFinal;
		   
			$data['record'] = $record=$this->SMS_model->edit_sms_component($id);
			
			$sms =$this->SMS_model->get_sms_from_masterData($record['jc_number']);
			
			if(is_array($sms)){
				foreach ($sms as $smsValue){
					$smsFinal[] = $smsValue->mdata_sms;
				}
			}else{
				$smsFinal = '';
			}
		
			$data['sms'] = $smsFinal;
			
			
			$series =$this->SMS_model->get_assetSeries_from_masterData($record['jc_number'],$record['sms_number']);
			
			if(is_object($series)){
				$seriesFinal= json_decode($series->mdata_item_series);
			}else{
				$seriesFinal = '';
			}
			$data['series'] = $seriesFinal;
			
			$asset =$this->SMS_model->get_asset_from_product($record['series']);
			
			if(is_object($asset)){
			
				$assetFinal= json_decode($asset->product_components);
			}else{
				$assetFinal = '';
			}
			$data['asset'] = $assetFinal;
			
			$this->load->view('edit_sms_component', $data);
			
	}
	
	function update_sms_component(){
		if (isset($_POST['update_components'])){

			$id=$_REQUEST['id'];
			$jc_number=$this->input->post('jc_number');
			$sms_number=$this->input->post('sms_number');
			$series_name=$this->input->post('series_name');
			$item_code=$this->input->post('item_code');
			$item_quantity=$this->input->post('item_quantity');
			$no_of_lines=$this->input->post('no_of_lines');
			$status=$this->input->post('status');

			$dbdata=array('jc_number'=>$jc_number, 'sms_number'=>$sms_number, 
			'series'=>$series_name,'item_code'=>$item_code,
			'item_quantity'=>$item_quantity,'no_of_lines'=>$no_of_lines,'status'=>$status,'created_date'=>now());

			$this->load->model('SMS_model');
			$result= $this->SMS_model->update_sms_component($dbdata,$id);
			
			if(!$result)
			{
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Failed to update SMS Component.</div>');
				redirect($this->client_url.'/Sms_controller/sms_component_view');
			}
			else
			{
				$this->session->set_flashdata('msg','<div class="alert alert-success capital">SMS Component successfully Updated. Thankyou!</div>');
				redirect($this->client_url.'/Sms_controller/sms_component_view');
			}
		}
		
		
	}
	
	function delete_sms_component(){
		$id=$_GET['id'];
		$this->load->model('SMS_model');
		$result=$this->SMS_model->component_delete($id);
		
		if($result){
			$this->session->set_flashdata('msg','One row deleted');
			redirect($this->client_url.'/Sms_controller/sms_component_view');
		}else{
			$this->session->set_flashdata('msg','Error! no row deleted');
			redirect($this->client_url.'/Sms_controller/sms_component_view');
		}		
	}
	
	function export_sms_component_excel(){
		if (file_exists('uploads/sms_components.xls')) {
			if(!unlink('uploads/sms_components.xls')){
				echo "Not deleted";
			}
		}
		
		$this->load->library('excel');
		
		$objPHPExcel = new PHPExcel();
			
		// Set properties
		$objPHPExcel->getProperties()
						->setTitle("Office 2007 XLSX SMS Component")
						->setSubject("SMS Component XLSX ")
						->setKeywords("SMS Component openxml php")
						->setCategory("SMS Component file");
		$objPHPExcel->getActiveSheet()
						->setTitle('SMS Component List');
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'JobCard Number')
						->setCellValue('B1', 'SMS Number')
						->setCellValue('C1', 'ASSET Series')
						->setCellValue('D1', 'ASSET Code')
						->setCellValue('E1', 'ASSET Quantity')
						->setCellValue('F1', 'NO. OF LINES')
						->setCellValue('G1', 'Status');
		
			$header = 'A1:O1';
			$style = array(
				'font' => array('bold' => true,),
				'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
				'startcolor' => array(
									 'rgb' => '000000'
								)
			);
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($style);
			
			
			for($col = ord('A'); $col <= ord('G'); $col++){
			//set column dimension
				$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
				 //change the font size
				$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
				 
				$objPHPExcel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		// load database
		$this->load->database();
 
		// load model
		$this->load->model('SMS_model');
		$student_list = $this->SMS_model->sms_component_view(1);
		// read data to active sheet
	
		$objPHPExcel->getActiveSheet()->fromArray($student_list, null, 'A2');
		
		$filename='sms_components.xls'; //save our workbook as this file name
 
		header('Content-Type: application/vnd.ms-excel'); //mime type
 
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
 
		header('Cache-Control: max-age=0'); //no cache
					
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 
		//force user to download the Excel file without writing it to server's HD 
		//$objWriter->save('php://output');
		
		//To download via ajax 
		//$objWriter->save($filename);
		$objWriter->save('uploads/'.$filename);
		$url = base_url().'uploads/'.$filename;
		echo $url;
		/* End of Download Excel File Code */
		
	}

	function add_SmsComponent_excel(){
            
                $client_id=$_SESSION['client']['client_id'];
		$this->load->view('add_SmsComponent_excel');
		
		if (isset($_POST['add_componentexcel'])&& $_SERVER['REQUEST_METHOD']=='POST'){
			$file=$_FILES["excel_file"]["name"];
			$allowedExts = array("csv", "xls", "xlsx");
			$ext = explode(".", $_FILES["excel_file"]["name"]);
			$extension = $ext[1];
                       
                         $dir = FCPATH."uploads/".$client_id."/xls/";
                         if(!file_exists($dir)){
                           mkdir($dir,0777,true);
                        }
                       
			if ((in_array($extension, $allowedExts))){
			
				if ($_FILES["excel_file"]["error"] > 0){
					$data['msg']='Invalid file';
					$this->load->view('add_SmsComponent_excel',$data);
				}else{
					#$dir = FCPATH."uploads/xls/";
                                        $uplaod_path= $dir.$file;      
					move_uploaded_file($_FILES["excel_file"]["tmp_name"],$uplaod_path);
					#$file_path= FCPATH."uploads/xls/".$file;
					$file_path= $uplaod_path;
                                        
					$result=$this->import_sms_xls($file_path);
					if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
						redirect($this->client_url.'/sms_controller/add_SmsComponent_excel');
					}
				}
			}else{
				$this->session->set_flashdata('msg','Invalid file');
				redirect($this->client_url.'/sms_controller/add_SmsComponent_excel');
			}
		}
	}
	
	function import_sms_xls($file_path=null){
		error_reporting(E_ALL);
		ini_set("memory_limit","512M");
		ini_set('max_execution_time',600);
		// load library
		$this->load->library('excel');
		$objPHPExcel=PHPExcel_IOFactory::load($file_path);
		// get worksheet
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$data=array();
		//get only row iterator
		$count=0;
		//static $counter=0;

		$client_id=$_SESSION['client']['client_id'];

		foreach($objWorksheet->getRowIterator() as $key=>$row){  
			if ($key==1) continue;
			
			$this->load->model('SiteId_model');
			if($this->SiteId_model->isRowNotEmpty($row)){
				
				$data[$count]['client_fk']=$client_id;
				$cellIterator=$row->getCellIterator();
				foreach($cellIterator as $cell){
					switch($cell->getColumn()){
						case 'A':
						$data[$count]['jc_number']=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'B':
						$data[$count]['sms_number']=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'C':
						$data[$count]['series']=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'D':
						$data[$count]['item_code']=preg_replace('/\s+/', '', strtoupper(trim($cell->getValue())));
						break;
						case 'E':
						$data[$count]['item_quantity']=trim($cell->getValue());
						break;
						case 'F':
						$data[$count]['no_of_lines']=trim($cell->getValue());
						$data[$count]['status']='Active';
						$data[$count]['created_date']=now();
						break;				
					}
				}
				$count++;
			}
		}

		$compid =$this->SMS_model->add_sms_component($data);	
		if($compid){
			$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
			return true;
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">ERROR IN UPLOADING FILE.</div>');
			return false;
		}
	}
	
/* Public Functions END*/
		
}// end of controller class 




?>