<?php //echo "current file"; ?>

<?php $this->load->view('includes/header'); ?>
<!-- Navigation -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" />
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<?php $this->load->view('includes/head'); ?>
 
<script>
		$(document).ready(function(){	
			/* *********** search_tool_expected_result *********************** */
			$("#search_tool_expected_result").keyup(function(e){
				var query=$(this).val();
					
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get( "http://arresto.in/kare/manage_kare/ajax_get_asset",
						{ 'search' : value },
						function(data){
							//alert(data);
						$(".search-expectedResult").html(data);	 
					});
			});
		});	
		$(document).on("click", ".search-expectedResult",":checkbox",function(){
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}
		});
			
			
		$(document).on("click", "#com_sel_btn_expectedResult", function(){
				// get all the selected values
				$("input.subresult:hidden").each(function(){
					sub_result_list.push($(this).val());				       	
				});

				$("div#sel_expectedResult input:checked").each(function(index,ele){
					
					 subResult_key = $(this).val();
					var subResult_Value = $(this).attr('rel');
						$("<p class='bg-success'>"+subResult_key+'<span rel="'+subResult_Value+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component sub_result" name="assetSerise[]" value="'+subResult_key+'"/>'
						+"</p>").appendTo("#selected_expectedResult");
					//}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
		});
		
	</script>
	
<style>
#master_table {
	border-collapse: collapse;
}
.bg-danger {
	background-color: #ffaeae;
}
</style>
<?php 

error_reporting(E_ALL & ~E_WARNING);

$CI=& get_instance();
$CI->load->model('kare_model');
$component_list=array_column($CI->kare_model->get_components_list(),'component_code');
$product_list=array_column($CI->kare_model->get_products_list('product_code'),'product_code');

$product_components=array_column($CI->kare_model->get_products_list('product_components'),'product_components');

#echo "<pre>";
#print_r($product_components); die;


foreach($product_components as $key=>$val){
	if($val ==''){
		$product_components[$key] = 0;
	}   
}

$item_list=array($product_list,$product_components);
//print_r($component_list);die;
//$dealer_list=$CI->kare_model->get_dealer_list('client_id,client_name');
$duplicate_jobSMS_list=$CI->kare_model->duplicate_jobSMS_list();

?>

	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php  	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
<?php 
	if($group_id != 9){ ?>
<div class="row">
<div class="col-md-12" >
<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
  <li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["add_master_data"]['description'] !='' ){ echo $lang["add_master_data"]['description']; }else{ echo "ADD MASTER DATA"; } ?></a></li>
  <li role="presentation"><a data-target="#mdata_import" aria-controls="profile" role="tab" data-toggle="tab"><?php if( $lang["import_master_data"]['description'] !='' ){ echo $lang["import_master_data"]['description']; }else{ echo "IMPORT MASTER DATA"; } ?></a></li>
  <li role="presentation"><a href="#mdata_export" aria-controls="messages" role="tab" data-toggle="tab"><?php if( $lang["export_master_data"]['description'] !='' ){ echo $lang["export_master_data"]['description']; }else{ echo "EXPORT MASTER DATA"; } ?></a></li>
  <li role="presentation"><a href="<?php echo $base_url;?>manage_kare/download_mdata_sample"><?php if( $lang["download_sample_master_data"]['description'] !='' ){ echo $lang["download_sample_master_data"]['description']; }else{ echo "DOWNLOAD SAMPLE MASTER DATA"; } ?></a></li>
</ul>
<?php
  //print_r($item);
  
  ?>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="mdata_form">
    <div class="row">
      <div class="col-md-12">
		<div class="panel panel-default">
		<div class="panel-heading home-heading">
			<span ><?php if( $lang["add_master_data"]['description'] !='' ){ echo $lang["add_master_data"]['description']; }else{ echo "ADD MASTER DATA"; } ?></span>
		</div>
		<div class="panel-body">
		<?php echo form_open_multipart(current_url() ,'class="master_data_form"'); ?>
		<div class="form-group col-md-2">
          <label for="email" class="control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo "Job Card No."; } ?></label>
          <!--<input type="text" class="form-control" id="product_jobcard" name="mdata_jobcard" 
                        value="<?php echo set_value('mdata_jobcard',$item['mdata_jobcard']);?>" />-->
		<input type="text" class="form-control"  name="mdata_jobcard" 
                        value="<?php echo set_value('mdata_jobcard',$item['mdata_jobcard']);?>" />				
        <?php //echo form_error('mdata_jobcard'); ?></div>
          
        <div class="form-group col-md-2">
          <label for="email" class="control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo "SMS Number"; } ?></label>
          <!--<input type="text" class="form-control" id="mdata_sms" name="mdata_sms" 
                        value="<?php echo set_value('mdata_code',$item['mdata_sms']);?>" />-->
			<input type="text" class="form-control"  name="mdata_sms" 
                        value="<?php echo set_value('mdata_code',$item['mdata_sms']);?>" />			
          <?php //echo form_error('mdata_sms'); ?></div>
        <div class="form-group col-md-2">
          <label for="email" class="control-label"><?php if( $lang["batch_number"]['description'] !='' ){ echo $lang["batch_number"]['description']; }else{ echo "Batch Number"; } ?></label>
          <input type="text" class="form-control" id="mdata_batch" name="mdata_batch" 
                        value="<?php echo set_value('mdata_batch',$item['mdata_batch']);?>" />
          <?php echo form_error('mdata_batch'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["serial_number"]['description'] !='' ){ echo $lang["serial_number"]['description']; }else{ echo "Serial Number"; } ?></label>
          <input type="text" id="mdata_serial" name="mdata_serial" value="<?php echo set_value('mdata_serial',$item['mdata_serial']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_serial'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["rfid_number"]['description'] !='' ){ echo $lang["rfid_number"]['description']; }else{ echo "RFID number"; } ?></label>
          <input type="text" id="mdata_rfid" name="mdata_rfid" value="<?php echo set_value('mdata_rfid',$item['mdata_rfid']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_rfid'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["bar_code_number"]['description'] !='' ){ echo $lang["bar_code_number"]['description']; }else{ echo "Bar Code Number"; } ?></label>
          <input type="text" id="mdata_barcode" name="mdata_barcode" value="<?php echo set_value('mdata_barcode',$item['mdata_barcode']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_barcode'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["uin"]['description'] !='' ){ echo $lang["uin"]['description']; }else{ echo "UIN"; } ?></label>
          <input type="text" id="mdata_uin" name="mdata_uin" value="<?php echo set_value('mdata_uin',$item['mdata_uin']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_uin'); ?> </div>
        <div class="form-group col-md-5">
			<label for="email" class=" control-label"><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo "Client's Name"; } ?></label>
			<select name="mdata_client" data-show-subtext="true" data-live-search="true" class="form-control selectpicker">
            <option value="">Select Client</option>
            <?php
					foreach($client_list as $cl){
						$id = $cl['client_id'];
						$client_name = $cl['client_name'];
						$client_type = $cl['client_type'];
						$selected = ($client_name===$item['mdata_client'])? 'selected' : '';
						echo "<option ".$selected." value='".$client_name."' >".$client_name." (".$client_type.")</option>";
					}
					?>
			</select>
			<?php echo form_error('mdata_client'); ?> 
		</div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["po_number"]['description'] !='' ){ echo $lang["po_number"]['description']; }else{ echo "PO Number"; } ?></label>
          <input type="text" id="mdata_po" name="mdata_po" value="<?php echo set_value('mdata_po',$item['mdata_po']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_po'); ?> </div>
        <br />

        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["material_invoice_number"]['description'] !='' ){ echo $lang["material_invoice_number"]['description']; }else{ echo "Material Invoice No."; } ?></label>
          <input type="text" id="mdata_material_invoice" name="mdata_material_invoice" value="<?php echo set_value('mdata_material_invoice',$item['mdata_material_invoice']);?>" class="form-control tooltip_trigger" />
          <?php echo form_error('mdata_material_invoice'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class=" control-label"><?php if( $lang["material_invoice_date"]['description'] !='' ){ echo $lang["material_invoice_date"]['description']; }else{ echo "Material Invoice Date"; } ?> </label>
          <input type="text" id="mdata_material_invoice_date" name="mdata_material_invoice_date" value="<?php echo set_value('mdata_material_invoice_date',$item['mdata_material_invoice_date']);?>" class="form-control tooltip_trigger date1" readonly />
          <?php echo form_error('mdata_material_invoice_date'); ?> </div>
		<div class="form-group col-md-2">
			<label for="email" class="control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
				<select  id="status" name="status" class="form-control tooltip_trigger">
					<option selected value=""> - Status - </option>
					<option value="Active">Active</option>
					<option value="Inactive">Inactive</option>
				</select>
			 <?php echo form_error('status'); ?>
		</div>
		<div class="form-group col-md-2">
			<label for="date_of_first_use" class=" control-label"><?php if( $lang["date_of_first_use"]['description'] !='' ){ echo $lang["date_of_first_use"]['description']; }else{ echo "Date of First Use"; } ?></label>
				<input type="text" class="form-control date1" name="date_of_first_use" id="date_of_first_use" />
				<?php echo form_error('date_of_first_use'); ?> 

		</div>
		<div class="form-group col-md-2">
			<label for="date_of_inspection" class=" control-label"><?php if( $lang["date_of_inspection"]['description'] !='' ){ echo $lang["date_of_inspection"]['description']; }else{ echo "Date of Inspection"; } ?></label>
				<input type="text" class="form-control date1" name="date_of_inspection" id="date_of_inspection" />
				<?php echo form_error('date_of_inspection'); ?> 
		</div>
		
		<!-------------------------------------------------------------------------------------->
		<div class="form-group col-md-12">
			<label for="email" class="control-label"><?php if( $lang["asset"]['description'] !='' ){ echo $lang["asset"]['description']; }else{ echo "Asset"; } ?> </label>	
			<input style="width:40.5%;" type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
			<div class="row">
				<div class="col-md-5">
					<div class="component-container search-expectedResult form-control" id='sel_expectedResult' style="height: 150px;border: 1px solid #CCC;">
						<?php 
						if(!empty($component_list)){
							foreach($component_list as $resultKey=>$resultValue){ 
						?>
						<p><?php echo $resultValue; ?>
						<input  class="pull-right" type="checkbox" name="assetSerise[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultValue; ?>" rel="<?php echo $resultValue; ?>" /></p>
						<?php } } ?> 
					</div> 
				</div>
				<div class="col-md-2">
					<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
				</div>
				<div class="col-md-5" >
					<div class="component-container form-control" id="selected_expectedResult" style="height: 150px;margin-left:20px;">
					</div>
				</div>
			</div> 
		 </div>
		<!--------------------------------------------end------------------------------------------>
        <div class="form-group col-md-12">
          <label for="email" class=" control-label"><?php if( $lang["asset_series"]['description'] !='' ){ echo $lang["asset_series"]['description']; }else{ echo "Asset Series"; } ?> </label>
          <input style="width:40.5%;" type="text" id="search_assetSeries" name="search_tool" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Asset Series"/>
          <div class="row">
            <div class="col-md-5">
				<div class="search-container" id='sel_component'>
					<?php 
						$mitem = $item_list[0];
						$product_component = $item_list[1];
					for($i=0; $i<count($item_list[0]);$i++){
					// foreach($item_list as $mitem){
						?>
						<p id="<?php echo $i; ?>"><?php echo $mitem[$i]; ?>
							<input class="pull-right" type="checkbox" name="components[]" rel='<?php echo $product_component[$i]; ?>' value="<?php echo $mitem[$i]; ?>" />
						</p>
					<?php }?>
				</div>
            </div>
            <div class="col-md-2">
              <button id="com_sel_btn_master" class="btn" type="button" style="margin-top:50px;"> >> </button>
            </div>
            <div class="col-md-5" >
              <div class="component-container" id="product_component_master" data-name='mdata_insert'>
               <?php if($item['mdata_item_series']!='')
			         {
				    $dbitem=json_decode($item['mdata_item_series']);
				    foreach($dbitem as $x){ 
 					echo $respose="<p class='bg-success'>".$x.'<span rel="'.$x.'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'."<input type='hidden' class='pull-right' name='product_components[]' value='$x' />"."</p>";
					}
				}
					?>
               </div>
            </div>
          </div>
        </div>
		<div class="form-group" >
          <div class="col-md-offset-5 col-md-6" style="margin-top:30px; margin-bottom:30px;">
            <input type="submit" name="submit_mdata" class="btn btn-primary" id="submit_mdata" value="<?php if( $lang["save_master_data"]['description'] !='' ){ echo $lang["save_master_data"]['description']; }else{ echo "SAVE MASTER DATA"; } ?>" />
          </div>
        </div>
        <!--</form>--> 
        <?php echo form_close();?> 
		</div>
		</div>
		</div>
    </div>
  </div>
	<div role="tabpanel" class="tab-pane" id="mdata_import">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span ><?php if( $lang["import_data_from_xls_csv_file"]['import_data_from_xls_csv_file'] !='' ){ echo $lang["asset_series"]['description']; }else{ echo "IMPORT DATA FROM XLS/CSV"; } ?></span>
					</div>
					<div class="panel-body">
						<?php echo form_open_multipart($base_url.'manage_kare/import_master_data', 'class="form-horizontal"'); ?>
						<div class="form-group">
						<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_xls_file"]['description'] !='' ){ echo $lang["upload_xls_file"]['description']; }else{ echo "Upload Xls File"; } ?></label>
						<div class="col-md-8">
						<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
						<?php echo form_error('file_upload'); ?> </div>
						</div>
						<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
						<input type="submit" name="import_inspection_xls" class="btn btn-primary" id="import_inspection_xls" value="Uplaod XLS" />
						</div>
						</div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
			<div role="tabpanel" class="tab-pane" id="mdata_export">
				<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span ><?php if( $lang["export_data_into_xls_csv_file"]['description'] !='' ){ echo $lang["export_data_into_xls_csv_file"]['description']; }else{ echo "EXPORT DATA INTO XLS/CSV"; } ?></span>
					</div>
					<div class="panel-body">
					<?php echo form_open_multipart($base_url.'manage_kare/export_inspection_xls' , 'class="form-horizontal"'); ?>
					<div class="form-group">
					<label for="email" class="col-md-4 control-label"><?php if( $lang["select_file_type"]['description'] !='' ){ echo $lang["select_file_type"]['description']; }else{ echo "Select File Type"; } ?></label>
					<div class="col-md-8">
					<select name="export_filetype" class="form-control">
					<option value="CSV Format" >CSV Format</option>
					<option value="XLS Format">XLS Format</option>
					</select>
					<?php echo form_error('export_filetype'); ?> </div>
					</div>
					<div class="form-group">
					<div class="col-md-offset-4 col-md-8">
					<input type="submit" name="export_inspection_xls" class="btn btn-primary" id="export_masterdata_xls" value="EXPORT" />
					</div>
					</div>
					<?php echo form_close();?> 
					</div>
					</div>
				</div>
			</div>
			</div>
</div>
<!--/.tab-content -->
</div></div>
<!--/.row -->
<?php } ?>


