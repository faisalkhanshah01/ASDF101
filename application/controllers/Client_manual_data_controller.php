<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client_manual_data_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->model('Client_manual_data_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if(! $this->flexi_auth->is_logged_in_via_password()) 
		{
			// Set a custom error message.
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
	}
	
	function client_manual_data(){
		$this->data['clientManualData']=$this->Client_manual_data_model->get_all_data();	
		$this->load->view('clientManualData/manage_clientManualData',$this->data);
	}
	
	
	public function insert_client_manual_data()
	{
		if(isset($_POST['clientManualData_submit'])){
			// echo "<pre>";
			// print_r($_POST);
			// die();
			$tableName = 'client_manual_data';
			$dbdata = array(
						'client_name' => $this->input->post('client_name'),
						'installation_date' => $this->input->post('installation_date'),
						'dealer_name' => $this->input->post('dealer_name'),
						'material_invoice_date' => $this->input->post('material_invoice_date'),
						'product' => $this->input->post('product'),
						'latitude_longitude' => $this->input->post('latitude_longitude'),
						'circle_state' => $this->input->post('circle_state'),
						'RFID' => $this->input->post('RFID'),
						'district' => $this->input->post('district'),
						'bar_code' => $this->input->post('bar_code'),
						'batch_code' => $this->input->post('batch_code'),
						'uin' => $this->input->post('uin')
							);
					$result= $this->Common_model->insert_table_data($dbdata,$tableName);
					if($result){
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Data Added Successfully.</p>');
							redirect('Client_manual_data_controller/client_manual_data', 'refresh');
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b> Data Not Inserted. Plz try again</p>');
					}
		}
		$this->load->view('clientManualData/clientManualData_insert');
	}
	
	function edit_Client_manual_data($id){
		$tableName = 'client_manual_data';
		if(isset($_POST['edit_Client_manual_data'])){
			
			$tableName = 'client_manual_data';
			$cmd_id = $this->input->post('id');
			
			$dbdata = array(
						'client_name' 	=> $this->input->post('client_name'),
						'installation_date' => $this->input->post('installation_date'),
						'dealer_name' => $this->input->post('dealer_name'),
						'material_invoice_date' => $this->input->post('material_invoice_date'),
						'product' => $this->input->post('product'),
						'latitude_longitude' => $this->input->post('latitude_longitude'),
						'circle_state' => $this->input->post('circle_state'),
						'RFID' => $this->input->post('RFID'),
						'district' => $this->input->post('district'),
						'bar_code' => $this->input->post('bar_code'),
						'batch_code' => $this->input->post('batch_code'),
						'uin' => $this->input->post('uin')
							);
							
					$result= $this->Common_model->update_tabel_data($cmd_id,$dbdata,$tableName);
					if($result){
							$this->session->set_flashdata('msg','<p class="alert alert-success capital">Data Updated Successfully.</p>');
							redirect('Client_manual_data_controller/client_manual_data', 'refresh');
					}else{
						$this->session->set_flashdata('msg','<p class="alert alert-danger capital"><b>Syntax Error! :</b> Data Not Inserted. Plz try again</p>');
					}
		}
		
		$this->data['clientManualData']=$this->Client_manual_data_model->get_data_from_id($id);
		
		$this->load->view('clientManualData/clientManualData_edit',$this->data);
	}
	
	function delete_client_manual_data($id){
		$tableName = 'client_manual_data';
		$result = $this->Common_model->delete_table_data($id,$tableName);
		if($result){
			$this->session->set_flashdata('msg','<div class="alert alert-success capital">Data Deleted Successfully.</div>');
			redirect('Client_manual_data_controller/client_manual_data', 'refresh');
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Error! : Data Not Deleted.</div>');
			redirect('productcategory_controller/manage_product_categogy/', 'refresh');
		}
	}
	
	
	
	
	
	
	
	
}// end of controller class 
?>