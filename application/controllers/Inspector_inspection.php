<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	*	Author		:	Shakti Singh
	*	Email		:	shakti.singh@flashonmind.com
	*	Function	:	For Admin to APPROVE / REJECT the report submitted by Inspector.
*/
class Inspector_inspection extends CI_Controller{
   
   // Initializing kare controller
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Inspector_inspection_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()){
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		
		$this->data = null;
		$this->data['lang']  = $this->sma->get_lang_level('first');
	}
	
	
	/* Public Functions Start*/	
	
	function index(){
		$this->data['inspection_list'] = $result =  $this->Inspector_inspection_model->get_inspectionData_for_admin();

		$this->load->view('admin_inspection/inspection_list', $this->data);
	}
	
	/* First Page of Inspection */
	function view_inspection(){
		/*
		if (isset($_POST['approved_inspection']) || isset($_POST['reject_inspection'])){

			$report_no = $_SESSION['admin_inspection']['report_no'];
			$id = $_SESSION['admin_inspection']['inspection_table_id'];
			
			$dbdata = array(
							'approved_by'=>$_SESSION['admin_inspection']['user_id'],
							'approved_admin_id'=>$_SESSION['admin_inspection']['user_id'],
							'pdf_status'=>'true',
							'email_status'=>'true',
							'admin_approved_date'=> now()
							);
			$dbdata['approved_status'] = $approved_status = (isset($_POST['approved_inspection']))?  'Approved' :  'Rejected';
			
			// Update the Values(Lat/Long, RFID, UIN etc.) in inspection_list_1 table 
			$result = $this->Inspector_inspection_model->update_approved_values($dbdata,$id);
			
			// Update the Values(Lat/Long, RFID, UIN etc.) 
			$res_Updation = $this->update_data_in_siteid($id);
			
			
			if($result && $res_Updation){
				
				//	Generate PDF of Work Permit
				$workPermit_id = $this->Inspector_inspection_model->get_workpermit_id($id);
				$workPermit_pdf = $this->generateWorkPermitPDF($id, $workPermit_id);
				
				//	Generate PDF of Work Report 
				$result_pdf = $this->generatePDF($approved_status, $report_no, $id);
				
				if(!$result_pdf){
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Generating PDF.</p>');
				}elseif(!$workPermit_pdf){
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Generating PDF.</p>');
				}else{
					$attachment  = array($result_pdf, $workPermit_pdf);
					//	Send Email
					$result_email = $this->generate_email($report_no, $attachment);  
					if(!$result_email){
						if(isset($_POST['approved_inspection'])){
							$this->session->set_flashdata('msg','<p class="alert alert-warning capital"><b>SYNTAX ERROR ! :</b> ERROR IN SENDING EMAIL. YET FORM APPROVED SUCCESSFULLY.</p>');
						}else if(isset($_POST['reject_inspection'])){
							$this->session->set_flashdata('msg','<p class="alert alert-warning capital"><b>SYNTAX ERROR ! :</b> Error in sending email. Though Form Rejected Successfully.</p>');
						}
					}else{
						
						// Start: Remove Site ID from the Inspector List Which GOT App/Rej 
							$res = $this->Inspector_inspection_model->get_sms_jobCard($_SESSION['admin_inspection']['inspection_table_id']);

							$res_deletion = $this->delete_siteID($res['job_card'],$res['sms'],$_SESSION['admin_inspection']['siteID_id_no']);
						// End: Removing of Site ID
						
						
						if(isset($_POST['approved_inspection'])){
							if(isset($res_deletion['error'])){
								// print_r($res_deletion);
								$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error In Deleting Assign Site ID From Site ID Table. Though Form is Approved, PDF GENERATED AND EMAIL IS SENT.</p>');
							}else{
								$this->session->set_flashdata('msg','<p class="alert alert-success capital">Forms Approved Successfully. An email with pdf Document is sent.</p>');
							}
						}else if(isset($_POST['reject_inspection'])){
							if(isset($res_deletion['error'])){
								// print_r($res_deletion);
								$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error In Deleting Assign Site ID From Site ID Table. Though Form is Rejected, PDF GENERATED AND EMAIL IS SENT. </p>');
							}else{
								$this->session->set_flashdata('msg','<p class="alert alert-success capital">Forms Rejected 	Successfully. An email with pdf Document is sent.</p>');
							}
						}
					}
				}

				unset($_SESSION['admin_inspection']);
				redirect('inspector_inspection/');
			}else{
				if(!$result){
					echo "Error in update_approved_values";
				}
				else if(!$res_Updation){
					echo "Error in update_data_in_siteid";
				}
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Updating Inspection Form Values. FORM Not Approved Yet.</p>');
				redirect('inspector_inspection/');
			}
		}else{
			*/
			if(!isset($_GET['id'])){
				redirect('inspector_inspection');
			}
			$id 	= 	$_GET['id'];
			$type	=	"view";
			$data = $this->get_inspection_data($id,$type);
			$this->load->view('admin_inspection/inspection_details', $data);
		//}
	}
	
	
	
	function submit_report(){
		$submitValue = $_REQUEST['value'];
		
			//isset($_POST['approved_inspection']) || isset($_POST['reject_inspection'])){

			$report_no = $_SESSION['admin_inspection']['report_no'];
			$id = $_SESSION['admin_inspection']['inspection_table_id'];
			
			$dbdata = array(
							'approved_by'=>$_SESSION['admin_inspection']['user_id'],
							'approved_admin_id'=>$_SESSION['admin_inspection']['user_id'],
							'pdf_status'=>'true',
							'email_status'=>'true',
							'admin_approved_date'=> now()
							);
			$dbdata['approved_status'] = $approved_status = ($submitValue == 'approved_inspection')?  'Approved' :  'Rejected';
			
			/* Update the Values(Lat/Long, RFID, UIN etc.) in inspection_list_1 table */
			$result = $this->Inspector_inspection_model->update_approved_values($dbdata,$id);
			
			/* Update the Values(Lat/Long, RFID, UIN etc.) */
			$res_Updation = $this->update_data_in_siteid($id);
			
			
			if($result && $res_Updation){
				/* Start: Remove Site ID from the Inspector List Which GOT App/Rej */
				$res = $this->Inspector_inspection_model->get_sms_jobCard($_SESSION['admin_inspection']['inspection_table_id']);

				$res_deletion = $this->delete_siteID($res['job_card'],$res['sms'],$_SESSION['admin_inspection']['siteID_id_no']);
				/* End: Removing of Site ID */
				//	Generate PDF of Work Permit
				$workPermit_id = $this->Inspector_inspection_model->get_workpermit_id($id);
				$workPermit_pdf = $this->generateWorkPermitPDF($id, $workPermit_id);
				
				/*	Generate PDF of Work Report */
				$result_pdf = $this->generatePDF($approved_status, $report_no, $id);
				
				if(!$result_pdf){
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Generating PDF.</p>');
				}elseif(!$workPermit_pdf){
					$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Generating PDF.</p>');
				}else{
					$attachment  = array($result_pdf, $workPermit_pdf);
					/*	Send Email   */
					$result_email = $this->generate_email($report_no, $attachment);  
					if(!$result_email){
						if($submitValue == 'approved_inspection'){
							$this->session->set_flashdata('msg','<p class="alert alert-warning capital"><b>SYNTAX ERROR ! :</b> ERROR IN SENDING EMAIL. YET FORM APPROVED SUCCESSFULLY.</p>');
						}else if($submitValue == 'reject_inspection'){
							$this->session->set_flashdata('msg','<p class="alert alert-warning capital"><b>SYNTAX ERROR ! :</b> Error in sending email. Though Form Rejected Successfully.</p>');
						}
					}else{
						
						
						
						
						if($submitValue == 'approved_inspection'){
							if(isset($res_deletion['error'])){
								// print_r($res_deletion);
								$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error In Deleting Assign Site ID From Site ID Table. Though Form is Approved, PDF GENERATED AND EMAIL IS SENT.</p>');
							}else{
								$this->session->set_flashdata('msg','<p class="alert alert-success capital">Forms Approved Successfully. An email with pdf Document is sent.</p>');
							}
						}else if($submitValue == 'reject_inspection'){
							if(isset($res_deletion['error'])){
								// print_r($res_deletion);
								$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error In Deleting Assign Site ID From Site ID Table. Though Form is Rejected, PDF GENERATED AND EMAIL IS SENT. </p>');
							}else{
								$this->session->set_flashdata('msg','<p class="alert alert-success capital">Forms Rejected 	Successfully. An email with pdf Document is sent.</p>');
							}
						}
					}
				}

				unset($_SESSION['admin_inspection']);
				return true;
			}else{
				if(!$result){
					echo "Error in update_approved_values";
				}
				else if(!$res_Updation){
					echo "Error in update_data_in_siteid";
				}
				$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>SYNTAX ERROR ! :</b> Error in Updating Inspection Form Values. FORM Not Approved Yet.</p>');
				return true;
			}
	}
	
	
	/* Once Inspection of One site ID is done and Approved / Rejected by Admin, Delete the Site id from Assigned Inspector. */
	function delete_siteID($jobCard,$sms,$site_id){
		return $this->Inspector_inspection_model->delete_siteID_from_inspectorAssigned($jobCard,$sms,$site_id);
	}
	
	function generateWorkPermitPDF($id, $workPermit_id){
		$workPermit_data = $this->Inspector_inspection_model->get_workpermit_data($workPermit_id);
		$this->load->library('pdf');
		$pdf 	= 	$this->pdf->load();
		$type	=	"pdf";
		$whtml	=	$this->get_workpermit_datas($id, $workPermit_id);
		$pdf	->	WriteHTML($whtml);
		$wfile 	=	$workPermit_data['workPermit_number'].date('').".pdf";
		$dir 	= 	FCPATH."uploads/workPermit_pdf/";
		$res 	= 	$pdf->Output($dir.$wfile,'F');
		return $dir.$wfile;
	}
	
	
	
	/* To Generate PFD Once Admn Approved or Reject the Inspection */
	function generatePDF($approved_status='', $report_no='',$id="")
	{
		//ini_set('memory_limit','32M');
		$status = ($approved_status !='')? $approved_status : '';
		$this->load->library('pdf');
		$pdf 	= 	$this->pdf->load();
		$type	=	"pdf";
		$html	=	$this->get_inspection_data($id,$type,$status);
		$pdf	->	WriteHTML($html);
		$file 	=	$report_no.date('').".pdf";
		$dir 	= 	FCPATH."uploads/inspection_pdf/";
		$res 	= 	$pdf->Output($dir.$file,'F');
		return $dir.$file;
	}
	
	/* To Send an Email after Admin Approved or Reject the Inspection */
	function generate_email($report_no, $attachment_path){
		
		$this->load->model('flexi_auth_model');
	//	$email = array('Sagar.dixit@karam.in', 'shakti.singh@flashonmind.com');
		$email 			= array('service@karam.in', 'shakti.singh@flashonmind.com');
		$email_to 		= $email;
		$email_title	= 'Inspection Form';
		$user_data 		= array(
							'user_name' => $_SESSION['admin_inspection']['admin_name']
						);
		$template = $this->auth->email_settings['email_template_directory'].$this->auth->email_settings['email_template_inspection'];

		if ($this->flexi_auth_model->send_email($email_to, $email_title, $user_data, $template, $attachment_path))
		{
			return TRUE;
		}else{
			return false;
		}
	}
	
	function get_workpermit_datas($id, $workpermit_id){
		/* Get Admin Data From Session */
			$userdata 		= $this->session->userdata();
			$user_f_name 	= $userdata['firstName'];
			$user_l_name 	= $userdata['lastName'];
			$flexi_auth 	= $userdata['flexi_auth'];

			$user_id = $flexi_auth['id'];
			$_SESSION['admin_inspection']['user_id']	= $user_id;
			$_SESSION['admin_inspection']['admin_name']	= $userdata['firstName'].' '.$userdata['lastName'];
		/* End of Admin Data from Session */

		$this->data['inspection_values'] 	= $result1 =  $this->Inspector_inspection_model->get_inspectionData_for_admin($id);
		
		$this->data['client_address'] 	= $this->Inspector_inspection_model->get_client_address($result1['siteID_id']);
		
		$this->data['work']	= $workPermit_data = $this->Inspector_inspection_model->get_workpermit_data($workpermit_id);
		return $this->load->view('admin_inspection/generateworkPermit_pdf', $this->data , true);
		
	}
	
	/* To get and display Inspected Data Values in the form */
	function get_inspection_data($id, $type,$approved_status=''){

		/* Get Admin Data From Session */
			$userdata 		= $this->session->userdata();
			$user_f_name 	= $userdata['firstName'];
			$user_l_name 	= $userdata['lastName'];
			$flexi_auth 	= $userdata['flexi_auth'];

			$user_id = $flexi_auth['id'];
			$_SESSION['admin_inspection']['user_id']	= $user_id;
			$_SESSION['admin_inspection']['admin_name']	= $userdata['firstName'].' '.$userdata['lastName'];
		/* End of Admin Data from Session */

		$this->data['approved_status'] 		= $approved_status;
		$this->data['inspection_values'] 	= $result1 =  $this->Inspector_inspection_model->get_inspectionData_for_admin($id);
		
		$_SESSION['admin_inspection']['siteID_id_no'] 	= $result1['siteID_id'];
		
		/* Get Client Name from Master data according to Job Card and SMS. */
		$this->data['master_values'] 	= $result = $this->Inspector_inspection_model->get_client_masterData_details($result1['job_card'],$result1['sms']);
		$this->data['client_address'] 	= $this->Inspector_inspection_model->get_client_address($result1['siteID_id']);
		
		$ins_results= $this->Inspector_inspection_model->get_inspectionDetails_for_admin($id,$result1['job_card'],$result1['sms'],$result1['asset_series']);
		
		//Changes of 03-March-2017
		// Get array in user defined way for PN7000 & Tower(Relaince).
		//$final = $this->Inspector_inspection_model->get_user_defined_array($ins_results);
		if($result1['asset_series'] == 'TOWER(RELIANCE)' || $result1['asset_series'] == 'PN7000(RELIANCE)' ){
			$final = $this->Inspector_inspection_model->get_user_defined_array($ins_results);
		}else{
			$final = $ins_results;
		}
		$this->data['inspection_data']	= $final;
		//print_r($this->data['inspection_data']);die();
		if($type="pdf"){
			return $this->load->view('admin_inspection/generate_pdf', $this->data , true);
		}else{
			return $this->data;
		}
	}
	
	/*
		Once Data is approved by admin in inspection form then
		get the values of lattitue and longitude from table of inspection_list_1 and
		update these values in site id table.
	*/
	function update_data_in_siteid($id){
		
		$result 		= $this->Inspector_inspection_model->get_lattitude_longitude($id);
		$site_id 		= $result['siteID_id'];
		$jobCard		= $result['job_card'];
		$sms			= $result['sms'];
		$input_method	= $result['input_method'];
		$input_value	= $result['input_value'];
		$created_date	= ($result['created_date'] != '' ) ? date("Y-m-d", strtotime($result['created_date'])) : '';

		if( !empty($result['asset_series'])){			
			$asset_series	 = (string)$result['asset_series'];		 
		}else{
			$asset_series	 = '';	
		}

		$dbdata 		= array(
						'site_lattitude' => $result['lattitude'],
						'site_longitude' => $result['longitude']
		);
		
		/* Update RFID / UIN / BARCODE value in master data */
		$this->update_uin_barcode_rfid_in_masterData($jobCard,$sms, $asset_series,$input_method,$input_value, $created_date);
		return $res = $this->Inspector_inspection_model->update_latitude_longitude_in_siteid($site_id,$dbdata);
	}
	
	function update_uin_barcode_rfid_in_masterData($jobCard,$sms, $asset_series, $input_method,$input_value, $created_date){
		$masterData = array();
		$inputMethod = strtolower($input_method);
		if($inputMethod == 'uin' || $inputMethod == 'uni'){
			$masterData['mdata_uin'] = $input_value;
		}else if($inputMethod == 'rfid'){
			$masterData['mdata_rfid'] = $input_value;
		}else if(strtolower($inputMethod) =='barcode'){
			$masterData['mdata_barcode'] = $input_value;
		}
		
		return $this->Inspector_inspection_model->update_uin_barcode_rfid_in_masterData($jobCard,$sms, $asset_series, $created_date, $masterData);
	}
	
	/* To fetch Approved and Rejected List */
	function approved_rejected_list(){
		$this->data['approved'] = $this->Inspector_inspection_model->get_approved_rejected_pending_list('Approved');
		$this->data['rejected'] = $this->Inspector_inspection_model->get_approved_rejected_pending_list('Rejected');
		$this->load->view('approved_rejected_inspection',$this->data); 
	}
/* Public Functions END*/		

	function delete_report($id){
		
		$this->load->model('Common_model');
		$loggedGroupID = $this->Common_model->get_loggegIn_user_groupID();
		if($loggedGroupID =='3' || $loggedGroupID =='3'){
			$res = $this->Inspector_inspection_model->delete_report($id);
			if($res){
				setMessages('Report Deleted Successfully');
			}else{
				setMessages('Error In Deleting the Report', 'error');
			}
		}else{
			setMessages('You Do not have privilege to Delete the Report', 'error');
		}
		redirect('inspector_inspection');
	}
	
}// end of controller class 
?>