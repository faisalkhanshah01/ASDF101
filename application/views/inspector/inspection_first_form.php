<?php $this->load->view('includes/header'); ?> 
	<!-- Demo Navigation -->
	<?php $this->load->view('includes/head'); 
		if(isset($_SESSION['inspector']['rfidForm'])){
			$rfidForm = $_SESSION['inspector']['rfidForm'];
			if($rfidForm['input_method'] == 'RFID'){
				$label = 'RFID NUMBER';
				$value = 'RFID Number';
			}elseif($rfidForm['input_method'] == 'UIN'){
				$label = 'UIN NUMBER';
				$value = 'UIN Number';
			}elseif($rfidForm['input_method'] == 'BARCODE'){
				$label = 'BARCODE NUMBER';
				$value = 'Barcode Number';
			}
		}
		$mdata_rfid 	= $_SESSION['inspector']['mdata_rfid'];
		$mdata_barcode  = $_SESSION['inspector']['mdata_barcode'];
		$mdata_uin  	= $_SESSION['inspector']['mdata_uin'];

	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#inputmethod').on('change',function(){
			if(	$(this).val()==''){
				$("#selected_value").html('');
			}
			if( $(this).val()==="RFID"){
				$("#selected_value").html('');
				$("#selected_value").append('<label for="Inputvalue" class="col-md-3 control-label">RFID NUMBER</label>'+
									'<div class="col-md-9">'+
									'<input type="text" title="RFID Number" class="form-control" required name="Inputvalue" id= "Inputvalue" value="<?php echo $mdata_rfid; ?>" placeholder="RFID Number" required />'+
									'</div>');
			}		
			else if( $(this).val()==="UIN"){		
				$("#selected_value").html('');
				$("#selected_value").append('<label for="Inputvalue" class="col-md-3 control-label">UIN NUMBER :</label>'+
									'<div class="col-md-9">'+
									'<input type="text" title="UIN Number" class="form-control" required name="Inputvalue" id= "Inputvalue" value="<?php echo $mdata_uin; ?>" placeholder="UIN Number" required />'+
									'</div>');
			}
			else if( $(this).val()==="BARCODE"){		
				$("#selected_value").html('');
				$("#selected_value").append('<label for="Inputvalue" class="col-md-3 control-label">BARCODE NUMBER :</label>'+
									'<div class="col-md-9">'+
									'<input type="text" title="Barcode Number" class="form-control" required name="Inputvalue"  id= "Inputvalue" value="<?php echo $mdata_barcode; ?>" placeholder="BARCODE Number" required />'+
									'</div>');
			}
		});
	});
	</script>
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>

			<div class="col-md-12 inspectionFirst">
				<form class="" id="assign_list_form" method="post">
					<div class="col-md-12">	
						<div class="form-group col-md-4">
						<label for="reportno" class="col-md-4 control-label">Report No.:</label>
							<div class="col-md-8">
								<div class="form-control inspection_form" id="reportno" name="reportno" placeholder="Report Number"><?php echo $details['report_no']; ?></div>
							</div>
						</div>
						<div class="form-group col-md-4">
						<label for="" class="col-md-4 control-label"></label>
							<div class="col-md-8">
								
							</div>
						</div>
						<div class="form-group col-md-4">
						<label for="date" class="col-md-3 control-label">Today Date:</label>
							<div class="col-md-9">
								<div class="form-control inspection_form"><?php echo $_SESSION['inspector']['form_submitting_date']; ?></div>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">					
						<div class="form-group col-md-4">
						<label for="clientname" class="col-md-4 control-label">Client Name:</label>
							<div class="col-md-8">
								<div class="panel panel-default inspection_form"><?php echo $details['client_name']; ?></div>
							</div>
						</div>
						
						<div class="form-group col-md-4">
						<label for="address" class="col-md-3 control-label">Address:</label>
							<div class="col-md-9">
								<div class="panel panel-default inspection_form"><?php echo $details['site_location'].", ".$details['site_city'].", ".$details['site_address']; ?></div>
							</div>
						</div>
						
						<div class="form-group col-md-4">
						<label for="siteid" class="col-md-3 control-label">Site ID:</label>
							<div class="col-md-9">
								<div class="form-control inspection_form"><?php echo $details['site_id']; ?></div>
							</div>
						</div>
											
					</div>
					
					<div class="col-md-12">	
					
						<div class="form-group col-md-4">
						<label for="jobcardno" class="col-md-4 control-label">Job Card No.:</label>
							<div class="col-md-8">
								<div class="form-control inspection_form"><?php echo $details['job_card']; ?></div>
							</div>
						</div>
						
						<div class="form-group col-md-4">
						<label for="smsno" class="col-md-3 control-label">SMS No.:</label>
							<div class="col-md-9">
								<div class="panel panel-default inspection_form"><?php echo $details['sms_no']; ?></div>
							</div>
						</div>
						
						<div class="form-group col-md-4">
						<label for="asset" class="col-md-3 control-label">Asset Series:</label>
							<div class="col-md-9">
								<div class="panel panel-default inspection_form" id="asset_series"><?php echo $details['asset_series']; ?></div>
							</div>
						</div>	
						
					</div>
					
					<div class="col-md-12">
						<div class="form-group col-md-4">
						<label for="pono" class="col-md-4 control-label">Po No.:</label>
							<div class="col-md-8">
								<div class="form-control inspection_form" id="pono"><?php echo $details['po_no']; ?></div>
							</div>
						</div>					
						<div class="form-group col-md-4">
						<label for="batchno" class="col-md-3 control-label">Batch No.:</label>
							<div class="col-md-9">
								<div class="form-control inspection_form" id="batchno" ><?php echo $details['batch_no']; ?></div>
							</div>
						</div>
						<div class="form-group col-md-4">
						<label for="serialno" class="col-md-3 control-label">Serial No.:</label>
							<div class="col-md-9">
								<div class="form-control inspection_form" id="serialno" ><?php echo $details['serial_no']; ?></div>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group col-md-4">
						<label for="location" class="col-md-4 control-label">Lattitude:</label>
							<div class="col-md-8">
								<input type="text" class="form-control inspection_form" id="lattitude" value="<?php echo ($_SESSION['inspector']['lattitude']!='')? $_SESSION['inspector']['lattitude'] : ''; ?>" name="lattitude" placeholder="lattitude" <?php echo ($_SESSION['inspector']['lattitude']!='')? 'readonly' : ''; ?>>
							</div>
						</div>
						<div class="form-group col-md-4">
						<label for="location" class="col-md-4 control-label">Longitude:</label>
							<div class="col-md-8">
								<input type="text" class="form-control inspection_form" id="longitude" name="longitude" value="<?php echo ($_SESSION['inspector']['longitude']!='')? $_SESSION['inspector']['longitude'] : ''; ?>" placeholder="Longitude" <?php echo ($_SESSION['inspector']['longitude']!='')? 'readonly' : ''; ?>>
							</div>
						</div>	
						<div class="form-group col-md-4">
						<label for="inspectedby" class="col-md-3 control-label">Inspected By:</label>
							<div class="col-md-9">
								<div class="form-control inspection_form" id="inspectedby"><?php echo $details['inspector_name']; ?></div>
							</div>
						</div>
					</div>
				
					<div class="col-md-12 inputDiv">
					
						<div class="form-group col-md-6">
						<label for="inputmethod" class="col-md-4 control-label">Select Input Method:</label>
							<div class="col-md-8">
							
								<select class="form-control inspection_form" id="inputmethod" name="inputmethod" required="required">
									<option value=""> - Select Input Method - </option>
									<option <?php echo (isset($rfidForm) && $rfidForm['input_method'] == 'RFID')? 'selected': '' ?> value="RFID">RFID NUMBER</option>
									<option <?php echo (isset($rfidForm) && $rfidForm['input_method'] == 'UIN')? 'selected': '' ?> value="UIN">UIN NUMBER</option>
									<option <?php echo (isset($rfidForm) && $rfidForm['input_method'] == 'BARCODE')? 'selected': '' ?> value="BARCODE">BARCODE NUMBER</option>								
								</select>
							</div>
						</div>
						<div class="form-group col-md-6" id="selected_value">
						<?php if(isset($rfidForm)){ ?>
							<label for="Inputvalue" class="col-md-3 control-label"><?php echo $label; ?> :</label>
								<div class="col-md-9">
								<input type="text" title="<?php echo $value; ?>" class="form-control" required name="Inputvalue"  id= "Inputvalue" value="<?php echo $rfidForm['input_value'] ?>" placeholder="<?php echo $value; ?>" required />
							</div>
						<?php } ?>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-6 col-md-6">
							<button type="submit" class="btn btn-info" name="inspection_submit" id="inspection_submit" value="inspection_submit">SUBMIT</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
