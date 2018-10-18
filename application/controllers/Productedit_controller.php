<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Productedit_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('kare_model');
		$this->load->model('Subassets_model');
		$this->load->model('Productedit_model');
		$this->load->model('Specification_model');
		$this->load->helper(array('url','form','kare','date'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('sma');
		$this->load->database();
		
	    $this->auth = new stdClass;

		$this->load->library('flexi_auth');	

		if (! $this->flexi_auth->is_logged_in_via_password()) 
		{
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}		
		$this->load->vars('base_url', base_url());
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		$this->data['lang']  = $this->sma->get_lang_level('first');
		
	}
	
	function index(){
		redirect('auth');
	}
	
	function product_edit(){
		if(isset($_SESSION['product_edit'])){
			unset($_SESSION['product_edit']);
		}
		$this->load->view('productedit/product_edit', $this->data);
	}
	
	function get_selected_data(){
		
		if(!isset($_POST['type_name'])){
			$type_name = $_SESSION['product_edit']['selected_type'];
		}else{
			$type_name = $_POST['type_name'];
			$_SESSION['product_edit']['selected_type'] = $type_name;
		}
		$rel = $_POST['rel'];
		
		$this->load->model('Specification_model');
		$type_details=$this->Specification_model->get_type_details_testing($type_name);
		
		$data['inspection'] = $inspection = $this->Subassets_model->get_type_category('inspection');
		
		$data['uom'] = $uom = $this->Subassets_model->get_type_category('uom');
		$uom_data = array('mtr' => 'MTR', 'nos' =>'NOS');

		$data['result'] = $result = $this->Subassets_model->get_type_category('result');
		
		$data['observation'] = $observation = $this->Subassets_model->get_type_category('observations');
		
		$html='';
		if(($type_name == 'components') || ($type_name == 'sub_assets')){
			
			if($type_name == 'components'){
				$submit = 'submit_assets';
				$title 	= 'Select Asset';
				$class = 'multiple_assets';
				$search_id = 'search__multiple_assets';
				$placeholder = 'Search for assets';
			}else{
				$submit = 'submit_subAssets';
				$title 	= 'Select Sub-Asset';
				$class = 'search_subassets';
				$search_id = 'search_tool_subAssets';
				$placeholder = 'Search for sub assets';
			}
			
			$html ="<div class='form-group'>
					<label class='col-md-2 control-label'>".$title." <br><small>Multiple Select</small></label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-12'> 
								<input type='text' id='".$search_id."' name='".$search_id."' class='form-control tooltip_trigger'  placeholder='".$placeholder."'/> 
							</div>
						</div>
						<div class='row'>
							<div class='col-md-5'> 
								<div class='component-container form-control ".$class."' id='sel_subAssets_productedit'>";
									if($type_details !=''){
										foreach($type_details as $sub_assets){ 
									$html .="<p>".$sub_assets['code']."
									<input class='pull-right' type='checkbox' name='sub_assets[]' id='chk_".$sub_assets['typeID']."' value='".$sub_assets['typeID']."' rel='".$sub_assets['code']."' /></p>";
									 } } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_subAssets_productedit' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container form-control' id='selected_subAssets'></div>
							</div>
						</div>
					</div>
				</div>";
		if($rel!='upload_speci'){
			$html.="<div class='form-group'>
					<label class='col-md-2 control-label'>Expected Result </label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-5'>
								<div class='component-container search-expectedResult' id='sel_expectedResult'>";
									if(is_array($result)){
									foreach($result as $resultKey=>$resultValue){ 
									$html .="<p>".$resultValue."
									<input class='pull-right' type='checkbox' name='expectedresult[]' id='chk_".$resultKey."' value='".$resultKey."' rel='".$resultValue."' /></p>";
									 } } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_expectedResult' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container' id='selected_expectedResult'></div>
							</div>
						</div> 
					</div>
				</div>
				<div class='form-group'>
					<label class='col-md-2 control-label'>Observations</label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-5'>
								<div class='component-container search-observation' id='sel_observation'>";
									if(is_array($observation)){
										foreach($observation as $obsKey=>$obsValue){
									$html .="<p>".$obsValue."
									<input class='pull-right' type='checkbox' name='observation[]' id='chk_".$obsKey."' value='".$obsKey."' rel='".$obsValue."' /></p>"; 
									} } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_observation' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container' id='selected_observation'></div>
							</div>
						</div> 
					</div>
				</div>
				<div class='form-group'>
					<label class='col-md-2 control-label'>Repair</label>
					<div class='col-md-10'>
						<span class='col-md-3'>
							<input  type='radio' id='subasset_repair' name='subasset_repair' value='yes' />Yes
						</span>
						<input type='radio' id='subasset_repair' name='subasset_repair' value='no' />No
					</div>
				</div>
				<div class='form-group'>
					<label class='col-md-2 control-label'>UOM</label>
					<div class='col-md-10'>
						<select id='subasset_uom' class='form-control' name='subasset_uom'>
							<option value=''> - Select UOM Type - </option>";					
								foreach($uom_data as $id=>$val){
								$html .="<option value='".$id."'>".$val."</option>";
								}
						$html .="</select>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-md-2 control-label'>Type of Inspection</label>
					<div class='col-md-10'>
						<select  id='subasset_inspectiontype' name='subasset_inspectiontype'  class='form-control tooltip_trigger' >
							<option value=''> - Select Inspection Type - </option>";
								if(!empty($inspection)){
									foreach($inspection as $insKey=>$insValue){
										$html .="<option value='".$insKey."'>".$insValue."</option>";
									}
								}
						$html .="</select>
					</div>
				</div>
				<div class='form-group'>
					<div class='col-md-offset-6 col-md-6'>
						<input type='submit' name='".$submit."' class='btn btn-primary' id='submit' value='SAVE' />
					</div>
				</div>";
		}
		}else{
			$class = 'search_assetsSeries';
			$search_id = 'search_multiple_assetsSeries';
			$placeholder = 'Search for asset series';
			
			$html ="<div class='form-group'>
					<label class='col-md-2 control-label'>Select Assets Series <br><small>Multiple Select</small></label>	
					<div class='col-md-10'>
						<div class='row'>
							<div class='col-md-12'> 
								<input type='text' id='".$search_id."' name='".$search_id."' class='form-control tooltip_trigger'  placeholder='".$placeholder."'/> 
							</div>
						</div>
						<div class='row'>
							<div class='col-md-5'> 
								<div class='component-container form-control ".$class."' id='sel_subAssets_productedit'>";
									if($type_details !=''){
										foreach($type_details as $sub_assets){ 
									$html .="<p>".$sub_assets['code']."
									<input class='pull-right' type='checkbox' name='sub_assets[]' id='chk_".$sub_assets['typeID']."' value='".$sub_assets['typeID']."' rel='".$sub_assets['code']."' /></p>";
									 } } 
								$html .="</div> 
							</div>
							<div class='col-md-2'>
								<button id='com_sel_btn_subAssets_productedit' class='btn btn-info' type='button' style='margin-top:50px;'> >> </button>
							</div>
							<div class='col-md-5' >
								<div class='component-container form-control' id='selected_subAssets'></div>
							</div>
						</div>
					</div>
				</div>";
		if($rel!='upload_speci'){
			$html.="<div class='form-group'>
					<label class='col-md-2 control-label'>Type of Inspection</label>
					<div class='col-md-10'>
						<select  id='subasset_inspectiontype' name='subasset_inspectiontype'  class='form-control tooltip_trigger'>
							<option value=''> - Select Inspection Type - </option>";
								if(!empty($inspection)){
									foreach($inspection as $insKey=>$insValue){
										$html .="<option value='".$insKey."'>".$insValue."</option>";
									}
								}
						$html .="</select>
					</div>
				</div>
		
				<div class='form-group'>
					<div class='col-md-offset-6 col-md-6'>
						<input type='submit' name='save_asset_series' class='btn btn-primary' id='submit' value='SAVE' />
					</div>
				</div>";
		
		} }
		
		if(!isset($_POST['type_name'])){
			$_SESSION['product_edit']['fetch_data'] = $html;
			$this->load->view('productedit/product_edit');
		}else{
			if(isset($_SESSION['product_edit']['fetch_data'])){
				unset($_SESSION['product_edit']['fetch_data']);
			}
			echo $html;
		}
	}
	
	function update_project(){
		if(isset($_POST['submit_subAssets'])){
			$sub_assets = $this->input->post('sub_assets');
			if(!empty($sub_assets)){
			
				$tableName 	= $this->input->post('upload_type');
				
				
				$dbdata['sub_assets_uom']	=	$this->input->post('subasset_uom');			
				$dbdata['sub_assets_inspection']	=	$this->input->post('subasset_inspectiontype');
				$dbdata['sub_assets_result']		=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';
				$dbdata['sub_assets_observation']	=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';
				$dbdata['sub_assets_repair']		=	$this->input->post('subasset_repair');
				
				$id_update = array();
				$id_failed = array();
				foreach($sub_assets as $id){
					$result = $this->Productedit_model->update_subAssets($id,$tableName,$dbdata);
						if($result){
							 $id_update[] = $id;
						}else{
							 $id_failed[] = $id;
						}	
				}
				setMessages('Product Successfully Update');
			}
			else{
				setMessages('Warning: Sub Assets Field should not be empty.','error');
				
			}
		}
		
		if(isset($_POST['submit_assets'])){
			
			$tableName 	= $this->input->post('upload_type');
			$sub_assets = $this->input->post('sub_assets');
			if(!empty($sub_assets)){
				$dbdata['component_uom']	=	$this->input->post('subasset_uom');			
				$dbdata['component_inspectiontype']	=	$this->input->post('subasset_inspectiontype');
				$dbdata['component_expectedresult']		=	count($this->input->post('expectedResult'))?json_encode($this->input->post('expectedResult')):'';
				$dbdata['component_observation']	=	count($this->input->post('observation'))?json_encode($this->input->post('observation')):'';
				$dbdata['component_repair']		=	$this->input->post('subasset_repair');
				
				$id_update = array();
				$id_failed = array();
				foreach($sub_assets as $id){
					$result = $this->Productedit_model->update_assets($id,$tableName,$dbdata);
						if($result){
							 $id_update[] = $id;
						}else{
							 $id_failed[] = $id;
						}	
				}
				setMessages('Product Successfully Update');
			}
			else{
				setMessages('Warning: Assets Field should not be empty.','error');
			}
		}
		
		if(isset($_POST['save_asset_series'])){
			
			$tableName 	= $this->input->post('upload_type');
			$sub_assets = $this->input->post('sub_assets');
			if(!empty($sub_assets)){		
				$dbdata['product_inspectiontype']	=	$this->input->post('subasset_inspectiontype');			
				
				$id_update = array();
				$id_failed = array();
				foreach($sub_assets as $id){
					$result = $this->Productedit_model->update_asset_series($id,$tableName,$dbdata);
						if($result){
							 $id_update[] = $id;
						}else{
							 $id_failed[] = $id;
						}	
				}
				setMessages('Product Successfully Update');
			}
			else{
				setMessages('Warning: Assets series Field should not be empty.','error');
			}
		}
		redirect('Productedit_controller/get_selected_data');
	}
	
	
	
}// end of controller class 
?>