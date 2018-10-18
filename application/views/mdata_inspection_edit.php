<?php $this->load->view('includes/header'); ?>
<!-- Navigation -->
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
$CI=& get_instance();
$CI->load->model('kare_model');
$component_list=array_column($CI->kare_model->get_components_list(),'component_code');
$product_list=array_column($CI->kare_model->get_products_list('product_code'),'product_code');
$product_components=array_column($CI->kare_model->get_products_list('product_components'),'product_components');
//$client_list=$CI->kare_model->get_client_list('client_id,client_name');

foreach($product_components as $key=>$val){
	if($val ==''){
		$product_components[$key] = 0;
	}
}

$item_list=array($product_list,$product_components);

//$dealer_list=$CI->kare_model->get_dealer_list('client_id,client_name');	
	
?>
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
  <li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["add_master_data"]['description'] !='' ){ echo $lang["add_master_data"]['description']; }else{ echo "ADD MASTER DATA"; } ?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="mdata_form">
    <div class="row">
      <div class="col-md-12"> <?php echo form_open_multipart(current_url() ,'class="master_data_form"'); ?>
        <legend><?php if( $lang["edit_master_data"]['description'] !='' ){ echo $lang["edit_master_data"]['description']; }else{ echo "EDIT MASTER DATA"; } ?></legend>
        <div class="form-group col-md-2">
          <label for="email" class="control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo "Job Card No."; } ?></label>
          <input type="text" class="form-control"  name="mdata_jobcard" 
                        value="<?php echo set_value('mdata_jobcard',$item['mdata_jobcard']);?>"/>
          <?php //echo form_error('mdata_jobcard'); ?> </div>
        <div class="form-group col-md-2">
          <label for="email" class="control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo "SMS Number"; } ?></label>
          <input type="text" class="form-control" name="mdata_sms" 
                        value="<?php echo set_value('mdata_code',$item['mdata_sms']);?>" />
          <?php //echo form_error('mdata_sms'); ?> </div>
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
          <select name="mdata_client" class="form-control">
            <option value="">Select Client</option>
            <?php
				foreach($client_list as $cl){
					$id = $cl['client_id'];
					$client_name = $cl['client_name'];
					$client_type = $cl['client_type'];
					$selected = ($id===$item['mdata_client'])? 'selected' : '';
					echo "<option ".$selected." value='".$id."' >".$client_name." (".$client_type.")</option>";
				}
			?>
          </select>
          <?php echo form_error('mdata_client'); ?> </div>
		<!--
        <div class="form-group col-md-5">
          <label for="email" class=" control-label">Dealer Involved</label>
          <select name="mdata_dealer" class="form-control">
            <option value="">Select Dealer</option>
            <?php /*
				foreach($dealer_list as $dl){
					$dlid = $dl['client_id'];
					$dlclient_name = $dl['client_name'];
					$dlselected = ($dlid===$item['mdata_dealer'])? 'selected' : '';
					echo "<option ".$dlselected." value='".$dlid."' >".$dlclient_name."</option>";
				} 
			?>
          </select>
          <?php echo form_error('mdata_dealer'); */ ?> </div>
		  -->
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
			<?php 
			$mdata_material_invoice_date  = ( ($item['mdata_material_invoice_date'] == '1970-01-01') || ( $item['mdata_material_invoice_date'] == '0000-00-00' )  || ( $item['mdata_material_invoice_date'] == '' ) )? '': date("d-m-Y", strtotime($item['mdata_material_invoice_date']));
			?>
          <input type="text" id="mdata_material_invoice_date" name="mdata_material_invoice_date" value="<?php echo $mdata_material_invoice_date; ?>" class="form-control tooltip_trigger date1" readonly />
          <?php echo form_error('mdata_material_invoice_date'); ?> </div>

		<div class="form-group col-md-2">
			<label for="email" class=" control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
					<select  id="status" name="status"  class="form-control tooltip_trigger" required>
						<option value="" > - Status -</option>
						<option <?php set_option_state($item['status'],'Active'); ?> value="Active" >Active</option>
						<option <?php set_option_state($item['status'],'Inactive'); ?> value="Inactive">Inactive</option>
					</select>
				  <?php echo form_error('status'); ?>
		</div>
		<div class="form-group col-md-2">
			<label for="date_of_first_use" class=" control-label"><?php if( $lang["date_of_first_use"]['description'] !='' ){ echo $lang["date_of_first_use"]['description']; }else{ echo "Date of First Use"; } ?></label>
				<?php 
				$date_of_first_use  = ( $item['date_of_first_use'] == '1970-01-01' || ( $item['date_of_first_use'] == '0000-00-00' ) || ( $item['date_of_first_use'] == '' ) )? '': date("d-m-Y", strtotime($item['date_of_first_use']));
               
                if( ($date_of_first_use == '') && ( $mdata_material_invoice_date != '' ) ){
					$date_of_first_use  = $mdata_material_invoice_date;
				}

				?>
				
			<input type="text" class="form-control date1" name="date_of_first_use"  id="date_of_first_use" value="<?php echo $date_of_first_use ;?>" />
				<?php echo form_error('date_of_first_use'); ?> 

		</div>
		<div class="form-group col-md-2">
			<label for="date_of_inspection" class=" control-label"><?php if( $lang["date_of_inspection"]['description'] !='' ){ echo $lang["date_of_inspection"]['description']; }else{ echo "Date of Inspection"; } ?></label>
				<?php 
				$date_of_inspection  = ( ($item['date_of_inspection'] == '1970-01-01') || ($item['date_of_inspection'] == '0000-00-00')   ||  ($item['date_of_inspection'] == '')  )? '':''.date("d-m-Y", strtotime($item['date_of_inspection']));
				?>
				<input type="text" class="form-control date1" name="date_of_inspection" id="date_of_inspection" value="<?php echo $date_of_inspection; ?>" />
				<?php echo form_error('date_of_inspection'); ?> 
		</div>
		<div class="form-group col-md-2">
			<label for="inspection_due_date" class=" control-label"><?php if( $lang["inspection_due_date"]['description'] !='' ){ echo $lang["inspection_due_date"]['description']; }else{ echo "Inspection Due Date"; } ?></label>

				<?php 
				$inspection_due_date  = ( $item['inspection_due_date'] == '1970-01-01' || $item['inspection_due_date'] == '0000-00-00' || $item['inspection_due_date'] == ''  )? '':''.date("d-m-Y", strtotime($item['inspection_due_date']));
                
               if( $inspection_due_date == '' ){   
					if( isset($item['frequency_month']) ){
						if( ( $item['frequency_month'] > 0 ) && ( $date_of_first_use != '' ) ){  
							$frequency_month  = $item['frequency_month'];
							$effectiveDate = strtotime("+".$frequency_month." months", strtotime($date_of_first_use));
							$inspection_due_date	= date('d-m-Y',$effectiveDate);					
						}
						
					}else if( $date_of_first_use != '' ) {
							
							$inspection_due_date	= $date_of_first_use;	
							
					}
			   }

				?>
			<input type="text" class="form-control "  id="inspection_due_date" value="<?php echo $inspection_due_date; ?>" readonly />
				<?php echo form_error('inspection_due_date'); ?> 
		</div>
		<!---------------------------------------------------------------------------->
		<div class="form-group col-md-12">
			<label for="email" class="control-label"><?php if( $lang["asset"]['description'] !='' ){ echo $lang["asset"]['description']; }else{ echo "Asset"; } ?> </label>	
			<input type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search expected result"/> 
			<div class="row">
				<div class="col-md-5">
					<div class="component-container search-expectedResult form-control" id='sel_expectedResult' style="height: 150px;border: 1px solid #CCC;">
						<?php 
						if(!empty($component_list)){
							foreach($component_list as $resultKey=>$resultValue){ 
						?>
						<p><?php echo $resultValue; ?>
						<input class="pull-right" type="checkbox" name="assetSerise[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultValue; ?>" rel="<?php echo $resultValue; ?>" /></p>
						<?php } } ?> 
					</div> 
				</div>
				<div class="col-md-2">
					<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
				</div>
				<div class="col-md-5" >
					<div class="component-container form-control" id="selected_expectedResult" style="height: 150px;margin-left:20px;">
							
							<?php	if($item['mdata_asset']!=''){
										$assetList = json_decode($item['mdata_asset'],true);
										foreach($assetList as $dbKey=>$dbVal){
									?>
										<p class='bg-success' id='<?php echo $dbKey; ?>'><?php echo $dbVal; ?><span rel="<?php echo $dbKey; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
												<input type="hidden" class="sel-component sub_result" name="assetSerise[]" value="<?php echo $dbVal; ?>"/>
						                </p>
							<?php } }
									?>
								
					</div>
				</div>
			</div> 
		 </div>
		<!------------------------------end---------------------------------------------->
		<div class="form-group col-md-12">
          <label for="email" class=" control-label"> <?php if( $lang["asset_series"]['description'] !='' ){ echo $lang["asset_series"]['description']; }else{ echo "Asset Series"; } ?></label>
          <input type="text" id="search_assetSeries" name="search_tool" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search Components / Products"/>
			<div class="row">
            <div class="col-md-5">
              <div class="search-container" id='sel_component'>
				<?php 
						$mitem = $item_list[0];
						$product_component = $item_list[1];
					for($i=0; $i<count($item_list[0]);$i++){
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
				<?php
				if($item['mdata_item_series']!='')
				{
					$dbitem=json_decode($item['mdata_item_series'],true);
					$productItems = array();
					$coun = count($dbitem);
				}
				$counter = (isset($coun))? $coun : 0; 
				?>
              <div class="component-container" id="product_component_master" data-name="mdata_edit" rel='<?php echo $counter; ?>'>
			<?php	if($item['mdata_item_series']!='')
					{
						foreach($dbitem as $dbKey=>$dbVal){
							$prKey = array_search($dbVal,$mitem);
							if($prKey){
							$prValue = json_decode($product_component[$prKey]);
					?>
							<div id='<?php echo $dbKey; ?>' class='bg-success product_main'><b><?php echo $dbVal; ?></b><span rel='<?php echo $dbKey; ?>' class="pull-right text-danger  glyphicon glyphicon-trash"></span><input type="hidden" class="sel-component" name="product_components[]" value="<?php echo $dbVal; ?>"/>
						<?php   ?>
							</div><p></p>
					<?php	}else{ ?>
								<div id='<?php echo $dbKey; ?>' class='bg-danger product_main'><?php echo $dbVal; ?><span rel='<?php echo $dbKey; ?>' class="pull-right text-danger  glyphicon glyphicon-trash"></span> </div><p></p>
							<?php }
						}
					}
					?>
               </div>
            </div>
          </div>
        </div>
        <div class="form-group" >
          <div class="col-md-offset-4 col-md-8" style="margin-top:30px;">
            <input type="submit" name="submit_mdata" class="btn btn-primary" id="submit_mdata" value="<?php if( $lang["update_master_data"]['description'] !='' ){ echo $lang["update_master_data"]['description']; }else{ echo "UPDATE MASTER DATA"; } ?>" />
			<a href="<?php echo base_url('manage_kare/mdata_inspection'); ?>" class="btn btn-default">BACK</a>
          </div>
        </div>
        <!--</form>--> 
        <?php echo form_close();?> </div>
    </div>
  </div>
</div>
<!--/.tab-content -->
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>

<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>