<div class="row">
  <div class="col-md-12" >
	<div class="panel panel-default">
		<div class="panel-heading home-heading">
			<span><?php if( $lang["master_data_inspection"]['description'] !='' ){ echo $lang["master_data_inspection"]['description']; }else{ echo "MASTER DATA INSPECTION"; } ?></span>
		</div>
		<div class="panel-body">
			<?php if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
			<div class="col-md-offset-10">
				<a href="<?php echo $base_url;?>manage_kare/reset_table_data/master" class="btn btn-danger delete"><?php if( $lang["reset_master_table"]['description'] !='' ){ echo $lang["reset_master_table"]['description']; }else{ echo "Reset Master Table"; } ?></a>
				</br></br>
			</div>
			<?php } ?>
			<table id="master_data_table" class="table table-bordered" style="overflow-x: scroll; max-width:1515px; ">
			  <thead>
				  <th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; } ?><br /></td>
				  <th data-link="mdata_jobcard"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo "Job Card No."; } ?></th>
				  <th data-link="mdata_sms"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo "SMS Number"; } ?></th>
                                  <th data-link="mdata_assets_series">Assets Series</th>
				  <th data-link="mdata_assets">Assets</th>

				  <th data-link="mdata_batch"><?php if( $lang["batch_number"]['description'] !='' ){ echo $lang["batch_number"]['description']; }else{ echo "Batch Number"; } ?></th>
				  <th data-link="mdata_serial"><?php if( $lang["serial_number"]['description'] !='' ){ echo $lang["serial_number"]['description']; }else{ echo "Serial Number"; } ?></th>
				  <th data-link="mdata_rfid"><?php if( $lang["rfid_number"]['description'] !='' ){ echo $lang["rfid_number"]['description']; }else{ echo "RFID number"; } ?></th>
				  <th data-link="mdata_barcode"><?php if( $lang["bar_code_number"]['description'] !='' ){ echo $lang["bar_code_number"]['description']; }else{ echo "Bar Code Number"; } ?></th>
				  <th data-link="mdata_uin"><?php if( $lang["uin"]['description'] !='' ){ echo $lang["uin"]['description']; }else{ echo "UIN"; } ?></th>
				  <th data-link="mdata_client"><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo "Client's Name"; } ?></th>
				<!--  <th data-link="mdata_dealer">Dealer Involved</th>-->
				  <th data-link="mdata_status"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></th>
				  <!-- <th data-link="date_of_first_use">Date of First Use</th>
				  <th data-link="date_of_inspection">Date of Inspection</th>
				  <th data-link="inspection_due_date">Inspection Due Date</th> -->
				
			  </thead>
			</table>
		</div>
	</div>
	</div>
</div>

	
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>

<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>