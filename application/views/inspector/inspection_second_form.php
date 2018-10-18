<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
<?php
	$asset = $_SESSION['inspector']['assets_values'];
	if(isset($_SESSION['inspector']['total_asset_id']) && isset($_SESSION['inspector']['completed_assets'])){
		$completed_assets = $_SESSION['inspector']['completed_assets'];
	}else{
		$completed_assets = $_SESSION['inspector']['completed_assets'] = array();
	}
?>
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Assets</legend>
			<div class="panel-default">
				<div class="panel-heading"><label>Site ID :</label> <?php echo $_SESSION['inspector']['siteid']; ?><p class="pull-right"><label>Date :</label> <?php echo $_SESSION['inspector']['form_submitting_date']; ?></p></div>
				<div class="panel-body">
					<form class="form-horizontal" id="inspection_second_form" action="" method="post">
					<table class="table table-bordered table-hover assign_site_table">
						<thead>							
							<tr>
								<th>S.No</th>
								<th>Asset Code</th>
								<th>Action</th>
								<th>Checked</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$count = 1;
							$totalCount = 0;
							$total_asset_id = array();
							foreach($asset as $key=>$val){
								$asset_code =  $key;
								$uom = $val['asset_uom'];
								if($uom != ''){
									$lines = ($uom =='mtr')? 1 : $val['item_quantity'];
									$totalCount += $lines;
									if($lines !=0 && $lines!=1 && $uom !='mtr'){
										for($i = 1;$i<=$lines;$i++){
											if(isset($val['contain_subasset'])){ ?>
												<tr id="<?php echo $count; ?>">
												<td class="text-center colour<?php echo $count; ?>"><?php echo $count; ?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo $asset_code; ?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo (!in_array($count,$completed_assets))? '<a href="inspect_form_third?&count_id='.$count.'&asset_id='.$count.'&asset_code='.$key.'">Inspect Asset</a>': 'Inspect Asset' ;?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo (in_array($count,$completed_assets))? '<font color="green">Checked</font>' : '<font color="red">Not Checked</font>'; ?></a></td>
												</tr>
									<?php	}else{ ?>
												<tr id="<?php echo $count; ?>">
													<td class="text-center colour<?php echo $count; ?>"><?php echo $count; ?></td>
													<td class="text-center colour<?php echo $count; ?>"><?php echo $asset_code; ?></td>
													<td class="text-center colour<?php echo $count; ?>"><?php echo (!in_array($count,$completed_assets))? '<a href="inspect_asset?asset_code='.$asset_code.'&count_asset_id='.$count.'">Inspect Asset</a>': 'Inspect Asset' ;?></td>
													<td class="text-center colour<?php echo $count; ?>"><?php echo (in_array($count,$completed_assets))? '<font color="green">Checked</font>' : '<font color="red">Not Checked</font>'; ?></a></td>
												</tr>
									<?php 	}
											$total_asset_id[] =$count;
											$count ++;
										}
									}else{
										if(isset($val['contain_subasset'])){ ?>
												<tr id="<?php echo $count; ?>">
												<td class="text-center colour<?php echo $count; ?>"><?php echo $count; ?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo $asset_code; ?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo (!in_array($count,$completed_assets))? '<a href="inspect_form_third?&count_id='.$count.'&asset_id='.$count.'&asset_code='.$key.'">Inspect Asset</a>': 'Inspect Asset' ;?></td>
												<td class="text-center colour<?php echo $count; ?>"><?php echo (in_array($count,$completed_assets))? '<font color="green">Checked</font>' : '<font color="red">Not Checked</font>'; ?></a></td>
												</tr>
									<?php	}else{ ?>
										<tr id="<?php echo $count; ?>">
											<td class="text-center colour<?php echo $count; ?>"><?php echo $count; ?></td>
											<td class="text-center colour<?php echo $count; ?>"><?php echo $asset_code; ?></td>
											<td class="text-center colour<?php echo $count; ?>"><?php echo (!in_array($count,$completed_assets))? '<a href="inspect_asset?asset_code='.$asset_code.'&count_asset_id='.$count.'">Inspect Asset</a>': 'Inspect Asset' ;?></td>
											<td class="text-center colour<?php echo $count; ?>"><?php echo (in_array($count,$completed_assets))? '<font color="Green">Checked</font>' : '<font color="red">Not Checked</font>'; ?></a></td>
										</tr>
									<?php }
										$total_asset_id[] =$count;
										$count ++ ;
									}
								}
							}
							$_SESSION['inspector']['total_asset_id'] = $total_asset_id;
							?>
						</tbody>
						<?php 
							if((count($total_asset_id) == count($completed_assets)) && (count($total_asset_id) !='0' && count($completed_assets) !='0')){
						?>
						<tfoot>
							<tr>
								<td colspan="4">
								<div class="col-md-offset-5 col-md-6">
									<button type="submit" class="btn btn-info" name="inspection_second_form" id="inspection_second_form_final_submit">Click To Continue</button>
								</div>
								</td>
							</tr>
						</tfoot>
						<?php  } ?>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 