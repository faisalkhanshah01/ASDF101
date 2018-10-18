<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Language_controller extends CI_Controller{
   
   // Initializing kare controller 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Form_model');
		$this->load->model('kare_model');
		$this->load->helper(array('url','form','kare','date','download'));
		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->load->database();
	        $this->auth = new stdClass;
		$this->load->library('flexi_auth');
                
      
                // current domian/client info 
                $this->client_url = $_SESSION['client']['url_slug']."/";
                $this->client_id = $_SESSION['client']['client_id'];
                
         
		
		if (! $this->flexi_auth->is_logged_in_via_password()){
			$this->session->set_flashdata('msg', '<p class="alert alert-danger capital">'.$this->flexi_auth->get_messages().'</p>');
			redirect('auth');
		}
                
		$this->load->vars('base_url', base_url().$this->client_url);
		$this->load->vars('includes_dir', base_url().'includes/');
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		$this->data = null;
		$data = null;
		
	}
	/* Public Functions Start*/
	
	function index(){		
		$this->language_view();
	}

	public function language_view()
	{
		$this->load->model('Language_model');
		$data['language_level'] = $this->Language_model->get_language_list();				
		$this->load->view('language/language_list', $data);
	}

	public function angular(){
		
		if($_GET['action'] == 'getLanguageAjax'){
			$aColumns = array(	'level', 'group_name', 'en_description','fr_description', 'arabic_description'
							);
		}
		$relation = $_GET['action'];		
		$this->get_values_of_tables($aColumns,$relation);
	}

	function get_values_of_tables($aColumns,$relation){
		// SQL limit
		$sLimit = '';
		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = 'LIMIT ' . (int)$_GET['iDisplayStart'] . ', ' . (int)$_GET['iDisplayLength'];
		}

		$sOrder = '';
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = 'ORDER BY  ';
			for ($i=0 ; $i<(int)$_GET['iSortingCols'] ; $i++) {
				if ( $_GET[ 'bSortable_'.(int)$_GET['iSortCol_'.$i] ] == 'true' ) {
					$sOrder .= '`'.$aColumns[ (int)$_GET['iSortCol_'.$i] ].'` '.
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .', ';
				}
			}

			$sOrder = substr_replace($sOrder, '', -2);
			if ($sOrder == 'ORDER BY') {
				$sOrder = '';
			}
		}

		// SQL where
		//$sWhere = 'WHERE 1';
		if($relation =='getSmsComponentAjax'){
			$sWhere = "WHERE item_code NOT LIKE 'INS-%' ";
		}else{
			$sWhere = 'WHERE 1 ';
		}
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != '') {
			if($relation =='getSmsComponentAjax'){
				$sWhere .= " AND (";
			}else{
				$sWhere = 'WHERE 1 AND (';
			}

			for ($i=0; $i<count($aColumns) ; $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == 'true') {
					$sWhere .= '`' . $aColumns[$i]."` LIKE '%".$_GET['sSearch']."%' OR ";
				}
			}
			$sWhere = substr_replace( $sWhere, '', -3 );
			$sWhere .= ')';
		}

		if($relation =='getLanguageAjax'){
			return $this->getLanguageAjax($sWhere,$sOrder,$sLimit);
		}
		
	}

	function getLanguageAjax($sWhere,$sOrder,$sLimit) {		
		$this->load->model('Language_model');
		$Levels = $this->Language_model->get_language_list_via_ajax($sWhere,$sOrder,$sLimit);
		$iCnt = $this->Language_model->get_total_count_language();
		
		$output = array(
			'sEcho' => intval($_GET['sEcho']),
			'iTotalRecords' => count($Levels),
			'iTotalDisplayRecords' => $iCnt,
			'aaData' => array()
		);
		
		foreach ($Levels as $iID => $aInfo) {				
				$aItem = array(
					'<a class="text-primary" href="'.base_url().'language_controller/edit_lang?id='.$aInfo['id'].'"> <span class="glyphicon glyphicon-pencil"></span></a>',
					$aInfo['level'],
					$aInfo['group_name'],
					$aInfo['en_description'],
					$aInfo['fr_description'],
					$aInfo['arabic_description'],						
					'DT_RowId' => $aInfo['id']
				);
				$output['aaData'][] = $aItem;
		}		  
			echo json_encode($output);
	}

	public function edit_lang()
	{   
		$id = $_GET['id'];
		$this->load->model('Language_model');
		$this->data['edit'] = $this->Language_model->edit_lang($id);
		$this->load->view('language/edit_language', $this->data);
	}

	public function update_lang()
	{
		$this->load->model('Language_model');
		if (isset($_POST['update_lang']) )
		{   
			$id					= $this->input->post('id');			
			$level				= strtolower(trim($this->input->post('lang_level')));
			$level				= preg_replace("/[^a-zA-Z0-9\s]/", "_", $level); 
			$level				= str_replace(' ', "_", $level);
			$level				= str_replace('__', "_", $level);
			
			
			$group_name			= strtolower(trim($this->input->post('group_name')));
			$group_name			= preg_replace("/[^a-zA-Z0-9\s]/", "_", $group_name);
			$group_name			= str_replace(' ', "_", $group_name);
			$group_name			= str_replace('__', "_", $group_name);
			
			$en_description		= $this->input->post('en_description');
			$en_long_description		= $this->input->post('en_long_description');

			$fr_description		= $this->input->post('fr_description');
			$fr_long_description		= $this->input->post('fr_long_description');

			$arabic_description	= $this->input->post('arabic_description');
			$arabic_long_description	= $this->input->post('arabic_long_description');

			$fildArry			= array('id'=>$id,'level'=>$level,'group_name'=>$group_name);
			if($this->Language_model->chk_exclude_language_id($fildArry)){  
				$this->session->set_flashdata('msg','<div class="alert alert-danger capital">There dublicate level: '.$level.' with group '.$group_name.' .</div>');	
				redirect('language_controller/edit_lang?id='.$id);
			}else{
				$dbdata				= array('level'=>$level, 'group_name'=>$group_name,'en_description'=>$en_description, 'en_long_description'=>$en_long_description,'fr_description'=>$fr_description, 'fr_long_description'=>$fr_long_description, 'arabic_description'=>$arabic_description, 'arabic_long_description'=>$arabic_long_description);
				$result				= $this->Language_model->update_lang($dbdata,$id);
				if(!$result)
				{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Language could not Updated.</div>');
					redirect('/language_controller/index');
				}
				else
				{
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Language successfully Updated.</div>');
					redirect('/language_controller/index');
				}			
			}			
		}
	}

	public function language_level_add(){
		$this->load->model('Language_model'); 	
		if (! empty($_POST['add_lang']))
		{  			
			$level				= strtolower(trim($this->input->post('lang_level')));
			$level				= preg_replace("/[^a-zA-Z0-9\s]/", "_", $level); 
			$level				= str_replace(' ', "_", $level);
			$level				= str_replace('__', "_", $level);

			
			$group_name			= strtolower(trim($this->input->post('group_name')));
			$group_name			= preg_replace("/[^a-zA-Z0-9\s]/", "_", $group_name);
			$group_name			= str_replace(' ', "_", $group_name);
			$group_name			= str_replace('__', "_", $group_name);
			
			$en_description				= $this->input->post('en_description');
			$en_long_description		= $this->input->post('en_description');
			$fr_description				= $this->input->post('fr_description');
			$fr_long_description		= $this->input->post('fr_long_description');
			$arabic_description			= $this->input->post('arabic_description');
			$arabic_long_description	= $this->input->post('arabic_long_description');
			$dbdata						= array('level'=>$level, 'group_name'=>$group_name,'en_description'=>$en_description,'en_long_description'=>$en_long_description,'fr_description'=>$fr_description,'fr_long_description'=>$fr_long_description, 'arabic_description'=>$arabic_description, 'arabic_long_description'=>$arabic_long_description);
			
			if($this->Language_model->get_language_list($level, $group_name)){  
				 $this->session->set_flashdata('msg','<div class="alert alert-danger capital">There dublicate level: '.$level.' with group '.$group_name.' .</div>');				
			}else{			
				$result				= $this->Language_model->insert_lang($dbdata);
				if(!$result)
				{
					$this->session->set_flashdata('msg','<div class="alert alert-danger capital">Language could not add, Try again.</div>');
					redirect('/language_controller/index');
				}
				else
				{	
					$this->session->set_flashdata('msg','<div class="alert alert-success capital">Language successfully Updated.</div>');
					redirect('/language_controller/index');
				}
			}			
		}
		$this->load->view('language/add_language', $this->data);

	}

	public function import_lang_level_list(){
		
		if(isset($_POST['imp_excel'])&& $_SERVER['REQUEST_METHOD']=='POST'){
			
			$config['upload_path'] = "./uploads/xls/";
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['max_size'] = '2048';
			$config['max_width'] = '0';
			$config['max_height'] = '0';
			$this->load->library('upload',$config);
			
			if(!$this->upload->do_upload('file_upload')){
				 $error=$this->upload->display_errors();
				 $this->session->set_flashdata('msg','<div class="alert alert-danger capital">'.$error.'</div>');
			}else{
				$upload_data= $this->upload->data();
				// get uploaded file path 
				$file_path=$upload_data['full_path'];
				
				if($file_path){
					$result=$this->import_lang_xls($file_path);
					if($result){
						$this->session->set_flashdata('msg','<div class="alert alert-success capital">FILE SUCCESSFULLY IMPORTED.</div>');
						unlink($file_path); // delete the uploded file 	
					}else{
						echo "<div class='alert alert-danger capital'>file uploading problem</div>";	 
					}
				}
			}
			//redirect('language_controller/import_lang_level_list');
		}

		$this->load->view('language/import_languag_excel', $this->data);
		
	}


    public function import_lang_xls($file_path=null){
	
		$this->load->model('Language_model'); 			
		if( $_POST['imp_excel'] == 'yes' ){		
				
					$this->load->library('excel');
					$objPHPExcel=PHPExcel_IOFactory::load($file_path);
					// get worksheet
					$objWorksheet = $objPHPExcel->getActiveSheet();
					$data=array();
					//get only row iterator
					$count = 0;
					foreach($objWorksheet->getRowIterator() as $key=>$row){
						if($key==1) continue;
							$cellIterator=$row->getCellIterator();
						$this->load->model('SiteId_model');
						if($this->SiteId_model->isRowNotEmpty($row)) {
							//echo "<pre>"; print_r($cellIterator);  echo "</pre>";  echo "AAA"; exit;
							foreach($cellIterator as $cell){
								switch($cell->getColumn()){   // mdata_material_invoice
									case 'A':
									    $level  = str_replace(' ', "_", trim($cell->getValue()));
										$data[$count]['level']= strtolower($level);
									break;
									case 'B':
										$group_name =str_replace(' ', "_", trim($cell->getValue()));
										$data[$count]['group_name']= strtolower($group_name);
									break;
									case 'C':
										$data[$count]['en_description']=trim($cell->getValue());
									break;
									case 'D':
										$data[$count]['en_long_description']=trim($cell->getValue());
									break;
									case 'E':
										$data[$count]['fr_description']=trim($cell->getValue());
									break;
									case 'E':
										$data[$count]['fr_long_description']=trim($cell->getValue());
									break;
									case 'G':
										$data[$count]['arabic_description']=trim($cell->getValue());
									break;
									case 'H':
										$data[$count]['arabic_long_description']=trim($cell->getValue());
									break;
									

								}// end of switch 
							}// end celliterator
							
							$count++;
						}
					}// End row Iterator
					
					// insert data into database
					//echo "<pre>"; print_r($data);  echo "</pre>";  echo "AAA"; exit;
					$dublSTR = '';
					$fltLevelArray =  array();
					$countF = 0;
					$dublCount = 0;
					foreach($data AS $val){ 
						$level = $val['level'];
					    $group_name = $val['group_name'];
						$en_description = $val['en_description'];
						$en_long_description = $val['en_long_description'];
						$fr_description = $val['fr_description'];
						$fr_long_description = $val['fr_long_description'];
						$arabic_description = $val['arabic_description'];
						$arabic_long_description = $val['arabic_long_description'];

						$filtCond = array('level'=>$level, 'group_name' => $group_name);
						if( $this->Language_model->get_language_filt_arry_list($filtCond) ){
						  $dublCount++;
						  $dublSTR .= $level.' , '; 
						}else{
						  $fltLevelArray[$countF]['level'] = $level;
						  $fltLevelArray[$countF]['group_name'] = $group_name;
						  $fltLevelArray[$countF]['en_description'] = $en_description;
						  $fltLevelArray[$countF]['en_long_description'] = $en_long_description;
						  $fltLevelArray[$countF]['fr_description'] = $fr_description;
						  $fltLevelArray[$countF]['fr_long_description'] = $fr_long_description;
						  $fltLevelArray[$countF]['arabic_description'] = $arabic_description;
						  $fltLevelArray[$countF]['arabic_long_description'] = $arabic_long_description;
						}
						$countF = $count + 1;
					}

					$totalRecords = ( $countF - $dublCount );
					if( $totalRecords == $dublCount ){
						
						$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>All data is dublicate</div>");
						redirect('language_controller/index');	
						return true;
					}

					if( $dublCount > 0 ){
						$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>There are ". $dublCount .": (".$dublSTR.") data dublicate</div>");
						redirect('language_controller/index');	
						return true;
					}
         
		           
					$result=$this->Language_model->import_language_level_xls($data);	
					if($result){
						$this->session->set_flashdata('msg',"<div class='alert alert-success capital'>MASTER DATA LIST SUCCESSFULLY IMPORTED</div>");
						return true;
					}else{
						return false;
					}
		}		
	}

	function smaple_import_lang_excel(){
		$data = file_get_contents("./uploads/sampleFile/sample_multi_language_level.xlsx"); // Read the file's contents
		$name = 'sample_multi_language_level.xlsx';
		force_download($name, $data); 
	}
		
	
}// end of controller class
?>