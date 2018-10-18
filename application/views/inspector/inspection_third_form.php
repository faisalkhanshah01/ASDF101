<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
<?php
	$subAsset = json_decode($_SESSION['inspector']['asset_containing_subasset']);
	if(isset($_SESSION['inspector']['total_subAsset_id']) && isset($_SESSION['inspector']['completed_sub_assets'])){
		$completed_sub_assets = $_SESSION['inspector']['completed_sub_assets'];
	}else{
		$completed_sub_assets = $_SESSION['inspector']['completed_sub_assets'] = array();
	}
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">INSPECT SUB ASSETS</legend>
			<div class="panel-default">
					<div class="panel-heading"><label>Site ID :</label> <?php echo $_SESSION['inspector']['siteid']; ?><p class="pull-right"><label>Date :</label> <?php echo $_SESSION['inspector']['form_submitting_date']; ?></p></div>
				<div class="panel-body">
					<form class="form-horizontal" id="inspection_third_form" action="" method="post">
					<table class="table table-bordered  table-hover assign_site_table">
						<thead>							
							<tr>
								<th>S.No</th>
								<th>Sub Asset Code</th>
								<th>Action</th>
								<th>Checked</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$subCount = 1;
							$totalSubCount = 0;
							$total_subAsset_id = array();
							foreach($subAsset as $key=>$val){
								$totalSubCount ++;
								$asset_code =  $val;
									?>
										<tr id="<?php echo $subCount; ?>">
											<td class="text-center colour<?php echo $subCount; ?>"><?php echo $subCount; ?></td>
											<td class="text-center colour<?php echo $subCount; ?>"><?php echo $asset_code; ?></td>
											<td class="text-center colour<?php echo $subCount; ?>"><?php echo (!in_array($subCount,$completed_sub_assets))? '<a href="inspect_subAsset?subAsset_code='.$asset_code.'&count_subAsset_id='.$subCount.'">Inspect Sub Asset</a>': 'Inspect Sub Asset' ;?></td>
											<td class="text-center colour<?php echo $subCount; ?>"><?php echo (in_array($subCount,$completed_sub_assets))? '<font color="Green">Checked</font>' : '<font color="red">Not Checked</font>'; ?></a></td>
										</tr>
									<?php
									$total_subAsset_id[] = $subCount;
									$subCount ++ ;
							}
							$_SESSION['inspector']['total_subAsset_id'] = $total_subAsset_id;
							
							$_SESSION['inspector']['total_subAssets_available'] = $totalSubCount;
							?>
						</tbody>
						<?php 
							if((count($total_subAsset_id) == count($completed_sub_assets)) && (count($total_subAsset_id) !='0' && count($completed_sub_assets) !='0')){
						?>
						<tfoot>
							<tr>
								<td colspan="4">
								<div class="col-md-offset-5 col-md-6">
									<button type="submit" class="btn btn-info" name="inspection_third_form" id="inspection_third_form_final_submit">Click To Continue</button>
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
<?php $this->load->view('includes/scripts'); ?>